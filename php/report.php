<?php
include('connexion.php');
session_start();

if ($_POST['submit'] === "report") {

	$req = $bdd->prepare('SELECT * FROM reported WHERE id_user = ? AND id_user_report = ?');
	$req->execute(array($_SESSION['id'], $_POST['id']));

	if($req->rowCount() == 0)
	{
		$req = $bdd->prepare('INSERT INTO reported (	id_user, id_user_report) VALUES (:id_user, :id_user_report)');

		$req->bindParam(':id_user', $_SESSION['id']);
		$req->bindParam(':id_user_report', $_POST['id']);
		$req->execute();

			$req = $bdd->prepare('INSERT INTO notif (id_user, id_user_notified, txt, date, seen) VALUES (:id_user, :id_user_notified, :txt, :date, 0)');
			$req->execute(array(
				'id_user' => $_POST['id'],
				'id_user_notified' => intval($_SESSION['id']),
				'txt' => "reported your profil",
				'date' => date('Ymd')
			));

		echo "<style>#alert_div { background-color: #568456!important;} </style>";
		echo '<div id="alert_div"><p id="text_alert">USER REPORT</p><span class="closebtn" onclick="del_alert()">&times;</span></div>';
	}
	else{
		echo '<div id="alert_div"><p id="text_alert">USER ALREADY REPORT</p><span class="closebtn" onclick="del_alert()">&times;</span></div>';
	}
}

?>