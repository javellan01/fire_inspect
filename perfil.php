<?php 
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

	if($_SESSION['ativo'] == 0){
		header('HTTP/1.1 403 Forbidden');
		exit(); 
	}

	header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
	header("Pragma: no-cache"); // HTTP 1.0.
	header("Expires: 0"); //

	require("./controller/bombeirosController.php");	
	require("./DB/conn.php");

	$user = getBombeiro($conn,$_SESSION['userid']);		
	
?>	
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item ">
				<a href='central.php'>Central</a>
			</li>	
			<li class="breadcrumb-item active">Perfil</li>
		</ol>
	</nav>
	<div class="container-fluid">
				<div class="card">
					<div class='card-header'>
					<div class='row mt-4'>
						<div class='col-8'>
							<h3>Perfil: </h3>
						</div>
						<div class='col-4'>
												
						</div>
						</div>
						
					</div>	
					<div class="card-body">
	<div class='row'>
		<!-- info do usuario e alterar senha --->
		<div class='col-12'>
			<div class='card-header'>
				<h3>
					<button type='button' class='btn btn-outline-danger  btn-lg'><?php echo $user['tx_nome']; ?></button>
				</h3>
			</div>
			<div class='card-body'>
				<table class='table table-responsive-lg'>
				<tbody>
					<tr><th>Matricula: <?php echo $user['tx_matricula']; ?></th></tr>
					<tr><th>Email: <?php echo ($user['tx_email'] == '') ? "Email não cadastrado." : $user['tx_email']; ?></th></tr>
					<tr><th>Telefone: <?php echo ($user['tx_telefone'] == '') ? "Número não cadastrado." : $user['tx_telefone']; ?></th></tr>
				</tbody>	
				</table>
			</div>	
		</div>
	
	</div>
		<div class='row'>
			<div class='col-12'>
			<div class='card-header'>
				<h5>Alterar Senha: </h5>
				</div>
				<div class='card-body'>	
					<div class='row'>
					<div class='col-8'>	
						<form>
						<input class="form-control" type="password" id="formASenha" name="ASenha" placeholder="Nova Senha">
						</form>
					</div>	
					<div class='col-4'>	
						<a class='btn btn-outline-primary float-right' href="" role='button'>OK</a>
					</div>
				</div>	
				</div>
			</div>
		</div>
		<div id='process'><p></p></div>
	</div>
	</div>
</div>