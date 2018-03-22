<?php
include 'php/connexion.php';
require_once 'vendor/autoload.php';

$min_lat = 41;
$max_lat = 51;
$min_log = 0;
$max_log = 7.5;


$faker = Faker\Factory::create();
$j = 0;
while ($j < 500)
{
	$sexe = rand(1, 2);
	$cle = md5(microtime(TRUE)*100000);

	$hash = hash('whirlpool', $faker->password);
	$req = $bdd->prepare('INSERT INTO users (email, login, passwd, last_name, first_name, bio, sexe, orientation, age, score, latitude, longitude, last_log, cle, confirm) VALUES (:email, :login, :passwd, :last_name, :first_name, :bio, :sexe, :orientation, :age, :score, :latitude, :longitude, :last_log, :cle, :confirm)');

	$req->execute(array(
	'email' => $faker->email,
	'passwd' => $hash,
	'login' => $faker->userName,
	'last_name' => $faker->lastName,
	'first_name' => $faker->firstName,
	'bio' => $faker->text(999),
	'sexe' => $sexe,
	'orientation' => rand(1, 3),
	'age' => rand(18, 100),
	'score' => rand(0, 1000),
	'last_log' => time(),
	'cle' => $cle,
	'latitude' => rand($min_lat * pow(10, 6), $max_lat * pow(10, 6)) / pow(10, 6),
	'longitude' => rand($min_log * pow(10, 6), $max_log * pow(10, 6)) / pow(10, 6),
	'confirm' => 1
	));
	

	$link = "data/fake_img/women/";

	if ($sexe == 1) {
		$link = "data/fake_img/men/";
	}

	$id = $bdd->lastInsertId();
	$name = ['img1', 'img2', 'img3', 'img4', 'img5', 'img6', 'img7', 'img8', 'img9', 'img10', 'img11', 'img12'];

	$x = rand(0, count($name) - 1);

	$link .= $name[$x].".jpg";

	mkdir('data/'.$id);
	$target_dir = 'data/'.$id.'/1.jpg';

	copy($link, $target_dir);



	$tags = ['42', 'TRAVEL', 'COOKING', 'SHOPPING', 'FAMILY', 'WORK', 'SPORT', 'SHARE', 'TECH', 'CHILDREN', 'FOOD', 'MUSEUM'];
	$x = rand(0, count($tags) - 1);

	$tag = $tags[$x];

	$req = $bdd->prepare('SELECT * FROM tags WHERE tag = ?');
	$req->execute(array($tag));

	if($req->rowCount() == 0)
	{
		$add_tag = $bdd->prepare('INSERT INTO tags (tag) VALUES (:tag)');
		$add_tag->execute(array('tag' => $tag));

	}

	$select_id = $bdd->prepare('SELECT id_tag FROM tags WHERE tag = ?');
	$select_id->execute(array($tag));
	$id_t = $select_id->fetch();


	$add_assoc = $bdd->prepare('INSERT INTO assoc (id_tag, id_user) VALUES (:id_tag, :id_user)');
	$add_assoc->execute(array('id_tag' => $id_t['id_tag'], 'id_user' => $id));

	$j++;

}

header("location: index.php");
?>