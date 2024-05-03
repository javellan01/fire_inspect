<?php 

$key = include("./config/key.php");

	// Inicia sessões
	session_start(); 
    $access = $_SESSION['temp-k'];
	//echo session_status(); 
	// Verifica se existe os dados da sessão de login 
	if($access  !== $key['key']){
	// Usuário não logado! Redireciona para a página de login 
        header("Location: login.php"); 	
        exit();
	} 
    if(!$_SESSION['extintor']){
        header("Location: central.php"); 	    
    }

	header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
	header("Pragma: no-cache"); // HTTP 1.0.
	header("Expires: 0"); //

    $ext = $_SESSION['extintor'];
    unset($_SESSION['extintor']); 

    require("./DB/conn.php");
    require("./controller/extintorController.php");

    $extintor = getExtintor($conn,$ext);
    $inspecao = getExtLastInspection($conn,$ext);
    $ext = '';
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FireSystem - Inspeção Extintores</title>
    <!--script type="text/javascript" src="./js/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="./js/calc.js"></script-->
    <link 
    href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css"
    rel="stylesheet"/>
</head>
<body style="background-color:FireBrick;">

<div class="p-10">
        <!-- CARD PESQUISA PEÇA  -->
    <div style="background-color:white;" class="rounded-lg overflow-hidden shadow-lg">
    
    <div id="inspEnvioMulti">
        <div class="flex flex-col items-center m-8">
            <h3 class="text-xl bold mb-4">Cadastrar Inspeção</h3>
            <form @submit.prevent>
                <h3 style=""><?php echo $extintor['tx_nome'];?></h3>
                <h4><?php echo 'Prédio:'.$extintor['tx_predio'].' - Local: '.$extintor['tx_area'].', '.$extintor['tx_localiz'];?></h4>
                <h3 class="text-xl"><?php echo $extintor['bool_carreta'].' '.$extintor['tx_tipo'];?></h3>
                <h3>Capacidade: <?php echo $extintor['tx_capacidade'];?></h3>
                <h3>Nº Série: <i><?php echo $extintor['id_serie'];?></i> - Selo Inmetro: <i><?php echo $extintor['tx_inmetro'];?></i></h3>
                <h3>Última Inspeção: <?php echo $inspecao['dt_inspecao'];?></h3>
                <div class="m-2 p-4" style="border: 3px solid FireBrick;">
                    <h3 class="text-xl">Não Conformidade: </h3>
                <table>
                <tr style="margin: 3px;">
                    <td>
                    <label for="ch1">Sinal Vertical</label>    
                    <input type="checkbox" id="ch1" name="ch1" value="C">
                    </td>
                    <td>
                    <label for="ch2">Sinal Horizontal</label>    
                    <input type="checkbox" id="ch2" name="ch2" value="C">
                    </td>
                    <td>
                    <label for="ch3">Local Adequado</label>    
                    <input type="checkbox" id="ch3" name="ch3" value="C">
                    </td>
                </tr>
                <tr style="margin: 3px;">
                    <td>
                    <label for="ch4">Acesso Obstruído</label>    
                    <input type="checkbox" id="ch4" name="ch4" value="C">
                    </td>
                    <td>
                    <label for="ch5">Agente Ext. Adequado</label>    
                    <input type="checkbox" id="ch5" name="ch5" value="C">
                    </td>
                    <td>
                    <label for="ch6">Suporte</label>    
                    <input type="checkbox" id="ch6" name="ch6" value="C">
                    </td>
                </tr>
                <tr style="margin: 3px;">
                    <td>
                    <label for="ch7">Pressão Nominal</label>    
                    <input type="checkbox" id="ch7" name="ch7" value="C">
                    </td>
                    <td>
                    <label for="ch8">Teste Hidrostático</label>    
                    <input type="checkbox" id="ch8" name="ch8" value="C">
                    </td>
                    <td>
                    <label for="ch9">Carga / Peso</label>    
                    <input type="checkbox" id="ch9" name="ch9" value="C">
                    </td>
                </tr>
                <tr style="margin: 3px;">
                    <td>
                    <label for="ch10">Manômetro</label>    
                    <input type="checkbox" id="ch10" name="ch10" value="C">
                    </td>
                    <td>
                    <label for="ch11">Cilindro</label>    
                    <input type="checkbox" id="ch11" name="ch11" value="C">
                    </td>
                    <td>
                    <label for="ch12">Etiqueta</label>    
                    <input type="checkbox" id="ch12" name="ch12" value="C">
                    </td>
                </tr>
                <tr style="margin: 3px;">
                    <td>
                    <label for="ch13">Rótulo</label>    
                    <input type="checkbox" id="ch13" name="ch13" value="C">
                    </td>
                    <td>
                    <label for="ch14">Alça</label>    
                    <input type="checkbox" id="ch14" name="ch14" value="C">
                    </td>
                    <td>
                    <label for="ch15">Gatilho</label>    
                    <input type="checkbox" id="ch15" name="ch15" value="C">
                    </td>
                </tr>
                <tr style="margin: 3px;">
                    <td>
                    <label for="ch16">Trava</label>    
                    <input type="checkbox" id="ch16" name="ch16" value="C">
                    </td>
                    <td>
                    <label for="ch17">Lacre</label>    
                    <input type="checkbox" id="ch17" name="ch17" value="C">
                    </td>
                    <td>
                    <label for="ch18">Mangueira</label>    
                    <input type="checkbox" id="ch18" name="ch18" value="C">
                    </td>
                </tr>
                <tr style="margin: 3px;">
                    <td>
                    <label for="ch19">Punho</label>    
                    <input type="checkbox" id="ch19" name="ch19" value="C">
                    </td>
                    <td>
                    <label for="ch20">Difusor</label>    
                    <input type="checkbox" id="ch20" name="ch20" value="C">
                    </td>
                </tr>
                </table>
                    <h3>Comentário: ( {{multi.textData.length}} / 256 caracteres)
                        <textarea v-model="multi.textData" class="w-full border-2 rounded my-3 p-3"
                    maxlenght="300" row="3" required></textarea>
                    </h3>   
                </div>
                <button 
                @click="multipiece.insertpiece"
                class="font-bold 
                w-64 mx-3 my-3 p-3 rounded shadow text-xl uppercase gap-6">
                Cadastrar Inspeção
                </button>
                </form>
                
        </div>
        <div class="flex flex-col items-center justify-center mb-8" v-if="multi.saveSucess">
            <h3 class="text-xl bold">Inspeçao cadastrada com sucesso!</h3>
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
                await fetch(`insertExtintores.php`,{
                method: "POST",
                body: JSON.stringify(multi),
                headers: {"Content-type": "application/json; charset=UTF-8"} })
            .then(Response => Response.json())
            .then(json => console.log(json))
            .catch(err => console.log(err));

            }
        });

        createApp({ multipiece , multi }).mount("#inspEnvioMulti");

    </script>
</body>
</html>