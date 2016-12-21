<!DOCTYPE html>
<html>
	<!-- Head -->
	<?php
		/* Include(s) */
		require_once 'lib/BackendComponentPrinter.class.php';
		
		/* use namespace(s) */
		use SemanticCms\ComponentPrinter\BackendComponentPrinter;
		
		BackendComponentPrinter::printHead("Beispiel-Seite");
	?>
<body>
	<div> 
	<p> Hier kann irgendein anderer HTML Code stehen </p>
	</div>

	<!-- Sidebar -->
	<?php
	
	// Der BackendComponentPrinter ist bereits eingebunden
	
	/* Include(s) */
	require_once 'lib/Permission.enum.php';

	/* use namespace(s) */
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
	<p> Hier könnte auch anderer HTML Code stehen </p>
	</div>

	<?php
	/* Include(s) */
	require_once 'lib/DbUser.class.php';
	require_once 'config/config.php';

	/* use namespace(s) */
	use SemanticCms\config;
	use SemanticCms\DatabaseAbstraction\DbUser;


	$db = new DbUser($config['cms_db']['dbhost'],$config['cms_db']['dbuser'],$config['cms_db']['dbpass'],$config['cms_db']['database']);

	echo "<br><br> Inhalt einer Variablen (in diesem Fall db) ausgeben <br><br>";
	var_dump($db);
	echo "<br>";
	?>

</body>
</html>