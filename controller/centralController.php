<?php
//	session_start();
	//Modo por cnpj login='tx_cnpj' e userid='id_cliente'
	

	header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
	header("Pragma: no-cache"); // HTTP 1.0.
	header("Expires: 0");
	
	
	$e = null;
	$stmt = null;
	
	
	function data_usql($data) {
		$ndata = substr($data, 8, 2) ."/". substr($data, 5, 2) ."/".substr($data, 0, 4);
		return $ndata;
	}
    
    function cat_color($cat){
		$color = '#343236';
		if($cat == 9) $color = '#777777';
		if($cat == 5) $color = '#ce3500';
		if($cat == 6) $color = '#f8a300';
		if($cat == 7) $color = '#65623c';
		if($cat == 8) $color = '#46554f';
		if($cat == 1) $color = '#457725';
		if($cat == 2) $color = '#646e83';
		if($cat == 3) $color = '#09568d';
		if($cat == 4) $color = '#172035';
			
		return $color;
    }
    
    function getInspectEvents($conn,$id_bombeiro){
        
        
        $stmt = $conn->query("SELECT id_bombeiro, DATE_FORMAT(dt_inspecao, '%Y-%m-%d') AS data_inspecao, COUNT(*) AS numero_insp
                              FROM extintores_insp 
                              WHERE id_bombeiro = $id_bombeiro
                              GROUP BY data_inspecao
                              HAVING COUNT(*) > 0;
                              ");
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $total = 0;
        $events = [];
        $i = 0;
        $color = cat_color(1);

        foreach($data AS $row){
            
            $event['title'] = $row['numero_insp'].' Inspeções Realizadas';
            $event['start'] = $row['data_inspecao'];
            $event['end'] = $row['data_inspecao'].'T18:00:00';
            $event['color'] = $color;
            $event['allDay'] = 'false';
            $total += $row['numero_insp'];
            $events[$i]=$event;
            $i++;

        }
        
        //$events['total'] = $total;

        return $events;

    }
    
    function selectPedidos($conn){
        $stmt = $conn->query("SELECT c.tx_nome, p.tx_codigo, p.tx_local, p.id_pedido FROM pedido p INNER JOIN cliente c ON p.id_cliente = c.id_cliente");
        $obj = $stmt->fetchAll(PDO::FETCH_OBJ);

        $data = '';
        foreach($obj as $pedido){
            $data .= '<option value='.$pedido->id_pedido.'>';
            $data .= $pedido->tx_nome.' - '.$pedido->tx_codigo.' - '.$pedido->tx_local;        
            $data .= '</option>';

            
        }
        
        echo $data;
    }
?>