<?php

require("./controller/extintorController.php");

$ext = $_REQUEST['q'];
if(!$ext){
    header('Location: https://www.firesystems-am.com.br');
}

// Inicia sessões
session_start(); 
//echo session_status(); 
$_SESSION['extintor'] = $ext;
// Verifica se existe os dados da sessão de login 
if(!isset($_SESSION["login"]) || !isset($_SESSION["usuario"])) 
    { 
// Usuário não logado! Redireciona para a página de login 
    header("Location: login.php"); 
    exit; 
} 

header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.1.
header("Expires: 0"); //

header("Location: inspExtintores.php"); 

?>