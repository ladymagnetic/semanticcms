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
		<input id="username" name="username" type="text" required="true" placeholder="Username">
		<input id="password" name="password" type="password" required="true" placeholder="Passwort">
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
					
			if(!isset($_POST['username']) || !isset($_POST['password'])) { die("<p> Ein Fehler ist aufgetreten. Bitte versuchen Sie es erneut. </p>"); }
		
			$nameInput =  $_POST["username"];
			$password = $_POST["password"];
			
		/*	$salt = mcrypt_create_iv(60, MCRYPT_RAND );	
			$hash = password_hash($password, PASSWORD_BCRYPT, array('salt' => $salt, 'cost' => 12));
			//var_dump(password_verify($password, $hash));
			
			//$hash = hash('sha512', $password); */
			
			$login = $database->LoginUser($nameInput, $password);	
						
			if($login)
			{
				// set username and permissions
				if (filter_var($nameInput, FILTER_VALIDATE_EMAIL)) 
				{ $_SESSION['username'] = $database->FetchArray($database->SelectUserByEmail($nameInput))['username']; }
				else 
				{ $_SESSION['username'] = $nameInput; }

				$result = $database->GetUserPermissionByUsername($_SESSION['username']);
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
				echo "<p> Username und/oder Passwort sind falsch. Bitte versuchen Sie es erneut. </p>";
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