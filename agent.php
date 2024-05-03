
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
		  background: #a60117 ;
		  background: linear-gradient(to bottom right, #0D1E40 20%, #591117 80%);
		  position: absolute;
		  top: 0;
		  left: 0;
		  width: 100%;
		  overflow: hidden;
		}
		
		.container {
		  max-width: 600px;
		  margin: 0 auto;
		  padding: 25vh 0 0;
		  height: 100vh;
		  text-align: center;
		}
		.container h1 {
		  font-size: 40px;/*
		  transition-duration: 1s;
		  transition-timing-function: ease-in-put;*/
		  font-weight: 400;
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
	</style>
</head>
<body>

	<div class="wrap">
		<div class="container">
			<h1>Sistema FireSystems</h1>
				<p>Login Inválido!</p>
				<p><a href="./login.php">Voltar</a></p>
			

<?php 
// session_start inicia a sessão
session_start();
// as variáveis login e senha recebem os dados digitados na página anterior

header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0"); //

require("./DB/conn.php");
require("./controller/agentController.php");
require_once 'vendor/autoload.php';
use Firebase\JWT\JWT;

$key = include("./config/key.php");

$usuario = Auth::validateUser($conn);

if($usuario){
if($usuario !== FALSE){
	if($usuario['cs_ativo'] == 0){
		echo '<p>Erro 401 : Acesso não autorizado.</p>';
		header('HTTP/1.1 401 Unauthorized');
		exit();
	}

	JWT::encode($usuario, $key['jwt'],'HS256');

			$_SESSION['temp-k'] = $key['key'];
			header('Location: central.php');
}
}
?>		
		</div>
		</div>
	</body>
</html>