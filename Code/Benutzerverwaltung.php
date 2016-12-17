<?php
/* Include(s) */
require_once 'lib/DbEngine.class.php';
require_once 'lib/BackendComponentPrinter.class.php';
require_once 'config/config.php';

/* use namespace(s) */
use SemanticCms\config;
use SemanticCms\DatabaseAbstraction\DbEngine;
use SemanticCms\ComponentPrinter\BackendComponentPrinter;

$db = new DbEngine($config['cms_db']['dbhost'],$config['cms_db']['dbuser'],$config['cms_db']['dbpass'],$config['cms_db']['database']);

echo "<br><br> var_dump db <br><br>";
var_dump($db);
echo "<br>";

echo "IT WORKS";

// Printer Beispiel
BackendComponentPrinter::start_table();
BackendComponentPrinter::end_table("index.php");

// actions dbuser
if (isset($_POST['unlock'])) {
    include("../lib/DbUser.class.php");
    dbUser = new DbUser();
    $userId = 1234567988123456789876543234567; // to get from html
    dbUser.unlockUser($userId);    
}
else if (isset($_POST['lock'])) {
    include("../lib/DbUser.class.php");
    dbUser = new DbUser();
    $userId = 1234567988123456789876543234567; // to get from html
    dbUser.lockUser($userId);
}
else if (isset($_POST['details'])) {
    $userId = 1234567988123456789876543234567; // to get from html
    editUser($userId);
}
else if (isset($_POST['delete'])) {
    include("../lib/DbUser.class.php");
    dbUser = new DbUser();
    $userId = 1234567988123456789876543234567; // to get from html
    dbUser.deleteUser($userId);
}
else if (isset($_POST['newUser'])) {
    include("../lib/DbUser.class.php");
    dbUser = new DbUser();
    dbUser.createUser();
    $userId = newUser.id; // from dbuser
    editUser$userId();
}
else if (isset($_POST['defineRole'])) {
    include("../lib/DbUser.class.php");
    dbUser = new DbUser();
    dbUser.defineRole();
}
else if (isset($_POST['newRole'])) {
    include("../lib/DbUser.class.php");
    dbUser = new DbUser(); 
    dbUser.defineRole();   
}
else if (isset($_POST['deleteRole'])) {
    include("../lib/DbUser.class.php")
    dbUser = new DbUser();
    $roleId = = 1234567988123456789876543234567; // to get from html
    dbUser.deleteRole();
}
else if (isset($_POST['saveChanges'])) {
    include("../lib/DbUser.class.php");
    dbUser = new DbUser();
    dbUser.saveChanges($userId);
}

function editUser()
{
    // must call the page to edit the details of the user
}
?>

<!DOCTYPE html>
<html>

<!-- fuer head wird es wahrscheinlich ebenfalls eine Methode geben: printHead(titel?), diese dann ggf. nutzen -->
<head>
<meta content="de" http-equiv="Content-Language">
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<title>Benutzerverwaltung</title>
<link rel="stylesheet" href="css\backend.css">
<link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">
</head>

<body>
<!-- menue -->
<!-- dynamisch erzeugt je nach Rechten -->
<?php
require_once 'lib/BackendComponentPrinter.class.php';
use SemanticCms\ComponentPrinter\BackendComponentPrinter;

BackendComponentPrinter::printSidebar(array()/*Parameter fehlen noch -> Rechte des gerade eingeloggten Nutzers*/);
?>

<!--<nav id="menue">
    <div id="logo"></div>
	<ul>
        <li><a href="Benutzerverwaltung.php" title="Benutzerverwaltung"><i class="fa fa-user fontawesome"></i> Benutzerverwaltung</a></li>
        <li><a href="Seitenverwaltung.php" title="Seitenverwaltung"><i class="fa fa-file-text fontawesome"></i> Seitenverwaltung</a></li>
        <li><a href="Inhaltsverwaltung.php" title="Inhaltsverwaltung"><i class="fa fa-align-justify fontawesome"></i> Inhaltsverwaltung</a></li>
        <li><a href="Templates.php" title="Templates"><i class="fa fa-paint-brush fontawesome"></i> Templates</a></li>
	</ul>
</nav> -->


<section id="main">
    <h1><i class="fa fa-user fontawesome"></i> Benutzerverwaltung</h1>
    <table>
        <tr>
            <th>Benutzer</th>
            <th>entsperren/sperren</th>
            <th>Rolle</th>
            <th>Aktion</th>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>
            <form method="post" action="Benutzerverwaltung.php">
                <input id="unlock" name="unlock" type="button" value="entsperren"></form>
            </td>
            <td>&nbsp;</td>
            <td>
            <form method="post" action="Benutzerverwaltung.php">
                <input id="details" name="details" type="button" value="Details"><input id="delete" name="delete" type="button" value="löschen"></form>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
    </table>
    <form method="post" action="Benutzerverwaltung.php">
        <input id="newUser" name="newUser" type="button" value="Neuer Benutzer">
        <input id="defineRole" name="defineRole" type="button" value="Rollen definieren">
    </form>
    <h2>Rollen definieren</h2>
    <h3>Rollenname</h3>
    <table>
        <tr>
            <td>Admin</td>
        </tr>
        <tr>
            <td>Redakteur</td>
        </tr>
        <tr>
            <td>Gast</td>
        </tr>
    </table>
    <form method="post" action="Benutzerverwaltung.php">
        <input id="newRole" name="newRole" type="button" value="Neue Rolle">
        <input id="deleteRole" name="deleteRole" type="button" value="Rolle löschen">
    </form>
    <h3>Rechte</h3>
    <form method="post" action="Benutzerverwaltung.php">
        <input id="right1" name="right1" type="checkbox">
        <input id="right2" name="right2" type="checkbox">
        <input id="saveChanges" name="saveChanges" type="button" value="Änderungen speichern">
    </form>
</section>
</body>

</html>
