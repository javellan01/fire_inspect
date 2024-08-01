<?php
	session_start();

	header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
	header("Pragma: no-cache"); // HTTP 1.1
	header("Expires: 0"); //

	$extintor = '';
	$inspecao = '';
	
	if(isset($_SESSION['extintor'])){
    $ext = $_SESSION['extintor'];

    require("./DB/conn.php");
    require("./controller/extintorController.php");

	date_default_timezone_set('America/Manaus');

    $inspecao = getExtLastInspection($conn,$ext);
    $extintor = getExtintorBasic($conn,$ext);

	unset($_SESSION['extintor']);
	}

	?>

<!DOCTYPE html>
<html>
<head>
	<title>FireSystems | Login</title>
    <meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />

	<script src="./assets/js/jquery-3.6.0.min.js"></script>
	<script src="./assets/js/jquery.mask.js"></script>
	<script src="./assets/js/md5.min.js"></script>
	<style type="text/css">
		@import url(https://fonts.googleapis.com/css?family=Source+Sans+Pro:200,300);
		* {
		  box-sizing: border-box;
		  margin: 0;
		  padding: 0;
		  font-weight: 400;
		}
		body {
		  font-family: 'Source Sans Pro', sans-serif;
		  color: white;
		  font-weight: 400;
		}
		body ::-webkit-input-placeholder {
		  /* WebKit browsers */
		  font-family: 'Source Sans Pro', sans-serif;
		  color: white;
		  font-weight: 300;
		}
		body :-moz-placeholder {
		  /* Mozilla Firefox 4 to 18 */
		  font-family: 'Source Sans Pro', sans-serif;
		  color: white;
		  opacity: 1;
		  font-weight: 300;
		}
		body ::-moz-placeholder {
		  /* Mozilla Firefox 19+ */
		  font-family: 'Source Sans Pro', sans-serif;
		  color: white;
		  opacity: 1;
		  font-weight: 300;
		}
		body :-ms-input-placeholder {
		  /* Internet Explorer 10+ */
		  font-family: 'Source Sans Pro', sans-serif;
		  color: white;
		  font-weight: 300;
		}
		p{
		  font-family: 'Source Sans Pro', sans-serif;
		  color: white;
		  font-weight: 300;

		  color: white;
		  font-size: 1.3em;
		  margin: 15px 0 10px;
		}
		.wrap {
		  background: #333 ;
		  background: linear-gradient(to bottom, #333 0%, #777 100%);
		  position: absolute;
		  top: 0;
		  left: 0;
		  width: 100%;
		  overflow: hidden;
		}/*
		.wrap.form-success .container h1 {
		  -webkit-transform: translateY(85px);
		          transform: translateY(85px);
		}*/
		.container {
		  max-width: 100%;
		  margin: 0 auto;
		  padding: 36vh 0 22vh 0;
		  height: 100vh;
		  text-align: center;
		}
		.bg-img{
			background-image: url("./img/front.jpg");
			height: 280px;
			background-position: center;
			background-repeat: no-repeat;
			background-size: 90% 90%;
		}
		.container h1 {
		  font-size: 40px;/*
		  transition-duration: 1s;
		  transition-timing-function: ease-in-put;*/
		  font-weight: 400;
		}
		form {
		  padding: 20px 0;
		  position: relative;
		  z-index: 2;
		}
		form .error{color: yellow;}
		form input {
		  -webkit-appearance: none;
		     -moz-appearance: none;
		          appearance: none;
		  outline: 0;
		  border: 1px solid rgba(255, 255, 255, 0.4);
		  background-color: rgba(255, 255, 255, 0.2);
		  width: 250px;
		  border-radius: 3px;
		  padding: 10px 15px;
		  margin: 0 auto 10px auto;
		  display: inline-block;
		  text-align: center;
		  font-size: 18px;
		  color: white;
		  transition-duration: 0.25s;
		  font-weight: 300;
		}
		form input:hover {
		  background-color: rgba(255, 255, 255, 0.4);
		}
		form input:focus {
		  background-color: white;
		  width: 300px;
		  color: #004472;
		}
		form button {
		  -webkit-appearance: none;
		     -moz-appearance: none;
		          appearance: none;
		  outline: 0;
		  background-color: white;
		  border: 0;
		  padding: 10px 15px;
		  color: #004472;
		  border-radius: 3px;
		  width: 250px;
		  cursor: pointer;
		  font-size: 18px;
		  transition-duration: 0.25s;
		}
		form button:hover {
		  background-color: #f5f7f9;
		}
		button {
		  appearance: none;
		  outline: 0;
		  background-color: white;
		  border: 2px solid FireBrick;
		  padding: 10px 15px;
		  color: FireBrick;
		  border-radius: 4px;
		  width: 250px;
		  cursor: pointer;
		  font-size: 18px;
		  transition-duration: 0.25s;
		}
		button:hover {
		  background-color: #eee;
		}
		.footer {
		  display: flex;
		  flex-wrap: wrap;
		  align-items: center;
		  color: #fff;
		  border-top: 3px solid #c8ced3;
		  position: fixed;
		  margin: 0 5vh 0 5vh;
		  right: 0;
		  bottom: 0;
		  left: 0;
		  height: 100px;
		  
		}
		.header {
		  display: flex;
		  flex-wrap: wrap;
		  align-items: center;
		  color: #fff;
		  border-bottom: 3px solid #c8ced3;
		  position: fixed;
		  margin: 0 5vh 0 5vh;
		  right: 0;
		  top: 0;
		  left: 0;
		  height: 100px;
		  
		}

		@-webkit-keyframes square {
		  0% {
		    -webkit-transform: translateY(0);
		            transform: translateY(0);
		  }
		  100% {
		    -webkit-transform: translateY(-700px) rotate(600deg);
		            transform: translateY(-700px) rotate(600deg);
		  }
		}
		@keyframes square {
		  0% {
		    -webkit-transform: translateY(0);
		            transform: translateY(0);
		  }
		  100% {
		    -webkit-transform: translateY(-700px) rotate(600deg);
		            transform: translateY(-700px) rotate(600deg);
		  }
		 
		}
		a:link, a:visited, a:active {
			color: white;
			}
		a:hover {
			color: orange;
			} 
		/* The Modal (background) */
		.modal {
		
		display: none; /* Hidden by default */
		position: fixed; /* Stay in place */
		z-index: 10; /* Sit on top */
		left: 0;
		top: 0;
		width: 100%; /* Full width */
		height: 100%; /* Full height */
		overflow: auto; /* Enable scroll if needed */
		background-color: rgb(0,0,0); /* Fallback color */
		background-color: rgba(0,0,0,0.5); /* Black w/ opacity */
		}
		.modal-header {
			color: FireBrick;
			font-size: 22px;
			border-bottom: 1px solid FireBrick;
			margin-bottom: 10px;
		}
		/* Modal Content/Box */
		.modal-content {
		color: #004472;
		border-radius: 12px;	
		font-size: 20px;
		background-color: #fefefe;
		margin: 10% auto; /* 15% from the top and centered */
		padding: 20px;
		width: 80%; 
		max-width: 500px;
		animation-name: animatetop;
  		animation-duration: 0.4s/* Could be more or less, depending on screen size */
		}
		/* The Close Button */
		.close {
		color: #333;
		float: right;
		font-size: 28px;
		font-weight: bold;
		}
		.close:hover,
		.close:focus {
		color: black;
		text-decoration: none;
		cursor: pointer;
		}	
		/* Add Animation */
		@keyframes animatetop {
		from {top: -300px; opacity: 0}
		to {top: 0; opacity: 1}
		}

	</style>
</head>
<body>

	<div class="wrap" style="background-image: url('./img/front.jpg');
			background-position: center;
			background-repeat: no-repeat;
			background-size: cover;
			position: relative;">
		<div class="header">
			<div>
			<span></span>
			</div>
			<a style="margin-left: auto" href="http://www.firesystems-am.com.br/">
			<img src="https://firesystems-am.com.br/wp-content/uploads/2020/06/FIRE-LOGO.png" alt="FIRE-AM" width="202" height="68">
			</a>
		</div>
		
		<div class="container" >
			</div>
			<div class="container" style="
			position: absolute;
			top: 0;
			left: 0;
			width: 100%;
			text-align: center;
			">
			
			<h1>Inspeção Online</h1>
			<p>Informe Login e Senha para acessar o sistema</p>
			
			<form class="acessoOnline" autocomplete="off" method="POST" action="agent.php">
				<div>
				
					<input type="text" name="usuario" id="usuario" placeholder="Identificação de Usuário" autocomplete="nope" />
					<input type="text" name="fakeusernameremembered" style="display:none" />
					
				</div>
				<div>
					
					<input type="password" name="senha" id="senha" placeholder="Entrar Senha" autocomplete="new-password" />
					<input type="text" name="fakepasswordremembered" style="display:none" />
					
					<br/>
<!--
			<input type="text" id="teste" name="usuario" placeholder="eg. 000.000.000-00" pattern="\d{3}\.\d{3}\.\d{3}-\d{2}" max-lenght="14" /> 
<!---->
				</div>
				<button type="submit" id="subm" class="login-button">Entrar</button>
			</form>
			<?php if($extintor != ''){
				echo '<br><button id="openModal">Modo Visitante: Ver Detalhes do QR Code</button>';
			}
			?>
		</div>
		
		<div class="footer">
			<div>
			<a href="http://www.firesystems-am.com.br">FireSystems-AM</a>
	     	<span>©2024 Produtos e Serviços Contra Incêndio </span>
			</div>
		</div>
	</div>
	
	<!-- Modal content -->
	<div id="qrModal" class="modal">
		<div class="modal-content">
		<div class="modal-header">
			<h2>Dados do Extintor: </h2>
		</div>
		<div class="modal-body">
				<h3 style="font-weight: bold;"><?php echo $extintor['bool_carreta'].' '.$extintor['tx_tipo'];?></h3>
			    <h4>Capacidade:  <i style="font-weight: bold;"><?php echo $extintor['tx_capacidade'];?></i></h4>
                <h4>Nº Série: <i style="font-weight: bold;"><?php echo $extintor['id_serie'];?></i></h4>
                <h4>Selo Inmetro: <i style="font-weight: bold;"><?php echo $extintor['tx_inmetro'];?></i></h4>
                <h4>Última Inspeção: <i style="font-weight: bold;"><?php echo $inspecao['dt_inspecao'];?></i></h4>
				<br>
				<div style="text-align:center;">
				<button  id="closeModal">Fechar</button>
				</div>
		</div>
		</div>
	</div>	

	<script type="text/javascript">
		$(document).ready(function(){
		//$('#usuario').mask('000.000.000-00');
		
		$(".login-button").submit(function(event){

			var usuario = $("input#usuario").val();
			var senha = $("input#senha").val();
			return false;
			});

			var inusuario = document.getElementById("usuario");
			var insenha = document.getElementById("senha");
			var btnenter = document.getElementById("subm");
			// Get the modal
			var modal = document.getElementById("qrModal");

			// Get the button that opens the modal
			var btn = document.getElementById("openModal");
			var csbtn = document.getElementById("closeModal");

			// When the user clicks on the button, open the modal
			btn.onclick = function() {
			modal.style.display = "block";
				insenha.disabled = true;
				inusuario.disabled = true;
				inbtnenter.disabled = true;
			}

			csbtn.onclick = function() {
			modal.style.display = "none";
				insenha.disabled = false;
				inusuario.disabled = false;
				inbtnenter.disabled = false;
			}

			// When the user clicks anywhere outside of the modal, close it
			window.onclick = function(event) {
			if (event.target == modal) {
				modal.style.display = "none";
				insenha.disabled = false;
				inusuario.disabled = false;
				inbtnenter.disabled = false;
			}
			}


		});	


	</script>

</body>
</html>