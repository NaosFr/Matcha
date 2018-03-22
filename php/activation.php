<?php

include('connexion.php');

$comfirm = 0;
$login = $_GET['log'];
$cle = $_GET['cle'];
$cle = $cle;

$stmt = $bdd->prepare("SELECT * FROM users WHERE login=:login ");
if($stmt->execute(array(':login' => $login)) && $row = $stmt->fetch())
  {
    $clebdd = $row['cle'];
    $confirm = $row['confirm'];
  }

echo $confirm;
if($confirm == '1')
  {
          $txt = '<div id="alert_div"><p id="text_alert">Votre compte est déjà actif !</p><span class="closebtn" onclick="del_alert()">&times;</span></div>';
  }
else
  {

     if($cle == $clebdd)
       {
          echo "<style>#alert_div { background-color: #568456!important;} </style>";
          $txt = '<div id="alert_div"><p id="text_alert">Votre compte a bien été activé !</p><span class="closebtn" onclick="del_alert()">&times;</span></div>';
 
          $stmt = $bdd->prepare("UPDATE users SET confirm = 1 WHERE login like :login ");
          $stmt->bindParam(':login', $login);
          $stmt->execute();
       }
     else
       {
        $txt = '<div id="alert_div"><p id="text_alert">Erreur ! Votre compte ne peut être activé...</p><span class="closebtn" onclick="del_alert()">&times;</span></div>';

       }
  }
 
 ?>


<!DOCTYPE html>
<html id='HTML' xmlns:og="http://ogp.me/ns#" lang="fr">
<head>
  <meta charset="utf-8">
  <title>Matcha - Confirmation</title>
  <meta name="Content-Language" content="fr">
  <meta name="Description" content="">
  <meta name="keyword" content="">
  <meta name="Subject" content="">
  <meta name="Author" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <meta name="Copyright" content="© 2018 42. All rights reserved.">
  <link rel="icon" type="image/png" href="../assets/icon/favicon.png" />

  <!-- ******* CSS ***************** -->
  <link rel="stylesheet" type="text/css" href="../css/main.css">
  <style type="text/css">
    .background_menu{
      width: 100%;
      height: 100%;
      position: relative;
      text-align: center;
      justify-content: center;
      -webkit-justify-content: center;
      align-items: center;
      -webkit-align-items: center;
      display: -webkit-flex;
      cursor: pointer;
      background-image: url(../assets/icon/background-1.jpg);
    background-size: cover;
    background-position: center center;
    background-attachment: fixed;
    background-repeat: no-repeat;
    -webkit-background-size: cover;
    -moz-background-size: cover;
    -o-background-size: cover; 
    }

    .title_square{
      position: relative;
      font-size: 3.5vw;
      color: white; 
      padding: 20px;
      border: 1px solid white;
      background-color: #262626bf;
      -webkit-transition: .5s ease-out;
      -moz-transition: .5s ease-out;
      -o-transition: .5s ease-out;
      -ms-transition: .5s ease-out;
      transition: .5s ease-out;
    }
    
    .title_square a{
        color: white;
    }

    .title_square:hover{
      background-color: #f9f2f254;
    }
  </style>
</head>

<body>
<!-- ******* HEADER ***************** -->
  <header class="float_menu">
      <a href="index.php"><img src="../assets/icon/logo.png" alt="logo" class="logo"/></a>
  </header>

<!-- ******* ERROR ***************** -->
<div id="alert" class="alert">
  <?php echo $txt; ?>
</div>

<!-- ******* BACKGROUND CONFIRMATION ***************** -->
  <section class="background_menu">
    <h1 class="title_square"><a href="../account_login.php">LOGIN MATCHA</a></h1>
  </section>

<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript" src="../js/main.js"></script>
</body>
</html>