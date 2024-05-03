<?php
//session_start();

require("./DB/conn.php");
require("./controller/extintorController.php");

    $key = include("./config/key.php");
	$access = $_SESSION['temp-k'];

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
        insertExtintores($conn,$data);
    }
    else{
        header('HTTP/1.1 403 Forbidden');
        exit('Tamanho da string não atende.');
    }

?>