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
	<?php
	/* Include(s) */
	require_once 'lib/BackendComponentPrinter.class.php';
	require_once 'lib/Permission.enum.php';

	/* use namespace(s) */
	use SemanticCms\ComponentPrinter\BackendComponentPrinter;
	use SemanticCms\Model\Permission;

	// Printer Beispiel
	// Userdaten bekommt man evtl. aus einer PHP Session http://www.w3schools.com/php/php_sessions.asp
	// TRELLO-KARTE BEACHTEN!!!! Da steht dann wie wir es genau machen wollen
	// Setzen: z.B. $_SESSION["permissions"] = Array(Permission::Guestbookusage,Permission::Articlemanagment, Permission::Pagemanagment, Permission::Usermanagment);
	// Auslesen: z.B. printSidebar($_SESSION["permissions"])											
	BackendComponentPrinter::printSidebar(Array(Permission::Guestbookusage,
												Permission::Articlemanagment, 
												Permission::Pagemanagment, 
												Permission::Usermanagment));
	?>

	<div> 
	<p> Hier könnte noch anderer HTML Code stehen </p>
	</div>

	<?php
	/* Include(s) */
	require_once 'lib/DbUser.class.php';
	require_once 'config/config.php';

	/* use namespace(s) */
	use SemanticCms\config;
	use SemanticCms\DatabaseAbstraction\DbUser;


	$db = new DbUser($config['cms_db']['dbhost'],$config['cms_db']['dbuser'],$config['cms_db']['dbpass'],$config['cms_db']['database']);

	echo "<br><br> var_dump db <br><br>";
	var_dump($db);
	echo "<br>";

	echo "IT WORKS";
	?>

</body>
</html>