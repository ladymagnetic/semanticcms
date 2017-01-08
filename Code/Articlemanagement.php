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
// if submit button with name 'edit' is pressed
if (isset($_POST['edit'])) {
    $articleId = intval($_POST['articleId']);
    $pageName = $_POST['pageName'];
    EditArticle($pageName, $articleId, $dbContent, $dbUser);
    // has to return because other page
    return;
}
// if submit button with name 'delete' is pressed
else if (isset($_POST['delete'])) {
    $articleId = intval($_POST['articleId']);
    $pageName = $_POST['pageName'];
    ReallyDelete($articleId, $pageName);
    // has to return because other page
    return;
    
}
// if submit button with name 'reallydelete' is pressed
else if (isset($_POST['reallyDelete'])) {
    $articleId = intval($_POST['articleId']);
    $dbContent->DeleteArticleById($articleId);
}
// if submit button with name 'newArticle' is pressed
else if (isset($_POST['newArticle'])) {
    $pageName = $_POST['pageName'];
    CreateNewArticle($pageName, $dbContent);
    // has to return because other page
    return;
}
// if submit button with name 'newArticle' is pressed
else if (isset($_POST['searchSemanticPageName'])) {
    $pageName = $_POST['semanticPageName'];
    CreateNewArticle($pageName, $dbContent);
    // has to return because other page
    return;
}
// if submit button with name 'publish' is pressed
else if (isset($_POST['publish']))
{
    $pageId = intval($dbContent->FetchArray($dbContent->SelectPageByPagename($_POST['pageSelect']))['id']);
    $header = $_POST['header'];
    $content = $_POST['content'];
    $date = $_POST['date'];
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
        $authorId = $dbUser->FetchArray($dbUser->GetUserInformationByUsername($_SESSION['username']))['id'];
    }
    else
    {
        $authorId = 1;
    }

    $dbContent->InsertNewArticleToPage($header, $content, $date, $pageId, $authorId, $type, $public, $description);

    // assign labels
    foreach ($_POST['labels'] as $selectedOption)
    {
        $labelId = $dbContent->FetchArray($dbContent->SelectLableIdByLablename(mb_strtolower($selectedOption)))['id'];
        if ($labelId != "" && $labelId != null)
        {
            $labelId = intval($labelId);
        }
        else
        {
            $dbContent->InsertLable(mb_strtolower($selectedOption), "label.".mb_strtolower($selectedOption));
            $labelId = intval($dbContent->FetchArray($dbContent->SelectLableIdByLablename(mb_strtolower($selectedOption))));
        }
        $articleId = intval($dbContent->FetchArray($dbContent->SelectArticleByHeader($header))['id']);
        $dbContent->InsertLable_Article($labelId, $articleId);
    }
}
// if submit button with name 'updateArticle' is pressed
else if (isset($_POST['updateArticle']))
{
    $pageId = intval($dbContent->FetchArray($dbContent->SelectPageByPagename($_POST['pageSelect']))['id']);
    $header = $_POST['header'];
    $content = $_POST['content'];
    $date = $_POST['date']; 
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
        $authorId = $dbUser->FetchArray($dbUser->GetUserInformationByUsername($_SESSION['username']))['id'];
    }
    else
    {
        $authorId = 1;
    }

    $dbContent->UpdateArticleToPage($articleId, $header, $content, $date, $pageId, $authorId, $type, $public, $description);

    // delete all labels to assign all current labels
    $dbContent->DeleteLable_ArticleByArticleId($articleId);
  
    // assign labels
    foreach ($_POST['labels'] as $selectedOption)
    {  
        $labelId = $dbContent->FetchArray($dbContent->SelectLableIdByLablename(mb_strtolower($selectedOption)))['id'];
        if ($labelId != "" && $labelId != null)
        { 
            $labelId = intval($labelId);
        }
        else
        {
            $dbContent->InsertLable(mb_strtolower($selectedOption), "label.".mb_strtolower($selectedOption));
            $labelId = intval($dbContent->FetchArray($dbContent->SelectLableIdByLablename(mb_strtolower($selectedOption)))['id']);
        }
        $dbContent->InsertLable_Article($labelId, $articleId);
    }
}

BackendComponentPrinter::PrintHead("Inhaltsverwaltung", $jquery=true);
//*----- Permissions ----- */
/* Include(s) */
require_once 'lib/Permission.enum.php';
require_once 'config/config.php';

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
/*  Check if user has the permission the see this page */
else if(!in_array(Permission::Articlemanagment, $_SESSION['permissions']))
{
    die($config['error']['permissionMissing']);  	  
}	

BackendComponentPrinter::PrintSidebar($_SESSION['permissions']);
//*----- Permissions End ----- */

/* Datatables */
BackendComponentPrinter::PrintDatatablesPlugin();
if (isset($_REQUEST['pageName']))
{
    $pageName = $_REQUEST['pageName'];
}
else 
{
    $pageName = "";
}
echo
"<body><main>
    <h1><i class='fa fa-align-justify fontawesome'></i> Inhaltsverwaltung</h1>";
$pageSelect = "";
$pageRows = $dbContent->GetAllPages();
while ($pageRow = $dbContent->FetchArray($pageRows))
{
    $pageSelect .= "<option";
    if ($pageName == $pageRow['title'])
    {
        $pageSelect .= " selected";
    }
    $pageSelect .= ">".$pageRow['title']."</option>";
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
            if ($articleRow['page_id'] == $dbContent->FetchArray($dbContent->SelectPageByPagename($pageName))['id'])
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
            $tableRow1 = $articleRow['header'];
            $tableRow2 = $articleRow['publicationdate'];
            $tableRow3 = 
                "<form method='post' action='Articlemanagement.php'>
                <input id='delete' name='delete' type='submit' value='Löschen'><input name='edit' type='submit' value='bearbeiten'>".
                "<input id='articleId' name='articleId' type='hidden' value='".$articleRow['id']."'>
                <input id='pageName' name='pageName' type='hidden' value='".$pageName."'></form>";
            BackendComponentPrinter::PrintTableRow(array($tableRow1, $tableRow2, $tableRow3));
        }
    }
}
BackendComponentPrinter::PrintTableEnd();
echo
    "<form method='post' action='Articlemanagement.php'>
    <input id='pageName' name='pageName' type='hidden' value='".$pageName."'>
    <input id='newArticle' name='newArticle' type='submit' value='Neuer Inhalt'></form>
    </main>
        </body>

        </html>";

/**
* Opens the page to create a new article
* @param string $pageName The corresponding pagename
* @param DbContent $dbContent The corresponding dbcontent
*
*/
function CreateNewArticle($pageName, $dbContent)
{
    BackendComponentPrinter::PrintHead("Inhaltsverwaltung", $jquery=true, $jqueryUI=true);
    //*----- Permissions ----- */
    /* Include(s) */
    require_once 'lib/Permission.enum.php';
    require_once 'config/config.php';
    		
    BackendComponentPrinter::PrintSidebar($_SESSION['permissions']);
    //*----- Permissions End ----- */
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
        "<body><main>
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
            <label for='date'>Veröffentlichungsdatum</label>
            <input required id='date' name='date' type='text' value='".date("Y-m-d")."'><br><br>";
    $pageSelect = "";
    $pageRows = $dbContent->GetAllPages();
    while ($pageRow = $dbContent->FetchArray($pageRows))
    {
        $pageSelect .= "<option";
        if ($pageName == $pageRow['title'])
        {
            $pageSelect .= " selected";
        }
        $pageSelect .=
            " value='".$pageRow['title']."'>".$pageRow['title']."</option>";
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
            <input id='pageName' name='pageName' type='hidden' value='".$pageName."'>";
             /* labels */
            echo 
                // select2
                "<link href='https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css' rel='stylesheet' />
                <script src='https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js'></script>".
                "<script type='text/javascript'>
                $(document).ready(function() {
                $('#labels').select2({
                    tags: true
                });
                });
                </script>";
            echo 
                "<label for='labels[]'>Labels</label><select required style='width: 500px;' id='labels' name='labels[]' multiple='multiple'>";
            $labelRows = $dbContent->SelectAllLables();
            while ($labelRow = $dbContent->FetchArray($labelRows))
            {
                echo
                    "<option value='".$labelRow['lablename']."'>".$labelRow['lablename']."</option>";
            }
            echo
                "</select><br><br>";
            /* labels end */
            echo
                "<input id='publish' name='publish' type='submit' value='Publish'>
                </form>";
    echo
            "<form method='post' action='Articlemanagement.php'><input id='back' name='back' type='submit' value='Zurück'>
            <input id='pageName' name='pageName' type='hidden' value='".$pageName."'>
            <form>";
    /* ---- Writing Assistant/ Semantic Mindmap ---- */
    echo
            "<h1><i class='fa fa-share-alt fontawesome'></i> Semantische Mindmap</h1>";
    // search
    echo
            "<form method='post' action='Articlemanagement.php'>
            <input id='semanticPageName' name='semanticPageName' type='text' value='".$pageName."'>
            <input id='searchSemanticPageName' name='searchSemanticPageName' type='submit' value='Suchen'>
            <form><br><br>";
    /* ml-lod-live */
    echo
            "<link rel='stylesheet' href='media/ml-lodlive/dist/ml-lodlive.all.css'>

            <div id='graph' style='width: 100%; height: 600px;'></div>

            <!-- Would like to remove these deps -->
            <script src='media/ml-lodlive/dist/ml-lodlive.complete.js'></script>

            <!-- this is now just a customized options object, might make sense to just pass it in as part of init rather then with the constructor -->
            <script src='media/ml-lodlive/js/profile/profile.example.js'></script>
            <script>
            'use strict';
            jQuery('#graph').lodlive({ profile: ExampleProfile, firstUri: 'http://dbpedia.org/resource/".str_replace(" ", "_", $pageName)."', ignoreBnodes: true });
            </script>";
    echo
            "</main></body></html>";

    /* Datepicker */
    echo
        "<link rel='stylesheet' href='//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css'>
        <link rel='stylesheet' href='/resources/demos/style.css'>
        <script>
        $(document).ready(function() {
                $( '#date' ).datepicker({ dateFormat: 'yy-mm-dd' });
            });
        </script>
    ";
}

/**
* Opens the page to edit a article
* @param string $pageName The corresponding pagename
* @param int $articleId The corresponding artticleId
* @param DbContent $dbContent The corresponding dbcontent
* @param DbUser $dbUser The corresponding dbuser
*
*/
function EditArticle($pageName, $articleId, $dbContent, $dbUser)
{
    BackendComponentPrinter::PrintHead("Inhaltsverwaltung", $jquery=true,  $jqueryUI=true);
    //*----- Permissions ----- */
    /* Include(s) */
    require_once 'lib/Permission.enum.php';
    require_once 'config/config.php';
    	
    BackendComponentPrinter::PrintSidebar($_SESSION['permissions']);
    //*----- Permissions End ----- */
    
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
    $articleRow = $dbContent->FetchArray($dbContent->SelectOneArticleById($articleId));
    $authorName = $dbUser->FetchArray($dbUser->GetUserInformationById($articleRow['author']))['username'];
    echo
        "<body><main>
            <h1><i class='fa fa-align-justify fontawesome'></i> Inhalt bearbeiten</h1>
            <form method='post' action='ArticleManagement.php'>
            <label for='header'>Überschrift</label>
            <input required id='header' name='header' type='text' value='".$articleRow['header']."'><br><br>
            <label for='summernote'>Inhalt</label>
            <div id='summernote' name='summernote'>".$articleRow['content']."</div><br><br>
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
            <input id='content' name='content' type='hidden' value='".$articleRow['content']."'>
            <label for='date'>Veröffentlichungsdatum</label>
            <input required id='date' name='date' type='text' value='".$articleRow['publicationdate']."'><br><br>".
            "<label for='author'>Author</label>
            <input readonly id='author' name='author' type='text' value='".$authorName."'><br><br>";
    $pageSelect = "";
    $pageRows = $dbContent->GetAllPages();
    while ($pageRow = $dbContent->FetchArray($pageRows))
    {
        $pageSelect .= "<option";
        if ($pageName == $pageRow['title'])
        {
            $pageSelect .= " selected";
        }
        $pageSelect .=
            " value='".$pageRow['title']."'>".$pageRow['title']."</option>";
    }
    echo
        "<label for='pageSelect'>Seite</label><select name='pageSelect'>";
    echo $pageSelect;
    echo
        "</select><br><br>";
    echo
            "<label for='type'>Typ</label>
            <input required id='type' name='type' type='text' value='".$articleRow['type']."'><br><br>
            <label for='public'>öffentlich</label>
            <input id='public' name='public' type='checkbox'";
            if (boolval($articleRow['public']))
            {
                echo " checked";
            };
            echo
            "><br><br>".
            "<label for='description'>Beschreibung</label>
            <input required id='description' name='description' type='text' value='".$articleRow['description']."'><br><br>";
            /* labels */
            echo 
                // select2
                "<link href='https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css' rel='stylesheet' />
                <script src='https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js'></script>".
                "<script type='text/javascript'>
                $(document).ready(function() {
                $('#labels').select2({
                    tags: true
                });
                });
                </script>";
            echo 
                "<label for='labels[]'>Labels</label><select required style='width: 500px;' id='labels' name='labels[]' multiple='multiple'>";
            $selectedlabelRows = $dbContent->SelectAllLablesFromAnArticleById($articleRow['id']);
            while ($selectedlabelRow = $dbContent->FetchArray($selectedlabelRows))
            {
                echo
                    "<option selected value='".$selectedlabelRow['lablename']."'>".$selectedlabelRow['lablename']."</option>";
            }
            $labelRows = $dbContent->SelectAllLables();
            while ($labelRow = $dbContent->FetchArray($labelRows))
            {
                echo
                    "<option value='".$labelRow['lablename']."'>".$labelRow['lablename']."</option>";
            }
            echo
                "</select><br><br>";
            /* labels end */
    echo
            "<input id='updateArticle' name='updateArticle' type='submit' value='Publish'>".
            "<input id='pageName' name='pageName' type='hidden' value='".$pageName."'>".
            "<input id='articleId' name='articleId' type='hidden' value='".$articleId."'>
            </form>";
    echo
            "<form method='post' action='Articlemanagement.php'><input id='back' name='back' type='submit' value='Zurück'>".
            "<input id='pageName' name='pageName' type='hidden' value='".$pageName."'>".
            "<form>";
    echo
        "</main></body></html>";

    /* Datepicker */
    echo
        "<link rel='stylesheet' href='//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css'>
        <link rel='stylesheet' href='/resources/demos/style.css'>
        <script>
        $(document).ready(function() {
                $( '#date' ).datepicker({ dateFormat: 'yy-mm-dd' });
            });
        </script>
    ";
}

/**
* Opens the page to decide if really delete article
* @param int $articleId The corresponding articleid
* @param string $pageName The corresponding pagename
*
*/
function ReallyDelete($articleId, $pageName)
{
    BackendComponentPrinter::PrintHead("Inhaltsverwaltung");
    //*----- Permissions ----- */
    /* Include(s) */
    require_once 'lib/Permission.enum.php';
    require_once 'config/config.php';
    	
    BackendComponentPrinter::PrintSidebar($_SESSION['permissions']);
    //*----- Permissions End ----- */
    
    echo
            "<body><main><form method='post' action='Articlemanagement.php'>".
            "<input id='articleId' name='articleId' type='hidden' value='".$articleId."'><br><br>".
            "<input id='pageName' name='pageName' type='hidden' value='".$pageName."'>".
            "<p>Möchten Sie wirklich löschen?</p>".
            "<p><img src='media/Pictures/Gnome-edit-delete.png' height='auto' width='250px'></p>".
            "<input id='reallyDelete' name='reallyDelete' type='submit' value='Löschen'>";
    echo
            "</form>";
    echo
            "<form method='post' action='Articlemanagement.php'><input id='back' name='back' type='submit' value='Zurück'>".
            "<input id='pageName' name='pageName' type='hidden' value='".$pageName."'>".
            "<form>";
    echo
            "</main></body></html>";

}
?>