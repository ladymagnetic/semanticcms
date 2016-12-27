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
$dbUser = new DbUser($config['cms_db']['dbhost'], $config['cms_db']['dbuser'], $config['cms_db']['dbpass'], $config['cms_db']['database']);

/*---- Submit Buttons ----*/
// if submit button with name 'edit' is pressed
if (isset($_POST['edit'])) {
    $contentId = intval($_POST['contentId']);
    EditContent($editId, $dbUser);
    // has to return because other page
    return;
}
// if submit button with name 'delete' is pressed
else if (isset($_POST['delete'])) {
    $contentId = intval($_POST['contentId']);
    $dbUser->DeleteContentById($contentId);
}
// if submit button with name 'newUser' is pressed
else if (isset($_POST['newContent'])) {
    CreateNewContent($dbUser);
    // has to return because other page
    return;
}
// if submit button with name 'assignPage' is pressed
else if (isset($_POST['assignContentToPage'])) {
    $pageId = intval($dbUser->FetchArray($dbUser->SelectRoleByRolename($_POST['pageId']))['id']);
    $contentId = intval($_POST['contentId']);
    $dbUser->AssignContentToPage($pageId, $contentId);
}
// if submit button with name 'applyChanges' is pressed
else if (isset($_POST['publish']))
{
    $userId = intval($_POST['userId']);
    $userName = $_POST['userName'];
    $name = $_POST['name'];
    $foreName = $_POST['foreName'];
    $email = $_POST['email'];
    $dbUser->UpdateUserDifferentNamesById($userId, $userName, $name, $foreName, $email);
}
isset
        edit
        selectPage
        publish

// if submit button with name 'registrateUser' is pressed
else if (isset($_POST['registrateUser']))
{
    $role_id = intval($dbUser->FetchArray($dbUser->SelectRoleByRolename($_POST['assignedRole']))['id']);
    $lastname = $_POST['name'];
    $firstname = $_POST['foreName'];
    $username = $_POST['userName'];
    $password = $_POST['currentPassword'];
    $email = $_POST['email'];
    $birthdate = $_POST['birthdate'];
    $dbUser->RegistrateUser($role_id, $lastname, $firstname, $username, $password, $email, $birthdate);
}
// if submit button with name 'saveRoleChanges' is pressed
else if (isset($_POST['saveRoleChanges'])) 
{
    $id = intval($_POST['roleId']);
    $rolename = $_POST['roleName'];
    $uri = $_POST['uri'];
    SetPermissionsFromForm($guestbookmanagement, $usermanagement, $pagemanagement, $articlemanagement, $guestbookusage, $templateconstruction);
    $dbUser->UpdateRoleById($uri, $rolename, $guestbookmanagement, $usermanagement, $pagemanagement, $articlemanagement, $guestbookusage, $templateconstruction, $id);
}

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
BackendComponentPrinter::PrintTableStart(array("Inhalte", "Veröffentlichungsdatum", "Aktion"));
// foreach content of page in database print
$userRows = $dbUser->SelectAllUsers();
while ($row = $dbUser->FetchArray($userRows))
{
    $tableRow1 = $row['firstname']." ".$row['lastname'];
    $tableRow2 = $row['firstname']." ".$row['lastname'];
    $tableRow3 = $row['firstname']." ".$row['lastname'];
    BackendComponentPrinter::PrintTableRow(array($tableRow1, $tableRow2, $tableRow3));
}
BackendComponentPrinter::PrintTableEnd();
echo
    "<form method='post' action='Articlemanagement.php'>
    <input id='newContent' name='newContent' type='button' value='Neuer Inhalt'></form>
    </main>
        </body>

        </html>";
?>
    <form id="page" name="page" action="../lib/BackendComponentPrinter.class.php"> <label>Seite: <select name="top5"> <option>Heino</option> <option>Michael Jackson</option> <option>Tom Waits</option> <option>Nina Hagen</option> <option>Marianne Rosenberg</option> </select> </label> </form>
    <table>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>
            <form method="post" action="../lib/BackendComponentPrinter.class.php">
                <input id="delete" name="delete" type="button" value="Löschen"><input name="edit" type="button" value="edit"></form>
            </td>
        </tr>
    </table>