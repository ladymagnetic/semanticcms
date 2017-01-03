<?php
// Start the session
session_start();
?>
<!DOCTYPE html>
<html vocab="https://schema.org/" typeof="WebPage" lang="de">
	<?php


		/* Include(s) */
		require_once 'lib/DbEngine.class.php';
		require_once 'lib/BackendComponentPrinter.class.php';
		require_once 'config/config.php';
		require_once 'lib/DbUser.class.php';
		require_once 'lib/Permission.enum.php';

		/* use namespace(s) */
		use SemanticCms\config;
		use SemanticCms\DatabaseAbstraction\DbEngine;
		use SemanticCms\ComponentPrinter\BackendComponentPrinter;
		use SemanticCms\DatabaseAbstraction\DbUser;
		use SemanticCms\Model\Permission;

		BackendComponentPrinter::PrintHead("Startseite");

		$dbUser = new DbUser($config['cms_db']['dbhost'], $config['cms_db']['dbuser'], $config['cms_db']['dbpass'], $config['cms_db']['database']);
	?>
<body>
	<?php


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

		// no special permissions required for startpage beside login
		BackendComponentPrinter::PrintSidebar($_SESSION['permissions']);
	?>
<main>
    <h1>Startseite</h1>
		<h4>Letzte Änderungen</h4>
		<?php
    BackendComponentPrinter::PrintTableStart(array("Logdatum", "Benutzername", "Rolle", "Beschreibung"));

		$changes = $dbUser->SelectAllLogs();

		while ($row = $dbUser->FetchArray($changes)) {
			$tableRow1 = $row['logdate'];
			$tableRow2 = $row['username'];
			$tableRow3 = $row['rolename'];
			$tableRow4 = $row['description'];

			BackendComponentPrinter::PrintTableRow(array($tableRow1, $tableRow2, $tableRow3, $tableRow4));
		}

		BackendComponentPrinter::PrintTableEnd();


		BackendComponentPrinter::PrintTableStart(array("Anzahl der Benutzer"));
		$allUser = $dbUser->CountUsers();
		BackendComponentPrinter::PrintTableRow(array($allUser));
		BackendComponentPrinter::PrintTableEnd();

		BackendComponentPrinter::PrintTableStart(array("Anzahl der Rollen"));
		$allRoles = $dbUser->CountRoles();
		BackendComponentPrinter::PrintTableRow(array($allRoles));
		BackendComponentPrinter::PrintTableEnd();

		BackendComponentPrinter::PrintTableStart(array("Anzahl der Artikel"));
		$allArticle = $dbUser->CountArticles();
		BackendComponentPrinter::PrintTableRow(array($allArticle));
		BackendComponentPrinter::PrintTableEnd();

		BackendComponentPrinter::PrintTableStart(array("Anzahl der Seiten"));
		$allPages = $dbUser->CountPages();
		BackendComponentPrinter::PrintTableRow(array($allPages));
		BackendComponentPrinter::PrintTableEnd();

		BackendComponentPrinter::PrintTableStart(array("Anzahl der Templates"));
		$allTemplates = $dbUser->CountTemplates();
		BackendComponentPrinter::PrintTableRow(array($allTemplates));
		BackendComponentPrinter::PrintTableEnd();
		?>
    <form method="post" action="Start.php">
		<button id="exportDatabase" name="action" value="exportDatabase"> Datenbank exportieren </button>
		<button id="importDatabase" name="action" value="importDatabase"> Datenbank importieren </button>
    </form>
	<?php
	// Form Handling nach POST
	if($_SERVER['REQUEST_METHOD']=='POST')
	{
	}
	?>
</main>
</body>

</html>
