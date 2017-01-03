<?php
// Start the session
session_start();
?>
<!DOCTYPE html>
<html vocab="https://schema.org/" typeof="WebPage" lang="de">
<?php
/* Include(s) */
require_once 'lib/BackendComponentPrinter.class.php';

/* use namespace(s) */
use SemanticCms\ComponentPrinter\BackendComponentPrinter;

BackendComponentPrinter::PrintHead("Seitenverwaltung", $jquery=true);
?>
<body>
<?php
/* Include(s) */
require_once 'lib/DbEngine.class.php';
require_once 'lib/DbContent.class.php';
require_once 'lib/DbUser.class.php';
require_once 'lib/Permission.enum.php';
require_once 'config/config.php';

/* use namespace(s) */
use SemanticCms\DatabaseAbstraction\DbEngine;
use SemanticCms\DatabaseAbstraction\DbContent;
use SemanticCms\DatabaseAbstraction\DbUser;
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
/*  Check if user has the permission to see this page */
else if(!in_array(Permission::Pagemanagment, $_SESSION['permissions']))
{
    die($config['error']['permissionMissing']);
}

BackendComponentPrinter::PrintSidebar($_SESSION['permissions']);

/** Database related objects */
$db = new DbEngine($config['cms_db']['dbhost'],$config['cms_db']['dbuser'],$config['cms_db']['dbpass'],$config['cms_db']['database']);
$dbContent = new DbContent($config['cms_db']['dbhost'], $config['cms_db']['dbuser'], $config['cms_db']['dbpass'], $config['cms_db']['database']);
$dbUser = new DbUser($config['cms_db']['dbhost'], $config['cms_db']['dbuser'], $config['cms_db']['dbpass'], $config['cms_db']['database']);

/* Datatables */
BackendComponentPrinter::PrintDatatablesPlugin();
?>
<main>
    <h1><i class="fa fa-file-text fontawesome"></i> Seitenverwaltung</h1>

    <?php
    /* Begin: React to user actions -------------------------------*/
    // Submit button with the name 'newPage' was clicked
    if (isset($_POST['newPage'])) {
        // Insert a new page
        $templates = $dbUser->SelectAllTemplates();
        if ($dbUser->GetResultCount($templates) < 1) {
            die("Es exististieren keine Templates");
        } else {
            // The first template in the array will be the default template
            $defaultTemplate = $dbUser->FetchArray($templates);
            // Create a new title
            $newTitlePrefix = 'Neue Seite';
            $newTitleSuffix = 0;
            do {
                $newTitleSuffix++;
                $newTitle = $newTitlePrefix.$newTitleSuffix;
            } while($dbContent->PagetitleAlreadyExists($newTitle));

            $dbContent->InsertPage($newTitle, $defaultTemplate['id']);
        }
    }
    // Submit button with the name 'options' was clicked
    if (isset($_POST['options'])) {
        // noch keine Aufgabe
    }

    /* End: React to user actions -------------------------------*/

    /* Print Pages table */
    $pages = $dbContent->SelectAllPages();
    BackendComponentPrinter::PrintTableStart(array("Seite", "Template", "Aktionen", "Relative Position"));
    while ($page = $dbContent->FetchArray($pages))
    {
        $siteTitle = $page['title'];
        $queryResult = $dbContent->SelectTemplateById($page['template_id']);
        $siteTemplate = $dbContent->FetchArray($queryResult);
        $siteActions = "<form method='post'>
                <input id='editContent' name='editContent' type='button' value='Löschen'><input name='Button2' type='button' value='Inhalte bearbeiten'></form>";
        $siteRelativePosition = $page['relativeposition'];

        BackendComponentPrinter::PrintTableRow(array($siteTitle, $siteTemplate['templatename'], $siteActions, $siteRelativePosition));
    }
    BackendComponentPrinter::PrintTableEnd();
    ?>

    <form method="post" action=''>
        <input id="newPage" name="newPage" type="submit" value="Neue Seite">
        <input id="options" name="options" type="submit" value="Optionen">
    </form>
</main>
</body>

</html>
