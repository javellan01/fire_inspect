<?php
session_start();
$token = md5(session_id());
if(isset($_GET['token']) && $_GET['token'] === $token) {
	$_SESSION = [];
	session_destroy();
	header('Location: login.php');
	exit();
	}
	else {
	   echo '<a href="logout.php?token='.$token.'>Confirmar logout</a>';
	}
	
?>