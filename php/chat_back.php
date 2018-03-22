<?php
include('connexion.php');
session_start();

if ($_POST['submit'] === "list_convo") {
	$req = $bdd->prepare('SELECT users.first_name, users.last_name, users.id_user FROM users INNER JOIN liked as a ON users.id_user = a.id_user_target INNER JOIN liked as b ON a.id_user_target = b.id_who WHERE b.id_user_target = ? AND a.id_who = ?');

	$req->execute(array($_SESSION['id'], $_SESSION['id']));

	while ($data = $req->fetch())
		echo '<div class="transition" onclick="convo('.$data['id_user'].')"><img src="data/'.$data['id_user'].'/1.jpg" alt="profil"><p>'.$data['first_name'].'  '.$data['last_name'].'</p>
	<img src="assets/icon/trash.svg" alt="trash" id="trash_convo" onclick="del_convo('.$data['id_user'].')" /></div>';
}

if ($_POST['submit'] === "msg_convo") {

	$req = $bdd->prepare('SELECT * FROM message WHERE (id_user_send = ? AND id_user_reiceve = ?) OR (id_user_send = ? AND id_user_reiceve = ?)');

	$req->execute(array($_SESSION['id'], $_POST['id'], $_POST['id'], $_SESSION['id']));

	while ($data = $req->fetch())
	{
		if ($data['id_user_send'] == $_SESSION['id']) {
			echo '<div class="div_my_msg"><p class="my_msg">'.$data['txt'].'</p></div>';
		}
		else{
			echo '<div class="div_user_msg user_msg">'.$data['txt'].'</div>';
		}
		
	}

}

if ($_POST['submit'] === "form") {
	echo '<form action="#" onsubmit="return false" accept-charset="utf-8">
		<input type="text" name="text" required maxlength="400" id="write" class="input_txt" />

		<input type="submit" value="SEND" class="submit transition" onclick="add_msg_convo('.$_POST['id'].')"/>
    </form>';
}

if ($_POST['submit'] === "add_msg") {

	$msg = htmlspecialchars($_POST['txt']);

	if (empty($msg)) {
		exit;
	}
	
	$req = $bdd->prepare('INSERT INTO message (id_user_send, id_user_reiceve, date, txt) VALUES (:id_user_send, :id_user_reiceve, :date, :txt)');

	$req->execute(array(
		'id_user_send' => $_POST['id'], 
		'id_user_reiceve' => $_SESSION['id'],
		'date' => date('Ymd'),
		'txt' => $msg
	));


	$req = $bdd->prepare('SELECT * FROM notif WHERE id_user_notified = ? AND id_user = ? AND txt = ?');
	$req->execute(array($_SESSION['id'], $_POST['id'], "sent you a message"));
	if($req->rowCount() == 0)
	{
		$req = $bdd->prepare('INSERT INTO notif (id_user, id_user_notified, txt, date, seen) VALUES (:id_user, :id_user_notified, :txt, :date, 0)');
		$req->execute(array(
			'id_user' => $_POST['id'],
			'id_user_notified' => intval($_SESSION['id']),
			'txt' => "sent you a message",
			'date' => date('Ymd')
		));
	}
}


if ($_POST['submit'] === "del_convo") {

	$req = $bdd->prepare('DELETE FROM liked WHERE id_who=:id_who AND id_user_target=:id_user_target');
	$req->bindParam(':id_who', $_SESSION['id'], PDO::PARAM_INT);
	$req->bindParam(':id_user_target', $_POST['id'], PDO::PARAM_INT);
	$req->execute();

}


?>