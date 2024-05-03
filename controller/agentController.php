<?php 

class Auth
    {
    private $conn;
    private $login;
    private $senha;
    private $user;
    private $uaccess;
    private $uid;
    
    public static function validateUser($conn){
        if(isset($_POST['usuario']) && isset($_POST['senha'])){
            if($_POST['usuario'] !== ''){

            $login = $_POST['usuario'];
            $senha = md5($_POST['senha']);

        $result = $conn->query("SELECT id_usuario, tx_nome, cs_ativo FROM bombeiro WHERE tx_matricula LIKE '".$login."' AND tx_password = '".$senha."'");        
        $data = $result->fetch(PDO::FETCH_ASSOC);
        
        if($data)
        {   
            $uid = $data['id_usuario'];
	        $user = $data['tx_nome'];
            $uaccess = $data['cs_ativo'];
        

            $_SESSION['login'] = $login;
            $_SESSION['usuario'] = $user;
            $_SESSION['userid'] = $uid;
            $_SESSION['ativo'] = $uaccess;
            
            $stmt = $conn->prepare("UPDATE bombeiro SET last_access = CURRENT_TIMESTAMP WHERE tx_matricula = :tx_matricula");
            $stmt->bindParam(':tx_matricula', $login);
            $stmt->execute();

            return $data;
        
        }else{
            return FALSE;
            }

        }

	    }
    }	

    public static function accessControl($catuser,$level){
        
    if(isset($_SESSION['catuser'])){
        if($catuser != $level){
            if($_SESSION['catuser'] == 0) return header('Location: central.php');
            if($_SESSION['catuser'] == 1) return header('Location: central_ger.php');
            if($_SESSION['catuser'] == 2) return header('Location: central_usr.php');
        }
    }
    else session_destroy();
    }
}


?>           