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

?>

<!DOCTYPE html>
<html><head>
	<meta lang='pt-BR'>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	
	<title>FireSystem - Leitor QR</title>
	<link rel="stylesheet" href="./assets/css/jquery-ui.min.css">
	<link rel="stylesheet" href="./dist/css/coreui.min.css">
	<link rel="stylesheet" href="./dist/css/coreui-icons.min.css">
	<link rel="stylesheet" href="./assets/css/insp-custom.css">
    
		
		<script src="./assets/js/jquery-3.6.0.min.js"></script>
		<script src="./assets/js/jquery-ui.min.js"></script>
		<script src="./assets/js/jquery.ajax.form.js"></script>
		<script src="./dist/js/bootstrap.bundle.min.js"></script>
		<script src="./assets/js/coreui.min.js"></script>
        <script src="./assets/js/insp-custom.js"></script>
        <script src="./assets/js/html5-qrcode.min.js"></script>

</head>

<body class="app header-fixed">
<header class='app-header navbar' style='background: #2f353a; border-bottom: 4px solid #a60117;'>
			<ul class="nav navbar-nav m-auto">
				<li class="nav-item p-2">
				<a class="nav-link text-white" style="font-weight: 600;" href=""><i class="nav-icon cui-user"></i><?php echo "  ".mb_strtoupper($_SESSION['usuario'], 'UTF-8');?></a>
				</li>
			</ul>
			<ul class="nav navbar-nav m-auto">
				<li class="nav-item p-2">
				<a class="btn btn-outline-light" href="logout.php?token=<?php echo md5(session_id());?>">Logout <i class="nav-icon cui-account-logout"></i></a>
				</li>
			</ul>
</header>

<div class="app-body">	
<!-- Seção 0000, PARTE CENTRAL DOS DISPLAY DOS DADOS - USAR AJAX PARA NAVEGAR SEM SAIR DA CENTRAL -->
<main class="main" style="background-image:url('img/fire-back.jpg'); background-repeat: no-repeat; background-size: cover; background-attachment: fixed; background-position: top;">
	<div id="main">
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="central.php">Central</a></li>
			<li class="breadcrumb-item active">Leitura QR Code</li>
		</ol>
        
	</nav>
	<div class="container-fluid">
		<div class="card">
			<div class='card-header'>
                <div class="row">
                    <div class="col-12">
                    <h4 class='btn btn-outline-primary float-right'> <?php 
                    $current_time = date("d/m/Y H:i:s", $_SERVER['REQUEST_TIME']);
                    echo "Manaus, ".$current_time;?></h4>
                    </div>
			    </div>
                <div class="row mt-1">
                    <div class="col-12">
                    <h3><i class="nav-icon cui-magnifying-glass"></i><cite> Sistema FireSystems</cite> - Ler QR Code:</h3>
                    </div>
			    </div>
			</div>
			
			<div class="card-body">	  
                <div style="width: auto" id="reader"></div>
            </div>
            </div> 
		</div>
    </div>			
	</div>
</main>
<!-- Div body-app Encerramento -->
</div>
	<footer class="app-footer">
		<div>
		<a href="http://www.firesystems-am.com.br">FireSystems-AM</a>
		<span>©2024 Produtos e Serviços Contra Incêndio </span>
		</div>
		<div class="ml-auto">
		<span>Sistema de Inspeção Online</span>
		
		</div>
	</footer>
		<!-- HTML5-QRREADER ----------------------------------------------------->
		
	<script>
/**
 * Scans an Image File for QR Code.
 * 
 * This feature is mutually exclusive to camera based scanning, you should call
 * stop() if the camera based scanning was ongoing.
 * 
 * @param {File} imageFile a local file with Image content.
 * @param {boolean} showImage if true the Image will be rendered on given element.
 * 
 * @returns Promise with decoded QR code string on success and error message on failure.
 *            Failure could happen due to different reasons:
 *            1. QR Code decode failed because enough patterns not found in image.
 *            2. Input file was not image or unable to load the image or other image load
 *              errors.
*/
function onScanSuccess(decodedText, decodedResult) {
    //$("#result_a").text(decodedText);
    //$("#result_b").text(decodedResult);

    window.location.href = decodedText;
    // Handle on success condition with the decoded text or result.
    //console.log(`Scan result: ${decodedText}`, decodedResult);

}

var html5QrcodeScanner = new Html5QrcodeScanner(
    "reader", { fps: 10, qrbox: 250 });

html5QrcodeScanner.render(onScanSuccess);

</script>
 </body> 
 
</html> 