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
    }

	header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
	header("Pragma: no-cache"); // HTTP 1.1
	header("Expires: 0"); //

    $ext = $_SESSION['extintor'];
    $resp = 0;

    require("./DB/conn.php");
    require("./controller/extintorController.php");
	
    $inspecao = getExtLastInspection($conn,$ext);

    if(isset($_POST['submit'])){
        $data['nb_desvio'] = 0;
        $data['id_serie'] = $_SESSION['extintor'];
        $data['tx_coment'] = $_POST['comentario'];
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
             $data['nb_desvio'] += 1;
         }else{ $data['ch7'] = 'C';}
        if(isset($_POST['ch8'])) {
             $data['ch8'] = 'N/C';
             $data['nb_desvio'] += 1;
         }else{ $data['ch8'] = 'C';}
        if(isset($_POST['ch9'])) {
             $data['ch9'] = 'N/C';
             $data['nb_desvio'] += 1;
         }else{ $data['ch9'] = 'C';}
        if(isset($_POST['ch10'])) {
             $data['ch10'] = 'N/C';
             $data['nb_desvio'] += 1;
        }else{  $data['ch10'] = 'C';}
       if(isset($_POST['ch11'])) {
             $data['ch11'] = 'N/C';
             $data['nb_desvio'] += 1;
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
             $data['nb_desvio'] += 1;
        }else{  $data['ch19'] = 'C';}
        if(isset($_POST['ch20'])) {
             $data['ch20'] = 'N/C';
             $data['nb_desvio'] += 1;
        }else{  $data['ch20'] = 'C';}

        unset($_SESSION['extintor']); 
        unset($_POST);
        
        $resp = insertInspecaoExtintor($conn,$data);
    }

	date_default_timezone_set('America/Manaus');

    $extintor = getExtintor($conn,$ext);
    
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
				<li class="nav-item px-3">
				<a class="nav-link text-white" style="font-weight: 800;" href=""><i class="nav-icon cui-user"></i><?php echo "  ".mb_strtoupper($_SESSION['usuario'], 'UTF-8');?></a>
				</li>
			</ul>
			<ul class="nav navbar-nav m-auto">
				<li class="nav-item px-3">
				<a class="btn btn-light" href="logout.php?token=<?php echo md5(session_id());?>">Logout <i class="nav-icon cui-account-logout"></i></a>
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
                <div class="row mt-1">
				<div class="col-9">
				<h3><i class="nav-icon cui-task"></i><cite> Sistema FireSystems</cite> - Inspeção de Extintor:</h3>
				</div>
				<div class='col-3'>
					<h3 class='btn btn-outline-danger float-right'> <?php echo "Data Atual: ".date("d/m/Y H:i:s", $_SERVER['REQUEST_TIME']);?></h3>
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
                <h4><i class="nav-icon cui-note"></i>Capacidade: <?php echo $extintor['tx_capacidade'];?></h4>
                <h4><i class="nav-icon cui-note"></i>Nº Série: <i><?php echo $extintor['id_serie'];?></i> - Selo Inmetro: <i><?php echo $extintor['tx_inmetro'];?></i></h4>
                <h4><i class="nav-icon cui-calendar"></i> Última Inspeção: <?php echo $inspecao['dt_inspecao'];?></h4>
                <h4><i class="nav-icon cui-calendar text-danger"></i> Vencimento: <?php echo $inspecao['dt_inspecao'];?></h4>
                <div class="card" <?php if($resp == 1 || $inspecao['insp_block'] == 1) echo "hidden";?>>
					<div class="card-header">
						<h3 class="text-xl"><i class="nav-icon cui-note"></i> NÃO CONFORMIDADES: </h3>
					</div>   
				
                <table class="table table-borderless m-2">
                <tr class="m-2">
                    <td>
                    <label class="btn btn-outline-danger" for="ch1">Sinal Vertical</label>    
                    <input type="checkbox" class="btn-check" id="ch1" name="ch1" value="N/C">
                    </td>
                    <td>
                    <label class="btn btn-outline-danger" for="ch2">Sinal Horizontal</label>    
                    <input type="checkbox" class="btn-check" id="ch2" name="ch2" value="N/C">
                    </td>
                    <td>
                    <label class="btn btn-outline-danger" for="ch3">Local Adequado</label>    
                    <input type="checkbox" class="btn-check" id="ch3" name="ch3" value="N/C">
                    </td>
                </tr>
                <tr class="m-2">
                    <td>
                    <label class="btn btn-outline-danger" for="ch4">Acesso Obstruído</label>    
                    <input class="btn-check" type="checkbox" id="ch4" name="ch4" value="N/C">
                    </td>
                    <td>
                    <label class="btn btn-outline-danger" for="ch5">Agente Ext. Adequado</label>    
                    <input class="btn-check" type="checkbox" id="ch5" name="ch5" value="N/C">
                    </td>
                    <td>
                    <label class="btn btn-outline-danger" for="ch6">Suporte</label>    
                    <input class="btn-check" type="checkbox" id="ch6" name="ch6" value="N/C">
                    </td>
                </tr>
                <tr class="m-2">
                    <td>
                    <label class="btn btn-outline-danger" for="ch7">Pressão Nominal</label>    
                    <input class="btn-check" type="checkbox" id="ch7" name="ch7" value="N/C">
                    </td>
                    <td>
                    <label class="btn btn-outline-danger" for="ch8">Teste Hidrostático</label>    
                    <input class="btn-check" type="checkbox" id="ch8" name="ch8" value="N/C">
                    </td>
                    <td>
                    <label class="btn btn-outline-danger" for="ch9">Carga / Peso</label>    
                    <input class="btn-check" type="checkbox" id="ch9" name="ch9" value="N/C">
                    </td>
                </tr>
                <tr class="m-2">
                    <td>
                    <label class="btn btn-outline-danger" for="ch10">Manômetro</label>    
                    <input class="btn-check" type="checkbox" id="ch10" name="ch10" value="N/C">
                    </td>
                    <td>
                    <label class="btn btn-outline-danger" for="ch11">Cilindro</label>    
                    <input class="btn-check" type="checkbox" id="ch11" name="ch11" value="N/C">
                    </td>
                    <td>
                    <label class="btn btn-outline-danger"  for="ch12">Etiqueta</label>    
                    <input class="btn-check" type="checkbox" id="ch12" name="ch12" value="N/C">
                    </td>
                </tr>
                <tr class="m-2">
                    <td>
                    <label class="btn btn-outline-danger" for="ch13">Rótulo</label>    
                    <input class="btn-check" type="checkbox" id="ch13" name="ch13" value="N/C">
                    </td>
                    <td>
                    <label class="btn btn-outline-danger" for="ch14">Alça</label>    
                    <input class="btn-check" type="checkbox" id="ch14" name="ch14" value="N/C">
                    </td>
                    <td>
                    <label class="btn btn-outline-danger"  for="ch15">Gatilho</label>    
                    <input class="btn-check" type="checkbox" id="ch15" name="ch15" value="N/C">
                    </td>
                </tr>
                <tr class="m-2">
                    <td>
                    <label class="btn btn-outline-danger" for="ch16">Trava</label>    
                    <input class="btn-check" type="checkbox" id="ch16" name="ch16" value="N/C">
                    </td>
                    <td>
                    <label class="btn btn-outline-danger" for="ch17">Lacre</label>    
                    <input class="btn-check" type="checkbox" id="ch17" name="ch17" value="N/C">
                    </td>
                    <td>
                    <label class="btn btn-outline-danger" for="ch18">Mangueira</label>    
                    <input class="btn-check" type="checkbox" id="ch18" name="ch18" value="N/C">
                    </td>
                </tr>
                <tr class="m-2">
                    <td>
                    <label class="btn btn-outline-danger" for="ch19">Punho</label>    
                    <input class="btn-check" type="checkbox" id="ch19" name="ch19" value="N/C">
                    </td>
                    <td>
                    <label class="btn btn-outline-danger" for="ch20">Difusor</label>    
                    <input class="btn-check" type="checkbox" id="ch20" name="ch20" value="N/C">
                    </td>
                </tr>
                </table>
				<div class="input-group">
                    <h5 class="input-group-text">Comentário: ( {{multi.textData.length}} / 256 caracteres)</h5>  
                        <textarea v-model="multi.textData" class="form-control" maxlenght="256" row="3" name="comentario"></textarea>    
				</div>	
                </div>
				<div class="d-grid gap-2 col-3 mx-auto" 
                <?php if($resp == 1 || $inspecao['insp_block'] == 1) echo "hidden";?>>
					<button type="submit" value="submit" name="submit" style="font-weight: bold;"
					class="btn btn-outline-primary btn-lg"><i class="nav-icon cui-pencil"></i>
					CADASTRAR INSPEÇÃO
					</button>
				</div>
                <?php if($resp == 1) echo '<div class="row my-2"><div class="d-grid gap-2 col-3 mx-auto">
					<a href="central.php"><button style="font-weight: bold; overflow: overflow-wrap;"
					class="btn btn-success btn-lg"><i class="nav-icon cui-check"></i>
					INSPEÇÃO CADASTRADA COM SUCESSO!
					</button></a>
				    </div></div>';?>
                    <?php if($inspecao['insp_block'] == 1) echo '<div class="row my-2"><div class="d-grid gap-2 col-3 mx-auto">
					<a href="central.php"><button style="font-weight: bold; overflow: overflow-wrap;"
					class="btn btn-warning btn-lg"><i class="nav-icon cui-check"></i>
					INSPEÇÃO JÁ REALIZADA NESTE ITEM
					</button></a>
				    </div></div>';?>
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

    </script>
 </body> 
 
</html> 