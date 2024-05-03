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
    
    function getEvents($conn){
        $stmt = $conn->query("SELECT c.tx_nome, p.dt_idata, p.dt_tdata, p.tx_codigo, p.tx_local, p.cs_estado, p.id_pedido FROM pedido p INNER JOIN cliente c ON p.id_cliente = c.id_cliente");
        $obj = $stmt->fetchAll(PDO::FETCH_OBJ);

        $item = 1;
        $data = '';
        $data .= '[';
        foreach($obj  AS $pedido){
            if($pedido->cs_estado == 1) {
                $color = cat_color(9);
                $status = 'Encerrado';
            }	
            if($pedido->cs_estado == 0){ 
                $color = cat_color($item);
                $status = 'Ativo';
                }
            $url = "#";
            $periodo = 'Início: '.data_usql($pedido->dt_idata).' - Término: '.data_usql($pedido->dt_tdata);
            
            $data .= "{ title: '".$pedido->tx_nome."', pedido:'".$pedido->tx_codigo."', start:'".$pedido->dt_idata."', end:'".$pedido->dt_tdata."T18:00:00',url:'".$url."', color:'".$color."', status:'".$status."', periodo:'".$periodo."', allDay: false},";
            $item += 1;
        }
        $data .= ']';

        echo $data;
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