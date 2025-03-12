<?php 
	$key = include("./config/key.php");
	// Inicia sessões
	session_start(); 
	//echo session_status(); 
	// Verifica se existe os dados da sessão de login 
	if(!isset($_SESSION["login"]) || !isset($_SESSION["usuario"])) 
		{ 
	// Usuário não logado! Redireciona para a página de login 
		header("Location: login.php"); 
		exit(); 
	}

	$access = $_SESSION['temp-k'];
	//echo session_status(); 
	// Verifica se existe os dados da sessão de login 
	if($access  !== $key['key']){
	// Usuário não logado! Redireciona para a página de login 
        header("Location: login.php"); 	
        exit();
	} 
	
	if($_SESSION['ativo'] == 0){
		header('HTTP/1.1 403 Forbidden');
		exit(); 
	}

	
	header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
	header("Pragma: no-cache"); // HTTP 1.0.
	header("Expires: 0"); //

	date_default_timezone_set('America/Manaus');

	require("./controller/centralController.php");	
	require("./controller/agentController.php");
	require("./DB/conn.php");	
	
    if(isset($_POST['submit']) && $_POST['submit'] == 'submit'){
        
        $data['bombeiro'] = $_SESSION['userid'];
        $data['defeito'] = $_POST['defeito'];
        $data['local'] = $_POST['local'];
        $data['tipo'] = $_POST['tipo'];
        $data['sistema'] = $_POST['sistema'];
        $data['cliente'] = $_POST['cliente'];
        
        unset($_POST);
        
        $resp = insertDefeito($conn,$data);
    }

    $def_table = getLastestDefeitos($conn,$_SESSION['userid']);

?>
<!DOCTYPE html>
<html><head>
	<meta lang='pt-BR'>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	
	<title>FireSystems | Bombeiro</title>
	
	<link rel="stylesheet" href="./assets/css/jquery-ui.min.css">
	<link rel="stylesheet" href="./dist/css/coreui.min.css">
	<link rel="stylesheet" href="./dist/css/coreui-icons.min.css">
	<link rel="stylesheet" href="./dist/pace/red/pace-theme-minimal.css" />

	<style>
	  th {font-weight: normal;}
    </style>
		<script src="./assets/js/jquery-3.6.0.min.js"></script>
		<script src="./assets/js/jquery-ui.min.js"></script>
		<script src="./assets/js/datepicker-pt-br.js"></script>
		<script src="./assets/js/jquery.ajax.form.js"></script>
		<script src="./assets/js/jquery.mask.min.js"></script>
		<script src="./assets/js/moment.min.js"></script>
		<script src="./dist/js/bootstrap.bundle.min.js"></script>
		<script src="./assets/js/perfect-scrollbar.min.js"></script>
		<script src="./assets/js/coreui.min.js"></script>
		<script src="./dist/pace/pace.min.js"></script>

</head>

<body class="app header-fixed">
<header class='app-header navbar' style='background: #2f353a; border-bottom: 4px solid #a60117;'>
			<ul class="nav navbar-nav m-auto">
				<li class="nav-item p-2">
				<a class="nav-link text-white" style="font-weight: 600;" href="javascript:loadPhp('perfil.php');"><i class="nav-icon cui-user"></i><?php echo " ".mb_strtoupper($_SESSION['usuario'], 'UTF-8');?></a>
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
<main class="main" style="background-image:url('img/fire-inspect.jpg'); background-repeat: no-repeat; background-size: cover; background-attachment: fixed; background-position: top;">
	<div id="main">
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item active">Central</li>
		</ol>
	</nav>
	<div class="container-fluid" style="max-width: 1200px;">
		<div class="card">
			<div class='card-header'>
			<div class="row mt-1">
				<div class="col-7">
				<h3><i class="nav-icon cui-home"></i><cite>Sistema FireSystems</cite> - Cadastro de Defeito:</h3>
				</div>
				<div class='col-5'>
					<h3 class='btn btn-outline-primary float-right'><?php echo "Manaus, ".date("d/m/Y", $_SERVER['REQUEST_TIME']);?></h3>
				</div>
			</div>
			</div> 
			<div class="card-body">	

            <div class="p-2">
            <?php echo $def_table;?>
            </div>

            <form action="inDefeito.php" method="POST">	
            <strong>

           		<p class=" p-1">
                        Cliente:
                    <select class="form-select form-select-lg" id="cliente" name="cliente">
                        <option selected>Selecionar Cliente:</option>
                        <option value="59">P&G - SOLIMÕES</option>
                        <option value="61">P&G - RIO NEGRO</option>
                    </select>
				</p>

				<p class=" p-1">
                    Sistema:
                    <select class="form-select form-select-lg" id="sistema" name="sistema">
                        <option selected>Selecionar Sistema:</option>
                        <option value="ABRIGO DE MANGUEIRAS E HIDRANTES">ABRIGO DE MANGUEIRAS E HIDRANTES</option>
                        <option value="ACIONADORES MANUAIS (BOTOEIRAS)">ACIONADORES MANUAIS (BOTOEIRAS)</option>
                        <option value="ARMÁRIO CORTA-FOGO">ARMÁRIO CORTA-FOGO</option>
                        <option value="ARMÁRIO DE VESTIMENTA DA BRIGADA">ARMÁRIO DE VESTIMENTA DA BRIGADA</option>
                        <option value="CASA DE BOMBAS">CASA DE BOMBAS</option>
                        <option value="CHUVEIRO E LAVA OLHOS">CHUVEIRO E LAVA OLHOS</option>
                        <option value="DETECÇÃO DE FUMAÇA">DETECÇÃO DE FUMAÇA</option>
                        <option value="DIQUES E CAIXA DE CONTENÇÃO">DIQUES E CAIXA DE CONTENÇÃO</option>
                        <option value="EPRA">EPRA</option>
                        <option value="EQUIPAMENTOS DE EMERGÊNCIA">EQUIPAMENTOS DE EMERGÊNCIA</option>
                        <option value="ESCADA MARINHEIRO">ESCADA MARINHEIRO</option>
                        <option value="ESPAÇO CONFINADO">ESPAÇO CONFINADO</option>
                        <option value="EXTINTORES">EXTINTORES</option>
                        <option value="FM 200 SISTEMA FIXO">FM 200 SISTEMA FIXO</option>
                        <option value="ILUMINAÇÃO DE EMERGÊNCIA">ILUMINAÇÃO DE EMERGÊNCIA</option>
                        <option value="MACAS DE EMERGÊNCIA">MACAS DE EMERGÊNCIA</option>
                        <option value="PAINÉIS E REPETIDORAS">PAINÉIS E REPETIDORAS</option>
                        <option value="PONTOS DE ENCONTRO">PONTOS DE ENCONTRO</option>
                        <option value="PORTAS DE EMERGÊNCIA">PORTAS DE EMERGÊNCIA</option>
                        <option value="TURBO VENTILADOR">TURBO VENTILADOR</option>
                        <option value="VB - VÁLVULAS DE BLOQUEIO">VB - VÁLVULAS DE BLOQUEIO</option>
                        <option value="VGA's,SPRINKLERS">VGA's,SPRINKLERS</option>
                        <option value="VTR - AMBULÂNCIA">VTR - AMBULÂNCIA</option>
                        <option value="VTR - COMBATE À INCÊNDIO">VTR - COMBATE À INCÊNDIO</option>
                    </select>
				</p>
                <p class=" p-1">
                        Tipo:
                    <select class="form-select form-select-lg" id="tipo" name="tipo">
                        <option selected>Selecionar Tipo do Defeito:</option>
                        <option value="AB: Ambiental">AB: Ambiental</option>
                        <option value="D&C: Desenho e Construção">D&C: Desenho e Construção</option>
                        <option value="EC: Espaço Confinado">EC: Espaço Confinado</option>
                        <option value="EM: Emergências">EM: Emergências</option>
                        <option value="EQ: Emergência Química">EQ: Emergência Química</option>
                        <option value="FP: Fire Protection">FP: Fire Protection</option>
                        <option value="O&M: Operação e Manutenção">O&M: Operação e Manutenção</option>
                    </select>
				</p>    

<p class="form-floating p-1 col-4">

Local:
<input type="text" class="form-control" maxlenght="32" name="local" id="local"></input> 

</p>

<div class="form-floating p-1" id="inputDefeito" >
              

                    <label for="defeito">Defeito: ( {{multi.textData.length}} / 200 caracteres)</label>
                    <textarea v-model="multi.textData" class="form-control" maxlenght="200" row="3" name="defeito" id="defeito" style="height: 120px;"></textarea>    
</div>

</strong>   

            </div>

            

            <div class="container text-center mb-3">
					<button type="submit" value="submit" name="submit" style="font-weight: bold; white-space: normal;"
					class="btn btn-outline-primary btn-lg"><i class="nav-icon cui-pencil"></i>
					CADASTRAR DEFEITO
					</button>
				</div>
                

            </form>

			<!-------- VUE PORTION -------------------------->
			
			<!-------- VUE PORTION -------------------------->
				
			</div>
		</div>
    </div>			
	
	</div>
</main>
<!-- Div body-app Encerramento -->
</div>
	
	
	<footer class="app-footer">
		<div>
		<a href="https://www.firesystems-am.com.br" target="_blank">FireSystems-AM</a>
		<span>©2024 Produtos e Serviços Contra Incêndio </span>
		</div>
		<div class="ml-auto">
		<span>Sistema de Inspeção Online</span>
		
		</div>
	</footer>
			<script>
  			 
			  var user_data = <?php echo $_SESSION['userid'];?>;

			  const permit = [ 1,2];
			
			  
			</script>	
		<!-- fullCallendar ----------------------------------------------------->


		<!-- PETIT VUE ----------------------------------------------------->
		
		<script type="module">
		
		import { createApp, reactive } from "https://unpkg.com/petite-vue@0.4.1/dist/petite-vue.es.js?module";

        const multi = reactive({
            textData: "",
            counter: 0
        });
        
        createApp({ multi }).mount("#inputDefeito");

   	 </script>
 </body> 
 
</html> 