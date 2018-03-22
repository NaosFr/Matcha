<?php

date_default_timezone_set("Europe/Paris");
try
{
	$bdd = new PDO('mysql:dbname=matcha;host=192.168.99.100;charset=utf8', 'root', 'root');
	$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$bdd->exec("SET NAMES 'UTF8'");
}
catch (Exception $e)
{
	print "Erreur !: " . $e->getMessage() . "<br/>";
	exit;
}
session_start();
?>
