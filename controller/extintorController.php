<?php

require_once 'vendor/autoload.php';
use Hidehalo\Nanoid\Client;
use Hidehalo\Nanoid\GeneratorInterface;

function data_usqlS($data) {
    $ndata = substr($data, 8, 2) ."/". substr($data, 5, 2) ."/".substr($data, 0, 4);
    return $ndata;
} 

function isInvalidQr($string) {
    //at least one invalid character invalidates the input string
    //1 = true or 0 = false
    // '^' negates valid characters
    return preg_match('/[^a-zA-Z0-9]/', $string);
}

//Recebe data atual e limite em dias, e verifica data atual está dentro do limite ou não
function toInspect($current_date, $limit){

    $data['diff'] = '';
    $data['msg'] = '';
    $last=date_create($current_date);
    $server_now = date_create(date("Y-m-d H:i:s", $_SERVER['REQUEST_TIME']));
    $diff=date_diff($last,$server_now);
    //echo $diff->format("%a days");
    $nb_diff = $diff->format("%a") * 1;

    $differ = $limit - $nb_diff;

    if($differ < 0 ) $differ = 0;

    $data['diff'] = $differ;   
    $data['msg'] = $differ > 0 ? 'Inspeção N1 será liberada em: '.$differ.' dia(s).' : '';

    return $data;

}

function getExtLastInspection($conn,$ext){

    $stmt = $conn->prepare("SELECT id_inspecao, DATE_FORMAT(dt_inspecao, '%d/%m/%Y') AS dt_inspecao, DATE_FORMAT(dt_inspecao, '%m') AS dt_mes, dt_inspecao AS date_sqlformat
                        FROM extintores_insp
                        WHERE id_serie LIKE :id_serie
                        ORDER BY id_inspecao DESC
                        LIMIT 1;");
    $stmt->bindParam(':id_serie', $ext);  
    $stmt->execute();

    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    $mes = date("m", $_SERVER['REQUEST_TIME']);
    
    if($data){
        if($mes == $data['dt_mes']){
            $data['insp_block'] = 1;
            $data['msg'] = 'Inspeção será liberada no mês seguinte.';
        }else{
            $diff = toInspect($data['date_sqlformat'],10);
            $diff['diff'] > 0 ? $data['insp_block'] = 1 : $data['insp_block'] = 0;
            $data['msg'] = $diff['msg'];
        }
    }

    if(!$data){
        $data['dt_inspecao'] = 'Inspeção anterior não cadastrada.';
        $data['insp_block'] = 0;
        $data['msg'] = '';
    }

    return $data;
    
}

function getExtintor($conn,$ext){

    $stmt = $conn->prepare("SELECT map.tx_predio, map.tx_area, map.tx_localiz, map.id_posicao,
                        ext.tx_inmetro, ext.id_serie, ext.tx_tipo, ext.tx_capacidade, 
                        ext.bool_carreta, ext.cs_estado, cli.tx_nome, ext.id_cliente, ext.uuid, 
                        ext.dt_vencimenton2,ext.dt_vencimenton3
                        FROM extintores AS ext
                        INNER JOIN cliente AS cli ON ext.id_cliente = cli.id_cliente
                        LEFT JOIN cliente_map AS map ON ext.id_serie = map.id_serie
                        WHERE ext.uuid LIKE :uuid");
    $stmt->bindParam(':uuid', $ext);  
    $stmt->execute();

    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $data['insp_mode'] = 0;

    switch($data['tx_tipo']){
        case "AP": 
            $data['tx_tipo'] = 'Água Pressurizada';
            $data['cs_checkbox'] = 0;
            break;
        case "ESP. MEC.": 
            $data['tx_tipo'] = 'Espuma Mecânica';
            $data['cs_checkbox'] = 1;
            break;
        case "ABC": 
            $data['tx_tipo'] = 'Pó Químico ABC';
            $data['cs_checkbox'] = 2;
            break;
        case "BC": 
            $data['tx_tipo'] = 'Pó Químico BC';
            $data['cs_checkbox'] = 3;
            break;
        case "Co2": 
            $data['tx_tipo'] = 'CO2';
            $data['cs_checkbox'] = 4;
            break;
    }

    switch($data['bool_carreta']){
        case 1: 
            $data['bool_carreta'] = 'Carreta';
            break;
        default: 
            $data['bool_carreta'] = 'Extintor Portátil';
            break;
    }

    return $data;

}

function getExtintorBasic($conn,$ext){

    $stmt = $conn->prepare("SELECT ext.tx_inmetro, ext.id_serie, ext.uuid, ext.tx_tipo, ext.tx_capacidade, ext.bool_carreta, ext.cs_estado
                        FROM extintores AS ext
                        WHERE ext.uuid LIKE :uuid");
    $stmt->bindParam(':uuid', $ext);  
    $stmt->execute();

    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $data['insp_mode'] = 0;

    switch($data['tx_tipo']){
        case "AP": 
            $data['tx_tipo'] = 'Água Pressurizada';
            break;
        case "ESP. MEC.": 
            $data['tx_tipo'] = 'Espuma Mecânica';
            break;
        case "ABC": 
            $data['tx_tipo'] = 'Pó Químico ABC';
            break;
        case "BC": 
            $data['tx_tipo'] = 'Pó Químico BC';
            break;
        case "Co2": 
            $data['tx_tipo'] = 'CO2';
            break;
    }

    switch($data['bool_carreta']){
        case 1: 
            $data['bool_carreta'] = 'Carreta';
            break;
        default: 
            $data['bool_carreta'] = 'Extintor Portátil';
            break;
    }

    return $data;

}

function insertInspecaoExtintor($conn,$data){
    $e = null;

    try{
    $stmt = $conn->prepare("INSERT INTO extintores_insp 
                        (id_posicao, id_serie, nb_desvio, tx_sv, tx_sh, tx_la, tx_ao, tx_aea, tx_sp, tx_pn, tx_th, 
                        tx_carga, tx_manom, tx_cil, tx_etq, tx_rot, tx_alc, tx_gat, tx_trv, tx_lcr, tx_mang, tx_pun, tx_dif, tx_coment, id_bombeiro, tx_inmetro, tx_predio, tx_area, tx_localiz, id_cliente)
                        VALUES (:id_posicao, :id_serie, :nb_desvio, :tx_sv, :tx_sh, :tx_la, :tx_ao, :tx_aea, :tx_sp, :tx_pn, :tx_th, 
                        :tx_carga, :tx_manom, :tx_cil, :tx_etq, :tx_rot, :tx_alc, :tx_gat, :tx_trv, :tx_lcr, :tx_mang, :tx_pun, :tx_dif, :tx_coment, :id_bombeiro, :tx_inmetro, :tx_predio, :tx_area, :tx_localiz, :id_cliente)");
    
    $stmt->bindParam(':id_posicao', $data['id_posicao']);  
    $stmt->bindParam(':id_serie', $data['id_serie']);  
    $stmt->bindParam(':nb_desvio', $data['nb_desvio']);
    $stmt->bindParam(':tx_sv', $data['ch1']);
    $stmt->bindParam(':tx_sh', $data['ch2']);
    $stmt->bindParam(':tx_la', $data['ch3']);
    $stmt->bindParam(':tx_ao', $data['ch4']);
    $stmt->bindParam(':tx_aea', $data['ch5']);
    $stmt->bindParam(':tx_sp', $data['ch6']);
    $stmt->bindParam(':tx_pn', $data['ch7']);
    $stmt->bindParam(':tx_th', $data['ch8']);
    $stmt->bindParam(':tx_carga', $data['ch9']);
    $stmt->bindParam(':tx_manom', $data['ch10']);
    $stmt->bindParam(':tx_cil', $data['ch11']);
    $stmt->bindParam(':tx_etq', $data['ch12']);
    $stmt->bindParam(':tx_rot', $data['ch13']);
    $stmt->bindParam(':tx_alc', $data['ch14']);
    $stmt->bindParam(':tx_gat', $data['ch15']);
    $stmt->bindParam(':tx_trv', $data['ch16']);
    $stmt->bindParam(':tx_lcr', $data['ch17']);
    $stmt->bindParam(':tx_mang', $data['ch18']);
    $stmt->bindParam(':tx_pun', $data['ch19']);
    $stmt->bindParam(':tx_dif', $data['ch20']);
    $stmt->bindParam(':tx_coment', $data['comentario']);
    $stmt->bindParam(':id_bombeiro', $data['bombeiro']);
    $stmt->bindParam(':tx_inmetro', $data['inmetro']);
    $stmt->bindParam(':tx_predio', $data['predio']);
    $stmt->bindParam(':tx_area', $data['area']);
    $stmt->bindParam(':tx_localiz', $data['localiz']);
    $stmt->bindParam(':id_cliente', $data['cliente']);
    $stmt->execute();
    
    }catch(PDOException $e)
    {
    $e->getMessage();
    }

    if($e == null){
        return 1;
    }else{
        return 0;
    }
    
}
function map_updateExtintores($conn, $data){

    $e = null;
    $pieceCount = 0;
    $pieceList = array();
    $pieceText = array();
    $str = $data['textData'];
    $cliente = $data['codCliente'];

    $regCodigo = '/(.*),(.*),(.*),(.*),(.*)/';
    preg_match_all($regCodigo,$str,$pieceList);

    $pieceCount = count($pieceList[0]);

    echo "(count)Extintores contados: ".$pieceCount;
    
    echo "<br>";

    try{
        $conn->beginTransaction();

        $stmt = $conn->prepare("UPDATE cliente_map
                        SET id_posicao = :id_posicao , tx_predio = :tx_predio , tx_area = :tx_area, tx_localiz = :tx_localiz
                        WHERE id_serie = :id_serie
                        ");
    //array 1 => 0 => numero de serie
    //array 2 => 0 => id_posicao
    //array 3 => 0 => tx_predio
    //array 4 => 0 => tx_area
    //array 5 => 0 => tx_localiz

    for($i = 0; $i < $pieceCount ; $i++){

        
          echo "<br>Listagem de parametros: Extintor count:".$i."<br>";
          echo $pieceList[1][$i]."<br>";
         // echo $pieceList[2][$i]."<br>";
         // echo $pieceList[3][$i]."<br>";
         // echo $pieceList[4][$i]."<br>";
         // echo $pieceList[5][$i]."<br>";
        

        $stmt->bindParam(':id_serie', $pieceList[1][$i]);  
        $stmt->bindParam(':id_posicao', $pieceList[2][$i]);  
        $stmt->bindParam(':tx_predio', $pieceList[3][$i]);  
        $stmt->bindParam(':tx_area', $pieceList[4][$i]);     
        $stmt->bindParam(':tx_localiz', $pieceList[5][$i]);  

        $stmt->execute();
        
      }

    $conn->commit();
 
    }catch(PDOException $e)
		{
		echo "Erro ao cadastrar coleção de ".$pieceCount." extintores! " . $e->getMessage();
        $conn->rollback();
		}
		
		if($e == null){
            return true;
        }else{
            return false;
        }

}

function inm_updateExtintores($conn, $data){

    $e = null;
    $pieceCount = 0;
    $pieceList = array();
    $pieceText = array();
    $str = $data['textData'];
    $cliente = $data['codCliente'];

    $regCodigo = '/(.*),(.*)/';
    preg_match_all($regCodigo,$str,$pieceList);

    $pieceCount = count($pieceList[0]);

    echo "(count)Extintores contados: ".$pieceCount;
    
    echo "<br>";

    try{
        $conn->beginTransaction();

        $stmt = $conn->prepare("UPDATE extintores_inm
                        SET tx_inmetro = :tx_inmetro
                        WHERE id_serie = :id_serie
                        ");
    //array 1 => 0 => numero de serie
    //array 2 => 0 => tx_inmetro

    for($i = 0; $i < $pieceCount ; $i++){

        
          echo "<br>Listagem de parametros: Extintor count:".$i."<br>";
          echo $pieceList[1][$i]."<br>";
         // echo $pieceList[2][$i]."<br>";
        

        $stmt->bindParam(':id_serie', $pieceList[1][$i]);  
        $stmt->bindParam(':tx_inmetro', $pieceList[2][$i]);  

        $stmt->execute();
        
      }

    $conn->commit();
 
    }catch(PDOException $e)
		{
		echo "Erro ao cadastrar coleção de ".$pieceCount." extintores! " . $e->getMessage();
        $conn->rollback();
		}
		
		if($e == null){
            return true;
        }else{
            return false;
        }

}
function insertExtintores($conn,$data){
    $uuid_client = new Client();
    $e = null;
    $pieceCount = 0;
    $pieceList = array();
    $pieceText = array();
    $str = $data['textData'];
    $cliente = $data['codCliente'];

    $regCodigo = '/(.*),(.*),(.*),(.*),(.*),(.*),(.*),(.*),(.*)/';
    preg_match_all($regCodigo,$str,$pieceList);

    //$regCodigo = '/[A-Z]{1}[a-z]{2,}.*/';
    //preg_match_all($regCodigo,$str,$pieceText);
    //pieceValue[0]={PR 2.50,...}  pieceValue[1]={PR,...} pieceValue[2]={2.70,...} 
    //$regCodigo = '/([A-Z]{2,3}|[A-Z]{1,2}[1-2]{1})\s([0-9]{1,4}\.[0-9]{2})/';
    //preg_match_all($regCodigo,$str,$pieceValue);

    $pieceCount = count($pieceList[0]);

    echo "(count)Extintores contados: ".$pieceCount;
    
    echo "<br>";

    try{
        $conn->beginTransaction();

        $stmt = $conn->prepare("INSERT INTO extintores (id_serie, tx_tipo, tx_capacidade, bool_carreta, id_cliente, uuid, dt_vencimenton2, dt_vencimenton3)
                        VALUES (:id_serie, :tx_tipo, :tx_capacidade, :bool_carreta, :id_cliente, :uuid, :dt_vencimenton2, :dt_vencimenton3);
                            ");
        $stmt2 = $conn->prepare("INSERT INTO extintores_inm (id_serie, tx_inmetro)
                        VALUES (:id_serie, :tx_inmetro);
                        ");
        $stmt3 = $conn->prepare("INSERT INTO cliente_map (id_serie, id_cliente, tx_area, tx_localiz)
                        VALUES (:id_serie, :id_cliente, :tx_area, :tx_localiz);
                        ");
    //array 1 => 0 => numero de serie
    //array 2 => 0 => tipo
    //array 3 => 0 => capacidades
    //array 4 => 0 => inmetro
    //array 5 => 0 => predio
    //array 6 => 0 => area
    //array 7 => 0 => localizacao
    //array 8 => 0 => ano
    for($i = 0; $i < $pieceCount ; $i++){
        $uuid = "";
        $uuid = $uuid_client->formattedId($alphabet = '0123456789abcdefghijklmnopqrstuvwxyzQWERTYUIOPLKJHGFDSAZXCVBNM', $size = 9);
        $numeric = "";
        preg_match('/(\d*)/', $pieceList[3][$i] , $numeric);
        $numeric = $numeric[0] + 0;
        $pieceList[1][$i] .= "/".$pieceList[7][$i];

          echo "<br>Listagem de parametros: Extintor count:".$i."<br>";
          echo $pieceList[1][$i]."<br>";
     //   echo $uuid."<br>";  
     //   echo $pieceList[2][$i]."<br>";  
     //   echo $pieceList[3][$i]."<br>";  
      //  echo $pieceList[4][$i]."<br>";  
     //   echo $pieceList[5][$i]."<br>";  
      //  echo $pieceList[6][$i]."<br>";  
      //  echo $pieceList[7][$i]."<br>";
      //  echo $pieceList[8][$i]."<br>";
       // echo $pieceList[9][$i]."<br>";
        if($numeric > 15){
            //echo "Carreta<br>";
        }
        else{
           // echo "Extintor Portátil<br>";
        }

        $stmt->bindParam(':id_serie', $pieceList[1][$i]);  
        $stmt->bindParam(':tx_tipo', $pieceList[2][$i]);  
        $stmt->bindParam(':tx_capacidade', $pieceList[3][$i]);     
        $stmt->bindParam(':dt_vencimenton2', $pieceList[8][$i]);     
        $stmt->bindParam(':dt_vencimenton3', $pieceList[9][$i]);     
        if($numeric > 15){
            $stmt->bindValue(':bool_carreta',1);
        }
        else{
            $stmt->bindValue(':bool_carreta',0);
        }
        $stmt->bindParam(':id_cliente', $cliente);
        $stmt->bindParam(':uuid', $uuid);
        
        $stmt2->bindParam(':id_serie', $pieceList[1][$i]);
        $stmt2->bindParam(':tx_inmetro', $pieceList[4][$i]);
        
        $stmt3->bindParam(':id_serie', $pieceList[1][$i]); 
        $stmt3->bindParam(':id_cliente', $cliente);
        $stmt3->bindParam(':tx_area', $pieceList[5][$i]);
        $stmt3->bindParam(':tx_localiz', $pieceList[6][$i]);

        $stmt->execute();
        $stmt2->execute();
        $stmt3->execute();

      }
    $conn->commit();
 
    }catch(PDOException $e)
		{
		echo "Erro ao cadastrar coleção de ".$pieceCount." extintores! " . $e->getMessage();
        $conn->rollback();
		}
		
		if($e == null){
            return true;
        }else{
            return false;
        }

}

?>