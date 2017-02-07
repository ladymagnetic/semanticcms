<?php
// Start the session
session_start();

/* Include(s) */
require_once 'lib/BackendComponentPrinter.class.php';
require_once 'lib/DbEngine.class.php';
require_once 'lib/DbContent.class.php';
require_once 'lib/Permission.enum.php';
require_once 'config/config.php';
require_once 'lib/FrontendBuilder.class.php';

/* use namespace(s) */
use SemanticCms\ComponentPrinter\BackendComponentPrinter;
use SemanticCms\DatabaseAbstraction\DbEngine;
use SemanticCms\DatabaseAbstraction\DbContent;
use SemanticCms\Model\Permission;
use SemanticCms\FrontendGenerator\FrontendBuilder;

FrontendBuilder::Init();
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
    EditWebsiteOptions(true);
    return;
}
// Submit button with the name 'newPage' was clicked
else if (isset($_POST['newPage'])) {
    // Insert a new page
    $templates = $dbContent->SelectAllTemplates();
    if ($dbContent->GetResultCount($templates) < 1) {
        BackendComponentPrinter::PrintResultMessage("Es exististieren keine Templates", true);
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
        $result = $dbContent->InsertPage($newTitle, $relativePosition, $defaultTemplate['id'], $websiteId);
        if (!$result) {
            BackendComponentPrinter::PrintResultMessage("Seite konnte nicht erzeugt werden!", true);
        } else {
            FrontendBuilder::MakeNewPage($newTitle, $defaultTemplate['filelink'], $websiteId);
        }
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
    $contactContent = isset($_POST['contactEnabled']) ?
        $_POST['contactContent'] : "NULL";
    $imprintContent = isset($_POST['imprintEnabled']) ?
        $_POST['imprintContent'] : "NULL";
    $privacyInformationContent = isset($_POST['privacyInformationEnabled']) ?
        $_POST['privacyInformationContent'] : "NULL";
    $gtcContent = isset($_POST['gtcEnabled']) ?
        $_POST['gtcContent'] : "NULL";

    $queryResult = $dbContent->SelectTemplateByTemplatename($_POST['technicalSiteTemplateId']);
    $templateId = $dbContent->FetchArray($queryResult)['id'];

    if ($websiteId < 1) {
        // $websiteId is not valid -> we create a new website
        $result = $dbContent->InsertWebsite($_POST['headerTitle'], $contactContent, $imprintContent,
            $privacyInformationContent, $gtcContent, $loginEnabled, $guestbookEnabled,
            $templateId);
        if (!$result) {
            BackendComponentPrinter::PrintResultMessage("Webseite konnte nicht erzeugt werden!", true);
        } else {
            // get the id of the inserted website
            $queryResult = $dbContent->SelectAllWebsite();
            while ($website = $dbContent->FetchArray($queryResult)) {
                if ($website['headertitle'] == $_POST['headerTitle']) {
                    $websiteId = $website['id'];
                }
            }
            FrontendBuilder::MakeNewSite($websiteId);
        }
    } else {
        // $websiteId is valid -> we update an existing website
        $dbContent->UpdateWebsiteById($websiteId, $_POST['headerTitle'], $contactContent, $imprintContent,
            $privacyInformationContent, $gtcContent, $loginEnabled, $guestbookEnabled,
            $templateId);
        FrontendBuilder::UpdateSite($websiteId);
    }
}
// Submit button with the name 'siteDetails' was clicked
else if (isset($_POST['pageDetails'])) {
    EditPageDetails($_POST['pageId']);
    return;
}
// Submit button with the name 'savePageChanges' was clicked
else if (isset($_POST['savePageChanges'])) {
    $oldPage = $dbContent->FetchArray($dbContent->SelectPageById($_POST['pageId']));
    $queryResult = $dbContent->SelectTemplateById($oldPage['template_id']);
    $oldTemplatePath = $dbContent->FetchArray($queryResult)['filelink'];

    $queryResult = $dbContent->SelectTemplateByTemplatename($_POST['templateName']);
    $newTemplate = $dbContent->FetchArray($queryResult);

    $dbContent->UpdatePageById($_POST['pageId'], $_POST['pageTitle'],
        $_POST['relativePosition'], $newTemplate['id'], $websiteId);
    FrontendBuilder::UpdatePage($oldPage['title'], $oldTemplatePath, $_POST['pageTitle'],
        $newTemplate['filelink'], $oldPage['website_id'], $websiteId);
}
// Submit button with the name 'deletePage' was clicked
else if (isset($_POST['deletePage'])) {
    BackendComponentPrinter::AskIfReallyDelete('Seitenverwaltung', 'Pagemanagement.php',
        'pageId', $_POST['pageId']);
    return;
}
// Submit button with the name 'deleteWebsite' was clicked
else if (isset($_POST['deleteWebsite'])) {
    BackendComponentPrinter::AskIfReallyDelete('Seitenverwaltung', 'Pagemanagement.php',
        'website', $_POST['website']);
    return;
}
// User has confirmed the deletion of a page
else if (isset($_POST['reallyDelete'])) {
    if (isset($_POST['pageId'])) {
        // a page should be deleted
        $pageId = intval($_POST['pageId']);
        $page = $dbContent->FetchArray($dbContent->SelectPageById($pageId));
        $queryResult = $dbContent->SelectTemplateById($page['template_id']);
        $templatePath = $dbContent->FetchArray($queryResult)['filelink'];
        $dbContent->DeletePageById($pageId);
        FrontendBuilder::DeletePage($page['title'], $templatePath, $page['website_id']);
    } else if (isset($_POST['website'])) {
        // a website should be deleted
        $websiteIdToDelete = intval($_POST['website']);
        $dbContent->DeleteWebsiteById($websiteIdToDelete);
        FrontendBuilder::DeleteSite($websiteIdToDelete);
    }
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
                <label for='website'><strong>Webseite</strong></label>";
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
              <input id='deleteWebsite' name='deleteWebsite' type='submit' value='Löschen'>
              </form><br><br>";

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
     * @param boolean $websiteIsToBeCreated If true, we are going to create the website, which options we want to edit.
     */
    function EditWebsiteOptions($websiteIsToBeCreated = false)
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

        $contactEnabled = false;
        $imprintEnabled = false;
        $privacyInformationEnabled = false;
        $gtcEnabled = false;

        if ($websiteIsToBeCreated) {
            // set some default values
            $websiteId = -1;
            $headerTitle = "";
            $login = false;
            $guestbook = false;
            $imprint = "";
            $contact = "";
            $privacyInformation = "";
            $gtc = "";
            $templateId = 1;

        } else {
            if ($websiteId < 1) {
                BackendComponentPrinter::PrintResultMessage("Es wurde keine gültige Website ausgewählt!", true);
                return;
            }
            $website = $dbContent->FetchArray($dbContent->SelectWebsiteById($websiteId));
            if (null == $website) {
                BackendComponentPrinter::PrintResultMessage("Es wurde noch keine Webseite erstellt!", true);
                return;
            }

            // take the values from the selected website
            $headerTitle = $website['headertitle'];
            $login = $website['login'];
            $guestbook = $website['guestbook'];
            $imprint = $website['imprint'];
            if (!empty(trim($imprint)) && 'NULL' != trim($imprint)) {
                $imprintEnabled = true;
            } else {
                $imprint = "";
            }
            $contact = $website['contact'];
            if (!empty(trim($contact)) && 'NULL' != trim($contact)) {
                $contactEnabled = true;
            } else {
                $contact = "";
            }
            $privacyInformation = $website['privacyinformation'];
            if (!empty(trim($privacyInformation)) && 'NULL' != trim($privacyInformation)) {
                $privacyInformationEnabled = true;
            } else {
                $privacyInformation = "";
            }
            $gtc = $website['gtc'];
            if (!empty(trim($gtc)) && 'NULL' != trim($gtc)) {
                $gtcEnabled = true;
            } else {
                $gtc = "";
            }
            $templateId = $website['template_id'];
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
                <input id='headerTitle' name='headerTitle' value='".$headerTitle."' required style='width: 500px;'>
                <br><br>
                
                <label for='loginEnabled'>Login aktivieren</label>
                <input type='checkbox' id='loginEnabled' name='loginEnabled' value=''";
        if (boolval($login)) {
            echo " checked";
        };

        echo "><br><br>
                <label for='guestbookEnabled'>Gästebuch aktivieren</label>
                <input type='checkbox' id='guestbookEnabled' name='guestbookEnabled' value=''";
        if (boolval($guestbook)) {
            echo " checked";
        };

        echo "><br><br>
                <label for='imprintEnabled'>Impressum</label>
                <input type='checkbox' id='imprintEnabled' name='imprintEnabled' value=''";
        if ($imprintEnabled) {
            echo " checked";
        }
                echo ">
                <div id='summernoteImprint' name='summernoteImprint'>".$imprint."</div>
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
            <input id='imprintContent' name='imprintContent' type='hidden' value='".$imprint."'>
                <br><br>
                
                <label for='contactEnabled'>Kontakt-Formular</label>
                <input type='checkbox' id='contactEnabled' name='contactEnabled' value=''";
        if ($contactEnabled) {
            echo " checked";
        }
        echo ">
                <div id='summernoteContact' name='summernoteContact'>".$contact."</div>
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
            <input id='contactContent' name='contactContent' type='hidden' value='".$contact."'>
                <br><br>
                
                <label for='privacyInformationEnabled'>Datenschutz-Seite</label>
                <input type='checkbox' id='privacyInformationEnabled' name='privacyInformationEnabled' value=''";
        if ($privacyInformationEnabled) {
            echo " checked";
        }
        echo ">
                <div id='summernotePrivacyInformation' name='summernotePrivacyInformation'>".$privacyInformation."</div>
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
            <input id='privacyInformationContent' name='privacyInformationContent' type='hidden' value='".$privacyInformation."'>
                <br><br>
                
                <label for='gtcEnabled'>AGB-Seite</label>
                <input type='checkbox' id='gtcEnabled' name='gtcEnabled' value=''";
        if ($gtcEnabled) {
            echo " checked";
        }
        echo ">
                <div id='summernoteGTC' name='summernoteGTC'>".$gtc."</div>
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
            <input id='gtcContent' name='gtcContent' type='hidden' value='".$gtc."'>
                <br><br>
                
                <label for='technicalSiteTemplateId'>Template</label>";
        // fetch all existing templates
        $queryResult = $dbContent->SelectAllTemplates();
        echo "<select name='technicalSiteTemplateId' size='1'>";
        while ($template = $dbContent->FetchArray($queryResult)){
            if ($template['id'] == $templateId) {
                echo "<option selected='selected'>".$template['templatename']."</option>";
            } else {
                echo "<option>".$template['templatename']."</option>";
            }
        }
        echo "</select>
             </label>
             <br><br>";

        echo "<br><br>
                <input id='saveWebsiteChanges' name='saveWebsiteChanges' type='submit' value='Änderungen speichern'>
                <input id='back' name='back' type='submit' value='Zurück'><form>
                </form>";
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
