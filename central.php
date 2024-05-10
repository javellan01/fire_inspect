<?php 
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
	

	
?>
<!DOCTYPE html>
<html><head>
	<meta lang='pt-BR'>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	
	<title>Bombeiro | FireSystems</title>
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
		<script src="./assets/js/moment.min.js"></script>
		<script src="./dist/js/bootstrap.bundle.min.js"></script>
		<script src="./assets/js/perfect-scrollbar.min.js"></script>
		<script src="./assets/js/coreui.min.js"></script>
		<script src="./assets/js/toastr.min.js"></script>
		<script src="./dist/pace/pace.min.js"></script>
		<script src="./dist/fullcalendar/main.min.js"></script>
		<script src="./dist/fullcalendar/pt-br.js"></script>
		<script src="./dist/spectrum/spectrum.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
	<!-- AJAX Scriping for loading dynamically PHP on server -->
		<script src="./assets/js/central.js"></script>
	

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
	<div class="container-fluid">
		<div class="card">
			<div class='card-header'>
			<div class="row mt-1">
				<div class="col-7">
				<h3><i class="nav-icon cui-home"></i><cite>Sistema FireSystems</cite> - Calendário:</h3>
				</div>
				<div class='col-5'>
					<h3 class='btn btn-outline-primary float-right'><?php echo "Manaus, ".date("d/m/Y", $_SERVER['REQUEST_TIME']);?></h3>
				</div>
			</div>
			</div> 
			<div class="card-body">	
			<div class='row'>
				<div class='col-12'>
				<div class="w-full m-1 p-1 shadow rounded" id="calendar"></div>
				</div>		
					
			</div>		
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
		<!-- fullCallendar ----------------------------------------------------->
		
			<script>
			$(document).ready(function() {
			var calendarEl = document.getElementById('calendar');
			
			var calendar = new FullCalendar.Calendar(calendarEl, {
				displayEventTime : false,
				locale: 'pt-br',
				headerToolbar: {
				  left: 'title',
				  center: '',
				  right: 'prev,next'
				},
				aspectRatio: 1.15,
				editable: false,
				dayMaxEvents: true, // allow "more" link when too many events
				
			  });

			calendar.render();

			});
		</script>
 </body> 
 
</html> 