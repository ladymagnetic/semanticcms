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
    $banId = $_POST['banId'];
    $dbUser->DebanUserViaBanId($banId);    
}
else if (isset($_POST['ban'])) {
    $userId = $_POST['userId'];
    BanUser($userId, $dbUser);
    // has to return because other page
    return;
}
else if (isset($_POST['banUser']))
{
    $user_id = $_POST['userId']; 
    $reason_id = $_POST['reasonId']; 
    $description = $_POST['description']; 
    $begindatetime = $_POST['begindatetime'];
    $enddatetime = $_POST['enddatetime'];
    $dbUser->InsertBanViaUserId($user_id, $reason_id, $description, $begindatetime, $enddatetime);
}
else if (isset($_POST['details'])) {
    $userId = $_POST['userId'];
    EditUser($userId, $dbUser);
    // has to return because other page
    return;
}
else if (isset($_POST['delete'])) {
    $userId = $_POST['userId'];
    $dbUser->DeleteUserById($userId);
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
    $id = $_POST['roleId'];
    $rolename = $_POST['roleName'];
    $uri = $_POST['uri'];
    SetPermissionsFromForm($guestbookmanagement, $usermanagement, $pagemanagement, $articlemanagement, $guestbookusage, $templateconstruction);
    $dbUser->UpdateRoleById($uri, $rolename, $guestbookmanagement, $usermanagement, $pagemanagement, $articlemanagement, $guestbookusage, $templateconstruction, $id);
}
else if (isset($_POST['createRole'])) 
{
    $rolename = $_POST['rolename'];
    $uri = $_POST['uri'];
    SetPermissionsFromForm($guestbookmanagement, $usermanagement, $pagemanagement, $articlemanagement, $guestbookusage, $templateconstruction);
    $dbUser->NewRole($uri, $rolename, $guestbookmanagement, $usermanagement, $pagemanagement, $articlemanagement, $guestbookusage, $templateconstruction);
}

BackendComponentPrinter::PrintHead("Benutzerverwaltung");
/* menue */
/* dynamisch erzeugt je nach Rechten */
BackendComponentPrinter::PrintSidebar(array());// PrintSidebar($_SESSION["permissions"]);

echo
"<section id='main'>
    <h1><i class='fa fa-user fontawesome'></i> Benutzerverwaltung</h1>";
BackendComponentPrinter::PrintTableStart(array("Benutzer", "entsperren/sperren", "Rolle", "Aktion"));
// foreach user in database print
$userRows = $dbUser->SelectAllUsers();
while ($row = $dbUser->FetchArray($userRows))
{
    echo 
    "<tr><td>".$row['firstname']." ".$row['lastname']."</td>";

    //if user is banned/debanned
    $bannedUsers = $dbUser->SelectAllUsersWhichAreBannedNow();
    $banned = false;
    while ($bannedUser = $dbUser->FetchArray($bannedUsers))
    {
        if ($bannedUser['id'] == $row['id'])
        {
            if (!$banned)
            {
                echo "<td>";
            }
            $banned = true;
            $reason = "";
            $banRow = $dbUser->FetchArray($dbUser->SelectBanByUserid($row['id']));
            $reasonRows = $dbUser->SelectAllBan_Reason();
            while ($reasonRow = $dbUser->FetchArray($reasonRows))
            {
                if ($reasonRow['id'] == $banRow['reason_id'])
                {
                    $reason = $reasonRow['reason'];
                }
            }
            echo
            "<form method='post' action='Usermanagement.php'>
            <input id='banId' name='banId' type='hidden' value='".$banRow['id']."'>".
            "<p>".$reason."<p>".
            "<input id='deban' name='deban' type='submit' value='entsperren'>";
            if ($banned)
            {
                echo "<br>";
            }
        }
    }
    if (!$banned)
    {
        echo "<td>".
        "<form method='post' action='Usermanagement.php'>
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
BackendComponentPrinter::PrintTableEnd();
echo
        "<form method='post' action='Usermanagement.php'>
            <input id='newUser' name='newUser' type='submit' value='Neuer Benutzer'>
        </form>
        <h2><i class='fa fa-key fontawesome'></i> Rollen definieren</h2>";
BackendComponentPrinter::PrintTableStart(array("Rollenname", "Aktion"));
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
BackendComponentPrinter::PrintTableEnd();
echo
        "<form method='post' action='Usermanagement.php'>
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
            <h1><i class='fa fa-user fontawesome'></i> Benutzer bearbeiten</h1>
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
            <form method='post' action='Usermanagement.php'>";
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
            <h1><i class='fa fa-key fontawesome'></i> Rolle bearbeiten</h1>";
    $roleRow = $dbUser->FetchArray($dbUser->SelectRoleById($roleId));
    echo
            "<form method='post' action='Usermanagement.php'>".
            "<input id='roleId' name='roleId' type='hidden' value='".$roleId."'>".
            "<label for='roleName'>Rollenname</label>
            <input id='roleName' name='roleName' type='text' value='".$roleRow['rolename']."'><br><br>";
    echo
            "<label for='uri'>Uri</label>
            <input id='uri' name='uri' type='text' value='".$roleRow['uri']."'><br><br>";
    echo    
            "<label for='guestbookmanagement'>Gästebuch verwalten</label>
            <input id='guestbookmanagement' name='guestbookmanagement' type='checkbox'";
            if (boolval($roleRow['guestbookmanagement']))
            {
                echo " checked";
            }
            echo
            " value='".$roleRow['guestbookmanagement']."'><br><br>";
    echo
            "<label for='usermanagement'>Benutzer verwalten</label>
            <input id='usermanagement' name='usermanagement' type='checkbox'";
            if (boolval($roleRow['usermanagement']))
            {
                echo " checked";
            }
            echo
            " value='".$roleRow['usermanagement']."'><br><br>";
    echo
            "<label for='pagemanagement'>Seiten verwalten</label>".
            "<input id='pagemanagement' name='pagemanagement' type='checkbox'";
            if (boolval($roleRow['pagemanagement']))
            {
                echo " checked";
            }
            echo
            " value='".$roleRow['pagemanagement']."'><br><br>";
    echo
            "<label for='articlemanagement'>Artikel verwalten</label>".
            "<input id='articlemanagement' name='articlemanagement' type='checkbox'"; 
            if (boolval($roleRow['articlemanagement']))
            {
                echo " checked";
            }
            echo
            " value='".$roleRow['articlemanagement']."'><br><br>";
    echo
            "<label for='guestbookusage'>Gästebuch nutzen</label>".
            "<input id='guestbookusage' name='guestbookusage' type='checkbox'";
            if (boolval($roleRow['guestbookusage']))
            {
                echo " checked";
            }
            echo
            " value='".$roleRow['guestbookusage']."'><br><br>";
    echo
            "<label for='templateconstruction'>Template erstellen</label>".
            "<input id='templateconstruction' name='templateconstruction' type='checkbox'";
            if (boolval($roleRow['templateconstruction']))
            {
                echo " checked";
            }
            echo
            " value='".$roleRow['templateconstruction']."'><br><br>".
            "<input id='saveRoleChanges' name='saveRoleChanges' type='submit' value='Rollenänderung speichern'></form>";
}
function CreateNewUser($dbUser)
{
    BackendComponentPrinter::PrintHead("Benutzerverwaltung");
    /* menue */
    /* dynamisch erzeugt je nach Rechten */
    BackendComponentPrinter::PrintSidebar(array());// PrintSidebar($_SESSION["permissions"]);
    echo
            "<section id='main'>
            <h1><i class='fa fa-user fontawesome'></i> Neuer Benutzer</h1>
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
    echo
            "<label for='currentPassword'>aktuelles Passwort</label>
            <input id='currentPassword' name='currentPassword' type='password'><br><br>";
    echo
            "<label for='currentPassword'>Rolle</label>".
            "<select name='role'>";
    $roleRows = $dbUser->SelectAllRoles();
    while ($rolerow = $dbUser->FetchArray($roleRows))
    {
        echo "<option id='".$rolerow['id']."'";
        echo ">".$rolerow['rolename']."</option>";
    }
    echo
            "</select><br><br>";
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
            <h1><i class='fa fa-key fontawesome'></i> Rolle erstellen</h1>";
    echo
            "<form method='post' action='Usermanagement.php'>".
            "<label for='rolename'>Rollenname</label>
            <input id='rolename' name='rolename' type='text'><br><br>";
    echo
            "<label for='uri'>Uri</label>
            <input id='uri' name='uri' type='text'><br><br>";
    echo    
            "<label for='guestbookmanagement'>Gästebuch verwalten</label>
            <input id='guestbookmanagement' name='guestbookmanagement' type='checkbox' value='1'><br><br>";
    echo
            "<label for='usermanagement'>Benutzer verwalten</label>
            <input id='usermanagement' name='usermanagement' type='checkbox' value='1'><br><br>";
    echo
            "<label for='pagemanagement'>Seiten verwalten</label>".
            "<input id='pagemanagement' name='pagemanagement' type='checkbox' value='1'><br><br>";
    echo
            "<label for='articlemanagement'>Artikel verwalten</label>".
            "<input id='articlemanagement' name='articlemanagement' type='checkbox' value='1'><br><br>";
    echo
            "<label for='guestbookusage'>Gästebuch nutzen</label>".
            "<input id='guestbookusage' name='guestbookusage' type='checkbox' value='1'><br><br>";
    echo
            "<label for='templateconstruction'>Template erstellen</label>".
            "<input id='templateconstruction' name='templateconstruction' type='checkbox' value='1'><br><br>".
            "<input id='createRole' name='createRole' type='submit' value='Rolle erstellen'></form>";
}

function BanUser($userId, $dbUser)
{
    BackendComponentPrinter::PrintHead("Benutzerverwaltung");
    /* menue */
    /* dynamisch erzeugt je nach Rechten */
    BackendComponentPrinter::PrintSidebar(array());// PrintSidebar($_SESSION["permissions"]);
    
    echo
            "<section id='main'>
            <h1><i class='fa fa-ban'></i> Sperrung</h1>";
    echo
            "<form method='post' action='Usermanagement.php'>".
            "<input id='userId' name='userId' type='hidden' value='".$userId."'><br><br>";
    echo
            "<label for='reasonId'>Grund</label>";
    echo

            "<select name='reasonId'>";
            $reasonRows = $dbUser->SelectAllBan_Reason();
            while ($reasonRow = $dbUser->FetchArray($reasonRows))
            {
                echo "<option id='".reasonRow['id']."'";
                echo ">".$reasonRow['reason']."</option>";
            }
    echo
            "</select><br><br>";
    echo
            "<label for='description'>Beschreibung</label>".
            "<input id='description' name='description' type='text'><br><br>";
    echo
            "<label for='begindatetime'>Startdatum</label>".
            "<input id='begindatetime' name='begindatetime' type='date'><br><br>".
            "<label for='enddatetime'>Enddatum</label>".
            "<input id='enddatetime' name='enddatetime' type='date'><br><br>".
            "<input id='banUser' name='banUser' type='submit' value='Sperrung erstellen'></form>";
}
// call by reference --> &
function SetPermissionsFromForm(&$guestbookmanagement, &$usermanagement, &$pagemanagement, &$articlemanagement, &$guestbookusage, &$templateconstruction)
{
    if (isset($_POST['guestbookmanagement']))
    {
        $guestbookmanagement = 1;
    }
    else 
    {
        $guestbookmanagement = 0;
    }
    if (isset($_POST['usermanagement']))
    {
        $usermanagement = 1;
    }
    else 
    {
        $usermanagement = 0;
    }
    if (isset($_POST['pagemanagement']))
    {
        $pagemanagement = 1;
    }
    else 
    {
        $pagemanagement = 0;
    }
    if (isset($_POST['articlemanagement']))
    {
        $articlemanagement = 1;
    }
    else 
    {
        $articlemanagement = 0;
    }
    if (isset($_POST['guestbookusage']))
    {
        $guestbookusage = 1;
    }
    else 
    {
        $guestbookusage = 0;
    }
    if (isset($_POST['templateconstruction']))
    {
        $templateconstruction = 1;
    }
    else 
    {
        $templateconstruction = 0;
    }
}
?>
