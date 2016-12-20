<!DOCTYPE html>
<html>
	<!-- fuer head wird es wahrscheinlich ebenfalls eine Methode geben: printHead(titel?), diese dann ggf. nutzen -->
	<head>
		<meta content="de" http-equiv="Content-Language">
		<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
		<title>Login</title>
		<link rel="stylesheet" href="css/backend.css">
		<link rel="stylesheet" href="css/font-awesome/css/font-awesome.min.css">
	</head>

	<body>
	<section id="login">
	<h1>Login</h1>
	<form method="post" action="Login.php">
		<input id="username" name="username" type="text">
		<input id="password" name="password" type="password">
		<button id="ok" name="action" value="ok"> OK </button>
		<button id="forgotPassword" name="action" value="forgotPassword"> Passwort vergessen</button>
	</form>
	</section>

	<?php
	/* Include(s) */
	require_once 'lib/DbUser.class.php';
	require_once 'config/config.php';
	
	/* use namespace(s) */
	use SemanticCms\DatabaseAbstraction\DbUser;
	use SemanticCms\config;
	
	// eigentlich das hier
	// $database = new DbUser($config['cms_db']['dbhost'],$config['cms_db']['dbuser'],$config['cms_db']['dbpass'],$config['cms_db']['database']);
	$database = new DbUser($config['cms_db']['dbhost'],$config['cms_db']['dbuser'],$config['cms_db']['dbpass'],'cms-projekt_fiktive_testdaten');
	// hier Logik für logIn rein (Funktionsaufruf LoginUser.php)
	// an Passwort Hash Denken
	  $nameInput =  $_POST["username"];
	  $password = $_POST["password"];
	  $database->LoginUser($nameInput, $password);
	  
				// Seitenweiterleitung bei erfolgreichem Login
	?>
	</body>
</html>