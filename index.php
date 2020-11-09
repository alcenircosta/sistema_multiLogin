<?php
session_start();
$host = 'localhost';
$dbname = 'multi_login';
$user = 'root';
$password = '';
$pdo = new PDO('mysql:host='.$host.';dbname='.$dbname,$user,$password);
if(isset($_POST['login']) && !isset($_SESSION['login'])){
	$_SESSION['login'] = $_POST['login'];
	$_SESSION['token'] = uniqid();
	$sql =	$pdo->prepare("DELETE FROM `login` WHERE login = ?");
	$sql->execute(array($_SESSION['login']));
	$sql = $pdo->prepare("INSERT INTO `login` VALUES (null,?,?)");
	$sql->execute(array($_SESSION['login'],$_SESSION['token']));
}

if(!isset($_SESSION['login'])){ 
	?>
	<h2>Efetue LOGIN:</h2>
	<form method="post">
	<input type="text" name="login" />
	<input type="submit" name="acao"/>
	</form>
	<?php
}else{
	$login = $_SESSION['login'];
	$token = $_SESSION['token'];
	$check = $pdo->prepare("SELECT `id` FROM `login` WHERE login = ? AND token = ?");
	$check->execute(array($login,$token));
	if(($check->rowcount()) == 1){
		echo '<h1>Olá, bem vindo, <u>'.$_SESSION['login'].'</u></h1>';
	}else{
		echo '<h2>Você será desconectado pois encontramos outro <u>usuário logado com esta conta</u>';
		session_destroy();
	}
}
?>