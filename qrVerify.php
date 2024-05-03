<?php

require("./controller/extintorController.php");

$string = $_REQUEST['eq'];
if(!$string){
    header('Location: https://www.firesystems-am.com.br');
}
$ext = base64_decode_url($string);

echo $ext;

// Inicia sessões
session_start(); 
//echo session_status(); 
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

$_SESSION['extintor'] = $ext;
header("Location: inspExtintores.php"); 

?>