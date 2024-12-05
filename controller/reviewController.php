<?php

function data_usqlS($data) {
    $ndata = substr($data, 8, 2) ."/". substr($data, 5, 2) ."/".substr($data, 0, 4);
    return $ndata;
} 
	
//Carrega dados basico para gerar select->options
function loadExtbasic($conn){

    $data = array();
    $string = array();
    $string['year_option'] = '';
    $string['cliente_option'] = '';
    $count_a = 0;
    $count_b = 0;

    // YEAR DISTINC (load year option)
    $result = $conn->query("SELECT DISTINCT YEAR(dt_inspecao) AS dt_inspecao
                            FROM `extintores_insp` 
                            ORDER BY dt_inspecao DESC;");
    $data[0] = $result->fetchAll(PDO::FETCH_ASSOC);                        
    // CLIENTE DISTINC (load cliente option)
    $result = $conn->query("SELECT DISTINCT ins.id_cliente, cl.tx_nome_reduzido
                             FROM `extintores_insp` AS  `ins`
                             JOIN `cliente`AS `cl`ON ins.id_cliente = cl.id_cliente;");
    $data[1] = $result->fetchAll(PDO::FETCH_ASSOC);

    foreach($data[0] as $row){
        if($count_a == 0){
            $string['year_option'] .= '<option selected value='.$row["dt_inspecao"].'>'.$row["dt_inspecao"].'</option>';
        } else{
            $string['year_option'] .= '<option value='.$row["dt_inspecao"].'>'.$row["dt_inspecao"].'</option>';
        }

        $count_a++;
    }

    foreach($data[1] as $row){
        if($count_b == 0){
            $string['cliente_option'] .= '<option selected value='.$row["id_cliente"].'>'.$row["tx_nome_reduzido"].'</option>';
        } else {
            $string['cliente_option'] .= '<option value='.$row["id_cliente"].'>'.$row["tx_nome_reduzido"].'</option>';
        }
        
        $count_b++;
    }

    return $string;

}
/** OLD MODE 30-DAY
function loadPeriodo($year,$month,$day){

    $data = ['', ''];
    
    if($month < 2){
        $data[0] .= ($year-1).'-12-'.$day;
        $data[1] .= $year.'-1-'.$day;
    } else {
        $data[0] .= $year.'-'.($month-1).'-'.$day;
        $data[1] .= $year.'-'.$month.'-'.$day;
    }
    
    return $data;
    
}
*/
//NEW MODE - DIRECT
function loadPeriodo($year,$month,$day){

    $data = ['', ''];
    
    $data[0] .= $year.'-'.$month.'-1';
    $data[1] .= $year.'-'.$month.'-'.$day;
    
    return $data;
    
}

function getInspExtintores($conn,$cliente){

    $stmt = $conn->query("SELECT * FROM pgsolimoes_inspecao_extintores");
    $result = $stmt->fetchAll(PDO::FETCH_OBJ);
    
    header('Content-type:application/json');
        $output = json_encode($result);
        echo $output;

}

function getExtReview($conn,$data){

    $id_cliente = $data['cliente'];
    $periodo = loadPeriodo( $data['ano'],$data['mes'],$data['dia']);
    $result = array();

    // TOTAL_POSIÇÕES
    $stmt = $conn->query("SELECT COUNT(id_posicao) AS posicoes, id_cliente 
                            FROM cliente_map
                            WHERE id_posicao < 900 AND id_cliente = $id_cliente
                            GROUP BY id_cliente;
                            ");
    $result_a = $stmt->fetchAll(PDO::FETCH_OBJ);
    
    if($result_a){
            $result['total'] = $result_a[0]->posicoes;
        }else{
            $result['total'] = 0;
        }

    $stmt = NULL;

    // TOTAL_INSPEÇÕES
    $stmt = $conn->query("SELECT COUNT(dt_inspecao) AS total_insp, id_cliente AS cliente
                            FROM extintores_insp
                            WHERE id_cliente = $id_cliente 
                            AND dt_inspecao >= '$periodo[0]' AND dt_inspecao <= '$periodo[1]'
                            GROUP BY id_cliente;
                            ");

    $result_b = $stmt->fetchAll(PDO::FETCH_OBJ);
    
    if($result_b){
        $result['total_insp'] = $result_b[0]->total_insp;
    }else{
        $result['total_insp'] = 0;
    }

    $stmt = NULL;

    // TOTAL_N/C
    $stmt = $conn->query("SELECT COUNT(dt_inspecao) AS total_nc, id_cliente AS cliente
                            FROM extintores_insp
                            WHERE id_cliente = $id_cliente 
                            AND dt_inspecao >= '$periodo[0]' AND dt_inspecao <= '$periodo[1]' AND nb_desvio >= 6
                            GROUP BY id_cliente;
                            ");  
                            
    $result_c = $stmt->fetchAll(PDO::FETCH_OBJ);

    if($result_c){
        $result['total_nc'] = $result_c[0]->total_nc;
    }else{
        $result['total_nc'] = 0;
    }

    $stmt = NULL;
    
    //$result['cliente'] = $id_cliente;
    //$result['periodo_0'] = $periodo[0];
    //$result['periodo_1'] = $periodo[1];

    $result['nao_insp'] = $result['total'] - $result['total_insp'];
    $result['desvio'] =  $result['nao_insp'] + $result['total_nc'];
    if($result['total'] != 0){
        $result['final'] = ( $result['desvio'] / $result['total'] ) * 100;
    }else {
        $result['final'] = 0;
    }    
    $result['desvio'] = 100 - $result['final'];
    $result['final'] = number_format($result['final'],2);
    $result['desvio'] = number_format($result['desvio'],2);

    header('Content-type:application/json');
        $output = json_encode($result);
        echo $output;
    
}

