<?php
include('connexion.php');
session_start();

/////////////////////////////////
////////// CHANGE PROFIL
/////////////////////////////////

if ($_POST['submit'] === "change_profil") {


if (isset($_POST['email']) && $_POST['email'] != "" 
	&& isset($_POST['login']) && $_POST['login'] != ""
	&& isset($_POST['first_name']) && $_POST['first_name'] != ""
	&& isset($_POST['last_name']) && $_POST['last_name'] != ""
	&& isset($_POST['sexe']) && $_POST['sexe'] != ""
	&& isset($_POST['orientation']) && $_POST['orientation'] != ""
	&& isset($_POST['age']) && $_POST['age'] != ""
	&& isset($_POST['bio']) && $_POST['bio'] != "")
{
		$email = htmlspecialchars($_POST['email']);
		$login = htmlspecialchars($_POST['login']);
		$first_name = htmlspecialchars($_POST['first_name']);
		$last_name = htmlspecialchars($_POST['last_name']);
		$sexe = htmlspecialchars($_POST['sexe']);
		$orientation = htmlspecialchars($_POST['orientation']);
		$age = htmlspecialchars($_POST['age']);
		$bio = htmlspecialchars($_POST['bio']);
		$last_name = strtoupper($last_name);

		if ($orientation === "men")
			$number_orientation = 1;
		else if ($orientation === "women")
			$number_orientation = 2;
		else if ($orientation === "bi")
			$number_orientation = 3;
		else
			$number_orientation = 0;
		

		if ($sexe === "men")
			$number_sexe = 1;
		else if ($sexe === "women")
			$number_sexe = 2;
		else
			$number_sexe = 0;


		$req_login = $bdd->prepare('SELECT id_user FROM users WHERE login = ? AND id_user != '.$_SESSION['id'].'');
		$req_login->execute(array($login));

		$req_email = $bdd->prepare('SELECT id_user FROM users WHERE email = ? AND id_user != '.$_SESSION['id'].'');
		$req_email->execute(array($email));

		if($req_login->rowCount() > 0)
		{
			echo '<div id="alert_div"><p id="text_alert">ERROR : Pseudo already used !</p><span class="closebtn" onclick="del_alert()">&times;</span></div>';
		}
		else if ($req_email->rowCount() > 0) {
			echo '<div id="alert_div"><p id="text_alert">ERROR : Email already used !</p><span class="closebtn" onclick="del_alert()">&times;</span></div>';
		}
		else if ($age < 18) {
			echo '<div id="alert_div"><p id="text_alert">SORRY : Too young</p><span class="closebtn" onclick="del_alert()">&times;</span></div>';
		}
		else if ($age > 100) {
			echo '<div id="alert_div"><p id="text_alert">SORRY : Too old</p><span class="closebtn" onclick="del_alert()">&times;</span></div>';
		}
		else{
			$req = $bdd->prepare('SELECT * FROM users WHERE login = ? AND id_user = ?');
			$req->execute(array($_SESSION['login'] , $_SESSION['id']));

			if($req->rowCount() == 1)
			{
				if (isset($_POST['notif'])) {
					$notif = 1;
				}
				else{
					$notif = 0;
				}

				$stmt = $bdd->prepare("UPDATE users SET 
					email=:email, 
					login=:login, 
					first_name=:first_name,
					last_name=:last_name,
					sexe=:sexe,
					orientation=:orientation,
					age=:age,
					bio=:bio,
					notif=:notif 
					WHERE id_user like :id");

				$stmt->bindParam(':id', $_SESSION['id']);
				$stmt->bindParam(':email', $email);
				$stmt->bindParam(':login', $_SESSION['login']);
				$stmt->bindParam(':first_name', $first_name);
				$stmt->bindParam(':last_name', $last_name);
				$stmt->bindParam(':sexe', $number_sexe);
				$stmt->bindParam(':orientation', $number_orientation);
				$stmt->bindParam(':age', $age);
				$stmt->bindParam(':bio', $bio);
				$stmt->bindParam(':notif', $notif);
				$stmt->execute();
		
				echo "<style>#alert_div { background-color: #568456!important;} </style>";
	        	echo '<div id="alert_div"><p id="text_alert">Setting change</p><span class="closebtn" onclick="del_alert()">&times;</span></div>';
			}
			else{
				echo '<div id="alert_div"><p id="text_alert">SYSTEM ERROR !</p><span class="closebtn" onclick="del_alert()">&times;</span></div>';
			}	
		}	
}
else{
	echo '<div id="alert_div"><p id="text_alert">ERROR : Not completed !</p><span class="closebtn" onclick="del_alert()">&times;</span></div>';
}

}

/////////////////////////////////
////////// CHANGE PASSWD
/////////////////////////////////


if ($_POST['submit'] === "change_passwd") {

if (isset($_POST['old_password']) && $_POST['old_password'] != "" 
	&& isset($_POST['new_password']) && $_POST['new_password'] != "")
{
		$old_passwd = htmlspecialchars($_POST['old_password']);
		$new_passwd = htmlspecialchars($_POST['new_password']);
		$old_passwd = hash("whirlpool", htmlspecialchars($old_passwd));
		$new_passwd = hash("whirlpool", htmlspecialchars($new_passwd));

		if (strlen($_POST['new_password']) < 5){
			echo '<div id="alert_div"><p id="text_alert">ERROR : Password too short</p><span class="closebtn" onclick="del_alert()">&times;</span></div>';
		}
		else if (!preg_match("#[0-9]+#", $_POST['new_password'])){
			echo '<div id="alert_div"><p id="text_alert">ERROR : Password must include a number/p><span class="closebtn" onclick="del_alert()">&times;</span></div>';
		}
		else if (!preg_match("#[a-zA-Z]+#", $_POST['new_password'])){
			echo '<div id="alert_div"><p id="text_alert">ERROR : Password must include a letter</p><span class="closebtn" onclick="del_alert()">&times;</span></div>';
		}
		else{
			$req = $bdd->prepare('SELECT * FROM users WHERE login = ? AND passwd = ?');
			$req->execute(array($_SESSION['login'] , $old_passwd));

			if($req->rowCount() == 1)
			{
				$stmt = $bdd->prepare("UPDATE users SET passwd=:passwd WHERE login like :login");
				$stmt->bindParam(':login', $_SESSION['login']);
				$stmt->bindParam(':passwd', $new_passwd);
				$stmt->execute();
				echo "<style>#alert_div { background-color: #568456!important;} </style>";
				echo '<div id="alert_div"><p id="text_alert">Password change</p><span class="closebtn" onclick="del_alert()">&times;</span></div>';
			}
			else{
				echo '<div id="alert_div"><p id="text_alert">ERROR : Password Wrong</p><span class="closebtn" onclick="del_alert()">&times;</span></div>';
			}	
		}	
}

}

/////////////////////////////////
////////// ADD TAG
/////////////////////////////////

if ($_POST['submit'] === "add_tag") {

if (isset($_POST['tag']) && $_POST['tag'] != "")
{
		$tag = htmlspecialchars($_POST['tag']);
		$tag = strtoupper($tag);
		$req = $bdd->prepare('SELECT * FROM tags WHERE tag = ?');
		$req->execute(array($tag));

		if($req->rowCount() == 0)
		{
			$add_tag = $bdd->prepare('INSERT INTO tags (tag) VALUES (:tag)');
			$add_tag->execute(array('tag' => $tag));

		}

		$select_id = $bdd->prepare('SELECT id_tag FROM tags WHERE tag = ?');
		$select_id->execute(array($tag));
		$id = $select_id->fetch();

		$req_2 = $bdd->prepare('SELECT * FROM assoc WHERE id_tag = ? AND id_user = ?');
		$req_2->execute(array($id['id_tag'], $_SESSION['id']));

		if($req_2->rowCount() == 0)
		{

			$add_assoc = $bdd->prepare('INSERT INTO assoc (id_tag, id_user) VALUES (:id_tag, :id_user)');
			$add_assoc->execute(array('id_tag' => $id['id_tag'], 'id_user' => $_SESSION['id']));

			echo '<p id="tag_'.$id['id_tag'].'" onclick=del_tag('.$id['id_tag'].')>'.$tag.'</p>';
		}
}

}

/////////////////////////////////
////////// DEL TAG
/////////////////////////////////

if ($_POST['submit'] === "del_tag") {

if (isset($_POST['id']) && $_POST['id'] != "")
{
	$req = $bdd->prepare('DELETE FROM assoc WHERE id_assoc=:id');
	$req->bindParam(':id', $_POST['id'], PDO::PARAM_INT);
	$req->execute();
}

}

/////////////////////////////////
////////// LOCATION
/////////////////////////////////

if ($_POST['submit'] === "add_location") {

	$req = $bdd->prepare('UPDATE users SET latitude= ?, longitude= ? WHERE id_user= ?');
	$req->execute(array(
		$_POST['latitude'], 
		$_POST['longitude'], 
		$_SESSION['id']
	));
	echo "<style>#alert_div { background-color: #568456!important;} </style>";
	echo '<div id="alert_div"><p id="text_alert">Location change</p><span class="closebtn" onclick="del_alert()">&times;</span></div>';
}

/////////////////////////////////
////////// UNBLOCKED
/////////////////////////////////

if ($_POST['submit'] === "del_blocked") {

	$req = $bdd->prepare('DELETE FROM blocked WHERE id_blocked=:id');
	$req->bindParam(':id', $_POST['id'], PDO::PARAM_INT);
	$req->execute();


	$req = $bdd->prepare('INSERT INTO notif (id_user, id_user_notified, txt, date, seen) VALUES (:id_user, :id_user_notified, :txt, :date, 0)');
		$req->execute(array(
			'id_user' => $_POST['id'],
			'id_user_notified' => intval($_SESSION['id']),
			'txt' => "unblocked your profil",
			'date' => date('Ymd')
		));

		
	echo "<style>#alert_div { background-color: #568456!important;} </style>";
	echo '<div id="alert_div"><p id="text_alert">USER UNBLOCKED</p><span class="closebtn" onclick="del_alert()">&times;</span></div>';
}

?>