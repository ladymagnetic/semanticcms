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
    $dbUser->DebanUser($userId);    
}
else if (isset($_POST['ban'])) {
    $userId = $_POST['userId'];
    $dbUser->BanUser($userId);
}
else if (isset($_POST['details'])) {
    $userId = $_POST['userId'];
    EditUser($userId, $dbUser);
    // has to return because other page
    return;
}
else if (isset($_POST['delete'])) {
    $userId = $_POST['userId'];
    $dbUser->DeleteUser($userId);
}
else if (isset($_POST['newUser'])) {
    CreateNewUser($dbUser);
    // has to return because other page
    return;
}
else if (isset($_POST['newRole'])) {   
    CreateNewRole($dbUser);
    // has to return because other page
    return;
}
else if (isset($_POST['assignRole'])) {
    $roleId = $_POST['assignedRole'];
    $userId = $_POST['userId'];
    $dbUser->AssignRole($roleId, $userId);
}
else if (isset($_POST['deleteRole'])) {
    $roleId = $_POST['roleId'];
    $dbUser->DeleteRole($roleId, $dbUser);
}
else if (isset($_POST['roleDetails'])) {
    $roleId = $_POST['roleId'];
    EditRole($roleId, $dbUser);
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
    $dbUser->UpdateUserDifferentNamesById($userId, $userName, $name, $foreName, $email);
}
else if (isset($_POST['applyPasswordChanges']))
{
    $userId = $_POST['userId'];
    $password = $_POST['currentPassword'];
    $newPassword = $_POST['newPassword'];
    $newPasswordRepeat = $_POST['newPasswordRepeat'];
    $dbUser->ApplyPasswordChangesToUser($userId, $password, $newPassword, $newPasswordRepeat);
}
else if (isset($_POST['registrateUser']))
{
    $role_id = $_POST['role'];
    $lastname = $_POST['name'];
    $firstname = $_POST['foreName'];
    $username = $_POST['userName'];
    $password = $_POST['currentPassword'];
    $email = $_POST['email'];
    $birthdate = $_POST['birthdate'];
    $dbUser->RegistrateUser($role_id, $lastname, $firstname, $username, $password, $email, $birthdate);
}
else if (isset($_POST['saveRoleChanges'])) 
{
    $roleId = $_POST['roleId'];
    $rolename = $_POST['rolename'];
    $guestbookmanagement = $_POST['guestbookmanagement'];
    $usermanagement = $_POST['usermanagement'];
    $pagemanagement = $_POST['pagemanagement'];
    $articlemanagement = $_POST['articlemanagement'];
    $guestbookusage = $_POST['guestbookusage'];
    $templateconstruction = $_POST['templateconstruction'];
    $dbUser->UpdateRoleById($roleId, $rolename, $guestbookmanagement, $usermanagement, $pagemanagement, $articlemanagement, $guestbookusage, $templateconstruction);
}
else if (isset($_POST['createRole'])) 
{
    $rolename = $_POST['rolename'];
    $guestbookmanagement = $_POST['guestbookmanagement'];
    $usermanagement = $_POST['usermanagement'];
    $pagemanagement = $_POST['pagemanagement'];
    $articlemanagement = $_POST['articlemanagement'];
    $guestbookusage = $_POST['guestbookusage'];
    $templateconstruction = $_POST['templateconstruction'];
    $uri = $_POST['uri'];
    $dbUser->NewRole($uri, $rolename, $guestbookmanagement, $usermanagement, $pagemanagement, $articlemanagement, $guestbookusage, $templateconstruction);
}

BackendComponentPrinter::PrintHead("Benutzerverwaltung");
/* menue */
/* dynamisch erzeugt je nach Rechten */
BackendComponentPrinter::PrintSidebar(array());// PrintSidebar($_SESSION["permissions"]);

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
            $userRows = $dbUser->SelectAllUsers();
            while ($row = $dbUser->FetchArray($userRows))
            {
                echo 
                "<tr><td>".$row['firstname']." ".$row['lastname']."</td>";

                // if user is banned/debanned
                //$banned = $dbUser->IsUserBanned($row['id']);
                $banned = false;
                if ($banned)
                {
                    echo "<td>
                    <form method='post' action='Usermanagement.php'>
                    <input id='deban' name='deban' type='submit' value='entsperren'>";
                }
                else
                {
                    echo "<td>
                    <form method='post' action='Usermanagement.php'>
                    <input id='ban' name='ban' type='submit' value='sperren'>";
                }
                echo
                    "<input id='userId' name='userId' type='hidden' value='".$row['id']."'></form></td>";
                echo 
                    "<td><form action='Usermanagement.php'> <label>Rolle: <select name='assignedRole'>";
                    $roleRows = $dbUser->SelectAllRoles();
                    while ($rolerow = $dbUser->FetchArray($roleRows))
                    {
                        echo "<option id='".row['id']."'";
                        if ($rolerow['id'] == $row['role_id'])
                        {
                            echo " selected ";
                        }
                        echo ">".$rolerow['rolename']."</option>";
                    }
                echo
                    "<input id='userId' name='userId' type='hidden' value='".$row['id']."'></select></label><br><br><input type='submit' value='Zuweisen'></form></td>";
                echo
                    "<td><form method='post' action='Usermanagement.php'>"
                    ."<input id='details' name='details' type='submit' value='Details'><input id='delete' name='delete' type='submit' value='löschen'>"
                    ."<input id='userId' name='userId' type='hidden' value='".$row['id']."'>" 
                    ."</form></td>";
                echo 
                    "</tr>";
            }
echo
    "</table>
    <form method='post' action='Usermanagement.php'>
        <input id='newUser' name='newUser' type='submit' value='Neuer Benutzer'>
    </form>
    <h2>Rollen definieren</h2>
    <table>
        <tr>
            <th>Rollenname</th>
            <th>Aktion</th>
        </tr>";
            // foreach role in database print
            $roleRows = $dbUser->SelectAllRoles();
            while ($row = $dbUser->FetchArray($roleRows))
            {
                echo "<tr>";
                echo "<td>";
                echo $row['rolename'];
                echo "</td>";
                echo "<td><form method='post' action='Usermanagement.php'><input id='roleDetails' name='roleDetails' type='submit' value='Details'><input id='deleteRole' name='deleteRole' type='submit' value='Rolle löschen'>";
                echo "<input id='roleId' name='roleId' type='hidden' value='".$row['id']."'></form></td>";
                echo "</tr>";
                $row = $dbUser->SelectAllRoles();
            }
echo
    "</table>
    <form method='post' action='Usermanagement.php'>
        <input id='newRole' name='newRole' type='submit' value='Neue Rolle'>
    </form>
    </section>
    </body>

    </html>";


function EditUser($userId, $dbUser)
{
    BackendComponentPrinter::PrintHead("Benutzerverwaltung");
    /* menue */
    /* dynamisch erzeugt je nach Rechten */
    BackendComponentPrinter::PrintSidebar(array());// PrintSidebar($_SESSION["permissions"]);
    echo
    "<section id='main'>
    <h1>Kontodaten bearbeiten</h1>
        <form method='post' action='Usermanagement.php'>";
    $userRow = $dbUser->FetchArray($dbUser->GetUserInformationById($userId));
    echo
            "<label for='userName'>Benutzername</label>
            <input id='userName' name='userName' type='text' value='".$userRow['username']."'><br><br>";
    echo
            "<label for='name'>Name</label>
            <input id='name' name='name' type='text' value='".$userRow['lastname']."'><br><br>";
    echo    
            "<label for='foreName'>Vorname</label>
            <input id='foreName' name='foreName' type='text' value='".$userRow['firstname']."'><br><br>";
    echo
            "<label for='email'>Email</label>
            <input id='email' name='email' type='text' value='".$userRow['email']."'><br><br>";
    echo
            "<input id='userId' name='userId' type='hidden' value='".$userId."'>".
            "<input id='applyChanges' name='applyChanges' type='submit' value='Änderungen übernehmen'>";
        echo
        "</form>
        <h2>Passwort ändern</h2>
        <form method='post' action='../lib/BackendComponentPrinter.class.php'>";
        echo
            "<label for='currentPassword'>aktuelles Passwort</label>
            <input id='currentPassword' name='currentPassword' type='password'><br><br>
            <label for='newPassword'>neues Passwort</label>
            <input id='newPassword' name='newPassword' type='password'><br><br>
            <label for='newPasswordRepeat'>neues Passwort bestätigen</label>
            <input id='newPasswordRepeat' name='newPasswordRepeat' type='password'><br><br>
            <input id='userId' name='userId' type='hidden' value='".$userId."'>".
            "<input id='applyPasswordChanges' name='applyPasswordChanges' type='submit' value='Passwort übernehmen'>";
        echo
        "</form>
    </section>
    </body>

    </html>";
}
function EditRole($roleId, $dbUser)
{
    BackendComponentPrinter::PrintHead("Benutzerverwaltung");
    /* menue */
    /* dynamisch erzeugt je nach Rechten */
    BackendComponentPrinter::PrintSidebar(array());// PrintSidebar($_SESSION["permissions"]);
    
    echo
    "<section id='main'>
    <h1>Rolle bearbeiten</h1>";
    $roleRow = $dbUser->FetchArray($dbUser->SelectRoleById($roleId));
    echo
            "<form method='post' action='Usermanagement.php'>".
            "<input id='userId' name='userId' type='hidden' value='".$roleId."'>".
            "<label for='roleName'>Rollenname</label>
            <input id='roleName' name='roleName' type='checkbox' value='".$roleRow['rolename']."'><br><br>";
    echo
            "<label for='uri'>Uri</label>
            <input id='uri' name='uri' type='checkbox' value='".$roleRow['uri']."'><br><br>";
    echo    
            "<label for='guestbookmanagement'>Gästebuch verwalten</label>
            <input id='guestbookmanagement' name='guestbookmanagement' type='checkbox' value='".$roleRow['guestbookmanagement']."'><br><br>";
    echo
            "<label for='usermanagement'>Benutzer verwalten</label>
            <input id='usermanagement' name='usermanagement' type='checkbox' value='".$roleRow['usermanagement']."'><br><br>";
    echo
            "<label for='pagemanagement'>Seiten verwalten</label>".
            "<input id='pagemanagement' name='pagemanagement' type='checkbox' value='".$roleRow['pagemanagement']."'><br><br>";
    echo
            "<label for='articlemanagement'>Artikel verwalten</label>".
            "<input id='articlemanagement' name='articlemanagement' type='checkbox' value='".$roleRow['articlemanagement']."'><br><br>";
    echo
            "<label for='guestbookusage'>Gästebuch nutzen</label>".
            "<input id='guestbookusage' name='guestbookusage' type='checkbox' value='".$roleRow['guestbookusage']."'><br><br>";
    echo
            "<label for='templateconstruction'>Template erstellen</label>".
            "<input id='templateconstruction' name='templateconstruction' type='checkbox' value='".$roleRow['templateconstruction']."'><br><br>".
            "<input id='saveRoleChanges' name='saveRoleChanges' type='submit' value'Rollenänderung speichern'></form>";
}
function CreateNewUser($dbUser)
{
    BackendComponentPrinter::PrintHead("Benutzerverwaltung");
    /* menue */
    /* dynamisch erzeugt je nach Rechten */
    BackendComponentPrinter::PrintSidebar(array());// PrintSidebar($_SESSION["permissions"]);
    echo
    "<section id='main'>
    <h1>Neuer Benutzer</h1>
        <form method='post' action='Usermanagement.php'>";
    echo
            "<label for='userName'>Benutzername</label>
            <input id='userName' name='userName' type='text'><br><br>";
    echo
            "<label for='name'>Name</label>
            <input id='name' name='name' type='text'><br><br>";
    echo    
            "<label for='foreName'>Vorname</label>
            <input id='foreName' name='foreName' type='text'><br><br>";
    echo
            "<label for='email'>Email</label>
            <input id='email' name='email' type='text'><br><br>";
            "<label for='currentPassword'>aktuelles Passwort</label>
            <input id='currentPassword' name='currentPassword' type='password'><br><br>";
    echo
            "<label>Rolle: <select name='role'>";
    $roleRows = $dbUser->SelectAllRoles();
    while ($rolerow = $dbUser->FetchArray($roleRows))
    {
        echo "<option id='".row['id']."'";
        echo ">".$rolerow['rolename']."</option>";
    }
    echo
            "</select></label><br><br>";
    echo
            "<label for='birthdate'>Geburtsdatum</label>
            <input type='date' name='birthdate' id='birthdate'><br><br>";
    echo
            "<input id='registrateUser' name='registrateUser' type='submit' value='Anwender erstellen'>";

    echo
        "</form></section></body></html>";
}
function CreateNewRole($dbUser)
{
    BackendComponentPrinter::PrintHead("Benutzerverwaltung");
    /* menue */
    /* dynamisch erzeugt je nach Rechten */
    BackendComponentPrinter::PrintSidebar(array());// PrintSidebar($_SESSION["permissions"]);
    
    echo
    "<section id='main'>
    <h1>Rolle bearbeiten</h1>";
    echo
            "<form method='post' action='Usermanagement.php'>".
            "<label for='roleName'>Rollenname</label>
            <input id='roleName' name='roleName' type='checkbox'><br><br>";
    echo
            "<label for='uri'>Uri</label>
            <input id='uri' name='uri' type='checkbox'><br><br>";
    echo    
            "<label for='guestbookmanagement'>Gästebuch verwalten</label>
            <input id='guestbookmanagement' name='guestbookmanagement' type='checkbox'><br><br>";
    echo
            "<label for='usermanagement'>Benutzer verwalten</label>
            <input id='usermanagement' name='usermanagement' type='checkbox'><br><br>";
    echo
            "<label for='pagemanagement'>Seiten verwalten</label>".
            "<input id='pagemanagement' name='pagemanagement' type='checkbox'><br><br>";
    echo
            "<label for='articlemanagement'>Artikel verwalten</label>".
            "<input id='articlemanagement' name='articlemanagement' type='checkbox'><br><br>";
    echo
            "<label for='guestbookusage'>Gästebuch nutzen</label>".
            "<input id='guestbookusage' name='guestbookusage' type='checkbox'><br><br>";
    echo
            "<label for='templateconstruction'>Template erstellen</label>".
            "<input id='templateconstruction' name='templateconstruction' type='checkbox'><br><br>".
            "<input id='createRole' name='createRole' type='submit' value'Rolle erstellen'></form>";
}
?>
