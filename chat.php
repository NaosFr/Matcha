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
	<title>Matcha - Chat</title>
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
	<link rel="stylesheet" type="text/css" href="css/chat.css">
	<link rel="stylesheet" type="text/css" href="css/responsive.css">

	
</head>

<body>
<!-- ******* HEADER ***************** -->
	<header class="float_menu">
			<img src="assets/icon/menu.svg" alt="cross" class="cross" id="cross" />
			<a href="index.php"><img src="assets/icon/logo.png" alt="logo" class="logo"/></a>
			<div class="float_menu_rigth">
				<a href="match.php"><h2>MATCH</h2></a>
				<a href="profil.php"><h2>MY PROFIL</h2></a>
				<a href="account_setting.php"><h2>SETTING</h2></a>
				<a href="php/logout.php"><h2>LOGOUT </h2></a>
			</div>
	</header>

<!-- ******* NOTIF ***************** -->
<div class="notif_container"></div>

<!-- ******* ERROR ***************** -->
<div id="alert" class="alert"></div>


<section class="user_conv">

</section>


<section class="users_msg" id="gobottom">
	
</section>

<div class="users_msg_form">

</div>

<!-- ******* NAV MOBILE ***************** -->
<footer>
	<a href="chat.php"><img src="assets/icon/chat.svg" alt="chat" /></a>
	<a href="match.php"><img src="assets/icon/match.svg" alt="match" /></a>
	<a href="profil.php"><img src="assets/icon/man-user.svg" alt="profil" /></a>
	<a href="account_setting.php"><img src="assets/icon/settings.svg" alt="setting" /></a>
	<a href="php/logout.php"><img src="assets/icon/logout.svg" alt="logout" /></a>
</footer>

	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript">

	var bol = 0;
	$("#cross").click(function(){
    	if (bol == 1) {
    		$('.user_conv').show();
    		bol = 0;
    	}
    	else{
    		$('.user_conv').hide();
    		bol = 1;
    	}	
	});


	list_convo();

	function list_convo(){

	        var formData = {
	            'submit'    		: "list_convo"
	        };

	        $.ajax({
	            type        : 'POST',
	            url         : 'php/chat_back.php',
	            data        : formData,
	            encode      : true,
	            success		: function(data){
	            	$('.user_conv').html(data);
	            }
	        })
		}

	
   
	var log_id;

	function getConvo(formData)
	{
		$.ajax({
	            type        : 'POST',
	            url         : 'php/chat_back.php',
	            data        : formData,
	            encode      : true,
	            success		: function(data){
	            	var caca = $('.users_msg').html();
	            	if (caca != data)
	            	{
		            	$('.users_msg').html(data);
						var objDiv = document.getElementById("gobottom");
	                    objDiv.scrollTop = objDiv.scrollHeight;
	                }
	            }
	    });
	}

	async function convo(id){

		form(id);
		var my_id = id;
		log_id = id;
		var formData = {
	        'id'				: my_id,
	        'submit'    		: "msg_convo"
	    };
		while (my_id == log_id)
		{
			getConvo(formData);
			await sleep(1000);
		}
	}
	
	function sleep(ms)
	{
		return new Promise(resolve => setTimeout(resolve, ms));
	}

	function form(id){

	        var formData = {
	        	'id'				: id,
	            'submit'    		: "form"
	        };

	        $.ajax({
	            type        : 'POST',
	            url         : 'php/chat_back.php',
	            data        : formData,
	            encode      : true,
	            success		: function(data){
	            	$('.users_msg_form').html(data);
	            	document.getElementById("write").focus();

	            }
	        })
		
		}

	function del_convo(id){

	        var formData = {
	        	'id'				: id,
	            'submit'    		: "del_convo"
	        };

	        $.ajax({
	            type        : 'POST',
	            url         : 'php/chat_back.php',
	            data        : formData,
	            encode      : true,
	            success		: function(data){
	            	$('.users_msg').html("");
	            	list_convo();
	            }
	        })
		}

	function add_msg_convo(id){

	        var formData = {
	        	'txt'				: $('input[name=text]').val(),
	        	'id'				: id,
	            'submit'    		: "add_msg"
	        };

	        $.ajax({
	            type        : 'POST',
	            url         : 'php/chat_back.php',
	            data        : formData,
	            encode      : true,
	            success		: function(data){
	            	$('.users_msg').html(data);
	            	convo(id);
	            }
	        })
		}

	

	</script>
	<script type="text/javascript" src="js/main.js"></script>
</body>
</prepend>