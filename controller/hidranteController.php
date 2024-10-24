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
    $data['msg'] = $differ > 0 ? 'Inspeção será liberada em: '.$differ.' dia(s).' : '';

    return $data;

}

function getHidLastInspection($conn,$hid,$cliente){

    $stmt = $conn->prepare("SELECT id_inspecao, DATE_FORMAT(dt_inspecao, '%d/%m/%Y') AS dt_inspecao, 
                        DATE_FORMAT(dt_inspecao, '%m') AS dt_mes, dt_inspecao AS date_sqlformat,
                        tx_venc_mg_01, tx_venc_mg_02, tx_venc_mg_03, tx_venc_mg_04
                        FROM hidrantes_insp
                        WHERE id_serie = :id_serie AND id_cliente = :id_cliente
                        ORDER BY id_inspecao DESC
                        LIMIT 1;");
    $stmt->bindParam(':id_serie', $hid);  
    $stmt->bindParam(':id_cliente', $cliente);  
    $stmt->execute();

    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    $mes = date("m", $_SERVER['REQUEST_TIME']);
    
    if($data){
        if($mes == $data['dt_mes']){
            $diff = toInspect($data['date_sqlformat'],20);
            $data['insp_block'] = 1;
            $data['msg'] = 'Inspeção será liberada no mês seguinte.';
        }else{
            $diff = toInspect($data['date_sqlformat'],20);
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

function getHidrante($conn,$hid){

    $stmt = $conn->prepare("SELECT hid.*, cli.tx_nome
                        FROM hidrantes AS hid
                        JOIN cliente AS cli ON hid.id_cliente = cli.id_cliente
                        WHERE hid.uuid LIKE :uuid");
    $stmt->bindParam(':uuid', $hid);  
    $stmt->execute();

    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $data['insp_mode'] = 0;

    return $data;

}

function insertInspecaoHidrante($conn,$data){
    $e = null;

    try{
    $stmt = $conn->prepare("INSERT INTO hidrantes_insp 
                        (id_serie, nb_desvio, tx_sv, tx_sh, tx_abrg, tx_adap, tx_chv, tx_esg, tx_lcr, tx_tamp, 
                        tx_vg, tx_venc_mg_01, tx_venc_mg_02, tx_venc_mg_03, tx_venc_mg_04, tx_coment,
                        id_bombeiro, tx_local, id_cliente)
                        VALUES (:id_serie, :nb_desvio, :tx_sv, :tx_sh, :tx_abrg, :tx_adap, :tx_chv, :tx_esg, :tx_lcr, :tx_tamp, 
                        :tx_vg, :tx_venc_mg_01, :tx_venc_mg_02, :tx_venc_mg_03, :tx_venc_mg_04, :tx_coment,
                        :id_bombeiro, :tx_local, :id_cliente)");
    
    $stmt->bindParam(':id_serie', $data['id_serie']);  
    $stmt->bindParam(':nb_desvio', $data['nb_desvio']);
    $stmt->bindParam(':tx_sv', $data['ch1']);
    $stmt->bindParam(':tx_sh', $data['ch2']);
    $stmt->bindParam(':tx_abrg', $data['ch3']);
    $stmt->bindParam(':tx_adap', $data['ch4']);
    $stmt->bindParam(':tx_chv', $data['ch5']);
    $stmt->bindParam(':tx_esg', $data['ch6']);
    $stmt->bindParam(':tx_lcr', $data['ch7']);
    $stmt->bindParam(':tx_tamp', $data['ch8']);
    $stmt->bindParam(':tx_vg', $data['ch9']);
    $stmt->bindParam(':tx_venc_mg_m01', $data['mg01']);
    $stmt->bindParam(':tx_venc_mg_m02', $data['mg02']);
    $stmt->bindParam(':tx_venc_mg_m03', $data['mg03']);
    $stmt->bindParam(':tx_venc_mg_m04', $data['mg04']);
    $stmt->bindParam(':tx_coment', $data['comentario']);
    $stmt->bindParam(':id_bombeiro', $data['bombeiro']);
    $stmt->bindParam(':tx_local', $data['local']);
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

function map_updateHidrantes($conn, $data){

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

function insertHidrantes($conn,$data){
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

    echo "(count)Hidrantes contados: ".$pieceCount;
    
    echo "<br>";

    try{
        $conn->beginTransaction();

        $stmt = $conn->prepare("INSERT INTO hidrantes (id_serie, id_cliente, uuid, nb_esguicho,
                                nb_mangueira, tx_mangueira, tx_diam, tx_tipo, tx_local)
                        VALUES (:id_serie, :id_cliente, :uuid, :nb_esguicho, 
                        :nb_mangueira, :tx_mangueira, :tx_diam, :tx_tipo, :tx_local);
                            ");
    //array 1 => 0 => numero de serie
    //array 2 => 0 => tipo
    //array 3 => 0 => mangueiras
    //array 4 => 0 => qtd mangueiras
    //array 5 => 0 => diam
    //array 6 => 0 => qtd esgui
    //array 7 => 0 => local
    //array 8 => 0 => ano
    for($i = 0; $i < $pieceCount ; $i++){
        $uuid = "";
        $uuid = $uuid_client->formattedId($alphabet = '0123456789abcdefghijklmnopqrstuvwxyzQWERTYUIOPLKJHGFDSAZXCVBNM', $size = 9);

        $stmt->bindParam(':id_serie', $pieceList[1][$i]);
        $stmt->bindParam(':id_cliente', $cliente);
        $stmt->bindParam(':tx_tipo', $pieceList[2][$i]);  
        $stmt->bindParam(':tx_mangueira', $pieceList[3][$i]);
        $stmt->bindParam(':nb_mangueira', $pieceList[4][$i]);     
        $stmt->bindParam(':tx_diam', $pieceList[5][$i]);
        $stmt->bindParam(':nb_esguicho', $pieceList[6][$i]);     
        $stmt->bindParam(':tx_local', $pieceList[7][$i]);     
        $stmt->bindParam(':uuid', $uuid);

        $stmt->execute();

      }
    $conn->commit();
 
    }catch(PDOException $e)
		{
		echo "Erro ao cadastrar coleção de ".$pieceCount." hidrantes! " . $e->getMessage();
        $conn->rollback();
		}
		
		if($e == null){
            return true;
        }else{
            return false;
        }

}

?>