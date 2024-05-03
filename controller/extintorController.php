<?php
	function data_usqlS($data) {
		$ndata = substr($data, 8, 2) ."/". substr($data, 5, 2) ."/".substr($data, 0, 4);
		return $ndata;
	} 
	
function base64_encode_url($string) {
    return str_replace(['+','/','='], ['-','_',''], base64_encode($string));
}

function base64_decode_url($string) {
    return base64_decode(str_replace(['-','_'], ['+','/'], $string));
}

function getExtLastInspection($conn,$ext){

    $stmt = $conn->prepare("SELECT id_inspecao, DATE_FORMAT(dt_inspecao, '%d/%m/%Y') AS dt_inspecao, DATE_FORMAT(dt_inspecao, '%m') AS dt_mes
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
    }
    }
    if(!$data){
        $data['dt_inspecao'] = 'Inspeção anterior não cadastrada.';
    }

    return $data;
    
}

function getExtintor($conn,$ext){

    $stmt = $conn->prepare("SELECT map.tx_predio, map.tx_area, map.tx_localiz, inm.tx_inmetro, ext.id_serie, ext.tx_tipo, ext.tx_capacidade, ext.bool_carreta, ext.cs_estado, cli.tx_nome
                        FROM extintores AS ext
                        INNER JOIN cliente AS cli ON ext.id_cliente = cli.id_cliente
                        INNER JOIN extintores_inm AS inm ON ext.id_serie = inm.id_serie
                        INNER JOIN cliente_map AS map ON ext.id_serie = map.id_serie
                        WHERE ext.id_serie LIKE :id_serie");
    $stmt->bindParam(':id_serie', $ext);  
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

function insertExtintores($conn,$data){

    $e = null;
    $pieceCount = 0;
    $pieceList = array();
    $pieceText = array();
    $str = $data['textData'];
    $cliente = $data['codCliente'];

    $regCodigo = '/(.*),(.*),(.*),(.*),(.*),(.*),(.*)/';
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

        $stmt = $conn->prepare("INSERT INTO extintores (id_serie, tx_tipo, tx_capacidade, bool_carreta, id_cliente, base64_serie )
                        VALUES (:id_serie, :tx_tipo, :tx_capacidade, :bool_carreta, :id_cliente, :base64_serie );
                            ");
        $stmt2 = $conn->prepare("INSERT INTO extintores_inm (id_serie, tx_inmetro)
                        VALUES (:id_serie, :tx_inmetro);
                        ");
        $stmt3 = $conn->prepare("INSERT INTO cliente_map (id_serie, id_cliente, tx_predio, tx_area, tx_localiz)
                        VALUES (:id_serie, :id_cliente, :tx_predio, :tx_area, :tx_localiz);
                        ");
    //array 1 => 0 => numero de serie
    //array 2 => 0 => tipo
    //array 3 => 0 => capacidades
    //array 4 => 0 => inmetro
    //array 5 => 0 => predio
    //array 6 => 0 => area
    //array 7 => 0 => localizacao
    for($i = 0; $i < $pieceCount ; $i++){
        $base64 = "";
        $base64 = base64_encode_url($pieceList[1][$i]);
        $numeric = "";
        preg_match('/(\d*)/', $pieceList[3][$i] , $numeric);
        $numeric = $numeric[0] + 0;
        echo "<br>Listagem de parametros: Extintor count:".$i."<br>";
        echo $pieceList[1][$i]."<br>";
        echo $base64."<br>";  
        echo $pieceList[2][$i]."<br>";  
        echo $pieceList[3][$i]."<br>";  
        echo $pieceList[4][$i]."<br>";  
        echo $pieceList[5][$i]."<br>";  
        echo $pieceList[6][$i]."<br>";  
        echo $pieceList[7][$i]."<br>";
        if($numeric > 15){
            echo "Carreta<br>";
        }
        else{
            echo "Extintor Portátil<br>";
        }

        $stmt->bindParam(':id_serie', $pieceList[1][$i]);  
        $stmt->bindParam(':tx_tipo', $pieceList[2][$i]);  
        $stmt->bindParam(':tx_capacidade', $pieceList[3][$i]);     
        if($numeric > 15){
            $stmt->bindValue(':bool_carreta',1);
        }
        else{
            $stmt->bindValue(':bool_carreta',0);
        }
        $stmt->bindParam(':id_cliente', $cliente);
        $stmt->bindParam(':base64_serie', $base64);

        $stmt2->bindParam(':id_serie', $pieceList[1][$i]);
        $stmt2->bindParam(':tx_inmetro', $pieceList[4][$i]);
        
        $stmt3->bindParam(':id_serie', $pieceList[1][$i]); 
        $stmt3->bindParam(':id_cliente', $cliente);
        $stmt3->bindParam(':tx_predio', $pieceList[5][$i]);
        $stmt3->bindParam(':tx_area', $pieceList[6][$i]);
        $stmt3->bindParam(':tx_localiz', $pieceList[7][$i]);

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