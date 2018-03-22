<?php
include('connexion.php');
session_start();

function blocked($user1, $user2, $bdd)
{
	$req = $bdd->prepare('SELECT id_user FROM blocked WHERE (id_user_blocked = ? AND id_user = ?) OR (id_user = ? AND id_user_blocked = ?)');
	$req->execute(array($user1, $user2, $user1, $user2));
	if ($req->rowCount() == 0)
		return FALSE;
	else
		return TRUE;
}


function sort_by($arr, $order)
{
	for ($i = 0; $i < count($arr); $i++)
	{
		for ($j = 0; $j < $i; $j++)
		{
			if ($arr[$i][$order] < $arr[$j][$order])
			{
				$tmp = $arr[$i];
				$arr[$i] = $arr[$j];
				$arr[$j] = $tmp;
			}
		}
	}
	return ($arr);
}

function distanceCalculation($point1_lat, $point1_long, $point2_lat, $point2_long, $unit = 'km', $decimals = 0) {
	$degrees = rad2deg(acos((sin(deg2rad($point1_lat))*sin(deg2rad($point2_lat))) + (cos(deg2rad($point1_lat))*cos(deg2rad($point2_lat))*cos(deg2rad($point1_long-$point2_long)))));
	switch($unit) {
		case 'km':
			$distance = $degrees * 111.13384;
			break;
		case 'mi':
			$distance = $degrees * 69.05482;
			break;
		case 'nmi':
			$distance =  $degrees * 59.97662;
	}
	return round($distance, $decimals);
}

function check_image($id){

	$dir = '../data/'.$id.'/1.';
	if (file_exists($dir."jpeg"))
		return TRUE;
	else if (file_exists($dir."jpg"))
		return TRUE;
	else if (file_exists($dir."gif"))
		return TRUE;
	else if (file_exists($dir."png"))
		return TRUE;
	else
		return FALSE;
}

$nbr_picture = htmlspecialchars($_POST['nbr_picture']);
if ($nbr_picture == 0) {
	$nbr_picture = 20;
}


$req = $bdd->prepare('SELECT * FROM users WHERE id_user=:id');
$req->bindParam(':id', $_SESSION['id'], PDO::PARAM_INT);
$req->execute();
$data_user = $req->fetch();

$orientation = $data_user['orientation'];
$latitude= $data_user['latitude'];
$longitude= $data_user['longitude'];
$sexe_users= $data_user['sexe'];

$tags = json_decode($_POST['tags']);

$age = explode(" ", $_POST['age']);
$age_min = intval($age[0]);
$age_max = intval($age[2]);

$location = explode(" ", $_POST['location']);
$location_min = $location[0];
$location_max = $location[2];

$popularity = explode(" ", $_POST['popularity']);
$popularity_min = $popularity[0];
$popularity_max = $popularity[2];

$users = [];
$req = $bdd->prepare('SELECT * FROM users WHERE age != "" AND bio != "" AND confirm = 1 AND sexe != 0 AND orientation != 0 AND ? & sexe = sexe AND orientation & ? = ? AND id_user != ?');

$req->execute(array($orientation, $sexe_users, $sexe_users, $_SESSION['id']));
while ($data = $req->fetch())
	$users[] = $data;

$tmp = [];
	
if (count($tags) > 0) {
	$i = 0;
	$sql = "SELECT * FROM tags INNER JOIN assoc ON tags.id_tag = assoc.id_tag AND assoc.id_user = ? AND tags.tag IN (";
	foreach ($tags as $tag)
	{
		if ($i > 0)
			$sql .= ", ";
		$sql .= "?";
		$i++;
	}
	$sql .= ")";

}

foreach ($users as $key => $profil)
{
	if ($profil['id_user'] == $_SESSION['id'])
		continue ;

	$km = distanceCalculation($latitude, $longitude, $profil['latitude'], $profil['longitude']);
	$profil['km'] = $km;

	$nbr_tags = 0;
		if (count($tags) > 0)
		{
			$req = $bdd->prepare($sql);
			$arr = $tags;
			array_unshift($arr, $profil['id_user']);
			$req->execute($arr);
			$nbr_tags = $req->rowCount();
		}


	$profil['tags'] = $nbr_tags;

	if (!check_image($profil['id_user']))
		continue ;
	if (blocked($_SESSION['id'], $profil['id_user'], $bdd))
			continue ;
	if (count($tags) != 0 && $nbr_tags == 0)
		continue ;
	if ($km < $location_min || $km > $location_max)
		continue ;
	if ($age_min > $profil['age'] || $age_max < $profil['age'])
		continue ;
	if ($popularity_min > $profil['score'] || $popularity_max < $profil['score'])
		continue ;
	$tmp[] = $profil;
}

////////// ALL
if ($_POST['type'] == 0){
	$users = sort_by($tmp, 'login');
}

////////// AGE
else if ($_POST['type'] == 1) {
	$users = sort_by($tmp, 'age');
}

////////// LOCATION
else if ($_POST['type'] == 2) {
	$users = sort_by($tmp, 'km');
}

////////// POPULARITY
else if ($_POST['type'] == 3) {
	$users = sort_by($tmp, 'score');
}

////////// TAGS
else if ($_POST['type'] == 4) {
	$users = sort_by($tmp, 'tags');
}

$index = 0;
foreach ($users as $key => $data) {

	if ($index >= $nbr_picture) {
		echo '<img src="assets/icon/more.svg" alt="more" class="more_users transition" onclick="filter()"/>';
		exit ;
	}

		$data['last_name'] = strtoupper($data['last_name']);
		
				$dir = '../data/'.$data['id_user'].'/*.{jpg,jpeg,gif,png}';
				$files = glob($dir,GLOB_BRACE);
				$y = 0;
				foreach($files as $image)
				{ 
				   $f= str_replace($repertoire,'',$image);
				   if (file_exists($f) && $y == 0) {
				   		$tmp = '<img class="img_profil" src="'.$f.'">';
				   		$y = 1;
				   }
				   
				}
	


				echo '	<div class="user_card">
							<div class="picture">
								'.$tmp.'
							</div>
							<hr>
							<p class="name">'.$data['first_name'].' '.$data['last_name'].'</p>
							<p class="age">'.$data['age'].' <span class="blue">Years<span></p>
							<p class="distance">'.$data['km'].' <span class="blue">Km<span></p>
							<p class="score">'.$data['score'].' <span class="blue">Score<span></p>
							<a onclick="go_profil(';
				echo "'";
				echo $data['login'];
				echo "'";
				echo ')"><button class="more">MORE</button></a></div>';
	
	$index++;
}

echo '<img src="assets/icon/more.svg" alt="more" class="more_users transition" onclick="filter()"/>';

?>