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
// Submit button with the name 'newPage' was clicked
if (isset($_POST['newPage'])) {
    // Insert a new page
    $templates = $dbContent->SelectAllTemplates();
    if ($dbContent->GetResultCount($templates) < 1) {
        die("Es exististieren keine Templates");
    } else {
        // The first template in the array will be the default template
        $defaultTemplate = $dbContent->FetchArray($templates);
        // Create a new title
        $newTitlePrefix = 'Neue Seite';
        $newTitleSuffix = 0;
        do {
            $newTitleSuffix++;
            $newTitle = $newTitlePrefix.$newTitleSuffix;
        } while($dbContent->PagetitleAlreadyExists($newTitle));

        $relativePosition = $dbContent->GetHighestRelativeNumber()+1;
        $dbContent->InsertPage($newTitle, $relativePosition, $defaultTemplate['id']);
    }
}
// Submit button with the name 'options' was clicked
else if (isset($_POST['options'])) {
    // noch keine Aufgabe
}
// Submit button with the name 'siteDetails' was clicked
else if (isset($_POST['pageDetails'])) {
    EditPageDetails($_POST['pageId']);
    return;
}
// Submit button with the name 'savePageChanges' was clicked
else if (isset($_POST['savePageChanges'])) {
    $queryResult = $dbContent->SelectTemplateByTemplatename($_POST['templateName']);
    $templateId = $dbContent->FetchArray($queryResult)['id'];
    $dbContent->UpdatePageById($_POST['pageId'], $_POST['pageTitle'],
        $_POST['relativePosition'], $templateId);
}
// Submit button with the name 'deletePage' was clicked
else if (isset($_POST['deletePage'])) {
    BackendComponentPrinter::AskIfReallyDelete('Seitenverwaltung', 'Pagemanagement.php',
        'pageId', $_POST['pageId']);
    return;
}
// User has confirmed the deletion of a page
else if (isset($_POST['reallyDelete'])) {
    $pageId = intval($_POST['pageId']);
    $dbContent->DeletePageById($pageId);
}
// Submit button with the name 'editContent' was clicked
else if (isset($_POST['editContent'])) {
    $pageTitle = $_POST['pageTitle'];
    header("Location: Articlemanagement.php?pageName=$pageTitle");
    return;
}
/* End: React to user actions -------------------------------*/
?>

<!DOCTYPE html>
<html vocab="https://schema.org/" typeof="WebPage" lang="de">
<?php
BackendComponentPrinter::PrintHead("Seitenverwaltung", $jquery=true);
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
else if(!in_array(Permission::Pagemanagment, $_SESSION['permissions']))
{
    die($config['error']['permissionMissing']);
}
/* End: Permissions -------------------------------*/


BackendComponentPrinter::PrintSidebar($_SESSION['permissions']);

/* Datatables */
BackendComponentPrinter::PrintDatatablesPlugin();
?>
<main>
    <h1><i class="fa fa-file-text fontawesome"></i> Seitenverwaltung</h1>

    <?php


    /* Print Pages table */
    $pages = $dbContent->SelectAllPages();
    BackendComponentPrinter::PrintTableStart(array("Seite", "Template", "Aktionen", "Relative Position"));
    while ($page = $dbContent->FetchArray($pages))
    {
        $siteTitle = $page['title'];
        $queryResult = $dbContent->SelectTemplateById($page['template_id']);
        $siteTemplate = $dbContent->FetchArray($queryResult);
        $siteActions = "<form method='post' action=''>
                <input id='pageDetails' name='pageDetails' type='submit' value='Details'>
                <input id='deletePage' name='deletePage' type='submit' value='Löschen'>
                <input id='editContent' name='editContent' type='submit' value='Inhalte bearbeiten'>
                <input id='pageId' name='pageId' type='hidden' value='".$page['id']."'>
                <input id='pageTitle' name='pageTitle' type='hidden' value='".$page['title']."'></form>";
        $siteRelativePosition = $page['relativeposition'];

        BackendComponentPrinter::PrintTableRow(array($siteTitle, $siteTemplate['templatename'], $siteActions, $siteRelativePosition));
    }
    BackendComponentPrinter::PrintTableEnd();

    /**
     * Opens the page for the editing of the page details
     *
     */
    function EditPageDetails($pageId)
    {
        BackendComponentPrinter::PrintHead("Seitenverwaltung", $jquery=true);
        //*----- Permissions ----- */
        /* Include(s) */
        require_once 'lib/Permission.enum.php';
        require_once 'config/config.php';

        BackendComponentPrinter::PrintSidebar($_SESSION['permissions']);
        //*----- Permissions End ----- */

        /* Global variables */
        global $dbContent;

        /* Datatables */
        BackendComponentPrinter::PrintDatatablesPlugin();

        echo
        "<main>
            <h1><i class='fa fa-users fontawesome'></i> Seitendetails</h1>
                <form method='post' action='Pagemanagement.php'>";
        $page = $dbContent->FetchArray($dbContent->SelectPageById($pageId));

        // let the user edit page details
        echo "<form method='post' action=''>
                <input id='pageId' name='pageId' type='hidden' value='".$page['id']."'>
                
                <label for='pageTitle'>Seitentitel</label>
                <input id='pageTitle' name='pageTitle' value='".$page['title']."'>
                <br><br>
                <label for='relativePosition'>Relative Position</label>
                <input id='relativePosition' name='relativePosition' value='".$page['relativeposition']."'>
                <br><br>
                <label for='templateId'>Template</label>";
        // fetch all existing templates
        $queryResult = $dbContent->SelectAllTemplates();
        echo "<select name='templateName' size='1'>
              <option></option>";
        $templateAssigned = false;
        while ($template = $dbContent->FetchArray($queryResult)){
            if ($template['id'] == $page['template_id']) {
                echo "<option selected='selected'>".$template['templatename']."</option>";
                $templateAssigned = true;
            } else {
                echo "<option>".$template['templatename']."</option>";
            }
        }
        echo "</select>
             </label>";
        if (!$templateAssigned) {
            echo "<label>Der Seite ist kein Template zugewiesen!</label>";
        }
        echo "<br><br>
                <input id='savePageChanges' name='savePageChanges' type='submit' value='Änderungen speichern'>
                </form>";
        echo "<form method='post' action='Pagemanagement.php'><input id='back' name='back' type='submit' value='Zurück'><form>";

        echo "</main>
              </body>
              </html>";
    }
    ?>

    <form method="post" action=''>
        <input id="newPage" name="newPage" type="submit" value="Neue Seite">
        <input id="options" name="options" type="submit" value="Optionen">
    </form>
</main>
</body>

</html>
