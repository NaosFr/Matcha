<?php
include('connexion.php');
session_start();

$req = $bdd->prepare('DELETE FROM liked WHERE id_who=:id_who AND id_user_target=:id_user_target');
$req->bindParam(':id_who', $_SESSION['id'], PDO::PARAM_INT);
$req->bindParam(':id_user_target', $_POST['id'], PDO::PARAM_INT);
$req->execute();

$req = $bdd->prepare("UPDATE users SET score = score - 4 WHERE id_user=:id_user");
$req->bindParam(':id_user', $_GET['id'], PDO::PARAM_INT);
$req->execute();

echo '<div id="alert_div"><p id="text_alert">USER UNLIKED</p><span class="closebtn" onclick="del_alert()">&times;</span></div>';

?>