<?php
/* Include(s) */
require_once 'lib/DbEngine.class.php';
require_once 'lib/BackendComponentPrinter.class.php';
require_once 'config/config.php';
require_once 'lib/DbUser.class.php';

/* use namespace(s) */
use SemanticCms\config;
use SemanticCms\DatabaseAbstraction\DbEngine;
use SemanticCms\ComponentPrinter\BackendComponentPrinter;

$db = new DbEngine($config['cms_db']['dbhost'],$config['cms_db']['dbuser'],$config['cms_db']['dbpass'],$config['cms_db']['database']);

echo "<br><br> var_dump db <br><br>";
var_dump($db);
echo "<br>";

echo "IT WORKS";

// actions dbuser
if (isset($_POST['unlock'])) {
    $dbUser = new DbUser();
    $userId = $_POST['userId'];
    $dbUser.DebanUser($userId);    
}
else if (isset($_POST['lock'])) {
    $dbUser = new DbUser();
    $userId = $_POST['userId'];
    $dbUser.BanUser($userId);
}
else if (isset($_POST['details'])) {
    $userId = $_POST['userId'];
    EditUser($userId);
}
else if (isset($_POST['delete'])) {
    $dbUser = new DbUser();
    $userId = $_POST['userId'];
    $dbUser.DeleteUser($userId);
}
else if (isset($_POST['newUser'])) {
    $dbUser = new DbUser();
    $userId = dbUser.CreateUser();
    EditUser($userId);
}
else if (isset($_POST['defineRole'])) {
    $dbUser = new DbUser();
    $dbUser.DefineRole();
}
else if (isset($_POST['newRole'])) {
    $dbUser = new DbUser(); 
    $dbUser.NewRole();   
}
else if (isset($_POST['deleteRole'])) {
    $dbUser = new DbUser();
    $roleId = $_POST['roleId'];
    $dbUser.DeleteRole($roleId);
}
else if (isset($_POST['saveRoleChanges'])) {
    $dbUser = new DbUser();
    $dbUser.SaveRoleChanges();
}
else if (isset($_POST['applyChanges']))
{
    $dbUser = new DbUser();
    $userName = $_POST['userName'];
    $name = $_POST['name'];
    $foreName = $_POST['foreName'];
    $email = $_POST['email'];
    $dbUser.ApplyChangesToUser($userId, $userName, $name, $foreName, $email);
}
else if (isset($_POST['applyPasswordChanges']))
{
    $dbUser = new DbUser();
    $userId = $_POST['userId'];
    $password = $_POST['currentPassword'];
    $newPassword = $_POST['newPassword'];
    $newPasswordRepeat = $_POST['newPasswordRepeat'];
    $dbUser.ApplyPasswordChangesToUser($userId, $password, $newPassword, $newPasswordRepeat);
}

echo "<!DOCTYPE html>
<html>

<!-- fuer head wird es wahrscheinlich ebenfalls eine Methode geben: printHead(titel?), diese dann ggf. nutzen -->
<head>
<meta content="de" http-equiv="Content-Language">
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<title>Benutzerverwaltung</title>
<link rel="stylesheet" href="css/backend.css">
<link rel="stylesheet" href="css/font-awesome/css/font-awesome.min.css">
</head>
<body>";


<!-- menue -->
<!-- dynamisch erzeugt je nach Rechten -->
require_once 'lib/BackendComponentPrinter.class.php';
use SemanticCms\ComponentPrinter\BackendComponentPrinter;

BackendComponentPrinter::printSidebar(array()/*Parameter fehlen noch -> Rechte des gerade eingeloggten Nutzers*/);

echo
"<section id="main">
    <h1><i class="fa fa-user fontawesome"></i> Benutzerverwaltung</h1>
    <table>
        <tr>
            <th>Benutzer</th>
            <th>entsperren/sperren</th>
            <th>Rolle</th>
            <th>Aktion</th>
        </tr>";
            // foreach user in database print
            $dbUser = new DbUser();
            $userRows = $dbUser.GetUsers();
            foreach ($userRows as $row) {

                echo 
                "<tr>".$row['firstname']." ".$row['lastname']."<td>";

                // if user is unlocked/locked
                $unlocked = $dbUser.CheckIfUserIsUnlocked($row['id']);
                if ($unlocked)
                {
                    echo "<td>
                    <form method='post' action='Benutzerverwaltung.php'>
                    <input id='unlock' name='unlock' type='button' value='entsperren'>";
                }
                else
                {
                    echo "<td>
                    <form method='post' action='Benutzerverwaltung.php'>
                    <input id='lock' name='lock' type='button' value='sperren'>";
                }
                
                echo
                    "<input id='userId' name='userId' type='hidden' value='".$row['id']."'></form></td>";
                echo
                    "<td>".$row['role_id'."</td>";
                echo
                    "<td><form method='post' action='Benutzerverwaltung.php'>"
                    ."<input id='details' name='details' type='button' value='Details'><input id='delete' name='delete' type='button' value='löschen'>";
                    ."<input id='userId' name='userId' type='hidden' value='".$row['id']."'>" 
                    ."</form></td>";
                echo 
                    "</tr>";
            }
echo
    "</table>
    <form method="post" action="Benutzerverwaltung.php">
        <input id="newUser" name="newUser" type="button" value="Neuer Benutzer">
        <input id="userId" name="userId" type="hidden" value="userId">
        <input id="defineRole" name="defineRole" type="button" value="Rolle definieren">
        <input id="roleId" name="roleId" type="hidden" value="roleId">
    </form>
    <h2>Rollen definieren</h2>
    <table>
        <tr>
            <th>Rollenname</th>
            <th>Aktion</th>
        </tr>";
            // foreach role in database print
            $dbUser = new DbUser();
            $roleRows = $dbUser.GetRoles();
            foreach ($roleRows as $row) {
                echo "<tr>";
                echo "<td>";
                echo $row['rolename'];
                echo "</td>";
                echo "<td><input id='deleteRole' name='deleteRole' type='button' value='Rolle löschen'></td>";
                echo "<input id='roleId' name='roleId' type='hidden' value='".$row['id']."'>";
                echo "</tr>";
            }
echo
    "</table>
    <form method="post" action="Benutzerverwaltung.php">
        <input id="newRole" name="newRole" type="button" value="roleId">        
    </form>
    <h3>Rechte</h3>
    <form method="post" action="Benutzerverwaltung.php">";
            // foreach right in database print
            $dbUser = new DbUser();
            $roleRightsRows = $dbUser.GetRoleRights();
            foreach ($roleRightsRows as $row) {
                echo
                    //"<input id='".row['right']."'' name='".row['right']."' value='".row['right']."' type='checkbox'>";
            }
echo
        "<input id="saveRoleChanges" name="saveRoleChanges" type="button" value="Rollenänderung speichern">
    </form>
</section>
</body>

</html>";


function EditUser($userId)
{
    // must call the page to edit the details of the user
    echo
    "<!DOCTYPE html>
    <html>

    <head>
    <meta content="de" http-equiv="Content-Language">
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
    <title>Kontodaten bearbeiten</title>
    <link rel="stylesheet" href="BackendCSS.css">
    <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">
    </head>

    <body>
    <nav id="menue">
        <div id="logo"></div>
        <ul>
            <li><a href="Benutzerverwaltung.php" title="Benutzerverwaltung"><i class="fa fa-user fontawesome"></i> Benutzerverwaltung</a></li>
            <li><a href="Seitenverwaltung.php" title="Seitenverwaltung"><i class="fa fa-file-text fontawesome"></i> Seitenverwaltung</a></li>
            <li><a href="Inhaltsverwaltung.php" title="Inhaltsverwaltung"><i class="fa fa-align-justify fontawesome"></i> Inhaltsverwaltung</a></li>
            <li><a href="Templates.php" title="Templates"><i class="fa fa-paint-brush fontawesome"></i> Templates</a></li>
        </ul>
    </nav>
    <section id="main">
    <h1>Kontodaten bearbeiten</h1>
        <form method="post" action="../lib/BackendComponentPrinter.class.php">";
    $dbUser = new DbUser();
    $userRow = $dbUser.GetUserInformation($userId);
    echo
            "<label for="userName">Benutzername</label>
            <input id="userName" name="userName" type="text" value='".$userRow['username']."'>";
    echo
            "<label for="name">Name</label>
            <input id="name" name="name" type="text" value='".$userRow['username']."'>";
    echo    
            "<label for="foreName">Vorname</label>
            <input id="foreName" name="foreName" type="text" value='".$userRow['username']."'>";
    echo
            "<label for="email">Email</label>
            <input id="email" name="email" type="text" value='".$userRow['username']."'>";
    echo
            "<input id="userId" name="userId" type="hidden" value='".$userId."'>".
            "<input id="applyChanges" name="applyChanges" type="button" value="Änderungen übernehmen">"
        echo
        "</form>
        <h2>Passwort ändern</h2>
        <form method="post" action="../lib/BackendComponentPrinter.class.php">";
        echo
            "<label for="currentPassword">aktuelles Passwort</label>
            <input id="currentPassword" name="currentPassword" type="password">
            <label for="newPassword">neues Passwort</label>
            <input id="newPassword" name="newPassword" type="password">
            <label for="newPasswordRepeat">neues Passwort bestätigen</label>
            <input id="newPasswordRepeat" name="newPasswordRepeat" type="password">
            <input id="userID" name="userID" type="hidden" value='".$userId."'>".
            "<input id="applyPasswordChanges" name="applyPasswordChanges" type="button" value="Passwort übernehmen">";
        echo
        "</form>
    </section>
    </body>

    </html>";
}
?>
