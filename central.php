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
	
	$events = getInspectEvents($conn,$_SESSION['userid']);
	//$clientes = getAlocacao($conn,$_SESSION['userid']);
	//$vencimentos_n3 = getvencimentosN3($conn,59,$_SESSION['userid']);
	//$vencimentos_n2 = getvencimentosN2($conn,59,$_SESSION['userid']);

?>
<!DOCTYPE html>
<html><head>
	<meta lang='pt-BR'>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	
	<title>FireSystems | Bombeiro</title>
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
				<h3><i class="nav-icon cui-home"></i><cite>Sistema FireSystems</cite> - Calendário:</h3>
				</div>
				<div class='col-5'>
					<h3 class='btn btn-outline-primary float-right'><?php echo "Manaus, ".date("d/m/Y", $_SERVER['REQUEST_TIME']);?></h3>
				</div>
			</div>
			</div> 
			<div class="card-body">	
			<div class="container text-center my-2" id="btn_area">
					<a href="qrRead.php" style="font-weight: bold; white-space: normal;"
					   class="d-inline mx-2 btn btn-primary btn-lg"><i class="nav-icon cui-magnifying-glass"></i>
					LER QRCODE
					</a>
			</div>	
			<div class='row'>
				<div class='col-12'>
				<div class="w-full m-1 p-1 shadow rounded" id="calendar"></div>
			</div>	
			<!-------- VUE PORTION -------------------------->
			<div id='v-app'>
				<div v-show='showDiv' class='row'>
					<div class='col-12'>
					<div class='inline-block'>
						<p>Próximos vencimenos N2:</p>
												
						<div class="w-75 m-1 p-1 shadow rounded">
						<?php echo $vencimentos_n2;?></div>
					</div>
				</div>	
			</div>
			</div>
			<!-------- VUE PORTION -------------------------->
				<div class="container text-center my-2">
						
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
		<a href="https://www.firesystems-am.com.br" target="_blank">FireSystems-AM</a>
		<span>©2024 Produtos e Serviços Contra Incêndio </span>
		</div>
		<div class="ml-auto">
		<span>Sistema de Inspeção Online</span>
		
		</div>
	</footer>
			<script>
  			  var events_data = <?php echo json_encode($events, JSON_HEX_TAG); ?>;

			  var user_data = <?php echo $_SESSION['userid'];?>;

			  const permit = [ 1,2];
			
			  if(permit.includes(user_data)){
				let btn = `<a href="review.php" style="font-weight: bold; white-space: normal;"
						class="d-inline mx-2 btn btn-success btn-lg" role="button"><i class="nav-icon cui-graph"></i>
						VISÃO GERAL</a>`;
				$(btn).appendTo("div#btn_area");
				
			  }
			</script>	
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
				events: <?php echo json_encode($events, JSON_HEX_TAG); ?>,
				aspectRatio: 1.15,
				editable: false,
				dayMaxEvents: true, // allow "more" link when too many events
				
			  });

			calendar.render();

			});
		</script>

		<!-- PETIT VUE ----------------------------------------------------->
		
		<script type="module">
		
		import { createApp, reactive } from "https://unpkg.com/petite-vue@0.4.1/dist/petite-vue.es.js?module";

	

		        
        function readFile(event){
            this.textFile = event.target.value;
        };

		const app = reactive({
            showDiv: false
        });

		if(permit.includes(user_data)){
			app.showDiv = true;
		}

        const multi = reactive({
            textData: "",
            counter: 0
        });
        
		const multipiece = reactive({
            saveSucess: "",
            async insertpiece(){
                await fetch(`insertExtintores.php`,{
                method: "POST",
                body: JSON.stringify(multi),
                headers: {"Content-type": "application/json; charset=UTF-8"} })
            .then(Response => Response.json())
            .then(json => console.log(json))
            .catch(err => console.log(err));

            }
        });

        createApp({ app }).mount("#v-app");

   	 </script>
 </body> 
 
</html> 