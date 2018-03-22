<?php
include('connexion.php');
session_start();


$req = $bdd->prepare('UPDATE users SET last_log= ? WHERE id_user= ?');
$req->execute(array(time(), intval($_SESSION['id'])));


?>