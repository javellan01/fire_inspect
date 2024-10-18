<?php 
    header("Location: login.php"); 
    exit();
	// Inicia sessões
	//session_start(); 
	//echo session_status(); 
	// Verifica se existe os dados da sessão de login 
	//if(!isset($_SESSION["login"]) || !isset($_SESSION["usuario"])) 
	//	{ 
	// Usuário não logado! Redireciona para a página de login 
	//	header("Location: login.php"); 
	//	exit; 
	//} 

	header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
	header("Pragma: no-cache"); // HTTP 1.0.
	header("Expires: 0"); //

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FireSystem - Envio Extintores</title>
    <!--script type="text/javascript" src="./js/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="./js/calc.js"></script-->
    <link 
    href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css"
    rel="stylesheet"/>
</head>
<body style="background-color:rgb(60 40 20);">

<div class="p-10">
        <!-- CARD PESQUISA PEÇA  -->
    <div style="background-color:white;" class="rounded-lg overflow-hidden shadow-lg">
    
    <div id="extEnvioMulti">
        <div class="flex flex-col items-center justify-center mb-8">
            <h3 class="text-xl bold">Atualizar Localizaçao : Vários Extintores</h3>
            <form @submit.prevent>
                <h3 class="text-xl">Código Cliente: 
                <input v-model="multi.codCliente" class="text-xl border-2 rounded mx-3 my-3 p-3 shadow" 
                maxlength="2" required type="number"> 
                </h3>
                <h3 class="text-xl">Texto Completo: ( {{multi.textData.length}} / 24000 caracteres)
                    <textarea v-model="multi.textData" class="w-full border-2 rounded mx-3 my-3 p-3 shadow"
                  maxlenght="24000" row="10" required></textarea>
                </h3>   
                <button 
                @click="multipiece.insertpiece"
                class="font-bold 
                w-64 mx-3 my-3 p-3 rounded shadow text-xl uppercase gap-6">
                Processar Peças
                </button>
                </form>
                
        </div>
        <div class="flex flex-col items-center justify-center mb-8" v-if="multi.saveSucess">
            <h3 class="text-xl bold">Extintores '{{multi.textData}}' cadastrados com sucesso!</h3>
        </div>  
     
    </div>
    
    </div>
        <!-- CARD PESQUISA PEÇA  -->
</div>

<script type="module">
        import { createApp, reactive } from "https://unpkg.com/petite-vue@0.4.1/dist/petite-vue.es.js?module";

        const textFile = "";
        const textSize = "";

        function readFile(event){
            this.textFile = event.target.value;
        };

        const multi = {
            textData: "",
            codCliente: ""
        };

        const multipiece = reactive({
            saveSucess: "",
            async insertpiece(){
                await fetch(`map_updateExtintores.php`,{
                method: "POST",
                body: JSON.stringify(multi),
                headers: {"Content-type": "application/json; charset=UTF-8"} })
            .then(Response => Response.json())
            .then(json => console.log(json))
            .catch(err => console.log(err));

            }
        });

        createApp({ multipiece , multi }).mount("#extEnvioMulti");

    </script>
</body>
</html>