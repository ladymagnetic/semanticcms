<?php
// Start the session
session_start();
?>
<!DOCTYPE html>
<html vocab="https://schema.org/" typeof="WebPage" lang="de">
	<?php
		require_once 'lib/BackendComponentPrinter.class.php';
		use SemanticCms\ComponentPrinter\BackendComponentPrinter;
		BackendComponentPrinter::PrintHead("Beispiel-Seite");
	?>
<body>
	<?php
		// Der BackendComponentPrinter ist bereits eingebunden
		/* Include(s) */
		require_once 'lib/Permission.enum.php';
		require_once 'config/config.php';

		/* use namespace(s) */
		use SemanticCms\Model\Permission;
		
		/* Check if user is logged in */
		if(!isset($_SESSION['username'])) 
		{
			die($config['error']['noLogin']);  
		}
		/* Check if  permissions are set */
		else if(!isset($_SESSION['permissions']))
		{
			die($config['error']['permissionNotSet']);  		
		}
		/*  Check if user has the permission the see this page */
		// Nicht vergessen nach dem kopieren die wirklich benötigte permission abzufragen!!
		else if(!in_array(Permission::Usermanagement, $_SESSION['permissions']))
		{
			die($config['error']['permissionMissing']);  	  
		}

		// Printer Beispiel									
		BackendComponentPrinter::PrintSidebar($_SESSION['permissions']);
	?>
	<!-- Main -->
	<section id="main">	<!-- evtl. besser <main> verwenden -> am treffen reden -->
		<div> 
		<p> HTML - Code möglich </p>
		</div>

		<?php
		/* Include(s) */
		require_once 'lib/DbUser.class.php';

		/* use namespace(s) */
		use SemanticCms\config;
		use SemanticCms\DatabaseAbstraction\DbUser;

		$db = new DbUser($config['cms_db']['dbhost'],$config['cms_db']['dbuser'],$config['cms_db']['dbpass'],$config['cms_db']['database']);

		echo "<br><br> Inhalt einer Variablen (in diesem Fall db) ausgeben <br><br>";
		var_dump($db);
		echo "<br>";
		
		// BEISPIEL ZU GET UND POST
		
		if($_SERVER['REQUEST_METHOD']=='POST')
		{
			// POST wurde verwendet
		}
		else { /* müsste dann get sein */}
		
		// ALTERNATIVE
		if($_SERVER['REQUEST_METHOD']=='GET')
		{
			// get wurde verwendet
		}
		else { /* müsste dann post sein */}
	
		?>
	</section>
</body>
</html>