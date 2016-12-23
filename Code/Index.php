<?php
// Start the session
session_start();
session_regenerate_id();
?>
<!DOCTYPE html>
<html vocab="https://schema.org/" typeof="WebPage" lang="de">
	<?php
		require_once 'lib/BackendComponentPrinter.class.php';
		use SemanticCms\ComponentPrinter\BackendComponentPrinter;
		BackendComponentPrinter::PrintHead("Login");
	?>
	<body>
	<main id="login">
	<h1>Login</h1>
	<form method="post" action="Index.php">
		<input id="username" name="username" type="text">
		<input id="password" name="password" type="password">
		<button id="ok" name="action" value="login"> OK </button>
		<button id="forgotPassword" name="action" value="forgotPassword"> Passwort vergessen</button>
	</form>
	</main>

	<?php
	/* Include(s) */
	require_once 'lib/DbUser.class.php';
	require_once 'config/config.php';

	/* use namespace(s) */
	use SemanticCms\DatabaseAbstraction\DbUser;
	use SemanticCms\config;
	
	if($_SERVER['REQUEST_METHOD']=='POST')
	{
		// Login-Versuch
		if($_POST['action'] == "login")
		{
			$database = new DbUser($config['cms_db']['dbhost'],$config['cms_db']['dbuser'],$config['cms_db']['dbpass'],$config['cms_db']['database']);
			
			// Pruefung mit if(isset($_POST['...'])) einbauen (ist username/password tatsächlich gesetzt)
			
			$nameInput =  $_POST["username"];
			$password = $_POST["password"];
			
			// an Passwort Hash Denken
			
			$login = $database->LoginUser($nameInput, $password);	
						
			if($login)
			{
				// set username and permissions
				$_SESSION['username'] = $nameInput;
				//$_SESSION['permissions'] = $database->GetUserPermissions($nameInput); // noch zu implementieren
				
				// Seitenweiterleitung
				header("Location: start.php");
			}
		}
		// else if (/* Abfrage für password vergessen*/)
		// {}
		// else { /* Fehler */}
	}
	else 
	{	/* GET - falls hier was gemacht werden soll */	}
	?>
	</body>
</html>