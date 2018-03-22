<?php 
include('php/connexion.php');
session_start();

if ($_SESSION['id'] == "" || $_SESSION['login'] == "") {
	echo '<script>document.location.href="index.php"</script>';
}

$login = $_GET['login'];
$req = $bdd->prepare('SELECT * FROM users WHERE login=:login');
$req->bindParam(':login', $login);
$req->execute();
$data = $req->fetch();


$req = $bdd->prepare("UPDATE users SET score = score + IF((SELECT COUNT(id_visited) FROM visited WHERE id_user_target = ? AND id_who = ?) = 0, 1, 0) WHERE id_user = ?");
$req->execute(array($data['id_user'], $_SESSION['id'], $data['id_user']));
$req->execute();

$req =$bdd->prepare("SELECT * FROM visited WHERE id_who=:id_who AND id_user_target=:id_user_target");
$req->bindParam('id_who', $_SESSION['id']);
$req->bindParam('id_user_target', $data['id_user']);
$req->execute();

if($req->rowCount() == 0 && $data['id_user'] != $_SESSION['id'])
{
	$req =$bdd->prepare("INSERT INTO visited (id_who, id_user_target, date) VALUES (:id_who, :id_user_target, :date)");
	$req->bindParam('id_who', $_SESSION['id']);
	$req->bindParam('id_user_target', $data['id_user']);
	$req->bindParam(':date', date('Ymd'));
	$req->execute();

	$req = $bdd->prepare('SELECT * FROM notif WHERE id_user_notified = ? AND id_user = ? AND txt = ?');
	$req->execute(array($_SESSION['id'], $_POST['id'], "visited your profil"));
	if($req->rowCount() == 0)
	{
		$req = $bdd->prepare('INSERT INTO notif (id_user, id_user_notified, txt, date, seen) VALUES (:id_user, :id_user_notified, :txt, :date, 0)');
		$req->execute(array(
			'id_user' => $data['id_user'],
			'id_user_notified' => intval($_SESSION['id']),
			'txt' => "visited your profil",
			'date' => date('Ymd')
		));
	}
}

?>

<!DOCTYPE html>
<html id='HTML' xmlns:og="http://ogp.me/ns#" lang="fr">
<head>
	<meta charset="utf-8">
	<title>Matcha - <?php echo $_SESSION['login']; ?></title>
	<meta name="Content-Language" content="fr">
	<meta name="Description" content="">
	<meta name="keyword" content="">
	<meta name="Subject" content="">
	<meta name="Author" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<meta name="Copyright" content="© 2018 42. All rights reserved.">
	<link rel="icon" type="image/png" href="assets/icon/favicon.png" />

<!-- ******* CSS ***************** -->
	<link rel="stylesheet" type="text/css" href="css/user_profil.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<link rel="stylesheet" type="text/css" href="css/responsive.css">

<!-- ******* MAPBOX ***************** -->
	<script src='https://api.mapbox.com/mapbox-gl-js/v0.44.1/mapbox-gl.js'></script>
	<link href='https://api.mapbox.com/mapbox-gl-js/v0.44.1/mapbox-gl.css' rel='stylesheet' />

</head>

<body>
<!-- ******* HEADER ***************** -->
	<header class="float_menu">
			<a href="index.php"><img src="assets/icon/logo.png" alt="logo" class="logo"/></a>
			<div class="float_menu_rigth">
				<a href="match.php"><h2>MATCH</h2></a>
				<a href="profil.php"><h2>MY PROFIL</h2></a>
				<a href="account_setting.php"><h2>SETTING</h2></a>
				<a href="php/logout.php"><h2>LOGOUT </h2></a>
			</div>
	</header>

<!-- ******* ICON CHAT ***************** -->
<a href="chat.php"><div class="transition icon_chat_div"><img src="assets/icon/chat.svg" alt="icon_chat" class="icon_chat"/></div></a>


<!-- ******* NOTIF ***************** -->
<div class="notif_container"></div>


<!-- ******* ERROR ***************** -->
	<div id="alert" class="alert"></div>


<!-- ******* PROFIL ***************** -->

<section class="card_profil">

	<h1 class="title">PROFIL</h1>
	<div class="info_pic">
		<div class="picture">
			
			<?php
				$dir = 'data/'.$data['id_user'].'/*.{jpg,jpeg,gif,png}';
				$files = glob($dir,GLOB_BRACE);
				  
				foreach($files as $image)
				{ 
				   $f= str_replace($repertoire,'',$image);
				   echo '<img class="mySlides" src="'.$f.'" alt="image" />';
				}
			?>
		
			<div class="w3-button w3-display-left transition" onclick="plusDivs(-1)"><p>&#10094;</p></div>
			<div class="w3-button w3-display-right transition" onclick="plusDivs(+1)"><p>&#10095;</p></div>
		</div>
		<div class="activity">
			<h2>ACTIVITY USER</h2>

				<?php

					$req = $bdd->prepare('SELECT * FROM liked WHERE id_user_target = ? AND id_who = ?');
					$req->execute(array($data['id_user'], $_SESSION['id']));

					$date = date('Y/m/d h:i', $data['last_log']);

					$mytime = time();

					if ($data['last_log'] + 10 >= $mytime) {
						echo '<p class="connexion">online</p>';
					}
					else{
						echo '<p class="connexion">'.$date.'</p>';
					}
					echo '<div class="match_or_no">';

					if($req->rowCount() == 0 && $data['id_user'] != $_SESSION['id'])
					{
						echo '<img src="assets/icon/accept.svg" alt="accept" class="like transition" onclick="like('.$data['id_user'].')"/>';
					}
					else
					{
						echo '<img src="assets/icon/accept.svg" alt="accept" class="like transition" onclick="like('.$data['id_user'].')" style="display: none;"/>';
					}
					
					if($data['id_user'] != $_SESSION['id'])
					{
						echo '<img src="assets/icon/not_accept.svg" alt="not_accept" class="transition" onclick="unlike('.$data['id_user'].')"/>';
					}

				?>
				
			</div>
			<p class="lock" onclick="blocked(<?php echo $data['id_user'];?>)">BLOCK USER</p>
			<p class="report" onclick="report(<?php echo $data['id_user'];?>)">REPORT PROFIL</p>
		</div>
	</div>


	<p class="about">ABOUT</p>
	<p class="name"><?php  echo $data['first_name']." ".$data['last_name'];?></p>
	<p class="age"><?php  echo $data['age']." ";?><span class="blue">years<span></p>
	<p class="bio"><?php  echo $data['bio'];?></p>
	<p class="score">Score : <span class="blue"><?php  echo $data['score'];?></span></p>

	<div class="info">
		<div id='map'></div>
		<div id="list_tag">
			<?php
				$list = $bdd->prepare('SELECT * FROM tags INNER JOIN assoc ON tags.id_tag = assoc.id_tag AND assoc.id_user = ?');
				$list->execute(array($data['id_user']));
				while ($value = $list->fetch())
				{
					echo '<p id="tag_'.$value['id_assoc'].'" onclick=del_tag('.$value['id_assoc'].')>'.$value['tag'].'</p>';
				}

				?>
		</div>
	</div>
	

	
</section>

<!-- ******* NAV MOBILE ***************** -->
<footer>
	<a href="chat.php"><img src="assets/icon/chat.svg" alt="chat" /></a>
	<a href="match.php"><img src="assets/icon/match.svg" alt="match" /></a>
	<a href="profil.php"><img src="assets/icon/man-user.svg" alt="profil" /></a>
	<a href="account_setting.php"><img src="assets/icon/settings.svg" alt="setting" /></a>
	<a href="php/logout.php"><img src="assets/icon/logout.svg" alt="logout" /></a>
</footer>

<!-- ******* JS ***************** -->
	<script type="text/javascript" src="js/map.js"></script>
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript">
		var latitude;
		var longitude;

		latitude = <?php echo $data['latitude']?>;
		longitude = <?php echo $data['longitude']?>;

		show_position(latitude, longitude);


		function unlike(id){

	        var formData = {
	            'id'      		: id,
	            'submit'    		: "like"
	        };

	        $.ajax({
	            type        : 'POST',
	            url         : 'php/unlike.php',
	            data        : formData,
	            encode      : true,
	            success		: function(data){
	            	$('#alert').html(data);
	            	$('.like').show();
	            }
	        })
	    }


		function like(id){

	        var formData = {
	            'id'      		: id,
	            'submit'    		: "like"
	        };

	        $.ajax({
	            type        : 'POST',
	            url         : 'php/like.php',
	            data        : formData,
	            encode      : true,
	            success		: function(data){
	            	$('#alert').html(data);
	            	$('.like').hide();
	            }
	        })
	    }


		function report(id){

	        var formData = {
	            'id'      		: id,
	            'submit'    		: "report"
	        };

	        $.ajax({
	            type        : 'POST',
	            url         : 'php/report.php',
	            data        : formData,
	            encode      : true,
	            success		: function(data){
	            	$('#alert').html(data);
	            }
	        })
	    }

	    function blocked(id){

	        var formData = {
	            'id'      		: id,
	            'submit'    	: "blocked"
	        };

	        $.ajax({
	            type        : 'POST',
	            url         : 'php/blocked.php',
	            data        : formData,
	            encode      : true,
	            success		: function(data){
	            	$('#alert').html(data);
	            }
	        })
	    }

	    ///////// SLIDE PISCTURE

		var slideIndex = 1;
		showDivs(slideIndex);

		function plusDivs(n) {
		    showDivs(slideIndex += n);
		}

		function showDivs(n) {
		    var i;
		    var x = document.getElementsByClassName("mySlides");
		    if (n > x.length) {slideIndex = 1} 
		    if (n < 1) {slideIndex = x.length} ;
		    for (i = 0; i < x.length; i++) {
		        x[i].style.display = "none"; 
		    }
		    x[slideIndex-1].style.display = "block"; 
		}
	</script>
	<script type="text/javascript" src="js/main.js"></script>
</body>
</html>