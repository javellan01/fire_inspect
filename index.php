<?php 
$key = include("./config/key.php");
// Inicia sessões
session_start(); 

// Verifica se existe os dados da sessão de login 
if(!isset($_SESSION["login"]) || !isset($_SESSION["usuario"]) || !isset($_SESSION["userid"])) 
		{ 
	// Usuário não logado! Redireciona para a página de login 
		header("Location: login.php"); 
		exit; 
	} 
	else{
		if($_SESSION['temp-k'] == $key['key']) header('Location: central.php');
		
		exit; 
	}	
		
?>

<!DOCTYPE html>
<html>
<head>
	<title>FireSystems</title>
    <meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />

	<script src="./assets/js/jquery-3.6.0.min.js"></script>
	<script src="./assets/js/jquery.mask.min.js"></script>
	<script src="./assets/js/md5.min.js"></script>
	
</head>
<body>

</body>
</html>