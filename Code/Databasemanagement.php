<?php
// Start the session
session_start();

/* Include(s) */
require_once 'lib/BackendComponentPrinter.class.php';
require_once 'lib/DbEngine.class.php';
require_once 'lib/DbContent.class.php';
require_once 'lib/Permission.enum.php';
require_once 'config/config.php';

/* use namespace(s) */
use SemanticCms\ComponentPrinter\BackendComponentPrinter;
use SemanticCms\DatabaseAbstraction\DbEngine;
use SemanticCms\DatabaseAbstraction\DbContent;
use SemanticCms\Model\Permission;

/** Database related objects */
$db = new DbEngine($config['cms_db']['dbhost'],$config['cms_db']['dbuser'],$config['cms_db']['dbpass'],$config['cms_db']['database']);
$dbContent = new DbContent($config['cms_db']['dbhost'], $config['cms_db']['dbuser'], $config['cms_db']['dbpass'], $config['cms_db']['database']);

/* Begin: React to user actions -------------------------------*/
// Submit button with the name 'exportDatabase' was clicked
if (isset($_POST['exportDatabase'])) {
    $db->DownloadDB($config['cms_db_export']['dbhost'],$config['cms_db_export']['dbuser'],$config['cms_db_export']['dbpass'],$config['cms_db_export']['database']);
}
// Submit button with the name 'importDatabase' was clicked
else if (isset($_POST['importDatabase'])) {
    $db->UploadDB($config['cms_db_export']['dbhost'],$config['cms_db_export']['dbuser'],$config['cms_db_export']['dbpass'],$config['cms_db_export']['database']);
}
/* End: React to user actions -------------------------------*/
?>

<!DOCTYPE html>
<html vocab="https://schema.org/" typeof="WebPage" lang="de">
<?php
BackendComponentPrinter::PrintHead("Datenbankverwaltung", $jquery=true);
?>

<body>
<?php
/* Begin: Permissions -------------------------------*/
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
else if(!in_array(Permission::Databasemanagement, $_SESSION['permissions']))
{
    die($config['error']['permissionMissing']);
}
/* End: Permissions -------------------------------*/

BackendComponentPrinter::PrintSidebar($_SESSION['permissions']);

/* Datatables */
BackendComponentPrinter::PrintDatatablesPlugin();
?>
<main>
    <h1><i class="fa fa-file-text fontawesome"></i> Datenbankverwaltung</h1>

    <?php
    ?>

    <form method="post" action=''>
        <button id="exportDatabase" name="exportDatabase" type='submit' value="exportDatabase"> Datenbank exportieren </button>
        <button id="importDatabase" name="importDatabase" type='submit' value="importDatabase"> Datenbank importieren </button>
    </form>
</main>
</body>

</html>
