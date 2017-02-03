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

$websiteId = -1; // value "-1" means no websites exist
if (isset($_POST['websiteId'])) {
    // if we saved the id of the selected website -> restore it
    $websiteId = $_POST['websiteId'];
}


/* Begin: React to user actions -------------------------------*/
// Submit button with the name 'newWebsite' was clicked
if (isset($_POST['newWebsite'])) {
    $templates = $dbContent->SelectAllTemplates();
    if ($dbContent->GetResultCount($templates) < 1) {
        die("Es exististieren keine Templates");
    } else {
        // The first template in the array will be the default template
        $defaultTemplate = $dbContent->FetchArray($templates);

        $headerTitle = "Meine Webseite";
        $result = $dbContent->InsertWebsite($headerTitle, " ", " ", " ", " ", 0, 0, $defaultTemplate['id']);
        if (!$result) {
            die("Webseite konnte nicht erzeugt werden!");
        } else {
            // Get the id of the website we just created
            $websites = $dbContent->SelectAllWebsiteByHeadertitle($headerTitle);
            if ($dbContent->GetResultCount($websites) < 1) {
                die("Die Webseite konnte nicht gefunden werden!");
            }
            // Select the created website and let the user change the website´s options
            $websiteId = $dbContent->FetchArray($websites)['id'];
            EditWebsiteOptions();
        }

        return;
    }
}
// Submit button with the name 'newPage' was clicked
else if (isset($_POST['newPage'])) {
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
        $dbContent->InsertPage($newTitle, $relativePosition, $defaultTemplate['id'], $websiteId);
    }
}
// Submit button with the name 'options' was clicked
else if (isset($_POST['options'])) {
    EditWebsiteOptions();
    return;
}
// Submit button with the name 'saveWebsiteChanges' was clicked
else if (isset($_POST['saveWebsiteChanges'])) {
    $loginEnabled = isset($_POST['loginEnabled']) ? 1 : 0;
    $guestbookEnabled = isset($_POST['guestbookEnabled']) ? 1 : 0;
    $contactContent = $_POST['contactContent'];
    $imprintContent = $_POST['imprintContent'];
    $privacyInformationContent = $_POST['privacyInformationContent'];
    $gtcContent = $_POST['gtcContent'];

    $queryResult = $dbContent->SelectTemplateByTemplatename($_POST['technicalSiteTemplateId']);
    $templateId = $dbContent->FetchArray($queryResult)['id'];

    $dbContent->UpdateWebsiteById($websiteId, $_POST['headerTitle'], $contactContent, $imprintContent,
        $privacyInformationContent, $gtcContent, $loginEnabled, $guestbookEnabled,
        $templateId);
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
        $_POST['relativePosition'], $templateId, $websiteId);
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
// Submit button with the name 'setCurrentWebsite' was clicked
else if (isset($_POST['setCurrentWebsite'])) {
    $websiteId = intval($_POST['website']);
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
else if(!in_array(Permission::Pagemanagement, $_SESSION['permissions']))
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

    echo "<form method=\"post\" action=''>
            <input id=\"newWebsite\" name=\"newWebsite\" type=\"submit\" value=\"Neue Webseite\">
          </form><br><br>";

    $websites = $dbContent->SelectAllWebsite();
    $nrOfWebsites = $dbContent->GetResultCount($websites);
    if ($nrOfWebsites < 1) {
        echo "Es wurde noch keine Webseite erstellt!<br><br>";
    } else {
        /* -------------- let the user select the website -------------- */
        if ($websiteId < 1) {
            $websiteId = 1; // set the default selected websiteId if no website is currently
                            // selected
        }
        echo "<form method='post' action=''>
                <label for='website'>Webseite</label>";
        // fetch all existing websites
        $queryResult = $dbContent->SelectAllWebsite();
        echo "<select name='website' size='1'>";
        while ($website = $dbContent->FetchArray($queryResult)) {
            $option =  "<option ";
            if ($website['id'] == $websiteId) {
                $option .= "selected='selected' ";
            }
            $option .= "value='".$website['id']."'>";
            $option .= $website['headertitle']."</option>";
            echo $option;
        }
        echo "</select>
              <input id='setCurrentWebsite' name='setCurrentWebsite' type='submit' value='Auswählen'>
              </form>";

        /* -------------- Print Pages table -------------- */
        $pages = $dbContent->SelectAllPages();
        BackendComponentPrinter::PrintTableStart(array("Relative Position", "Seite", "Template", "Aktionen"));
        while ($page = $dbContent->FetchArray($pages)) {
            if ($page['website_id'] != $websiteId) {
                continue; // display only pages which belong to the currently selected website
            }
            $siteTitle = $page['title'];
            $queryResult = $dbContent->SelectTemplateById($page['template_id']);
            $siteTemplate = $dbContent->FetchArray($queryResult);
            $siteActions = "<form method='post' action=''>
                <input id='pageDetails' name='pageDetails' type='submit' value='Details'>
                <input id='deletePage' name='deletePage' type='submit' value='Löschen'>
                <input id='editContent' name='editContent' type='submit' value='Inhalte bearbeiten'>
                <input id='pageId' name='pageId' type='hidden' value='" . $page['id'] . "'>
                
                <input id='pageTitle' name='pageTitle' type='hidden' value='" . $page['title'] . "'>
                <input id='websiteId' name='websiteId' type='hidden' value='" . $websiteId . "'></form>";
            $siteRelativePosition = $page['relativeposition'];

            BackendComponentPrinter::PrintTableRow(array($siteRelativePosition, $siteTitle, $siteTemplate['templatename'], $siteActions));
        }
        BackendComponentPrinter::PrintTableEnd();
    }

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
        global $websiteId;

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
                <input id='websiteId' name='websiteId' type='hidden' value='".$websiteId."'> 
        
                <label for='pageTitle'>Seitentitel</label>
                <input id='pageTitle' name='pageTitle' value='".$page['title']."'>
                <br><br>
                <label for='relativePosition'>Relative Position</label>
                <input id='relativePosition' name='relativePosition' value='".$page['relativeposition']."'>
                <br><br>
                <label for='templateName'>Template</label>";
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
    }

    /**
     * Opens the page for the editing of the currently selected website details
     *
     */
    function EditWebsiteOptions()
    {
        BackendComponentPrinter::PrintHead("Seitenverwaltung", $jquery=true);
        //*----- Permissions ----- */
        /* Include(s) */
        require_once 'lib/Permission.enum.php';
        require_once 'config/config.php';

        BackendComponentPrinter::PrintSidebar($_SESSION['permissions']);
        //*----- Permissions End ----- */

        /* Datatables */
        BackendComponentPrinter::PrintDatatablesPlugin();

        /* Global variables */
        global $dbContent;
        global $websiteId;

        if ($websiteId < 1) {
            die("Es wurde keine gültige Website ausgewählt!");
        }
        $website = $dbContent->FetchArray($dbContent->SelectWebsiteById($websiteId));
        if (null == $website) {
            die("Es wurde noch keine Webseite erstellt!");
        }

        /* specific style because of summernote */
        echo
        "<script>
            $(document).ready(function() {
                $('ul li a').addClass('contentEditMenue');
            });
        </script>";

        /* Summernote */
        echo
        "<link href='http://netdna.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.css' rel='stylesheet'> 
        <script src='http://netdna.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.js'></script> 
        <link href='http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.2/summernote.css' rel='stylesheet'>
        <script src='http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.2/summernote.js'></script>";

        echo
        "<main>
            <h1><i class='fa fa-users fontawesome'></i> Webseiten-Einstellungen</h1>
                <form method='post' action='Pagemanagement.php'>";

        // let the user edit website details
        echo "<form method='post' action=''>
                <input id='websiteId' name='websiteId' type='hidden' value='".$websiteId."'>
                
                <label for='headerTitle'>Headertitel</label>
                <input id='headerTitle' name='headerTitle' value='".$website['headertitle']."'>
                <br><br>
                <label for='loginEnabled'>Login aktivieren</label>
                <input type='checkbox' id='loginEnabled' name='loginEnabled' value=''";
        if (boolval($website['login'])) {
            echo " checked";
        };
        echo "><br><br>
                <label for='guestbookEnabled'>Gästebuch aktivieren</label>
                <input type='checkbox' id='guestbookEnabled' name='guestbookEnabled' value=''";
        if (boolval($website['guestbook'])) {
            echo " checked";
        };
        echo "><br><br>
                <label for='summernoteImprint'>Impressum</label>
                 <div id='summernoteImprint' name='summernoteImprint'>".$website['imprint']."</div>
            <script>
                $(document).ready(function() {
                    $('#summernoteImprint').summernote({
                        callbacks: {
                            onChange: function(contents, \$editable) {
                                $('#imprintContent').val(contents);
                            }
                        }
                    });
                });
            </script>
            <input id='imprintContent' name='imprintContent' type='hidden'>
                <br><br>
                
                <label for='summernoteContact'>Kontakt-Formular</label>
                <div id='summernoteContact' name='summernoteContact'>".$website['contact']."</div>
            <script>
                $(document).ready(function() {
                    $('#summernoteContact').summernote({
                        callbacks: {
                            onChange: function(contents, \$editable) {
                                $('#contactContent').val(contents);
                            }
                        }
                    });
                });
            </script>
            <input id='contactContent' name='contactContent' type='hidden'>
                <br><br>
                
                <label for='summernotePrivacyInformation'>Datenschutz-Seite</label>
                <div id='summernotePrivacyInformation' name='summernotePrivacyInformation'>".$website['privacyinformation']."</div>
            <script>
                $(document).ready(function() {
                    $('#summernotePrivacyInformation').summernote({
                        callbacks: {
                            onChange: function(contents, \$editable) {
                                $('#privacyInformationContent').val(contents);
                            }
                        }
                    });
                });
            </script>
            <input id='privacyInformationContent' name='privacyInformationContent' type='hidden'>
                <br><br>
                
                <label for='summernoteGTC'>AGB-Seite</label>
                <div id='summernoteGTC' name='summernoteGTC'>".$website['gtc']."</div>
            <script>
                $(document).ready(function() {
                    $('#summernoteGTC').summernote({
                        callbacks: {
                            onChange: function(contents, \$editable) {
                                $('#gtcContent').val(contents);
                            }
                        }
                    });
                });
            </script>
            <input id='gtcContent' name='gtcContent' type='hidden'>
                <br><br>
                
                <label for='technicalSiteTemplateId'>Template für die Webseiten-Details-Seite</label>";
        // fetch all existing templates
        $queryResult = $dbContent->SelectAllTemplates();
        echo "<select name='technicalSiteTemplateId' size='1'>
              <option></option>";
        $templateAssigned = false;
        while ($template = $dbContent->FetchArray($queryResult)){
            if ($template['id'] == $website['template_id']) {
                echo "<option selected='selected'>".$template['templatename']."</option>";
                $templateAssigned = true;
            } else {
                echo "<option>".$template['templatename']."</option>";
            }
        }
        echo "</select>
             </label>";
        if (!$templateAssigned) {
            echo "<label>Der Webseite ist kein Template zugewiesen!</label>";
        }
                echo "<br><br>";

        echo "<br><br>
                <input id='saveWebsiteChanges' name='saveWebsiteChanges' type='submit' value='Änderungen speichern'>
                </form>";
        echo "<form method='post' action='Pagemanagement.php'><input id='back' name='back' type='submit' value='Zurück'><form>";

        // todo: ein-/ausblenden
        /*echo "<script>
        $(document).ready(function() {
           $('#summernoteImprint').hide();
           
           $('#imprintEnabled').click(function () {
               $('#summernoteImprint').fadeToggle();
           });
        });
        </script>";*/
    }
    ?>

    <?php
    global $websiteId;

    if ($websiteId > 0) {
        // display this form only if the current websiteId is valid
        echo "<form method='post' action=''>
            <input id='newPage' name='newPage' type='submit' value='Neue Seite'>
            <input id='options' name='options' type='submit' value='Optionen'>

            <input id='websiteId' name='websiteId' type='hidden' value='" . $websiteId . "'>
        </form>";
    }
?>

</main>
</body>

</html>
