<?php 
session_start();
setcookie('id', '');
setcookie('login', '');
$_SESSION['id'] = "";
$_SESSION['login'] = "";

header('Location: ../index.php');
?>