<?php
include('connexion.php');
session_start();

	$req = $bdd->prepare('SELECT * FROM notif WHERE id_user = ? AND seen = 0');
	$req->execute(array($_SESSION['id']));;
	if($req->rowCount() == 0)
	{
		echo '<img src="assets/icon/notif.svg" alt="notif" class="img_notif" onclick="notif()" />';
	}
	else{
		echo '<img src="assets/icon/notif_not_seen.svg" alt="notif" class="img_notif" onclick="notif()" />';
	}

// INSERT INTO `notif` (`id_notif`, `id_user`, `id_user_notified`, `txt`, `date`, `seen`) VALUES
// (12, 1, 706, 'liked your profil', '2018-03-09', 0),
// (13, 1, 706, 'visited your profil', '2018-03-09', 0),
// (14, 1, 706, 'liked your profil', '2018-03-09', 0);
?>