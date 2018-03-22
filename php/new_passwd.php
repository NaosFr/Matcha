<?php

include('connexion.php');

$email_save = $_GET['log'];
$cle_passwd = $_GET['cle'];
$cle_passwd = $cle_passwd;
$passwd = htmlspecialchars($_POST['password']);
$passwd = hash("whirlpool", $passwd);
$email = htmlspecialchars($_POST['email']);

$req = $bdd->prepare("SELECT id_user FROM users WHERE email = ? AND cle_passwd = ?");
$req->execute(array($email_save, $cle_passwd));

if($req->rowCount() == 1)
{
  $form = '<!-- ******* FORMULAIRE ***************** -->
  <section class="page_account-new" id="form">
    <!-- Form -->
    <form method="post" action="new_passwd.php" accept-charset="utf-8">
      
      <input type="hidden" name="email" value="'.$email_save.'">

      <label for="password"><p>PASSWORD</p></label>
      <br/>
      <input type="password" name="password" maxlength="20" required />

      <p class="register"><a href="../account_register.php">REGISTER</a></p>
      <!-- SIGN IN -->
      <input type="submit" name="go_login_account" value="SEND" class="submit"/>
    </form>
     <!-- /end Form -->
  </section>';
}

if (isset($_POST['password']) && $_POST['password'] != "" && isset($_POST['email']) && $_POST['email'] != "" )
{
  $form = '<!-- ******* FORMULAIRE ***************** -->
  <section class="page_account-new" id="form">
    <!-- Form -->
    <form method="post" action="new_passwd.php" accept-charset="utf-8">
      
      <input type="hidden" name="email" value="'.$_POST['email'].'">

      <label for="password"><p>PASSWORD</p></label>
      <br/>
      <input type="password" name="password" maxlength="20" required />

      <p class="register"><a href="../account_register.php">REGISTER</a></p>
      <!-- SIGN IN -->
      <input type="submit" name="go_login_account" value="SEND" class="submit"/>
    </form>
     <!-- /end Form -->
  </section>';

  if (strlen($_POST['password']) < 5){
    echo "<style>.alert { display: block!important; } </style>";
    $txt = "ERROR : Password too short";
  }
  else if (!preg_match("#[0-9]+#", $_POST['password'])){
    echo "<style>.alert { display: block!important; } </style>";
    $txt = "ERROR : Password must include a number";
  }
  else if (!preg_match("#[a-zA-Z]+#", $_POST['password'])){
    echo "<style>.alert { display: block!important; } </style>";
    $txt = "ERROR : Password must include a letter";
  }
  else{
    $stmt = $bdd->prepare("UPDATE users SET passwd=:passwd WHERE email like :email");
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':passwd', $passwd);
    $stmt->execute();
    
    echo "<style>#alert_div { background-color: #568456!important;} </style>";
    $txt =  '<div id="alert_div"><p id="text_alert">Password Change</p><span class="closebtn" onclick="del_alert()">&times;</span></div>';
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
  <link rel="icon" type="image/png" href="assets/icon/favicon.png" />

  <!-- ******* CSS ***************** -->
  <link rel="stylesheet" type="text/css" href="../css/main.css">
  <link rel="stylesheet" type="text/css" href="../css/form.css">
  <link rel="stylesheet" type="text/css" href="../css/new_passwd.css">
</head>

<body>
<!-- ******* HEADER ***************** -->
  <header class="float_menu">
      <a href="../index.php"><img src="../assets/icon/logo.png" alt="logo" class="logo"/></a>
  </header>

<!-- ******* ERROR ***************** -->
  <div id="alert" class="alert">
    <?php echo $txt;?>
  </div>

<!-- ******* FORMULAIRE ***************** -->
<?php echo $form; ?>

<script type="text/javascript" src="../js/main.js"></script>
</body>
</html>