<?php

require("./controller/hidranteController.php");

$hid = $_REQUEST['h'];

if(!$hid){
    header('Location: https://bombeiros.firesystems-am.com.br');
    exit();
}

if( strlen($hid) != 9 ){
    header('Location: https://bombeiros.firesystems-am.com.br');
    exit();
}

if( isInvalidQr($hid) ){
    header('Location: https://bombeiros.firesystems-am.com.br');
    exit();
}

// Inicia sessões
session_start(); 
//echo session_status(); 
$_SESSION['extintor'] = '';
$_SESSION['hidrante'] = $hid;
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

header("Location: inspHidrantes.php"); 

?>