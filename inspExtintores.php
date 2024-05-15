<?php 

$key = include("./config/key.php");

	// Inicia sessões
	session_start(); 
    $access = $_SESSION['temp-k'];
	//echo session_status(); 
	// Verifica se existe os dados da sessão de login 
	if($access  !== $key['key']){
	// Usuário não logado! Redireciona para a página de login 
        header("Location: login.php"); 	
        exit();
	} 
    if(!$_SESSION['extintor']){
        header("Location: central.php");
        exit();
    }

	header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
	header("Pragma: no-cache"); // HTTP 1.1
	header("Expires: 0"); //

    $ext = $_SESSION['extintor'];
    $resp = 0;

    require("./DB/conn.php");
    require("./controller/extintorController.php");

	date_default_timezone_set('America/Manaus');
    $inspecao = getExtLastInspection($conn,$ext);
    $extintor = getExtintor($conn,$ext);
    
    if(isset($_POST['submit']) && $_POST['submit'] == 'submit'){
        $data['nb_desvio'] = 0;
        $data['id_serie'] = $_SESSION['extintor'];
        $data['comentario'] = $_POST['comentario'];
        $data['bombeiro'] = $_SESSION['userid'];
        $data['predio'] = $extintor['tx_predio'];
        $data['area'] = $extintor['tx_area'];
        $data['localiz'] = $extintor['tx_localiz'];
        $data['inmetro'] = $extintor['tx_inmetro'];
        $data['cliente'] = $extintor['id_cliente'];
        if(isset($_POST['ch1'])) {
             $data['ch1'] = 'N/C';
             $data['nb_desvio'] += 1;
         }else{ $data['ch1'] = 'C';}
        if(isset($_POST['ch2'])) {
             $data['ch2'] = 'N/C';
             $data['nb_desvio'] += 1;
         }else{ $data['ch2'] = 'C';}
        if(isset($_POST['ch3'])) {
             $data['ch3'] = 'N/C';
             $data['nb_desvio'] += 1;
         }else{ $data['ch3'] = 'C';}
        if(isset($_POST['ch4'])) {
             $data['ch4'] = 'N/C';
             $data['nb_desvio'] += 1;
         }else{ $data['ch4'] = 'C';}
        if(isset($_POST['ch5'])) {
             $data['ch5'] = 'N/C';
             $data['nb_desvio'] += 1;
         }else{ $data['ch5'] = 'C';}
        if(isset($_POST['ch6'])) {
             $data['ch6'] = 'N/C';
             $data['nb_desvio'] += 1;
         }else{ $data['ch6'] = 'C';}
        if(isset($_POST['ch7'])) {
             $data['ch7'] = 'N/C';
             $data['nb_desvio'] += 6;
         }else{ 
             $extintor['cs_checkbox'] == 4 ? $data['ch7'] = 'N/A' : $data['ch7'] = 'C';
        }
        if(isset($_POST['ch8'])) {
             $data['ch8'] = 'N/C';
             $data['nb_desvio'] += 6;
         }else{ $data['ch8'] = 'C';}
        if(isset($_POST['ch9'])) {
             $data['ch9'] = 'N/C';
             $data['nb_desvio'] += 6;
         }else{
            $extintor['cs_checkbox'] == 4 ? $data['ch9'] = 'C' : $data['ch9'] = 'N/A';
            }
        if(isset($_POST['ch10'])) {
             $data['ch10'] = 'N/C';
             $data['nb_desvio'] += 6;
        }else{  
            $extintor['cs_checkbox'] == 4 ? $data['ch10'] = 'N/A' : $data['ch10'] = 'C';
        }
       if(isset($_POST['ch11'])) {
             $data['ch11'] = 'N/C';
             $data['nb_desvio'] += 6;
        }else{  $data['ch11'] = 'C';}
        if(isset($_POST['ch12'])) {
             $data['ch12'] = 'N/C';
             $data['nb_desvio'] += 1;
        }else{  $data['ch12'] = 'C';}
        if(isset($_POST['ch13'])) {
             $data['ch13'] = 'N/C';
             $data['nb_desvio'] += 1;
        }else{  $data['ch13'] = 'C';}
        if(isset($_POST['ch14'])) {
             $data['ch14'] = 'N/C';
             $data['nb_desvio'] += 1;
        }else{  $data['ch14'] = 'C';}
        if(isset($_POST['ch15'])) {
             $data['ch15'] = 'N/C';
             $data['nb_desvio'] += 1;
        }else{  $data['ch15'] = 'C';}
        if(isset($_POST['ch16'])) {
            $data['ch16'] = 'N/C';
            $data['nb_desvio'] += 1;
        }else{  $data['ch16'] = 'C';}
        if(isset($_POST['ch17'])) {
            $data['ch17'] = 'N/C';
            $data['nb_desvio'] += 1;
        }else{  $data['ch17'] = 'C';}
        if(isset($_POST['ch18'])) {
            $data['ch18'] = 'N/C';
            $data['nb_desvio'] += 1;
        }else{  $data['ch18'] = 'C';}
        if(isset($_POST['ch19'])){
            $data['ch19'] = 'N/C';
            $data['nb_desvio'] += 6;
        }else{  
            $extintor['cs_checkbox'] == 4 ? $data['ch19'] = 'C' : $data['ch19'] = 'N/A';
        }
        if(isset($_POST['ch20'])) {
             $data['ch20'] = 'N/C';
             $data['nb_desvio'] += 1;
        }else{  
            $extintor['cs_checkbox'] == 4 ? $data['ch20'] = 'C' : $data['ch20'] = 'N/A';
        }

        unset($_SESSION['extintor']); 
        unset($_POST);
        
        $resp = insertInspecaoExtintor($conn,$data);
    }
    $ext = '';
    
?>

<!DOCTYPE html>
<html><head>
	<meta lang='pt-BR'>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	
	<title>FireSystem - Inspeção de Extintor</title>
	<link rel="stylesheet" href="./assets/css/jquery-ui.min.css">
	<link rel="stylesheet" href="./dist/css/coreui.min.css">
	<link rel="stylesheet" href="./dist/css/coreui-icons.min.css">

		<script src="./assets/js/jquery-3.6.0.min.js"></script>
		<script src="./assets/js/jquery-ui.min.js"></script>
		<script src="./assets/js/jquery.ajax.form.js"></script>
		<script src="./dist/js/bootstrap.bundle.min.js"></script>
		<script src="./assets/js/coreui.min.js"></script>

</head>

<body class="app header-fixed">
<header class='app-header navbar' style='background: #2f353a; border-bottom: 4px solid #a60117;'>
			<ul class="nav navbar-nav m-auto">
				<li class="nav-item p-2">
				<a class="nav-link text-white" style="font-weight: 600;" href=""><i class="nav-icon cui-user"></i><?php echo "  ".mb_strtoupper($_SESSION['usuario'], 'UTF-8');?></a>
				</li>
			</ul>
			<ul class="nav navbar-nav m-auto">
				<li class="nav-item p-2">
				<a class="btn btn-outline-light" href="logout.php?token=<?php echo md5(session_id());?>">Logout <i class="nav-icon cui-account-logout"></i></a>
				</li>
			</ul>
</header>

<div class="app-body">	
<!-- Seção 0000, PARTE CENTRAL DOS DISPLAY DOS DADOS - USAR AJAX PARA NAVEGAR SEM SAIR DA CENTRAL -->
<main class="main" style="background-image:url('img/fire-back.jpg'); background-repeat: no-repeat; background-size: cover; background-attachment: fixed; background-position: top;">
	<div id="main">
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item active">Inspeção</li>
		</ol>
        
	</nav>
	<div class="container-fluid">
		<div class="card">
			<div class='card-header'>
                <div class="row">
				<div class="col-12">
				<h4 class='btn btn-outline-primary float-right'> <?php 
                $current_time = date("d/m/Y H:i:s", $_SERVER['REQUEST_TIME']);
                echo "Manaus, ".$current_time;?></h4>
				</div>
			    </div>
                <div class="row mt-1">
				<div class="col-12">
				<h3><i class="nav-icon cui-task"></i><cite> Sistema FireSystems</cite> - Inspeção de Extintor:</h3>
				</div>
			    </div>
			<div class="row mt-1">
				<div class="col-12 text-primary">
				<h4><i class="nav-icon cui-location-pin"></i> <?php echo $extintor['tx_nome'];?></h4>
                <h5><i class="nav-icon cui-location-pin"></i> <?php echo 'Prédio: '.$extintor['tx_predio'].' - Local: '.$extintor['tx_area'].', '.$extintor['tx_localiz'];?></h5>
				</div>
			</div>
			</div> 
			<form action="inspExtintores.php" method="POST">	
			<div class="card-body" id="inspEnvioMulti">	  
                <h3 class="text-xl mb-2 text-danger"><i class="nav-icon cui-magnifying-glass"></i> <?php echo $extintor['bool_carreta'].' '.$extintor['tx_tipo'];?></h3>
                <h4><i class="nav-icon cui-note"></i> Capacidade: <?php echo $extintor['tx_capacidade'];?></h4>
                <h4><i class="nav-icon cui-note"></i> Nº Série: <i><?php echo $extintor['id_serie'];?></i> - Selo Inmetro: <i><?php echo $extintor['tx_inmetro'];?></i></h4>
                <h4><i class="nav-icon cui-calendar"></i> Última Inspeção: <?php echo $inspecao['dt_inspecao'];?> <i class="text-warning text-sm"> <?php echo $inspecao['msg'];?></i></h4>
                <h4 class="text-primary"><i class="nav-icon cui-calendar"></i> Vencimento N2 (Carga): <?php echo $extintor['dt_vencimenton2'];?></h4>
                <h4 class="text-danger"><i class="nav-icon cui-calendar"></i> Vencimento N3 (Hidrostático): <?php echo $extintor['dt_vencimenton3'];?></h4>
                <div class="card" <?php if($resp == 1 || $inspecao['insp_block'] == 1) echo "hidden";?>>
					<div class="card-header">
						<h3 class="text-xl"><i class="nav-icon cui-note"></i> NÃO CONFORMIDADES: </h3>
					</div>   
				<div class='card-body'>
                <section class="m-2" 
                        style="display: grid;
                        gap: 1rem;
                        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
                        font-weight: 500;">
                    <div>
                    <label class="btn btn-outline-danger btn-lg" for="ch1">SINAL VERTICAL</label>    
                    <input type="checkbox" class="btn-check" id="ch1" name="ch1" value="1">
                    </div>
                    <div>
                    <label class="btn btn-outline-danger btn-lg" for="ch2">SINAL HORIZONTAL</label>    
                    <input type="checkbox" class="btn-check" id="ch2" name="ch2" value="1">
                    </div>
                    <div>
                    <label class="btn btn-outline-danger btn-lg" for="ch3">LOCAL ADEQUADO</label>    
                    <input type="checkbox" class="btn-check" id="ch3" name="ch3" value="1">
                    </div>
                
                
                    <div>
                    <label class="btn btn-outline-danger btn-lg" for="ch4">ACESSO OBSTRUÍDO</label>    
                    <input class="btn-check" type="checkbox" id="ch4" name="ch4" value="1">
                    </div>
                    <div>
                    <label class="btn btn-outline-danger btn-lg" for="ch5">AGENTE EXT. ADEQUADO</label>    
                    <input class="btn-check" type="checkbox" id="ch5" name="ch5" value="1">
                    </div>
                    <div>
                    <label class="btn btn-outline-danger btn-lg" for="ch6">SUPORTE</label>    
                    <input class="btn-check" type="checkbox" id="ch6" name="ch6" value="1">
                    </div>
                
                
                    <div>
                    <label class="btn 
                    <?php echo $extintor['cs_checkbox'] == 4 ? 'btn-outline-secondary btn-lg' : 'btn-outline-danger btn-lg';?>
                    " for="ch7">PRESSÃO NOMINAL</label>    
                    <input class="btn-check" type="checkbox" id="ch7" name="ch7" value="1" 
                    <?php if($extintor['cs_checkbox'] == 4) echo 'disabled';?>
                    >
                    </div>
                    <div>
                    <label class="btn btn-outline-danger btn-lg" for="ch8">TESTE HIDROSTÁTICO</label>    
                    <input class="btn-check" type="checkbox" id="ch8" name="ch8" value="1">
                    </div>
                    <div>
                    <label class="btn 
                    <?php echo $extintor['cs_checkbox'] == 4 ? 'btn-outline-danger btn-lg' : 'btn-outline-secondary btn-lg';?>
                    " for="ch9">CARGA / PESO</label>    
                    <input class="btn-check" type="checkbox" id="ch9" name="ch9" value="1" 
                    <?php if($extintor['cs_checkbox'] != 4) echo 'disabled';?>
                    >
                    </div>
                
                
                    <div>
                    <label class="btn 
                    <?php echo $extintor['cs_checkbox'] == 4 ? 'btn-outline-secondary btn-lg' :'btn-outline-danger btn-lg';?>
                    " for="ch10">MANÔMETRO</label>    
                    <input class="btn-check" type="checkbox" id="ch10" name="ch10" value="1"
                    <?php if($extintor['cs_checkbox'] == 4) echo 'disabled';?>
                    >
                    </div>
                    <div>
                    <label class="btn btn-outline-danger btn-lg" for="ch11">CILINDRO</label>    
                    <input class="btn-check" type="checkbox" id="ch11" name="ch11" value="1">
                    </div>
                    <div>
                    <label class="btn btn-outline-danger btn-lg"  for="ch12">ETIQUETA</label>    
                    <input class="btn-check" type="checkbox" id="ch12" name="ch12" value="1">
                    </div>
                
                
                    <div>
                    <label class="btn btn-outline-danger btn-lg" for="ch13">RÓTULO</label>    
                    <input class="btn-check" type="checkbox" id="ch13" name="ch13" value="1">
                    </div>
                    <div>
                    <label class="btn btn-outline-danger btn-lg" for="ch14">ALÇA</label>    
                    <input class="btn-check" type="checkbox" id="ch14" name="ch14" value="1">
                    </div>
                    <div>
                    <label class="btn btn-outline-danger btn-lg"  for="ch15">GATILHO</label>    
                    <input class="btn-check" type="checkbox" id="ch15" name="ch15" value="1">
                    </div>
                
                
                    <div>
                    <label class="btn btn-outline-danger btn-lg" for="ch16">TRAVA</label>    
                    <input class="btn-check" type="checkbox" id="ch16" name="ch16" value="1">
                    </div>
                    <div>
                    <label class="btn btn-outline-danger btn-lg" for="ch17">LACRE</label>    
                    <input class="btn-check" type="checkbox" id="ch17" name="ch17" value="1">
                    </div>
                    <div>
                    <label class="btn btn-outline-danger btn-lg" for="ch18">MANGUEIRA</label>    
                    <input class="btn-check" type="checkbox" id="ch18" name="ch18" value="1">
                    </div>
                
                
                    <div>
                    <label class="btn 
                    <?php echo $extintor['cs_checkbox'] == 4 ? 'btn-outline-danger btn-lg' : 'btn-outline-secondary btn-lg';?>
                    " for="ch19">PUNHO</label>    
                    <input class="btn-check" type="checkbox" id="ch19" name="ch19" value="1"
                    <?php if($extintor['cs_checkbox'] != 4) echo 'disabled';?>
                    >
                    </div>
                    <div>
                    <label class="btn 
                    <?php echo $extintor['cs_checkbox'] == 4 ? 'btn-outline-danger btn-lg' : 'btn-outline-secondary btn-lg';?>
                    " for="ch20">DIFUSOR</label>    
                    <input class="btn-check" type="checkbox" id="ch20" name="ch20" value="1"
                    <?php if($extintor['cs_checkbox'] != 4) echo 'disabled';?>
                    >
                    </div>
                
                </section>
                </div>
				<div class="form-floating p-2" id="inputComentario" hidden>
                    <h5><label for="comentario">Comentário: ( {{multi.textData.length}} / 256 caracteres)</label></h5>
                    <textarea v-model="multi.textData" class="form-control" maxlenght="256" row="3" name="comentario" id="comentario" style="height: 120px;"></textarea>    
				</div>	
                </div>
				<div class="container text-center"
                <?php if($resp == 1 || $inspecao['insp_block'] == 1) echo "hidden";?>>
					<button type="submit" value="submit" name="submit" style="font-weight: bold; white-space: normal;"
					class="btn btn-outline-primary btn-lg"><i class="nav-icon cui-pencil"></i>
					CADASTRAR INSPEÇÃO
					</button>
				</div>
                <?php if($resp == 1) echo '<div class="container text-center mt-3">
					<a href="central.php" style="font-weight: bold; white-space: normal;"
					class="btn btn-success btn-lg"><i class="nav-icon cui-check"></i>
					INSPEÇÃO CADASTRADA COM SUCESSO!
					</a>
				    </div>';?>
                    <?php if($inspecao['insp_block'] == 1) echo '<div class="container text-center mt-3">
					<a href="central.php" style="font-weight: bold; white-space: normal;"
					class="btn btn-warning btn-lg"><i class="nav-icon cui-check"></i>
					INSPEÇÃO JÁ REALIZADA NESTE ITEM
					</a>
				    </div>';?>
                </form>
                
        </div>
        
		</div>
    </div>			
	</div>
</main>
<!-- Div body-app Encerramento -->
</div>
	<footer class="app-footer">
		<div>
		<a href="http://www.firesystems-am.com.br">FireSystems-AM</a>
		<span>©2024 Produtos e Serviços Contra Incêndio </span>
		</div>
		<div class="ml-auto">
		<span>Sistema de Inspeção Online</span>
		
		</div>
	</footer>
		<!-- PETIT VUE ----------------------------------------------------->
		
		<script type="module">
        import { createApp, reactive } from "https://unpkg.com/petite-vue@0.4.1/dist/petite-vue.es.js?module";

        const textFile = "";
        const textSize = "";

        function readFile(event){
            this.textFile = event.target.value;
        };

        const multi = {
            textData: "",
            codCliente: ""
        };

        createApp({  multi }).mount("#inspEnvioMulti");

        const checkList = document.querySelectorAll("input[type='checkbox']");
        const textArea = document.getElementById("inputComentario");
        const countValue = 0;

        for (let i = 0; i < checkList.length; i++) {
            checkList[i].addEventListener("change", displayCheck);
        }

        function displayCheck (e) {         
        let counter = 0;
        for (let i = 0; i < checkList.length; i++) {
            if(checkList[i].checked == true) counter = counter + 1; 
            
        }
            counter == 0 ? textArea.hidden = true : textArea.hidden = false;
        };
    </script>
 </body> 
 
</html> 