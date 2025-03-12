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
    function getAlocacao($conn,$id_bombeiro){

        $stmt = $conn->query("SELECT c.tx_nome, c.id_cliente 
                        FROM bombeiro_aloc b 
                        JOIN cliente c ON b.id_cliente = c.id_cliente
                        WHERE b.id_bombeiro = $id_bombeiro
                        ");
        $obj = $stmt->fetchAll(PDO::FETCH_OBJ);

        $data = '';
        
        foreach($obj as $cliente){
            $data .= '<option value='.$cliente->id_cliente.'>';
            $data .= $cliente->tx_nome;        
            $data .= '</option>';
         
        }
        
        return $data;
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

    function getStatus($data){

        switch($data){
            case 0: 
                $result[0] = 'Pendente';
                $result[1] = 'table-warning';
                break;
            case 1: 
                $result[0] = 'Em Andamento';
                $result[1] = 'table-info';
                break;
            case 2: 
                $result[0] = 'Serviço Executado';
                $result[1] = 'table-success';
                break;
            case 3: 
                $result[0] = 'Não Executado';
                $result[1] = 'table-danger';
                break;
        }

        return $result;
    }

    function getLastestDefeitos($conn,$id_bombeiro){

        $data = '<p>Você ainda não cadastrou nenhum defeito.<p>';

        $e = null;
        
        $stmt = $conn->prepare("SELECT def.id_defeito, DATE_FORMAT(created_at, '%H:%i %d/%m/%y') AS data_criacao, def.tx_local, def.tx_sistema, cl.tx_nome_reduzido, def.nb_status
                                FROM insp_defeitos AS def
                                JOIN cliente AS cl ON def.id_cliente = cl.id_cliente
                                WHERE def.id_bombeiro = :id_bombeiro
                                ORDER BY def.id_defeito DESC
                                LIMIT 5");
        $stmt->bindParam(':id_bombeiro', $id_bombeiro);                        
        $stmt->execute();

        $obj = $stmt->fetchAll(PDO::FETCH_OBJ);

        if(!$obj){
            return $data;
        }

        $data = NULL;
        $data .= '<table class="table"><thead><tr>
        <th>Item</th>
        <th>Planta</th>
        <th>Sistema</th>
        <th>Local</th>
        <th>Status</th>
        <th>Data</th>
        </tr></thead><tbody>';

        foreach($obj as $defeito){
            $status = getStatus($defeito->nb_status);
            $data .= '<tr class="'.$status[1].'"><td>'.$defeito->id_defeito.'</td>';
            $data .= '<td>'.$defeito->tx_nome_reduzido.'</td>';
            $data .= '<td>'.$defeito->tx_sistema.'</td>';
            $data .= '<td>'.$defeito->tx_local.'</td>';
            $data .= '<td>'.$status[0].'</td>';
            $data .= '<td>'.$defeito->data_criacao.'</td></tr>';
            
        }
        
        $data .= '</tbody></table>';

        $obj = NULL;
        
        return $data;
    }

    function getvencimentosN3($conn,$id_cliente,$id_bombeiro){
    
        $data = '';
        $id_list = array(1,2);    

    if(in_array($id_bombeiro,$id_list)){

        $current_year = date("Y", $_SERVER['REQUEST_TIME']);

        $stmt = $conn->prepare("SELECT ext.id_serie, ext.dt_vencimenton3, map.id_posicao
                                FROM extintores AS ext
                                JOIN cliente_map AS map ON ext.id_serie = map.id_serie
                                WHERE ext.id_cliente = :id_cliente AND
                                      ext.dt_vencimenton3 <= :current_year
                                ORDER BY map.id_posicao ASC;
                                ");
        $stmt->bindParam(':id_cliente', $id_cliente);
        $stmt->bindParam(':current_year', $current_year);
        $stmt->execute();

        $obj = $stmt->fetchAll(PDO::FETCH_OBJ);

        if(!$obj){
            return $data;
        }

        $total = count($obj);

        $data .= '<table><tr><th>Nº</th><th>NºSÉRIE</th><th>VENCIMENTO N3</th></tr>';

        foreach($obj as $extintor){
            $data .= '<tr><td>'.$extintor->id_posicao.'</td>';
            $data .= '<td>'.$extintor->id_serie.'</td>';
            $data .= '<td>'.$extintor->dt_vencimenton3.'</td></tr>';
        }

        $data .= '<tr><td></td><td>TOTAL</td><td>'.$total.'</td></tr></table>';
        
        $obj = NULL;
    }

        return $data;

    }

    function getvencimentosN2($conn,$id_cliente,$id_bombeiro){

        $data = '';
        $id_list = array(1,2);    

        if(in_array($id_bombeiro,$id_list)){

        $current_year = date("Y", $_SERVER['REQUEST_TIME']);
        $current_month = date("m", $_SERVER['REQUEST_TIME']);

        $stmt = $conn->prepare("SELECT * FROM 
                        (SELECT ext.id_serie, (RIGHT(ext.dt_vencimenton2,2)+2000) AS ano_n2, 
                        LEFT(ext.dt_vencimenton2,3) AS mes_n2, ext.dt_vencimenton2, map.id_posicao 
                        FROM extintores AS ext 
                        JOIN cliente_map AS map ON ext.id_serie = map.id_serie 
                        WHERE ext.id_cliente = :id_cliente) tb1 
                        WHERE tb1.ano_n2 <= :current_year 
                        ORDER BY id_posicao ASC; 
                                ");
        $stmt->bindParam(':id_cliente', $id_cliente);
        $stmt->bindParam(':current_year', $current_year);
        $stmt->execute();

        $obj = $stmt->fetchAll(PDO::FETCH_OBJ);
        
        if(!$obj){
            return $data;
        }
        $total = count($obj);
        
        $data .= '<table><tr><th>Nº</th><th>NºSÉRIE</th><th>VENCIMENTO N2</th></tr>';

        foreach($obj as $extintor){
            $data .= '<tr><td>'.$extintor->id_posicao.'</td>';
            $data .= '<td>'.$extintor->id_serie.'</td>';
            $data .= '<td>'.$extintor->dt_vencimenton2.'</td></tr>';
        }

        $data .= '<tr><td></td><td>TOTAL</td><td>'.$total.'</td></tr></table>';
        
        $obj = NULL;
        }

        return $data;

    }

function insertDefeito($conn,$data){
        $e = null;
    
        try{
        $stmt = $conn->prepare("INSERT INTO insp_defeitos
                            (id_bombeiro, id_cliente, tx_defeito, tx_local, tx_sistema, tx_tipo)
                            VALUES 
                            (:id_bombeiro, :id_cliente, :tx_defeito, :tx_local, :tx_sistema, :tx_tipo)");
        
        $stmt->bindParam(':tx_defeito', $data['defeito']);
        $stmt->bindParam(':id_bombeiro', $data['bombeiro']);
        $stmt->bindParam(':tx_sistema', $data['sistema']);
        $stmt->bindParam(':tx_tipo', $data['tipo']);
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


?>