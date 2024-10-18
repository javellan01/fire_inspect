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
	
	function getPost(){
    if(!empty($_POST))
    {
        // when using application/x-www-form-urlencoded or multipart/form-data as the HTTP Content-Type in the request
        // NOTE: if this is the case and $_POST is empty, check the variables_order in php.ini! - it must contain the letter P
        return $_POST;
    }

    // when using application/json as the HTTP Content-Type in the request 
    $post = json_decode(file_get_contents('php://input'), true);
    if(json_last_error() == JSON_ERROR_NONE)
    {
        return $post;
    }

    return [];
}

    $data = getPost();  

    if($access  == $key['key']){
        getExtReview($conn,$data);
    }
    else{
        header('HTTP/1.1 403 Forbidden');
        exit('POST ERROR');
    }


?>