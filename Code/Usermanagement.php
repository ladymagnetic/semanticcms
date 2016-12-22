<?php
/* Include(s) */
require_once 'lib/DbEngine.class.php';
require_once 'lib/BackendComponentPrinter.class.php';
require_once 'config/config.php';
require_once 'lib/DbUser.class.php';
require_once 'lib/BackendComponentPrinter.class.php';

/* use namespace(s) */
use SemanticCms\config;
use SemanticCms\DatabaseAbstraction\DbEngine;
use SemanticCms\ComponentPrinter\BackendComponentPrinter;
use SemanticCms\DatabaseAbstraction\DbUser;

$db = new DbEngine($config['cms_db']['dbhost'],$config['cms_db']['dbuser'],$config['cms_db']['dbpass'],$config['cms_db']['database']);
$dbUser = new DbUser($config['cms_db']['dbhost'], $config['cms_db']['dbuser'], $config['cms_db']['dbpass'], $config['cms_db']['database']);

// actions dbuser
if (isset($_POST['deban'])) {
    $userId = $_POST['userId'];
    $dbUser.DebanUser($userId);    
}
else if (isset($_POST['ban'])) {
    $userId = $_POST['userId'];
    $dbUser.BanUser($userId);
}
else if (isset($_POST['details'])) {
    $userId = $_POST['userId'];
    EditUser($userId);
    // has to return because other page
    return;
}
else if (isset($_POST['delete'])) {
    $userId = $_POST['userId'];
    $dbUser.DeleteUser($userId);
}
else if (isset($_POST['newUser'])) {
    $userId = dbUser.CreateUser();
    EditUser($userId);
    // has to return because other page
    return;
}
else if (isset($_POST['newRole'])) {
    $roleId = $dbUser.NewRole();   
    EditRole($roleId);
    // has to return because other page
    return;
}
else if (isset($_POST['assignRole'])) {
    $roleId = $_POST['assignedRole'];
    $userId = $_POST['userId'];
    $dbUser.AssignRole($roleId, $userId);
}
else if (isset($_POST['deleteRole'])) {
    $roleId = $_POST['roleId'];
    $dbUser.DeleteRole($roleId);
}
else if (isset($_POST['roleDetails'])) {
    $roleId = $_POST['roleId'];
    $dbUser.EditRole($roleId);
    // has to return because other page
    return;
}
else if (isset($_POST['applyChanges']))
{
    $userId = $_POST['userId'];
    $userName = $_POST['userName'];
    $name = $_POST['name'];
    $foreName = $_POST['foreName'];
    $email = $_POST['email'];
    $dbUser.UpdateUserDifferentNamesById($userId, $userName, $name, $foreName, $email);
}
else if (isset($_POST['applyPasswordChanges']))
{
    $userId = $_POST['userId'];
    $password = $_POST['currentPassword'];
    $newPassword = $_POST['newPassword'];
    $newPasswordRepeat = $_POST['newPasswordRepeat'];
    $dbUser.ApplyPasswordChangesToUser($userId, $password, $newPassword, $newPasswordRepeat);
}
else if (isset($_POST['saveRoleChanges'])) {
    $roleId = $_POST['roleId'];
    $rolename = $_POST['rolename'];
    $guestbookmanagement = $_POST['guestbookmanagement'];
    $usermanagement = $_POST['usermanagement'];
    $pagemanagement = $_POST['pagemanagement'];
    $articlemanagement = $_POST['articlemanagement'];
    $guestbookusage = $_POST['guestbookusage'];
    $templateconstruction = $_POST['templateconstruction'];
    $dbUser.UpdateRoleById($roleId, $rolename, $guestbookmanagement, $usermanagement, $pagemanagement, $articlemanagement, $guestbookusage, $templateconstruction);
}

BackendComponentPrinter::printHead("Benutzerverwaltung");
/* menue */
/* dynamisch erzeugt je nach Rechten */
BackendComponentPrinter::printSidebar(array());// printSidebar($_SESSION["permissions"]);

echo
"<section id='main'>
    <h1><i class='fa fa-user fontawesome'></i> Benutzerverwaltung</h1>
    <table>
        <tr>
            <th>Benutzer</th>
            <th>entsperren/sperren</th>
            <th>Rolle</th>
            <th>Aktion</th>
        </tr>";
            // foreach user in database print
            $userRows = $dbUser.SelectAllUsers();
            $roleRows = $dbUser.SelectAllRoles();
            foreach ($userRows as $row) {

                echo 
                "<tr>".$row['firstname']." ".$row['lastname']."<td>";

                // if user is banned/debanned
                $debanned = $dbUser.IsUserBanned($row['id']);
                if ($debanned)
                {
                    echo "<td>
                    <form method='post' action='Usermanagement.php'>
                    <input id='deban' name='deban' type='button' value='entsperren'>";
                }
                else
                {
                    echo "<td>
                    <form method='post' action='Usermanagement.php'>
                    <input id='ban' name='ban' type='button' value='sperren'>";
                }
                echo
                    "<input id='userId' name='userId' type='hidden' value='".$row['id']."'></form></td>";
                echo 
                    "<td><form action='../lib/BackendComponentPrinter.class.php'> <label>Rolle: <select name='assignedRole'>";
                    foreach ($roleRows as $rolerow) {
                        echo "<option id='".row['id']."'";
                        if ($rolerow['id'] == $row['role'])
                        {
                            echo " selected ";
                        }
                        echo ">".$rolerow['rolename']."</option>"; 
                    }
                echo
                    "<input id='userId' name='userId' type='hidden' value='".$row['id']."'></select></label></form></td>";
                echo
                    "<td>".$row['role_id']."</td>";
                echo
                    "<td><form method='post' action='Usermanagement.php'>"
                    ."<input id='details' name='details' type='button' value='Details'><input id='delete' name='delete' type='button' value='löschen'>"
                    ."<input id='userId' name='userId' type='hidden' value='".$row['id']."'>" 
                    ."</form></td>";
                echo 
                    "</tr>";
            }
echo
    "</table>
    <form method='post' action='Usermanagement.php'>
        <input id='newUser' name='newUser' type='button' value='Neuer Benutzer'>
        <input id='userId' name='userId' type='hidden' value='userId'>
    </form>
    <h2>Rollen definieren</h2>
    <table>
        <tr>
            <th>Rollenname</th>
            <th>Aktion</th>
        </tr>";
            // foreach role in database print
            $roleRows = $dbUser.SelectAllRoles();
            foreach ($roleRows as $row) {
                echo "<tr>";
                echo "<td>";
                echo $row['rolename'];
                echo "</td>";
                echo "<td><form method='post' action='Usermanagement.php'><input id='roleDetails' name='roleDetails' type='button' value='Details'><input id='deleteRole' name='deleteRole' type='button' value='Rolle löschen'></form></td>";
                echo "<input id='roleId' name='roleId' type='hidden' value='".$row['id']."'>";
                echo "</tr>";
            }
echo
    "</section>
    </body>

    </html>";


function EditUser($userId)
{
printHead("Benutzerverwaltung");

/* menue */
/* dynamisch erzeugt je nach Rechten */
require_once 'lib/BackendComponentPrinter.class.php';

BackendComponentPrinter::printSidebar($config['cms_db']['dbuser']);
    echo
    "<section id='main'>
    <h1>Kontodaten bearbeiten</h1>
        <form method='post' action='../lib/BackendComponentPrinter.class.php'>";
    $userRow = $dbUser.SelectUserById($userId);
    echo
            "<label for='userName'>Benutzername</label>
            <input id='userName' name='userName' type='text' value='".$userRow['username']."'>";
    echo
            "<label for='name'>Name</label>
            <input id='name' name='name' type='text' value='".$userRow['lastname']."'>";
    echo    
            "<label for='foreName'>Vorname</label>
            <input id='foreName' name='foreName' type='text' value='".$userRow['firstname']."'>";
    echo
            "<label for='email'>Email</label>
            <input id='email' name='email' type='text' value='".$userRow['email']."'>";
    echo
            "<input id='userId' name='userId' type='hidden' value='".$userId."'>".
            "<input id='applyChanges' name='applyChanges' type='button' value='Änderungen übernehmen'>";
        echo
        "</form>
        <h2>Passwort ändern</h2>
        <form method='post' action='../lib/BackendComponentPrinter.class.php'>";
        echo
            "<label for='currentPassword'>aktuelles Passwort</label>
            <input id='currentPassword' name='currentPassword' type='password'>
            <label for='newPassword'>neues Passwort</label>
            <input id='newPassword' name='newPassword' type='password'>
            <label for='newPasswordRepeat'>neues Passwort bestätigen</label>
            <input id='newPasswordRepeat' name='newPasswordRepeat' type='password'>
            <input id='userId' name='userId' type='hidden' value='".$userId."'>".
            "<input id='applyPasswordChanges' name='applyPasswordChanges' type='button' value='Passwort übernehmen'>";
        echo
        "</form>
    </section>
    </body>

    </html>";
}
function EditRole($roleId)
{
printHead("Benutzerverwaltung");

/* menue */
/* dynamisch erzeugt je nach Rechten */
require_once 'lib/BackendComponentPrinter.class.php';

BackendComponentPrinter::printSidebar($config['cms_db']['dbuser']);
    
    echo
    "<section id='main'>
    <h1>Rolle bearbeiten</h1>";
    $roleRow = $dbUser.SelectRoleById($roleId);
    echo
            "<form method='post' action='Usermanagement.php'>".
            "<input id='userId' name='userId' type='hidden' value='".$roleId."'>".
            "<label for='roleName'>Benutzername</label>
            <input id='roleName' name='roleName' type='checkbox' value='".$roleRow['rolename']."'>";
    echo
            "<label for='uri'>Name</label>
            <input id='uri' name='uri' type='checkbox' value='".$roleRow['uri']."'>";
    echo    
            "<label for='guestbookmanagement'>Vorname</label>
            <input id='guestbookmanagement' name='guestbookmanagement' type='checkbox' value='".$roleRow['guestbookmanagement']."'>";
    echo
            "<label for='usermanagement'>Email</label>
            <input id='usermanagement' name='usermanagement' type='checkbox' value='".$roleRow['usermanagement']."'>";
    echo
            "<label for='pagemanagement'>Email</label>".
            "<input id='pagemanagement' name='pagemanagement' type='checkbox' value='".$roleRow['pagemanagement']."'>";
    echo
            "<label for='articlemanagement'>Email</label>".
            "<input id='articlemanagement' name='articlemanagement' type='checkbox' value='".$roleRow['articlemanagement']."'>";
    echo
            "<label for='guestbookusage'>Email</label>".
            "<input id='guestbookusage' name='guestbookusage' type='checkbox' value='".$roleRow['guestbookusage']."'>";
    echo
            "<label for='templateconstruction'>Email</label>".
            "<input id='templateconstruction' name='templateconstruction' type='checkbox' value='".$roleRow['templateconstruction']."'>".
            "<input id='saveRoleChanges' name='saveRoleChanges' type='button' value'Rollenänderung speichern'></form>";
}
?>
