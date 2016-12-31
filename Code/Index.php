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
	<?php
		BackendComponentPrinter::PrintSidebar(array());
	?>
	<main id="login">
	<h1>Login</h1>
	
	<form method="post" action="Index.php">
		<input id="username" name="username" type="text">
		<input id="password" name="password" type="password">
		<button id="ok" name="action" value="login"> OK </button>
		<button id="forgotPassword" name="action" value="forgotPassword"> Passwort vergessen</button>
	</form>

	<?php
	/* Include(s) */
	require_once 'lib/DbUser.class.php';
	require_once 'lib/Permission.enum.php';
	require_once 'config/config.php';

	/* use namespace(s) */
	use SemanticCms\DatabaseAbstraction\DbUser;
	use SemanticCms\Model\Permission;
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
				$_SESSION['username'] = $nameInput;	// Aufpassen wg. E-Mail
				
				$result = $database->GetUserPermissionByUsername($nameInput);
				$permissions = $database->FetchArray($result);
							
				$_SESSION['permissions'] = array();
				
				if($permissions["guestbookmanagement"] == 1)	array_push($_SESSION['permissions'],Permission::Guestbookmanagment);
				if($permissions["usermanagement"] == 1)			array_push($_SESSION['permissions'],Permission::Usermanagment);
				if($permissions["pagemanagement"] == 1) 		array_push($_SESSION['permissions'],Permission::Pagemanagment);
				if($permissions["articlemanagement"] == 1) 		array_push($_SESSION['permissions'],Permission::Articlemanagment);
				if($permissions["templateconstruction"] == 1) 	array_push($_SESSION['permissions'],Permission::Templateconstruction);
				if($permissions["guestbookusage"] == 1) 		array_push($_SESSION['permissions'],Permission::Guestbookusage);
						
				// Seitenweiterleitung
				header("Location: start.php");
			}
			else
			{
				echo "<p> Username und/oder Passwort sind falsch. Bitte versuchen Sie es erneut </p>";
			}
		}
		// else if (/* Abfrage für password vergessen*/)
		// {}
		// else { /* Fehler */}
	}
	else 
	{	/* GET - falls hier was gemacht werden soll */	}
	?>
	</main>
	</body>
</html>