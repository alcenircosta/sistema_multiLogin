<?php
session_start();
$pdo = new PDO('mysql:host=localhost;dbname=multi_login','root','');

if(isset($_POST['login']) && !isset($_SESSION['login'])){
	$_SESSION['login'] = $_POST['login'];
	$_SESSION['token'] = uniqid();
	$sql =	$pdo->prepare("DELETE FROM `login` WHERE login = ?");
	$sql->execute(array($_SESSION['login']));
	$sql = $pdo->prepare("INSERT INTO `login` VALUES (null,?,?)");
	$sql->execute(array($_SESSION['login'],$_SESSION['token']));
}

if(!isset($_SESSION['login'])){
	echo '<h2>Efetue LOGIN:</h2>';
	echo'<form method="post"><input type="text" name="login" /><input type="submit" name="acao"/></form>';
}else{
	$login = $_SESSION['login'];
	$token = $_SESSION['token'];
	$check = $pdo->prepare("SELECT `id` FROM `login` WHERE login = ? AND token = ?");
	$check->execute(array($login,$token));

	if(($check->rowcount()) == 1){
		echo 'Olá, bem vindo, '.$_SESSION['login'];
	}else{
		echo 'Você será desconectado pois encontramos outro usuário logado com esta conta';
		session_destroy();
	}
}
?>