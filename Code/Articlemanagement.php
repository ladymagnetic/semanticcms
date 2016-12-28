<?php
// Start the session
session_start();
/* Include(s) */
require_once 'lib/DbEngine.class.php';
require_once 'lib/BackendComponentPrinter.class.php';
require_once 'config/config.php';
require_once 'lib/dbContent.class.php';
require_once 'lib/Permission.enum.php';

/* use namespace(s) */
use SemanticCms\config;
use SemanticCms\DatabaseAbstraction\DbEngine;
use SemanticCms\ComponentPrinter\BackendComponentPrinter;
use SemanticCms\DatabaseAbstraction\dbContent;
use SemanticCms\Model\Permission;

$db = new DbEngine($config['cms_db']['dbhost'],$config['cms_db']['dbuser'],$config['cms_db']['dbpass'],$config['cms_db']['database']);
$dbContent = new DbContent($config['cms_db']['dbhost'], $config['cms_db']['dbuser'], $config['cms_db']['dbpass'], $config['cms_db']['database']);

/*---- Submit Buttons ----*/
// if submit button with name 'selectPage' is pressed
if (isset($_POST['selectPage'])) {
    $pageId = intval($dbContent->FetchArray($dbContent->SelectPageByPagename($_POST['pageName']))['id']);
    CreateArticleManagement($pageId, $dbContent);
    // has to return because other page
    return;
}
// if submit button with name 'edit' is pressed
else if (isset($_POST['edit'])) {
    $articleId = intval($_POST['articleId']);
    EditArticle($pageId, $articleId, $dbContent);
    // has to return because other page
    return;
}
// if submit button with name 'delete' is pressed
else if (isset($_POST['delete'])) {
    $articleId = intval($_POST['articleId']);
    $dbContent->DeleteArticleById($articleId);
}
// if submit button with name 'newContent' is pressed
else if (isset($_POST['newArticle'])) {
    $pageId = intval($_POST['pageId']);
    if ($pageId != "")
    {
        CreateNewArticle($pageId, $dbContent);
        // has to return because other page
        return;
    }
}
// if submit button with name 'applyChanges' is pressed
else if (isset($_POST['publish']))
{
    $pageId = intval($_POST['pageId']);
    $header = intval($_POST['header']);
    $content = $_POST['summernote'];
    $date = $_POST['date'];
    if ($_POST['date'] == "")
    {
        $date = date("Y-m-d M");
    } 
    $type = $_POST['type'];
    if (isset($_POST['public']))
    {
        $public = 1;
    }
    else 
    {
        $public = 0;
    }
    $description = $_POST['description'];

    $dbContent->InsertNewArticleToPage($pageId, $header, $content, $date, $type, $public, $description);
}
// if submit button with name 'applyChanges' is pressed
else if (isset($_POST['updateArticle']))
{
    $pageId = intval($_POST['pageId']);
    $header = $_POST['header'];
    $content = $_POST['summernote'];
    $date = $_POST['date'];
    if ($_POST['date'] == "")
    {
        $date = date("Y-m-d M");
    } 
    $type = $_POST['type'];
    if (isset($_POST['public']))
    {
        $public = 1;
    }
    else 
    {
        $public = 0;
    }
    $description = $_POST['description'];
    $articleId = intval($_POST['articleId']);

    $dbContent->UpdateArticleToPage($articleId, $pageId, $header, $content, $date, $type, $public, $description);
}

CreateArticleManagement("", $dbContent);

function CreateArticleManagement($pageId, $dbContent)
{
    BackendComponentPrinter::PrintHead("Inhaltsverwaltung", $jquery=true);
    /* menue */
    /* dynamisch erzeugt je nach Rechten */
    /* Check if user is logged in */
    /*--------------------------------------------------------------------------------------- Permissionkram zum testen ausgeklammert*/
    //if(!isset($_SESSION['username'])) 
    //{
    //    die($config['error']['noLogin']);  
    //}
    /* Check if  permissions are set */
    //else if(!isset($_SESSION['permissions']))
    //{
    //    die($config['error']['permissionNotSet']);  		
    //}
    /*  Check if user has the permission the see this page */
    // Nicht vergessen nach dem kopieren die wirklich benötigte permission abzufragen!!
    //else if(!in_array(Permission::Usermanagment, $_SESSION['permissions']))
    //{
    //    die($config['error']['permissionMissing']);  	  
    //}

    // Printer Beispiel									
    //BackendComponentPrinter::PrintSidebar($_SESSION['permissions']);
    BackendComponentPrinter::PrintSidebar(array());
    /*--------------------------------------------------------------------------------------- Permissionkram zum testen ausgeklammert */
    /* Datatables */
    BackendComponentPrinter::PrintDatatablesPlugin();

    echo
    "<main>
        <h1><i class='fa fa-align-justify fontawesome'></i> Inhaltsverwaltung</h1>";
    $pageSelect = "";
    $pageRows = $dbContent->GetAllPages();
    while ($pageRow = $dbContent->FetchArray($pageRows))
    {
        $pageSelect .= "<option>".$pageRow['title']."</option>";
    }
    echo
        "<form method='post' action='Articlemanagement.php'>
        <select name='pageName'><option></option>";
    echo $pageSelect;
    echo
        "</select><input id='selectPage' name='selectPage' type='submit' value='Anzeigen'></form><br><br>";
    BackendComponentPrinter::PrintTableStart(array("Inhalte", "Veröffentlichungsdatum", "Aktion"));
    if ($pageId != "")
    { 
        // foreach content of page in database print
        $userRows = $dbContent->GetAllArticlesWithDetailedInformation();
        while ($articleRow = $dbContent->FetchArray($userRows))
        {
            $tableRow1 = $articleRow['header'];
            $tableRow2 = $articleRow['date'];
            $tableRow3 = 
                "<form method='post' action='Articlemanagement.php'>
                <input id='delete' name='delete' type='submit' value='Löschen'><input name='edit' type='submit' value='bearbeiten'>".
                "<input id='articleId' name='articleId' type='hidden' value='".$articleRow['id']."'>
                <input id='pageId' name='pageId' type='hidden' value='".$pageId."'></form>";
            BackendComponentPrinter::PrintTableRow(array($tableRow1, $tableRow2, $tableRow3));
        }
    }
    BackendComponentPrinter::PrintTableEnd();
    echo
        "<form method='post' action='Articlemanagement.php'>
        <input id='pageId' name='pageId' type='hidden' value='".$pageId."'>
        <input id='newArticle' name='newArticle' type='submit' value='Neuer Inhalt'></form>
        </main>
            </body>

            </html>";
}

function CreateNewArticle($pageId, $dbContent)
{
    BackendComponentPrinter::PrintHead("Inhaltsverwaltung", $jquery=true);
    /* menue */
    /* dynamisch erzeugt je nach Rechten */
    /* Check if user is logged in */
    /*--------------------------------------------------------------------------------------- Permissionkram zum testen ausgeklammert*/
    //if(!isset($_SESSION['username'])) 
    //{
    //    die($config['error']['noLogin']);  
    //}
    /* Check if  permissions are set */
    //else if(!isset($_SESSION['permissions']))
    //{
    //    die($config['error']['permissionNotSet']);  		
    //}
    /*  Check if user has the permission the see this page */
    // Nicht vergessen nach dem kopieren die wirklich benötigte permission abzufragen!!
    //else if(!in_array(Permission::Usermanagment, $_SESSION['permissions']))
    //{
    //    die($config['error']['permissionMissing']);  	  
    //}

    // Printer Beispiel									
    //BackendComponentPrinter::PrintSidebar($_SESSION['permissions']);
    BackendComponentPrinter::PrintSidebar(array());
    /*--------------------------------------------------------------------------------------- Permissionkram zum testen ausgeklammert */
    /* Summernote */
    echo 
        "<link href='http://netdna.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.css' rel='stylesheet'>
        <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.js'></script> 
        <script src='http://netdna.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.js'></script> 
        <link href='http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.2/summernote.css' rel='stylesheet'>
        <script src='http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.2/summernote.js'></script>";
    echo
        "<main>
            <h1>Inhalt erstellen</h1>
            <form method='post' action='ArticleManagement.php'>
            <label for='header'>Überschrift</label>
            <input id='header' name='header' type='text'><br><br>
            <label for='summernote'>Inhalt</label>
            <div id='summernote' name='summernote'><p>Hello Summernote</p></div><br><br>
            <script>
                $(document).ready(function() {
                    $('#summernote').summernote();
                });
            </script>
            <label for='date'>Datum</label>
            <input id='date' name='date' type='text'><br><br>
            <input id='pageId' name='pageId' type='hidden' value='".$pageId."'>
            <label for='type'>Typ</label>
            <input id='type' name='type' type='text' value=''><br><br>
            <label for='public'>öffentlich</label>
            <input id='public' name='public' type='checkbox' value=''><br><br>
            <label for='description'>Beschreibung</label>
            <input id='description' name='description' type='text' value=''><br><br>
            <input id='publish' name='publish' type='submit' value='Publish'>
            </form>
        <main></body></html>";
}

function EditArticle($pageId, $articleId, $dbContent)
{
    BackendComponentPrinter::PrintHead("Inhaltsverwaltung", $jquery=true);
    /* menue */
    /* dynamisch erzeugt je nach Rechten */
    /* Check if user is logged in */
    /*--------------------------------------------------------------------------------------- Permissionkram zum testen ausgeklammert*/
    //if(!isset($_SESSION['username'])) 
    //{
    //    die($config['error']['noLogin']);  
    //}
    /* Check if  permissions are set */
    //else if(!isset($_SESSION['permissions']))
    //{
    //    die($config['error']['permissionNotSet']);  		
    //}
    /*  Check if user has the permission the see this page */
    // Nicht vergessen nach dem kopieren die wirklich benötigte permission abzufragen!!
    //else if(!in_array(Permission::Usermanagment, $_SESSION['permissions']))
    //{
    //    die($config['error']['permissionMissing']);  	  
    //}

    // Printer Beispiel									
    //BackendComponentPrinter::PrintSidebar($_SESSION['permissions']);
    BackendComponentPrinter::PrintSidebar(array());
    /*--------------------------------------------------------------------------------------- Permissionkram zum testen ausgeklammert */
    $articleRow = $dbContent->FetchArray($dbContent->GetArticleInformationById($articleId));
    echo
        "<main>
            <h1>Inhalt erstellen</h1>
            <form method='post' action='ArticleManagement.php'>
            <label for='header'>Überschrift</label>
            <input id='header' name='header' type='text'><br><br>
            <label for='summernote'>Inhalt</label>
            <div id='summernote' name='summernote'><p>Hello Summernote</p></div><br><br>
            <script>
                $(document).ready(function() {
                    $('#summernote').summernote();
                });
            </script>
            <label for='date'>Datum</label>
            <input id='date' name='date' type='text'><br><br>
            <input id='pageId' name='pageId' type='hidden' value='".$pageId."'>
            <label for='type'>Typ</label>
            <input id='type' name='type' type='text' value='".$articleRow['type']."'><br><br>
            <label for='public'>öffentlich</label>
            <input id='public' name='public' type='checkbox'";
            if (boolval($articleRow['public']))
            {
                echo " checked";
            };
            echo
            "'><br><br>".
            "<label for='description'>Beschreibung</label>
            <input id='description' name='description' type='text' '".$articleRow['description']."'><br><br>
            <input id='updateArticle' name='updateArticle' type='submit' value='Publish'>
            <input id='articleId' name='articleId' type='hidden' value='".$articleId."'>
            </form>
        <main></body></html>";
}
?>