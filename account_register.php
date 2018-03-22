<!DOCTYPE html>
<html id='HTML' xmlns:og="http://ogp.me/ns#" lang="fr">
<head>
	<meta charset="utf-8">
	<title>Matcha - Register</title>
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
	<section class="page_account-register" id="form">
		<!-- Form -->
        <form action="#" onsubmit="return false" accept-charset="utf-8">
			<label for="email"><p>EMAIL</p></label>
			<br/>
			<input type="email" name="email" maxlength="40" required />
			
			<label for="login"><p>PSEUDO</p></label>
			<br/>
			<input type="login" name="login" maxlength="40" required />

			<label for="first_name"><p>FIRST NAME</p></label>
			<br/>
			<input type="text" name="first_name" maxlength="40" required />
			
			<label for="last_name"><p>LAST NAME</p></label>
			<br/>
			<input type="text" name="last_name" maxlength="40" required />

			<label for="password"><p>PASSWORD</p></label>
			<br/>
			<input type="password" name="password" maxlength="20" minlength="5" required />
			
			<label for="password_conf"><p>CONFIRMATION PASSWORD</p></label>
			<br/>
			<input type="password" name="password_conf" maxlength="20" minlength="5" required />
			
			<div id="capatcha">
				<div class="g-recaptcha" data-sitekey="6Ld10kMUAAAAAIe51_5J7Swv7sG6j_8k5bxl5OZT"></div>
			</div>

			<p class="register"><a href="account_login.php">LOGIN</a></p>
			<!-- CREATE ACCOUNT -->
			<input type="submit" value="CREATE ACCOUNT" class="submit" onclick="register_profil()" />
		</form>
	</section>
</body>

<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript">

	function register_profil(){

	        var formData = {
	            'login'             : $('input[name=login]').val(),
	            'password'          : $('input[name=password]').val(),
	            'password_conf'     : $('input[name=password_conf]').val(),
	            'email'          	: $('input[name=email]').val(),
	            'first_name'        : $('input[name=first_name]').val(),
	            'last_name'         : $('input[name=last_name]').val(),
	            'captcha'           : grecaptcha.getResponse(),
	            'submit'    		: "register_profil"
	        };

	        $.ajax({
	            type        : 'POST',
	            url         : 'php/acc_register.php',
	            data        : formData,
	            encode      : true,
	            success		: function(data){
	            	$('#alert').html(data);
	            	$('input[name=password]').val('');
	            	$('input[name=password_conf]').val('');
	            	grecaptcha.reset();
	            }
	        })
		}

</script>
<script type="text/javascript" src="js/main.js"></script>
</html>
