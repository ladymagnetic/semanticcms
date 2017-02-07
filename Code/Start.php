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


		if (isset($_POST['exportDatabase'])) {
			//Funktion aufrufen für Datenbank exportieren
		}
	?>
<main>
    <h1><i class='fa fa-home fontawesome'></i>Startseite</h1>
		<h4>Letzte Änderungen</h4>
		<?php
		BackendComponentPrinter::PrintDatatablesPlugin();
    BackendComponentPrinter::PrintTableStart(array("Logdatum", "Benutzername", "Rolle", "Beschreibung"));

		$changes = $dbUser->SelectAllLogs();
		$i=0;
		while ($row = $dbUser->FetchArray($changes)) {

			if($i<15)
			{
				$tableRow1 = $row['logdate'];
				$tableRow2 = $row['username'];
				$tableRow3 = $row['rolename'];
				$tableRow4 = $row['description'];

				BackendComponentPrinter::PrintTableRow(array($tableRow1, $tableRow2, $tableRow3, $tableRow4));
			}
			$i++;

		}

		BackendComponentPrinter::PrintTableEnd();

		echo "<br><br>";

		BackendComponentPrinter::PrintTableStart(array("Beschreibung", "Anzahl"));
		$allUser = $dbUser->CountUsers();
		BackendComponentPrinter::PrintTableRow(array("Benutzer", $allUser));
		$allRoles = $dbUser->CountRoles();
		BackendComponentPrinter::PrintTableRow(array("Rollen", $allRoles));
		$allArticle = $dbUser->CountArticles();
		BackendComponentPrinter::PrintTableRow(array("Artikel", $allArticle));
		$allPages = $dbUser->CountPages();
		BackendComponentPrinter::PrintTableRow(array("Seiten", $allPages));
		$allTemplates = $dbUser->CountTemplates();
		BackendComponentPrinter::PrintTableRow(array("Templates", $allTemplates));
		BackendComponentPrinter::PrintTableEnd();

		echo "<br><br><br>";

	?>


</main>
</body>

</html>
