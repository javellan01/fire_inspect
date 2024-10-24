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

	require("./controller/reviewController.php");	
	require("./DB/conn.php");	
	
	$extintor_option =  loadExtbasic($conn);


?>
<!DOCTYPE html>
<html><head>
	<meta lang='pt-BR'>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	
	<title>FireSystems | Visão Geral
	</title>
	<link rel="stylesheet" href="./assets/css/toastr.min.css">
	<link rel="stylesheet" href="./assets/css/jquery-ui.min.css">
	<link rel="stylesheet" href="./dist/css/coreui.min.css">
	<link rel="stylesheet" href="./dist/css/coreui-icons.min.css">
	<link rel="stylesheet" href="./dist/fullcalendar/main.min.css">
	<link rel="stylesheet" href="./dist/css/spectrum.min.css">
	<link rel="stylesheet" href="./dist/pace/red/pace-theme-minimal.css" />

	<style>
      .app-body { overflow-x: initial;}
	  .fc-daygrid-day.fc-day-sat {background-color: #eee;}
	  .fc-daygrid-day.fc-day-sun {background-color: #eee;}
	  .fc-daygrid-week-number {background-color: #ce3500; color: white;}
	  .fc-col-header {background-color: #09568d; color: white;}
	  th {font-weight: normal;}
    </style>
		<script src="./assets/js/jquery-3.6.0.min.js"></script>
		<script src="./assets/js/jquery-ui.min.js"></script>
		<script src="./assets/js/datepicker-pt-br.js"></script>
		<script src="./assets/js/jquery.ajax.form.js"></script>
		<script src="./assets/js/jquery.mask.min.js"></script>
		<script src="./dist/js/bootstrap.bundle.min.js"></script>
		<script src="./assets/js/perfect-scrollbar.min.js"></script>
		<script src="./assets/js/coreui.min.js"></script>
		<script src="./dist/pace/pace.min.js"></script>
		<script src="./dist/spectrum/spectrum.min.js"></script>
		<script src="./assets/js/html5-qrcode.min.js"></script>

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
		<li class="breadcrumb-item"><a href="central.php">Central</a></li>
		<li class="breadcrumb-item active">Visão Geral</li>
		</ol>
	</nav>
	<div class="container-fluid" style="max-width: 1200px;">
		<div class="card">
			<div class='card-header'>
			<div class="row mt-1">
				<div class="col-7">
				<h3><i class="nav-icon cui-home"></i><cite>Sistema FireSystems</cite> - Visão Geral:</h3>
				</div>
				<div class='col-5'>
					<h3 class='btn btn-outline-primary float-right'><?php echo "Manaus, ".date("d/m/Y", $_SERVER['REQUEST_TIME']);?></h3>
				</div>
			</div>
			</div> 
			<div class="card-body">	
<!-------- VUE PORTION EXTINTORES --------------------------------------------->
			<div id='extintores'>

<!-------- ROW 00 PESQUISA --------------------------------------------->
<div class='row' >
				<div class='col-12'>

					<div class="w-full m-1 p-1" >
					<h4><i class="nav-icon cui-note"></i>
							<label for="ext-cliente">Cliente:</label>
							<select id="ext-cliente" name="ext-cliente">
							<option selected value="0">Selecione Cliente</option>	
							<?php echo $extintor_option['cliente_option'];?>
							</select>
							<label for="ext-ano">Ano:</label>
							<select id="ext-ano" name="ext-ano">
							<?php echo $extintor_option['year_option'];?>
							</select> 
							<label for="ext-periodo">Período:</label>
							<select id="ext-periodo" name="ext-periodo">
								<option value='1'>Janeiro</option>
								<option value='2'>Fevereiro</option>
								<option value='3'>Março</option>
								<option value='4'>Abril</option>
								<option value='5'>Maio</option>
								<option value='6'>Junho</option>
								<option value='7'>Julho</option>
								<option value='8'>Agosto</option>
								<option value='9'>Setembro</option>
								<option value='10'>Outubro</option>
								<option value='11'>Novembro</option>
								<option value='12'>Dezembro</option>
							</select>
							<label for="ext-dia">Dia Corte:</label>
							<select id="ext-dia" name="ext-dia">
								<option value='5'>Dia 5</option>
								<option value='10'>Dia 10</option>
								<option value='15'>Dia 15</option>
								<option selected value='20'>Dia 20</option>
								<option value='25'>Dia 25</option>
							</select>
							
					</h4>
					</div>


				</div>	
			</div>	
<!-------- ROW 00 PESQUISA --------------------------------------------->

<!-------- ROW 01 EXTINTORES --------------------------------------------->
			
			<div class='row' >
				<div class='col-12'>

					<div class="w-80 m-1 p-1" >
					<h4>EXTINTORES - Desvio :
					<div class="progress" style="height: 28px; font-size: 1.5rem" >
						<div class="progress-bar progress-bar-striped" id='ext-desvio' role="progressbar" style="width:0%; background-color: red">
						N/C
						</div>
						<div class="progress-bar progress-bar-striped" id='ext-conforme' role="progressbar" style="width:100%; background-color: green">
						0.00%
						</div>
					</div> 
					</h4>
					<p>Total Posições: <span id='total'></span> - Inspeções: <span id='total_insp'></span> - Não Insp.: <span id='nao_insp'></span> - N/C: <span id='total_nc'></span>   </p>
					</div>

				</div>	
			</div>		
		</div>			
<!-------- VUE PORTION HIDRANTES ---------------------------------------------->	

<!-------- ROW 01 HIDRANTES --------------------------------------------->
		
			<div class='row' >
				<div class='col-12'>

					<div class="w-80 m-1 p-1" >
					<h4>HIDRANTES - Desvio :
					<div class="progress" style="height: 28px; font-size: 1.5rem" >
						<div class="progress-bar progress-bar-striped" id='hid-desvio' role="progressbar" style="width:0%; background-color: red">
						N/C
						</div>
						<div class="progress-bar progress-bar-striped" id='hid-conforme' role="progressbar" style="width:100%; background-color: green">
						0.00%
						</div>
					</div> 
					</h4>
					<p>Total Posições: <span id='ext-total'></span> - Inspeções: <span id='ext-total_insp'></span> - Não Insp.: <span id='ext-nao_insp'></span> - N/C: <span id='ext-total_nc'></span>   </p>
					</div>

				</div>	
			</div>		
		</div>			
<!-------- VUE PORTION HIDRANTES ---------------------------------------------->	

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
  			  var user_data = <?php echo $_SESSION['userid']; ?>;
			  var current_month = <?php echo date("m", $_SERVER['REQUEST_TIME']);?>;
			  var index = current_month - 1;
			  const permit = [ 1, 2];
			  var multi = {};


			document.querySelector('select#ext-periodo').options[index].selected = true;

			async function extintoresult(){

					multi.cliente =  $('select#ext-cliente').val();
					multi.ano = $('select#ext-ano').val();
					multi.mes = $('select#ext-periodo').val();
					multi.dia = $('select#ext-dia').val();

                	const extres = await fetch(`extResult.php`,{
               		method: "POST",
                	body: JSON.stringify(multi),
                	headers: {"Content-type": "application/json; charset=UTF-8"} });

					const res = await extres.json();
					
					$('div#ext-conforme').text(res.final+'%');
					$('div#ext-conforme').css("width", (res.desvio+'%'));
					$('div#ext-desvio').css("width", (res.final+'%'));
					$('span#ext-total').text(res.total);
					$('span#ext-total_insp').text(res.total_insp);
					$('span#ext-nao_insp').text(res.nao_insp);
					$('span#ext-total_nc').text(res.total_nc);
				}		
			
			$('select#ext-cliente').on('change', function () {
				extintoresult();
			});
			$('select#ext-ano').on('change', function () {
				extintoresult();
			});	
			$('select#ext-periodo').on('change', function () {
				extintoresult();
			});	
			$('select#ext-dia').on('change', function () {
				extintoresult();
			});	
				
				
			</script>	
		<!-- fullCallendar ----------------------------------------------------->

		<!-- PETIT VUE ----------------------------------------------------->
		 </body> 
 
</html> 