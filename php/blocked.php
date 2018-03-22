<?php
include('connexion.php');
session_start();

if ($_POST['submit'] === "blocked") {

	$req = $bdd->prepare('SELECT * FROM blocked WHERE id_user = ? AND id_user_blocked = ?');
	$req->execute(array($_SESSION['id'], $_POST['id']));

	if($req->rowCount() == 0)
	{
		$req = $bdd->prepare('INSERT INTO blocked (	id_user, id_user_blocked) VALUES (:id_user, :id_user_blocked)');

		$req->bindParam(':id_user', $_SESSION['id']);
		$req->bindParam(':id_user_blocked', $_POST['id']);
		$req->execute();


			$req = $bdd->prepare('INSERT INTO notif (id_user, id_user_notified, txt, date, seen) VALUES (:id_user, :id_user_notified, :txt, :date, 0)');
			$req->execute(array(
				'id_user' => $_POST['id'],
				'id_user_notified' => intval($_SESSION['id']),
				'txt' => "blocked your profil",
				'date' => date('Ymd')
			));

		echo "<style>#alert_div { background-color: #568456!important;} </style>";
		echo '<div id="alert_div"><p id="text_alert">USER BLOCKED</p><span class="closebtn" onclick="del_alert()">&times;</span></div>';
	}
	else{
		echo '<div id="alert_div"><p id="text_alert">USER ALREADY BLOCKED</p><span class="closebtn" onclick="del_alert()">&times;</span></div>';
	}
}

?>