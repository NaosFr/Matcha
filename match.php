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
	<link rel="stylesheet" type="text/css" href="css/match.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<link rel="stylesheet" type="text/css" href="css/responsive.css">

<!-- ******* SLIDER ***************** -->
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

</head>

<body>
<!-- ******* HEADER ***************** -->
	<header class="float_menu">
			<img src="assets/icon/menu.svg" alt="cross" class="cross" id="cross" />
			<a href="index.php"><img src="assets/icon/logo.png" alt="logo" class="logo"/></a>
			<div class="float_menu_rigth">
				<a href="match.php" class="link"><h2>MATCH</h2></a>
				<a href="profil.php"><h2>MY PROFIL</h2></a>
				<a href="account_setting.php"><h2>SETTING</h2></a>
				<a href="php/logout.php"><h2>LOGOUT </h2></a>				
			</div>
	</header>
	
<!-- ******* ICON CHAT ***************** -->
<a href="chat.php"><div class="transition icon_chat_div"><img src="assets/icon/chat.svg" alt="icon_chat" class="icon_chat"/></div></a>


<!-- ******* NOTIF ***************** -->
<div class="notif_container"></div>

<!-- ******* FILTER ***************** -->
	<section class="filter">
		<p class="suggestion">SUGGESTION</p>
		
		<input type="checkbox" name="suggestion" id="suggestion"/>
		<label for="suggestion" id="suggestion_label">✔</label>

		<h2 style="margin-top: 20px;">ORDER BY</h2>
		<p class="p_t" id="p_t_0" onclick="order_by(0)">ALL</p>
		<p class="p_t" id="p_t_1" onclick="order_by(1)">AGE</p>
		<p class="p_t" id="p_t_2" onclick="order_by(2)">LOCATION</p>
		<p class="p_t" id="p_t_3" onclick="order_by(3)">POPULARITY</p>
		<p class="p_t" id="p_t_4" onclick="order_by(4)">TAGS</p>
		<h2>FILTER</h2>
		
		<form action="#" onsubmit="return false" accept-charset="utf-8">
			<p>
			  <label for="age">AGE :</label>
			  <input type="text" id="age" name="age" readonly/>
			</p>
			<div id="slider-age" class="slider"></div>

			<p>
			  <label for="location">LOCATION :</label>
			  <input type="text" id="location" name="location" readonly/>
			</p>
			<div id="slider-location" class="slider"></div>

			<p>
			  <label for="popularity">POPULARITY :</label>
			  <input type="text" id="popularity" name="popularity" readonly/>
			</p>
			<div id="slider-popularity" class="slider"></div>
			
			<div id="list_tag_filter"></div>

			<input type="submit" id="submit" value="SEARCH" class="submit_search transition" onclick="filter()" style="margin-bottom: 30px; margin-top: 60px;" />
		</form>
	

		<form action="#" onsubmit="return false" accept-charset="utf-8" class="form_tag">
			
			<input type="text"  id="text_tag" name="tag" maxlength="40" required minlength="1" />
			
        	<!-- SIGN IN -->
			<input type="submit" value="ADD TAG" class="submit transition" onclick="add_tag()" />
        </form>

	
		
	</section>

<!-- ******* USERS ***************** -->
	<section class="users" id="users"></section>

<!-- ******* NAV MOBILE ***************** -->
<footer>
	<a href="chat.php"><img src="assets/icon/chat.svg" alt="chat" /></a>
	<a href="match.php"><img src="assets/icon/match.svg" alt="match" /></a>
	<a href="profil.php"><img src="assets/icon/man-user.svg" alt="profil" /></a>
	<a href="account_setting.php"><img src="assets/icon/settings.svg" alt="setting" /></a>
	<a href="php/logout.php"><img src="assets/icon/logout.svg" alt="logout" /></a>
</footer>

<!-- ******* JS ***************** -->
	<script>
	var bol = 0;
	$("#cross").click(function(){
    	if (bol == 1) {
    		$('.filter').show();
    		bol = 0;
    	}
    	else{
    		$('.filter').hide();
    		bol = 1;
    	}	
	});


	var tab = [];
	var index = 0;

	function add_tag(){
		
	    var data = $('input[name=tag]').val();

	    if (data != "") {
		    data = data.trim();
		    tab.push(data);

		    data = '<p id="tag_'+index+'" onclick="del_tag('+index+')">'+data+'</p>'

		    index++;
		    $('#text_tag').val("");
		    $('#list_tag_filter').prepend(data);
		}
	}
		

	function del_tag(id){
		

		var elt = document.getElementById('tag_'+id+'');
    	var monTexte = elt.innerText || elt.textContent;

		var index_text = tab.indexOf(monTexte);
		if (index_text > -1)
			tab.splice(index_text, 1);

	    var element = document.getElementById("tag_" + id);
		element.parentNode.removeChild(element);
	}


	var TYPE_SEND = 0;

	function order_by(type){
		if (type == 0)
			TYPE_SEND = 0;
		else if (type == 1)
			TYPE_SEND = 1;
		else if (type == 2)
			TYPE_SEND = 2;
	       else if (type == 3)
	        TYPE_SEND = 3;
		else if (type == 4)
			TYPE_SEND = 4;

		$('.p_t').css("color", "black");
		$('#p_t_'+ type).css("color", "rgb(53, 226, 223)");
		filter();
	}


	$("#suggestion").click(function(){
		filter();
	});

	var nbr_picture = 0;

	function filter(){
		nbr_picture += 20;
		var formData = {
			'nbr_picture'	: nbr_picture,
			'tags'			: JSON.stringify(tab),
			'age'    		: $('input[name=age]').val(),
			'location'    	: $('input[name=location]').val(),
			'popularity'    : $('input[name=popularity]').val(),
			'type'			: TYPE_SEND,
	        'ready'    		: "OK",
	    };

		var dir = "php/filter.php";
	    if ($("#suggestion").is(":checked"))
	        dir = "php/suggestion.php";
	    $.ajax({
	        type        : 'POST',
	        url         : dir,
	        data        : formData,
	        encode      : true,
	        success		: function(data){
	            $('#users').html(data);	
	        }
	    })
	}

		filter_start();

		function filter_start(){
			var formData = {
				'nbr_picture'	: "0",
				'age'    		: "18 - 50",
				'location'    	: "0 - 600",
				'popularity'    : "0 - 1000",
				'type'			: "0",
	            'ready'    		: "OK",

	        };

	        
	        $.ajax({
	            type        : 'POST',
	            url         : 'php/filter.php',
	            data        : formData,
	            encode      : true,
	            success		: function(data){
	            	$('#users').html(data);	
	            }
	        })
		}


	  $(function(){

	// SLIDER AGE
	    $("#slider-age").slider({
	      range: true,
	      min: 18,
	      max: 100,
	      values: [ 18, 50 ],
	      slide: function( event, ui ) {
	        $( "#age" ).val(ui.values[ 0 ] + " - " + ui.values[ 1 ] );
	      }
	    });
	    $( "#age" ).val($("#slider-age").slider("values", 0) +
	      " - " + $( "#slider-age" ).slider("values", 1));
		
	// SLIDER LOCATION
		$("#slider-location").slider({
	      range: true,
	      min: 0,
	      max: 1000,
	      values: [0, 600],
	      slide: function( event, ui ) {
	        $( "#location" ).val(ui.values[ 0 ] + " - " + ui.values[ 1 ] );
	      }
	    });
	    $( "#location" ).val($("#slider-location").slider("values", 0) +
	      " - " + $( "#slider-location" ).slider("values", 1));

	// SLIDER POPULARITY
		$("#slider-popularity").slider({
	      range: true,
	      min: 0,
	      max: 1000,
	      values: [0, 1000],
	      slide: function( event, ui ) {
	        $( "#popularity" ).val(ui.values[ 0 ] + " - " + ui.values[ 1 ] );
	      }
	    });
	    $( "#popularity" ).val($("#slider-popularity").slider("values", 0) +
	      " - " + $( "#slider-popularity" ).slider("values", 1));	  


	  });

	  </script>
	<script type="text/javascript" src="js/main.js"></script>
</body>
</html>