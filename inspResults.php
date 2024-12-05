<?php

header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0"); //

require("./controller/reviewController.php");	
require("./DB/conn.php");	
date_default_timezone_set('America/Manaus');

$key = include("./config/key.php");
	// Inicia sessões
	$access = $_GET['query'];
	$cliente = $_GET['cliente'];
	//echo session_status(); 
	// Verifica se existe os dados da sessão de login 
	if($access  !== $key['query_key']){
	// Usuário não logado! Redireciona para a página de login 
		header('HTTP/1.1 403 Forbidden');
    	exit();

	} 

	if($access == $key['query_key']){

			getInspExtintores($conn,$cliente);
	
	} 

    exit();

?>