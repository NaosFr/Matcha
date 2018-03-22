<?php
include('php/connexion.php');
session_start();

if ($_SESSION['id'] == "" || $_SESSION['login'] == "") {
	echo '<script>document.location.href="index.php"</script>';
}

if(isset($_POST["submit_pic"])) {
	if (is_uploaded_file($_FILES["fileToUpload_1"]['tmp_name'])) {
		$number = 1;
		$name = "fileToUpload_1";
	}
	else if (is_uploaded_file($_FILES["fileToUpload_2"]['tmp_name'])){
		$number = 2;
		$name = "fileToUpload_2";
	}
	else if (is_uploaded_file($_FILES["fileToUpload_3"]['tmp_name'])) {
		$number = 3;
		$name = "fileToUpload_3";
	}
	else if (is_uploaded_file($_FILES["fileToUpload_4"]['tmp_name'])) {
		$number = 4;
		$name = "fileToUpload_4";
	}
	else if (is_uploaded_file($_FILES["fileToUpload_5"]['tmp_name'])) {
		$number = 5;
		$name = "fileToUpload_5";
	}

	$target_dir = 'data/'.$_SESSION['id'].'/';
	$target_file = $target_dir . basename($_FILES[$name]["name"]);
	$uploadOk = 1;
	$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));


	$check = getimagesize($_FILES[$name]["tmp_name"]);
	if($check !== false) {
	    $uploadOk = 1;
	} else {
	    $uploadOk = 0;
	}

	if ($_FILES[$name]["size"] > 500000000) {
	    $txt = '<div id="alert_div"><p id="text_alert">Sorry, your file is too large</p><span class="closebtn" onclick="del_alert()">&times;</span></div>';
	    $uploadOk = 0;
	}

	else if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
	&& $imageFileType != "gif" ) {
		$txt = '<div id="alert_div"><p id="text_alert">Sorry, only JPG, JPEG, PNG & GIF files are allowed</p><span class="closebtn" onclick="del_alert()">&times;</span></div>';
	    $uploadOk = 0;
	}
	else if ($uploadOk == 0) {
		$txt = '<div id="alert_div"><p id="text_alert">Sorry, your file was not uploaded</p><span class="closebtn" onclick="del_alert()">&times;</span></div>';

	} else {
		if (file_exists('data/'.$_SESSION['id'].'') == 0)
			mkdir('data/'.$_SESSION['id'].'');

		$type = $_FILES[$name]["type"];
		$type = substr($type, 6); 

		if (file_exists($target_dir . $number . ".png")) {
			unlink($target_dir . $number . ".png");
		}
		if (file_exists($target_dir . $number . ".jpg")) {
			unlink($target_dir . $number . ".jpg");
		}
		if (file_exists($target_dir . $number . ".png")) {
			unlink($target_dir . $number . ".png");
		}
		if (file_exists($target_dir . $number . ".jpeg")) {
			unlink($target_dir . $number . ".jpeg");
		}

		$target_file = $target_dir . $number . "." . $type;

	    if (move_uploaded_file($_FILES[$name]["tmp_name"], $target_file)) {
	       echo "<style>#alert_div { background-color: #568456!important;} </style>";
	    $txt =  '<div id="alert_div"><p id="text_alert">Picture add</p><span class="closebtn" onclick="del_alert()">&times;</span></div>';


	    } else {
	        $txt = '<div id="alert_div"><p id="text_alert">ERROR UPLOAD</p><span class="closebtn" onclick="del_alert()">&times;</span></div>';
	    }
	}

}

?>

<!DOCTYPE html>
<html id='HTML' xmlns:og="http://ogp.me/ns#" lang="fr">
<head>
	<meta charset="utf-8">
	<title>Matcha - Setting</title>
	<meta name="Content-Language" content="fr">
	<meta name="Description" content="">
	<meta name="keyword" content="">
	<meta name="Subject" content="">
	<meta name="Author" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<meta name="Copyright" content="© 2018 42. All rights reserved.">
	<link rel="icon" type="image/png" href="assets/icon/favicon.png" />
		
	<!-- ******* CSS ***************** -->
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<link rel="stylesheet" type="text/css" href="css/form.css">
	<link rel="stylesheet" type="text/css" href="css/account_setting.css">
	<link rel="stylesheet" type="text/css" href="css/responsive.css">
	
	<!-- ******* MAPBOX ***************** -->
	<script src='https://api.tiles.mapbox.com/mapbox-gl-js/v0.44.1/mapbox-gl.js'></script>
    <link href='https://api.tiles.mapbox.com/mapbox-gl-js/v0.44.1/mapbox-gl.css' rel='stylesheet' />
	
  <style type='text/css'>
	#info {
		display: block;
		position: relative;
		margin: 0px auto;
		width: 50%;
		padding: 10px;
		border: none;
		border-radius: 3px;
		font-size: 12px;
		text-align: center;
		color: #222;
		background: #fff;
	}
	</style>

</head>

<body>
<!-- ******* HEADER ***************** -->
	<header class="float_menu">
			<a href="index.php"><img src="assets/icon/logo.png" alt="logo" class="logo"/></a>
			<div class="float_menu_rigth">
				<a href="match.php"><h2>MATCH</h2></a>
				<a href="profil.php"><h2>MY PROFIL</h2></a>
				<a href="account_setting.php" class="link"><h2>SETTING</h2></a>
				<a href="php/logout.php"><h2>LOGOUT </h2></a>
			</div>
	</header>

<!-- ******* NOTIF ***************** -->
<div class="notif_container"></div>

<!-- ******* ERROR ***************** -->
	<div id="alert" class="alert">
		<?php echo $txt;?>
	</div>

<!-- ******* FORMULAIRE ***************** -->
	<?php  
		$req_form = $bdd->prepare('SELECT * FROM users WHERE id_user = ?');
		$req_form->execute(array($_SESSION['id']));
		$value = $req_form->fetch();

		if ($value['notif'] == 1)
			$check = "checked";
		else
			$check = "";


		if ($value['orientation'] == 1)
			$orientation = '<option value="men" name="orientation" selected>MEN</option>
			  <option value="women" name="orientation">WOMEN</option>
			  <option value="bi" name="orientation">MEN AND WOMAN</option>';
		else if ($value['orientation'] == 2)
			$orientation = '<option value="men" name="orientation">MEN</option>
			  <option value="women" name="orientation" selected>WOMEN</option>
			  <option value="bi" name="orientation">MEN AND WOMEN</option>';
		else if ($value['orientation'] == 3)
			$orientation = '<option value="men" name="orientation">MEM</option>
			  <option value="women" name="orientation">WOMEM</option>
			  <option value="bi" name="orientation" selected>MEN AND WOMEN</option>';
		else{
			$orientation = '<option value="null" name="orientation" selected></option>
			  <option value="men" name="orientation">MEM</option>
			  <option value="women" name="orientation">WOMEM</option>
			  <option value="bi" name="orientation" >MEN AND WOMEN</option>';
		}

		if ($value['sexe'] == 1)
			$sexe = '<option value="men" name="sexe" selected>MEN</option>
			  <option value="women" name="sexe">WOMEN</option>';
		else if ($value['sexe'] == 2)
			$sexe = '<option value="men" name="sexe">MEN</option>
			  <option value="women" name="sexe" selected>WOMEN</option>';
		else
			$sexe = '<option value="null" name="sexe" selected></option>
			<option value="men" name="sexe">MEN</option>
			  <option value="women" name="sexe" >WOMEN</option>';
	?>

	<section  class="page_account-modify" id="form">
		
		<!-- ADD PICTURE-->
          <form action="account_setting.php" method="post" enctype="multipart/form-data" style="padding-top: 20px; padding-bottom: 60px;">

			<input type="file" name="fileToUpload_1" class="fileToUpload" id="picture_1" />
			<input type="file" name="fileToUpload_2" class="fileToUpload" id="picture_2" />
			<input type="file" name="fileToUpload_3" class="fileToUpload" id="picture_3" />
			<input type="file" name="fileToUpload_4" class="fileToUpload" id="picture_4" />
			
		<?php

		$num = 1;
		while($num <= 4)
		{
			$dir = 'data/'.$_SESSION['id'].'/'.$num.'.{jpg,jpeg,gif,png}';
			$files = glob($dir,GLOB_BRACE);
			$f= str_replace($repertoire,'',$files[0]);

			echo '<div class="div_picture">';
			if (!isset($files[0])) {
				echo '<img src="assets/icon/import.svg" alt="import" onclick="file_send('.$num.')" class="import transition"/>';
			}
			else{
				echo '<img src="assets/icon/import_white.svg" alt="import" onclick="file_send('.$num.')" class="import transition"/>
				<img src="'.$f.'" alt="img" id="picture_setting_user"/>';
			}
			echo '</div>';
			$num++;
		}




			
			// foreach($files as $image)
			// {
			//    $f= str_replace($repertoire,'',$image);
			//    echo '<img src="'.$f.'" alt="img" id="picture_setting_user"/>';
			// }
		?>
		    
		    <input type="submit" value="ADD PICTURE" class="submit transition" name="submit_pic"/>
		</form>





		<!-- Form Profil -->
          <form action="#" onsubmit="return false" accept-charset="utf-8" style="height: 871px; margin-top: 20px;">

			<label for="email"><p>EMAIL</p></label>
			<br/>
			<input type="email" id="email" name="email" maxlength="40" required value="<?php echo $value['email']?>" />

			<label for="login"><p>PSEUDO</p></label>
			<br/>
			<input type="login" id="login" name="login" maxlength="40" required value="<?php echo $value['login']?>" />

			<label for="first_name"><p>FIRST NAME</p></label>
			<br/>
			<input type="text"  id="" name="first_name" maxlength="40" required value="<?php echo $value['first_name']?>"/>
			
			<label for="last_name"><p>LAST NAME</p></label>
			<br/>
			<input type="text" name="last_name" maxlength="40" required value="<?php echo $value['last_name']?>"/>

			<label for="sexe"><p>SEXE</p></label>
			<br/>
			<select><?php echo $sexe; ?></select>

			<label for="orientation"><p>ORIENTATION SEXUEL</p></label>
			<br/>
			<select><?php echo $orientation; ?></select>
			
			<label for="age"><p>AGE</p></label>
			<br/>
			<input type="number" name="age" max="100" min="18" required value="<?php echo $value['age']?>">
			
			<label for="bio"><p>DESCRIPTION</p></label>
			<br/>
			<textarea maxlength="255" name="bio" required><?php echo $value['bio']?></textarea>

			<br/>
			<input id="toggle" type="checkbox" name="notif" <?php echo $check; ?> />
  			<label for="toggle" class="toggle"><p style="    margin-top: 0px;">ACTIVE NOTIFICATION</p></label>

        	<!-- SIGN IN -->
			<input type="submit" value="MODIFY PROFIL" class="submit transition" onclick="change_profil()" />
          </form>

		<!-- Form Passwd-->
		<form action="#" onsubmit="return false" accept-charset="utf-8" style="height: 270px; margin-top: 20px;">
			
			<label for="old_password"><p>OLD PASSWORD</p></label>
			<br/>
			<input type="password" name="old_password" maxlength="20" required />

			<label for="new_password"><p>NEW PASSWORD</p></label>
			<br/>
			<input type="password" name="new_password" maxlength="20" required />
			
			<br/>

			<p class="delete"><a href="account_delete.php">DELETE ACCOUNT</a></p>

        	<!-- SIGN IN -->
			<input type="submit" value="CHANGE PASSWORD" class="submit transition" onclick="change_passwd()"/>
          </form>
	
		<!-- Form Localisation-->
		<form action="#" onsubmit="return false" accept-charset="utf-8" style="height: 255px; margin-top: 20px;">
			<button id="localise_me" onclick="add_location_auto()">LOCALISE ME</button>
			<div id="map"></div>
			
        	<!-- SIGN IN -->
			<input type="submit" name="submit_localisation"  class="submit transition" onclick="add_location()"/>
        </form>


		<form action="#" onsubmit="return false" accept-charset="utf-8" style="height: 295px; margin-top: 20px; margin-bottom: 50px">
			
			<label for="tag"><p>TAG</p></label>
			<br/>
			<input type="text" name="tag" maxlength="40" required/>
			
			<div id="list_tag">
				<?php  

				$list = $bdd->prepare('SELECT * FROM tags INNER JOIN assoc ON tags.id_tag = assoc.id_tag AND assoc.id_user = ?');
				$list->execute(array($_SESSION['id']));
				while ($assoc = $list->fetch())
				{
					echo '<p id="tag_'.$assoc['id_assoc'].'" onclick=del_tag('.$assoc['id_assoc'].')>'.$assoc['tag'].'</p>';
				}

				?>
			</div>
        	<!-- SIGN IN -->
			<input type="submit" value="ADD TAG" class="submit transition" onclick="add_tag()" />
        </form>


		<form action="#" onsubmit="return false" accept-charset="utf-8" style="height: 295px; margin-top: -27px; margin-bottom: 50px">
			
			<p>USER BLOCKED</p>
			
			<div id="list_user_blocked">
				<?php  

				$list = $bdd->prepare('SELECT * FROM blocked WHERE id_user = ?');
				$list->execute(array($_SESSION['id']));
				while ($assoc = $list->fetch())
				{
					$req = $bdd->prepare('SELECT login FROM users WHERE id_user = ?');
					$req->execute(array($assoc['id_user_blocked']));
					$data = $req->fetch();

					echo '<p id="blocked_'.$assoc['id_blocked'].'" ><img src="assets/icon/trash.svg" alt="trash" onclick="unblocked('.$assoc['id_blocked'].')"/>'.$data['login'].'</p>';
				}

				?>
			</div>
        </form>



         <!-- /end Form -->
	</section>
	
	<!-- ******* NAV MOBILE ***************** -->
<footer>
	<a href="chat.php"><img src="assets/icon/chat.svg" alt="chat" /></a>
	<a href="match.php"><img src="assets/icon/match.svg" alt="match" /></a>
	<a href="profil.php"><img src="assets/icon/man-user.svg" alt="profil" /></a>
	<a href="account_setting.php"><img src="assets/icon/settings.svg" alt="setting" /></a>
	<a href="php/logout.php"><img src="assets/icon/logout.svg" alt="logout" /></a>
</footer>


	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/map.js"></script>
	<script type="text/javascript">
		var latitude;
		var longitude;

		latitude = <?php echo $value['latitude']?>;
		longitude = <?php echo $value['longitude']?>;

		show_position(latitude, longitude);

		map.on('click', function (e)
		{
			latitude = e.lngLat.lat;
			longitude = e.lngLat.lng;
			


			map.removeLayer('symbols_' + layer);
			layer++;
			map.addLayer(
			{
				"id": "symbols_" + layer,
				"type": "symbol",
				"source":
				{
					"type": "geojson",
					"data":
					{
						"type": "FeatureCollection",
						"features": [
						{
							"type": "Feature",
							"properties": {},
							"geometry":
							{
								"type": "Point",
								"coordinates": [longitude, latitude]
							}
						}]
					}
				},
				"layout":
				{
					"icon-image": "piker",
					"icon-size": 0.1
				}
			});
		});

		function unblocked(id){
	        var formData = {
	        	'id'    		: id,
	            'submit'    		: "del_blocked"
	        };

	        $.ajax({
	            type        : 'POST',
	            url         : 'php/setting.php',
	            data        : formData,
	            encode      : true,
	            success		: function(data){
	            	$('#alert').html(data);
	            }
	        })

	        var element = document.getElementById("blocked_" + id);
				element.parentNode.removeChild(element);
		}

		function change_profil(){

	        var formData = {
	            'email'             : $('input[name=email]').val(),
	            'login'             : $('input[name=login]').val(),
	            'first_name'        : $('input[name=first_name]').val(),
	            'last_name'         : $('input[name=last_name]').val(),
	            'sexe'              : $('option[name=sexe]:selected').val(),
	            'orientation'       : $('option[name=orientation]:selected').val(),
	            'age'             	: $('input[name=age]').val(),
	            'notif'             : $('input[name=notif]:checked').val(),
	            'bio'    			: $('textarea[name=bio]').val(),
	            'submit'    		: "change_profil"
	        };

	        $.ajax({
	            type        : 'POST',
	            url         : 'php/setting.php',
	            data        : formData,
	            encode      : true,
	            success		: function(data){
	            	$('#alert').html(data);
	            }
	        })
		}

		function change_passwd(){

	        var formData = {
	            'old_password'      : $('input[name=old_password]').val(),
	            'new_password'      : $('input[name=new_password]').val(),
	            'submit'    		: "change_passwd"
	        };

	        $.ajax({
	            type        : 'POST',
	            url         : 'php/setting.php',
	            data        : formData,
	            encode      : true,
	            success		: function(data){
	            	$('#alert').html(data);
	            }
	        })
	    }

	    function add_tag(){

	        var formData = {
	            'tag'      			: $('input[name=tag]').val(),
	            'submit'    		: "add_tag"
	        };

	        $.ajax({
	            type        : 'POST',
	            url         : 'php/setting.php',
	            data        : formData,
	            encode      : true,
	            success		: function(data){
	            	$('#list_tag').prepend(data);
	            }
	        })
	    }
		
		function del_tag(id){

	        var formData = {
	        	'id'    		: id,
	            'submit'    		: "del_tag"
	        };

	        $.ajax({
	            type        : 'POST',
	            url         : 'php/setting.php',
	            data        : formData,
	            encode      : true,
	        })

	        var element = document.getElementById("tag_" + id);
				element.parentNode.removeChild(element);
	    }


	    function file_send(id){
			var button = document.getElementById("picture_" + id);
			button.click();
		}
	</script>
	<script type="text/javascript" src="js/main.js"></script>
</body>
</prepend>