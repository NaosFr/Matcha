<?php
include('connexion.php');
session_start();

if ($_POST['submit'] === 'del') {
	$req = $bdd->prepare('DELETE FROM notif WHERE id_notif=:id');
	$req->bindParam(':id', $_POST['id'], PDO::PARAM_INT);
	$req->execute();
}

$req = $bdd->prepare('SELECT * FROM notif WHERE id_user = ? AND seen = 0');
$req->execute(array($_SESSION['id']));

while ($data = $req->fetch())
{

	$req_2 = $bdd->prepare('SELECT login FROM users WHERE id_user = ?');
	$req_2->execute(array($_SESSION['id']));
	$login = $req_2->fetch();

	echo '<p><img src="assets/icon/trash.svg" alt="trash" onclick="notif_del('.$data['id_notif'].')" /><span onclick="go_profil_notif(\''.$login['login'].'\', '.$data['id_notif'].')" class="blue">'.$login['login'].'</span> '.$data['txt'].'<br/> <span class="date_notif">'.$data['date'].'</span></p>';	
}

?>