<?php
include('connexion.php');
session_start();
	$secret = "6Ld10kMUAAAAAIuGcqRmKg1UKGBhNv1_HHoUXipV";
	$response = $_POST['captcha'];
	$remoteip = $_SERVER['REMOTE_ADDR'];
	$api_url = "https://www.google.com/recaptcha/api/siteverify?secret=" 
	    . $secret
	    . "&response=" . $response
	    . "&remoteip=" . $remoteip ;
	
	$decode = json_decode(file_get_contents($api_url), true);
	
	if (isset($_POST['login']) && isset($_POST['password'])
		&& $_POST['login'] != "" && $_POST['password'] != "")
		{
		if ($decode['success'] == true) {
			$login = htmlspecialchars($_POST['login']);
			$passwd = htmlspecialchars($_POST['password']);
			$passwd = hash("whirlpool", htmlspecialchars($passwd));
			
			$req = $bdd->prepare('SELECT id_user, confirm FROM users WHERE login = ? AND passwd = ?');
			$req->execute(array($login, $passwd));
			if($req->rowCount() == 1)
			{
				$data = $req->fetch();
				if ($data['confirm'] == 0)
				{
					echo '<div id="alert_div"><p id="text_alert">ERROR : Email not confirmed !</p><span class="closebtn" onclick="del_alert()">&times;</span></div>';
				}
				else
				{
					$_SESSION['id'] = $data['id_user'];
					$_SESSION['login'] = $login;

					$content = json_decode(file_get_contents("https://ipinfo.io/".$_POST['ip']."/json"));
					$loc = explode(',', $content->loc);
		

					$stmt = $bdd->prepare("UPDATE users SET latitude=:latitude, longitude=:longitude, ip=:ip WHERE login=:login");

					$stmt->bindParam(':latitude', $loc[0]);
					$stmt->bindParam(':longitude', $loc[1]);
					$stmt->bindParam(':ip', $_POST['ip']);
					$stmt->bindParam(':login', $login);
					$stmt->execute();

					echo '<script>document.location.href="../match.php";</script>';
					exit;
				}
			}
			else
			{
				echo '<div id="alert_div"><p id="text_alert">ERROR : login or password wrong!</p><span class="closebtn" onclick="del_alert()">&times;</span></div>';
			}
		}
		else {
			echo '<div id="alert_div"><p id="text_alert">ERROR : captcha !</p><span class="closebtn" onclick="del_alert()">&times;</span></div>';
		}
	}
?>