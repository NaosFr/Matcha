<?php
include 'database.php';
try
{
	$bdd = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
	$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$bdd->exec("SET NAMES 'UTF8'");
	$bdd->query("DROP DATABASE IF EXISTS matcha");
	$bdd->query("CREATE DATABASE matcha");
	$bdd->query("use matcha");



	$bdd->query('SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";');

	$bdd->query('SET time_zone = "+00:00";');

	$bdd->query('CREATE TABLE `assoc` (
  `id_assoc` int(11) NOT NULL,
  `id_tag` int(11) NOT NULL,
  `id_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;');

	$bdd->query('CREATE TABLE `blocked` (
  `id_blocked` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_user_blocked` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;');
	
	$bdd->query('CREATE TABLE `liked` (
  `id_liked` int(11) NOT NULL,
  `id_who` int(11) NOT NULL,
  `id_user_target` int(11) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;');

	$bdd->query('CREATE TABLE `message` (
  `id_msg` int(11) NOT NULL,
  `txt` text NOT NULL,
  `date` date NOT NULL,
  `id_user_send` int(11) NOT NULL,
  `id_user_reiceve` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;');

	$bdd->query('CREATE TABLE `notif` (
  `id_notif` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `id_user_notified` int(11) DEFAULT NULL,
  `txt` text,
  `date` date DEFAULT NULL,
  `seen` int(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;');

	$bdd->query('CREATE TABLE `reported` (
  `id_reported` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_user_report` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;');

	$bdd->query('CREATE TABLE `tags` (
  `id_tag` int(11) NOT NULL,
  `tag` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;');

	$bdd->query('CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `login` varchar(255) DEFAULT NULL,
  `passwd` varchar(300) DEFAULT NULL,
  `last_name` text,
  `first_name` text,
  `bio` text,
  `sexe` int(4) DEFAULT 0,
  `orientation` int(4) DEFAULT 0,
  `age` int(4) DEFAULT 0,
  `score` int(11) NOT NULL DEFAULT 0,
  `latitude` decimal(9,6) DEFAULT 0.000000,
  `longitude` decimal(9,6) NOT NULL DEFAULT 0.000000,
  `confirm` int(11) DEFAULT NULL,
  `cle` text NOT NULL,
  `cle_passwd` text,
  `last_log` int(11) DEFAULT NULL,
  `notif` int(4) NOT NULL DEFAULT 1,
  `ip` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;');

	$bdd->query('INSERT INTO `users` (`id_user`, `email`, `login`, `passwd`, `last_name`, `first_name`, `bio`, `sexe`, `orientation`, `age`, `score`, `latitude`, `longitude`, `confirm`, `cle`, `cle_passwd`, `last_log`, `notif`, `ip`) VALUES
(1, "ncella98@gmail.com", "ncella", "8d1e214d80c712762ba521bd6a097571a31f822bf63ffd8c1cbafb8ec3e85858fcca65679b7f9f90439bac34fe0b02f7f459465220632671fe3e1a2d6999e9ff", "CELLA", "Nicolas", "Lorem ipsum dolor sit amet, condsectetur adipisicing elit. Odit esse itaque, quaerat doloremque, eligendi eos commodi molestiae? Dicta ipsam recusswandae m4r4olestias sint eius sapiente blanditiis impedit, nobis itaque, eum sunt!s", 1, 2, 20, 148, "48.886900", "2.320400", 1, "fc8aa1cf51c8387e6e91734e00ac0282", "15f52cc92a62078d514a6723ab5a2a7e", 1521033782, 0, "62.210.32.152");');

	$bdd->query('CREATE TABLE `visited` (
  `id_visited` int(11) NOT NULL,
  `id_who` int(11) NOT NULL,
  `id_user_target` int(11) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;');

	$bdd->query('ALTER TABLE `assoc`
  ADD PRIMARY KEY (`id_assoc`);');

	$bdd->query('ALTER TABLE `blocked`
  ADD PRIMARY KEY (`id_blocked`);');

	$bdd->query('ALTER TABLE `liked`
  ADD PRIMARY KEY (`id_liked`);');

	$bdd->query('ALTER TABLE `message`
  ADD PRIMARY KEY (`id_msg`);');

	$bdd->query('ALTER TABLE `notif`
  ADD PRIMARY KEY (`id_notif`);');

	$bdd->query('ALTER TABLE `reported`
  ADD PRIMARY KEY (`id_reported`);');

	$bdd->query('ALTER TABLE `tags`
  ADD PRIMARY KEY (`id_tag`);');

	$bdd->query('ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`);');

	$bdd->query('ALTER TABLE `visited`
  ADD PRIMARY KEY (`id_visited`);');

	$bdd->query('ALTER TABLE `assoc`
  MODIFY `id_assoc` int(11) NOT NULL AUTO_INCREMENT;');

	$bdd->query('ALTER TABLE `blocked`
  MODIFY `id_blocked` int(11) NOT NULL AUTO_INCREMENT;');

	$bdd->query('ALTER TABLE `liked`
  MODIFY `id_liked` int(11) NOT NULL AUTO_INCREMENT;');

	$bdd->query('ALTER TABLE `message`
  MODIFY `id_msg` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=133;');

	$bdd->query('ALTER TABLE `notif`
  MODIFY `id_notif` int(11) NOT NULL AUTO_INCREMENT;');

	$bdd->query('ALTER TABLE `reported`
  MODIFY `id_reported` int(11) NOT NULL AUTO_INCREMENT;');

	$bdd->query('ALTER TABLE `tags`
  MODIFY `id_tag` int(11) NOT NULL AUTO_INCREMENT;');

	$bdd->query('ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;');

	$bdd->query('ALTER TABLE `visited`
  MODIFY `id_visited` int(11) NOT NULL AUTO_INCREMENT;');

	session_start();

	unset($_SESSION['id']);
	unset($_SESSION['login']);
	echo '<script>document.location.href="../feed.php";</script>';
	exit();
}
catch (Exception $e)
{
        die('Erreur : ' . $e->getMessage());
        echo '<script>document.location.href="error.php";</script>';
}
?>