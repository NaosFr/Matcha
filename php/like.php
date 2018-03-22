<?php
include('connexion.php');
session_start();

if ($_POST['submit'] === "like") {

	$req = $bdd->prepare('INSERT INTO liked (id_who, id_user_target, date) VALUES (:id_who, :id_user_target, :date)');

	$req->bindParam(':id_who', $_SESSION['id']);
	$req->bindParam(':id_user_target', $_POST['id']);
	$req->bindParam(':date', date('Ymd'));
	$req->execute();

	$req = $bdd->prepare("UPDATE users SET score = score + 4 WHERE id_user=:id_user");
	$req->bindParam(':id_user', $_POST['id'], PDO::PARAM_INT);
	$req->execute();


	$req = $bdd->prepare('SELECT * FROM notif WHERE id_user_notified = ? AND id_user = ? AND txt = ?');
	$req->execute(array($_SESSION['id'], $_POST['id'], "liked your profil"));
	if($req->rowCount() == 0)
	{
		$req = $bdd->prepare('INSERT INTO notif (id_user, id_user_notified, txt, date, seen) VALUES (:id_user, :id_user_notified, :txt, :date, 0)');
		$req->execute(array(
			'id_user' => $_POST['id'],
			'id_user_notified' => intval($_SESSION['id']),
			'txt' => "liked your profil",
			'date' => date('Ymd')
		));
	}

	echo "<style>#alert_div { background-color: #568456!important;} </style>";
	echo '<div id="alert_div"><p id="text_alert">USER LIKED</p><span class="closebtn" onclick="del_alert()">&times;</span></div>';
}

?>