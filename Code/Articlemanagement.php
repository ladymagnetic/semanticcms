<?php
// Start the session
session_start();
/* Include(s) */
require_once 'lib/DbEngine.class.php';
require_once 'lib/BackendComponentPrinter.class.php';
require_once 'config/config.php';
require_once 'lib/DbContent.class.php';
require_once 'lib/Permission.enum.php';

/* use namespace(s) */
use SemanticCms\config;
use SemanticCms\DatabaseAbstraction\DbEngine;
use SemanticCms\ComponentPrinter\BackendComponentPrinter;
use SemanticCms\DatabaseAbstraction\DbUser;
use SemanticCms\Model\Permission;

$db = new DbEngine($config['cms_db']['dbhost'],$config['cms_db']['dbuser'],$config['cms_db']['dbpass'],$config['cms_db']['database']);
$dbContent = new DbContent($config['cms_db']['dbhost'], $config['cms_db']['dbuser'], $config['cms_db']['dbpass'], $config['cms_db']['database']);

/*---- Submit Buttons ----*/
// if submit button with name 'selectPage' is pressed
if (isset($_POST['selectPage'])) {
    $pageId = intval($_POST['pageId']);
    CreateArticleManagement($pageId, $dbUser);
    // has to return because other page
    return;
}
// if submit button with name 'edit' is pressed
else if (isset($_POST['edit'])) {
    $articleId = intval($_POST['articleId']);
    EditArticle($pageId, $articleId, $dbUser);
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
    CreateNewArticle($pageId, $dbUser);
    // has to return because other page
    return;
}
// if submit button with name 'applyChanges' is pressed
else if (isset($_POST['publish']))
{
    $pageId = $_POST['pageId']);
    $header = $_POST['header']);
    $content = $_POST['summernote']);
    $date = $_POST['date']);
    $type = $_POST['type']);
    if (isset($_POST['public']))
    {
        $public = 1;
    }
    else 
    {
        $public = 0;
    }
    $description = $_POST['description']);

    $dbContent->InsertNewArticleToPage($pageId, $header, $content, $date, $type, $public, $description);
}
// if submit button with name 'applyChanges' is pressed
else if (isset($_POST['updateArticle']))
{
    $pageId = $_POST['pageId']);
    $header = $_POST['header']);
    $content = $_POST['summernote']);
    $date = $_POST['date']);
    $type = $_POST['type']);
    if (isset($_POST['public']))
    {
        $public = 1;
    }
    else 
    {
        $public = 0;
    }
    $description = $_POST['description']);
    $articleId = $_POST['articleId']);

    $dbContent->UpdateArticleToPage($articleId, $pageId, $header, $content, $date, $type, $public, $description)
}

CreateArticleManagement("", $dbUser);

function CreateArticleManagement($pageId, $dbUser)
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
    echo
            "<link rel='stylesheet' type='text/css' href='//cdn.datatables.net/1.10.13/css/jquery.dataTables.css'>
            <script type='text/javascript' charset='utf8' src='//cdn.datatables.net/1.10.13/js/jquery.dataTables.js'></script>".
            "<script>$(document).ready( function () {
                $('table').DataTable({
                    'language': {
                        'lengthMenu': '_MENU_ Werte pro Seite',
                        'zeroRecords': 'Nichts gefunden - Entschuldigung',
                        'info': 'Seite _PAGE_ von _PAGES_',
                        'infoEmpty': 'Nichts vorhanden',
                        'infoFiltered': '(gefiltert von _MAX_ gesamt)',
                        'search': 'Suche:',
                        'paginate': {
                            'previous': 'Zurück',
                            'next': 'Weiter',
                        }
                    }
                });
            } );</script>";

    echo
    "<main>
        <h1><i class='fa fa-align-justify fontawesome'></i> Inhaltsverwaltung</h1>";
    $pageRows = $dbContent->GetAllPages();
    while ($pageRow = $dbContent->FetchArray($pageRows))
    {
        $pageSelect = "<option id='".$pageRow['id']."'";
        $pageSelect .= ">".$pageRow['title']."</option>";
    }
    echo
        "<input id='selectPage' name='selectPage' type='submit' value='Anzeigen'>"

    echo $pageSelect;
    BackendComponentPrinter::PrintTableStart(array("Inhalte", "Veröffentlichungsdatum", "Aktion"));
    if ($pageId != "")
    { 
        // foreach content of page in database print
        $userRows = $dbContent->GetAllArticles();
        while ($articleRow = $dbUser->FetchArray($userRows))
        {
            $tableRow1 = $articleRow['header'];
            $tableRow2 = $articleRow['date'];
            $tableRow3 = 
                "<form method='post' action='Articlemanagement.php'>
                <input id='delete' name='delete' type='submit' value='Löschen'><input name='edit' type='submit' value='bearbeiten'>".
                ."<input id='articleId' name='articleId' type='hidden' value='".$articleRow['id']."'></form>";
            BackendComponentPrinter::PrintTableRow(array($tableRow1, $tableRow2, $tableRow3));
        }
    }
    BackendComponentPrinter::PrintTableEnd();
    echo
        "<form method='post' action='Articlemanagement.php'>
        <input id='newContent' name='newArticle' type='submit' value='Neuer Inhalt'></form>
        </main>
            </body>

            </html>";
}

function CreateNewArticle($pageId, $dbUser)
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
            <input id='header' name='header' type='text'>
            <label for='summernote'>Inhalt</label>
            <div id='summernote' name='summernote'><p>Hello Summernote</p></div>
            <script>
                $(document).ready(function() {
                    $('#summernote').summernote();
                });
            </script>
            <label for='date'>Datum</label>
            <input id='date' name='date' type='text'>
            <input id='pageId' name='pageId' type='hidden' value='".$pageId."'>
            <label for='type'>Typ</label>
            <input id='type' name='type' type='text' value=''>
            <label for='public'>öffentlich</label>
            <input id='public' name='public' type='checkbox' value=''>
            <label for='description'>Beschreibung</label>
            <input id='description' name='description' type='text' value=''>
            <input id='publish' name='publish' type='submit' value='Publish'>
            </form>
        <main></body></html>";
}

function EditArticle($pageId, $articleId, $dbUser)
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
            <input id='header' name='header' type='text'>
            <label for='summernote'>Inhalt</label>
            <div id='summernote' name='summernote'><p>Hello Summernote</p></div>
            <script>
                $(document).ready(function() {
                    $('#summernote').summernote();
                });
            </script>
            <label for='date'>Datum</label>
            <input id='date' name='date' type='text'>
            <input id='pageId' name='pageId' type='hidden' value='".$pageId."'>
            <label for='type'>Typ</label>
            <input id='type' name='type' type='text' value='".$articleRow['type']."'>
            <label for='public'>öffentlich</label>
            <input id='public' name='public' type='checkbox'";
            if (boolval($articleRow['public']))
            {
                echo " checked";
            };
            echo
            ."'>".
            "<label for='description'>Beschreibung</label>
            <input id='description' name='description' type='text' '".$articleRow['description']."'>
            <input id='updateArticle' name='updateArticle' type='submit' value='Publish'>
            <input id='articleId' name='articleId' type='hidden' value='".$articleId."'>
            </form>
        <main></body></html>";
}
?>