<!DOCTYPE html>
<html id='HTML' xmlns:og="http://ogp.me/ns#" lang="fr">
<head>
	<meta charset="utf-8">
	<title>Matcha - Login</title>
	<meta name="Content-Language" content="fr">
	<meta name="Description" content="">
	<meta name="keyword" content="">
	<meta name="Subject" content="">
	<meta name="Author" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<meta name="Copyright" content="© 2018 42. All rights reserved.">
	<link rel="icon" type="image/png" href="assets/icon/favicon.png" />

	<script src='https://www.google.com/recaptcha/api.js'></script>
		
	<!-- ******* CSS ***************** -->
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<link rel="stylesheet" type="text/css" href="css/form.css">
	<link rel="stylesheet" type="text/css" href="css/responsive.css">
</head>

<body>
<!-- ******* HEADER ***************** -->
	<header class="float_menu">
			<a href="index.php"><img src="assets/icon/logo.png" alt="logo" class="logo"/></a>
			<div class="float_menu_rigth">
				<a href="index.php"><h2>HOME</h2></a>
			</div>
	</header>

<!-- ******* ERROR ***************** -->
	<div id="alert" class="alert"></div>

<!-- ******* FORMULAIRE ***************** -->
	<section class="page_account-login" id="form">
		<div class="banner" style="height: 365px;"></div>
		<!-- Form -->
          <form action="#" onsubmit="return false" accept-charset="utf-8">

			<label for="login"><p>PSEUDO</p></label>
			<br/>
			<input type="login" name="login" maxlength="40" required value="<?php echo $_POST['login'] ?>"/>

			<label for="password"><p>PASSWORD</p></label>
			<br/>
			<input type="password" name="password" maxlength="20" required />
			
			<div id="capatcha">
				<div class="g-recaptcha" data-sitekey="6Ld10kMUAAAAAIe51_5J7Swv7sG6j_8k5bxl5OZT"></div>
			</div>

        	<p class="register"><a href="account_register.php">REGISTER</a></p>
        	<p class="forgot"><a href="passwd_forgot.php">FORGOT PASSWORD</a></p>
        	<!-- SIGN IN -->
			<input type="submit" value="SIGN IN" class="submit" onclick="login_profil()"/>
          </form>
          <!-- /end Form -->
	</section>

<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript">
	var ip;
    $.getJSON("https://api.ipify.org?format=jsonp&callback=?",
      function(json) {
      	ip = json.ip;
      }
    );


	function login_profil(){

	        var formData = {
	        	'ip'				: ip,
	            'login'             : $('input[name=login]').val(),
	            'password'          : $('input[name=password]').val(),
	            'captcha'           : grecaptcha.getResponse(),
	            'submit'    		: "login_profil"
	        };

	        $.ajax({
	            type        : 'POST',
	            url         : 'php/acc_login.php',
	            data        : formData,
	            encode      : true,
	            success		: function(data){
	            	$('#alert').html(data);
	            	$('input[name=password]').val('');
	            	grecaptcha.reset();
	            }
	        })
		}

</script>
<script type="text/javascript" src="js/main.js"></script>
</body>
</html>