<?php
// Start the session
session_start();
/* Include(s) */
require_once 'lib/DbEngine.class.php';
require_once 'lib/BackendComponentPrinter.class.php';
require_once 'config/config.php';
require_once 'lib/dbContent.class.php';
require_once 'lib/Permission.enum.php';
require_once 'lib/DbUser.class.php';

/* use namespace(s) */
use SemanticCms\config;
use SemanticCms\DatabaseAbstraction\DbEngine;
use SemanticCms\ComponentPrinter\BackendComponentPrinter;
use SemanticCms\DatabaseAbstraction\dbContent;
use SemanticCms\Model\Permission;
use SemanticCms\DatabaseAbstraction\DbUser;

$db = new DbEngine($config['cms_db']['dbhost'],$config['cms_db']['dbuser'],$config['cms_db']['dbpass'],$config['cms_db']['database']);
$dbContent = new DbContent($config['cms_db']['dbhost'], $config['cms_db']['dbuser'], $config['cms_db']['dbpass'], $config['cms_db']['database']);
$dbUser = new DbUser($config['cms_db']['dbhost'], $config['cms_db']['dbuser'], $config['cms_db']['dbpass'], $config['cms_db']['database']);

/*---- Submit Buttons ----*/
// if submit button with name 'selectPage' is pressed
if (isset($_POST['selectPage'])) {
    $pageName = utf8_encode($_POST['pageName']);
    CreateArticleManagement($pageName, $dbContent);
    // has to return because other page
    return;
}
// if submit button with name 'edit' is pressed
else if (isset($_POST['edit'])) {
    $articleId = intval($_POST['articleId']);
    $pageName = utf8_encode($_POST['pageName']);
    EditArticle($pageName, $articleId, $dbContent, $dbUser);
    // has to return because other page
    return;
}
// if submit button with name 'delete' is pressed
else if (isset($_POST['delete'])) {
    $articleId = intval($_POST['articleId']);
    $pageName = utf8_encode($_POST['pageName']);
    ReallyDelete($articleId, $pageName);
    // has to return because other page
    return;
    
}
// if submit button with name 'reallydelete' is pressed
else if (isset($_POST['reallyDelete'])) {
    $articleId = intval($_POST['articleId']);
    $dbContent->DeleteArticleById($articleId);
    $pageName = utf8_encode($_POST['pageName']);
    CreateArticleManagement($pageName, $dbContent);
    // has to return because other page
    return;
}
// if submit button with name 'back' is pressed
else if (isset($_POST['back'])) {
    $pageName = utf8_encode($_POST['pageName']);
    CreateArticleManagement($pageName, $dbContent);
    // has to return because other page
    return;
}
// if submit button with name 'newArticle' is pressed
else if (isset($_POST['newArticle'])) {
    $pageName = utf8_encode($_POST['pageName']);
    CreateNewArticle($pageName, $dbContent);
    // has to return because other page
    return;
}
// if submit button with name 'publish' is pressed
else if (isset($_POST['publish']))
{
    $pageId = intval($dbContent->FetchArray($dbContent->SelectPageByPagename(utf8_encode($_POST['pageSelect'])))['id']);
    $header = $_POST['header'];
    $content = $_POST['content'];
    $date = date("Y-m-d");
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
    if(isset($_SESSION['username'])) 
    {
        $authorId = $dbUser->FetchArray($dbUser->GetUserInformationByUsername(isset($_SESSION['username'])))['id'];
    }
    else
    {
        $authorId = 1;
    }

    $dbContent->InsertNewArticleToPage($header, $content, $date, $pageId, $authorId, $type, $public, $description);
    $pageName = $_POST['pageName'];
    CreateArticleManagement($pageName, $dbContent);
    // has to return because other page
    return;
}
// if submit button with name 'updateArticle' is pressed
else if (isset($_POST['updateArticle']))
{
    $pageId = intval($dbContent->FetchArray($dbContent->SelectPageByPagename(utf8_encode($_POST['pageSelect'])))['id']);
    $header = $_POST['header'];
    $content = $_POST['content'];
    $date = date("Y-m-d"); 
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
    if(isset($_SESSION['username'])) 
    {
        $authorId = $dbUser->FetchArray($dbUser->GetUserInformationByUsername(isset($_SESSION['username'])))['id'];
    }
    else
    {
        $authorId = 1;
    }

    $dbContent->UpdateArticleToPage($articleId, $header, $content, $date, $pageId, $authorId, $type, $public, $description);
    $pageName = utf8_encode($_POST['pageName']);
    CreateArticleManagement($pageName, $dbContent);
    // has to return because other page
    return;
}

CreateArticleManagement("", $dbContent);

/**
* Creates the default article management page
*
*/
function CreateArticleManagement($pageName, $dbContent)
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
        $pageSelect .= "<option";
        if (utf8_encode($pageName) == utf8_encode($pageRow['title']))
        {
            $pageSelect .= " selected";
        }
        $pageSelect .= ">".utf8_encode($pageRow['title'])."</option>";
    }
    echo
        "<form method='post' action='Articlemanagement.php'>
        <select name='pageName'><option></option>";
    echo $pageSelect;
    echo
        "</select><input id='selectPage' name='selectPage' type='submit' value='Anzeigen'></form><br><br>";
    BackendComponentPrinter::PrintTableStart(array("Inhalte", "Veröffentlichungsdatum", "Aktion")); 
    $articleInPage = false;
    if ($pageName != "")
    { 
        // foreach aticle of page in database print
        $articleRows = $dbContent->GetAllArticles();
        while ($articleRow = $dbContent->FetchArray($articleRows))
        {
            $pageRows = $dbContent->GetAllPages();
            while ($pageRow = $dbContent->FetchArray($pageRows))
            {
                if (intval($articleRow['page_id']) == intval($dbContent->FetchArray($dbContent->SelectPageByPagename(utf8_encode($pageName)))['id']))
                {
                    $articleInPage = true;
                }
                else 
                {
                    $articleInPage = false;
                }
            }
            if ($articleInPage)
            {
                $tableRow1 = utf8_encode($articleRow['header']);
                $tableRow2 = $articleRow['publicationdate'];
                $tableRow3 = 
                    "<form method='post' action='Articlemanagement.php'>
                    <input id='delete' name='delete' type='submit' value='Löschen'><input name='edit' type='submit' value='bearbeiten'>".
                    "<input id='articleId' name='articleId' type='hidden' value='".$articleRow['id']."'>
                    <input id='pageName' name='pageName' type='hidden' value='".utf8_encode($pageName)."'></form>";
                BackendComponentPrinter::PrintTableRow(array($tableRow1, $tableRow2, $tableRow3));
            }
        }
    }
    BackendComponentPrinter::PrintTableEnd();
    echo
        "<form method='post' action='Articlemanagement.php'>
        <input id='pageName' name='pageName' type='hidden' value='".utf8_encode($pageName)."'>
        <input id='newArticle' name='newArticle' type='submit' value='Neuer Inhalt'></form>
        </main>
            </body>

            </html>";
}

/**
* Opens the page to create a new article
*
*/
function CreateNewArticle($pageName, $dbContent)
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
            <h1><i class='fa fa-align-justify fontawesome'></i> Inhalt erstellen</h1>
            <form method='post' action='ArticleManagement.php'>
            <label for='header'>Überschrift</label>
            <input required id='header' name='header' type='text'><br><br>
            <label for='summernote'>Inhalt</label>
            <div id='summernote' name='summernote'>"."<p>Mein Artikel</p>"."</div><br><br>
            <script>
                $(document).ready(function() {
                    $('#summernote').summernote({
                        callbacks: {
                            onChange: function(contents, \$editable) {
                                $('#content').val(contents);
                            }
                        }
                    });
                });
            </script>
            <input id='content' name='content' type='hidden' value='<p>Mein Artikel</p>'>
            <label for='date'>Datum</label>
            <input readonly id='date' name='date' type='text' value='".date("Y-m-d")."'><br><br>";
    $pageSelect = "";
    $pageRows = $dbContent->GetAllPages();
    while ($pageRow = $dbContent->FetchArray($pageRows))
    {
        $pageSelect .= "<option";
        if (utf8_encode($pageName) == utf8_encode($pageRow['title']))
        {
            $pageSelect .= " selected";
        }
        $pageSelect .=
            " value='".utf8_encode($pageRow['title'])."'>".utf8_encode($pageRow['title'])."</option>";
    }
    echo
        "<label for='pageSelect'>Seite</label><select name='pageSelect'>";
    echo $pageSelect;
    echo
        "</select><br><br>";
    echo
            "<label for='type'>Typ</label>
            <input required id='type' name='type' type='text' value=''><br><br>
            <label for='public'>öffentlich</label>
            <input id='public' name='public' type='checkbox' value=''><br><br>
            <label for='description'>Beschreibung</label>
            <input required id='description' name='description' type='text' value=''><br><br>
            <input id='pageName' name='pageName' type='hidden' value='".utf8_encode($pageName)."'>
            <input id='publish' name='publish' type='submit' value='Publish'>
            </form>";
    echo
            "<form method='post' action='Articlemanagement.php'><input id='back' name='back' type='submit' value='Zurück'>
            <input id='pageName' name='pageName' type='hidden' value='".utf8_encode($pageName)."'>
            <form>";
    echo
            "<main></body></html>";
}

/**
* Opens the page to edit a article
*
*/
function EditArticle($pageName, $articleId, $dbContent, $dbUser)
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
    $articleRow = $dbContent->FetchArray($dbContent->SelectOneArticleById($articleId));
    $authorName = $dbUser->FetchArray($dbUser->GetUserInformationById($articleRow['author']))['username'];
    echo
        "<main>
            <h1><i class='fa fa-align-justify fontawesome'></i> Inhalt bearbeiten</h1>
            <form method='post' action='ArticleManagement.php'>
            <label for='header'>Überschrift</label>
            <input required id='header' name='header' type='text' value='".utf8_encode($articleRow['header'])."'><br><br>
            <label for='summernote'>Inhalt</label>
            <div id='summernote' name='summernote'>".utf8_encode($articleRow['content'])."</div><br><br>
            <script>
                $(document).ready(function() {
                    $('#summernote').summernote({
                        callbacks: {
                            onChange: function(contents, \$editable) {
                                $('#content').val(contents);
                            }
                        }
                    });
                });
            </script>
            <input id='content' name='content' type='hidden' value='".utf8_encode($articleRow['content'])."'>
            <label for='date'>Datum</label>
            <input readonly id='date' name='date' type='text' value='".$articleRow['publicationdate']."'><br><br>".
            "<label for='author'>Author</label>
            <input readonly id='author' name='author' type='text' value='".utf8_encode($authorName)."'><br><br>";
    $pageSelect = "";
    $pageRows = $dbContent->GetAllPages();
    while ($pageRow = $dbContent->FetchArray($pageRows))
    {
        $pageSelect .= "<option";
        if (utf8_encode($pageName) == utf8_encode($pageRow['title']))
        {
            $pageSelect .= " selected";
        }
        $pageSelect .=
            " value='".utf8_encode($pageRow['title'])."'>".utf8_encode($pageRow['title'])."</option>";
    }
    echo
        "<label for='pageSelect'>Seite</label><select name='pageSelect'>";
    echo $pageSelect;
    echo
        "</select><br><br>";
    echo
            "<label for='type'>Typ</label>
            <input required id='type' name='type' type='text' value='".utf8_encode($articleRow['type'])."'><br><br>
            <label for='public'>öffentlich</label>
            <input id='public' name='public' type='checkbox'";
            if (boolval($articleRow['public']))
            {
                echo " checked";
            };
            echo
            "><br><br>".
            "<label for='description'>Beschreibung</label>
            <input required id='description' name='description' type='text' value='".utf8_encode($articleRow['description'])."'><br><br>
            <input id='updateArticle' name='updateArticle' type='submit' value='Publish'>".
            "<input id='pageName' name='pageName' type='hidden' value='".utf8_encode($pageName)."'>".
            "<input id='articleId' name='articleId' type='hidden' value='".$articleId."'>
            </form>";
    echo
            "<form method='post' action='Articlemanagement.php'><input id='back' name='back' type='submit' value='Zurück'>".
            "<input id='pageName' name='pageName' type='hidden' value='".utf8_encode($pageName)."'>".
            "<form>";
    echo
        "<main></body></html>";
}

/**
* Opens the page to decide if really delete article
*
*/
function ReallyDelete($articleId, $pageName)
{
    BackendComponentPrinter::PrintHead("Inhaltsverwaltung");
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
    echo
            "<main><form method='post' action='Articlemanagement.php'>".
            "<input id='articleId' name='articleId' type='hidden' value='".$articleId."'><br><br>".
            "<input id='pageName' name='pageName' type='hidden' value='".utf8_encode($pageName)."'>".
            "<p>Möchten Sie wirklich löschen?</p>".
            "<p><img src='media/Pictures/Gnome-edit-delete.png' height='auto' width='250px'></p>".
            "<input id='reallyDelete' name='reallyDelete' type='submit' value='Löschen'>";
    echo
            "</form>";
    echo
            "<form method='post' action='Articlemanagement.php'><input id='back' name='back' type='submit' value='Zurück'>".
            "<input id='pageName' name='pageName' type='hidden' value='".utf8_encode($pageName)."'>".
            "<form>";
    echo
            "</main></body></html>";

}
?>