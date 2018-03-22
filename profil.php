<?php 
include('php/connexion.php');
session_start();

if ($_SESSION['id'] == "" || $_SESSION['login'] == "") {
	echo '<script>document.location.href="index.php"</script>';
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
	<link rel="stylesheet" type="text/css" href="css/profil.css">
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
				<a href="profil.php" class="link"><h2>MY PROFIL</h2></a>
				<a href="account_setting.php"><h2>SETTING</h2></a>
				<a href="php/logout.php"><h2>LOGOUT </h2></a>
			</div>
	</header>

<!-- ******* ICON CHAT ***************** -->
<a href="chat.php"><div class="transition icon_chat_div"><img src="assets/icon/chat.svg" alt="icon_chat" class="icon_chat"/></div></a>

<!-- ******* NOTIF ***************** -->
<div class="notif_container"></div>

<!-- ******* PROFIL ***************** -->
<section class="card_profil">

<?php  
	$req = $bdd->prepare('SELECT * FROM users WHERE id_user=:id');
	$req->bindParam(':id', $_SESSION['id'], PDO::PARAM_INT);
	$req->execute();
	$data = $req->fetch();
?>


	<h1 class="title">MY PROFIL</h1>
	<div class="info_pic">
		<div class="picture">

		<?php
			$dir = 'data/'.$_SESSION['id'].'/*.{jpg,jpeg,gif,png}';
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
			<div class="visit">
				<h2>USERS <span class="blue">VISITING</span></h2>
				<?php  
				$list = $bdd->prepare('SELECT users.login, visited.date FROM users INNER JOIN visited ON users.id_user = visited.id_who AND visited.id_user_target = ?');
				$list->execute(array($_SESSION['id']));
				while ($value = $list->fetch())
				{
					echo '<a href="user_profil.php?login='.$value['login'].'"><p>'.$value['date'].' : '.$value['login'].' </p></a>';
				}
				?>
			</div>
			<div class="liked">
				<h2>USERS <span class="blue">LIKING</span></h2>
				<?php  
				$list = $bdd->prepare('SELECT users.login, liked.date FROM users INNER JOIN liked ON users.id_user = liked.id_who AND liked.id_user_target = ?');
				$list->execute(array($_SESSION['id']));
				while ($value = $list->fetch())
				{
					echo '<a href="user_profil.php?login='.$value['login'].'"><p>'.$value['date'].' : '.$value['login'].' </p></a>';
				}
				?>
				
			</div>
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
				$list->execute(array($_SESSION['id']));
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
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/map.js"></script>
	<script type="text/javascript">

		var latitude;
		var longitude;

		latitude = <?php echo $data['latitude']?>;
		longitude = <?php echo $data['longitude']?>;

		show_position(latitude, longitude);

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