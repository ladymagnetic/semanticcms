<?php
// Start the session
session_start();
/* Include(s) */
require_once 'lib/DbEngine.class.php';
require_once 'lib/BackendComponentPrinter.class.php';
require_once 'config/config.php';
require_once 'lib/DbUser.class.php';
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
// if submit button with name 'deban' is pressed
if (isset($_POST['deban'])) {
    $banId = intval($_POST['banId']);
    $dbUser->DebanUserViaBanId($banId);    
}
// if submit button with name 'ban' is pressed
else if (isset($_POST['ban'])) {
    $userId = intval($_POST['userId']);
    BanUser($userId, $dbUser);
    // has to return because other page
    return;
}
// if submit button with name 'banUser' is pressed
else if (isset($_POST['banUser']))
{
    $user_id = intval($_POST['userId']); 
    $reason_id = intval($_POST['reasonId']); 
    $description = $_POST['description']; 
    $begindatetime = $_POST['begindatetime'];
    $enddatetime = $_POST['enddatetime'];
    $dbUser->InsertBanViaUserId($user_id, $reason_id, $description, $begindatetime, $enddatetime);
}
// if submit button with name 'details' is pressed
else if (isset($_POST['details'])) {
    $userId = intval($_POST['userId']);
    EditUser($userId, $dbUser);
    // has to return because other page
    return;
}
// if submit button with name 'delete' is pressed
else if (isset($_POST['delete'])) {
    $userId = intval($_POST['userId']);
    ReallyDelete($userId);
    // has to return because other page
    return;
}
// if submit button with name 'reallydelete' is pressed
else if (isset($_POST['reallyDelete'])) {
    $userId = intval($_POST['userId']);

    $dbUser->DeleteUserById($userId);
}
// if submit button with name 'newUser' is pressed
else if (isset($_POST['newUser'])) {
    CreateNewUser($dbUser);
    // has to return because other page
    return;
}
// if submit button with name 'newRole' is pressed
else if (isset($_POST['newRole'])) {   
    CreateNewRole($dbUser);
    // has to return because other page
    return;
}
// if submit button with name 'assignRole' is pressed
else if (isset($_POST['assignRole'])) {
    $roleId = intval($dbUser->FetchArray($dbUser->SelectRoleByRolename($_POST['assignedRole']))['id']);
    $userId = intval($_POST['userId']);
    $dbUser->AssignRole($roleId, $userId);
}
// if submit button with name 'deleteRole' is pressed
else if (isset($_POST['deleteRole'])) {
    $roleId = intval($_POST['roleId']);
    ReallyDeleteRole($roleId);
    // has to return because other page
    return;
}
// if submit button with name 'reallydelete' is pressed
else if (isset($_POST['reallyDeleteRole'])) {
    $roleId = intval($_POST['roleId']);

    $dbUser->DeleteRole($roleId, $dbUser);
}
// if submit button with name 'roleDetails' is pressed
else if (isset($_POST['roleDetails'])) {
    $roleId = intval($_POST['roleId']);
    EditRole($roleId, $dbUser);
    // has to return because other page
    return;
}
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
    if ($dbUser->EmailAlreadyExists($email))
    {
        echo
        /* Bootstrap temporary for nice alert */
            "<!-- Latest compiled and minified CSS -->
            <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css' integrity='sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u' crossorigin='anonymous'>
            <!-- Optional theme -->
            <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css' integrity='sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp' crossorigin='anonymous'>
            <!-- Latest compiled and minified JavaScript -->
            <script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js' integrity='sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa' crossorigin='anonymous'></script>";
        CreateNewUser($dbUser);
        echo
        "<div class='alert alert-danger warning'>
        <strong>Warnung!</strong> Email existiert bereits.
        </div";
        return;
    }
    else if ($dbUser->UsernameAlreadyExists($username))
    {
        echo
        /* Bootstrap temporary for nice alert */
            "<!-- Latest compiled and minified CSS -->
            <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css' integrity='sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u' crossorigin='anonymous'>
            <!-- Optional theme -->
            <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css' integrity='sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp' crossorigin='anonymous'>
            <!-- Latest compiled and minified JavaScript -->
            <script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js' integrity='sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa' crossorigin='anonymous'></script>";
        CreateNewUser($dbUser);
        echo
        "<div class='alert alert-danger warning'>
        <strong>Warnung!</strong> Benutzername existiert bereits.
        </div";
        return;
    }
    // check if valid mail
    else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo
        /* Bootstrap temporary for nice alert */
            "<!-- Latest compiled and minified CSS -->
            <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css' integrity='sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u' crossorigin='anonymous'>
            <!-- Optional theme -->
            <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css' integrity='sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp' crossorigin='anonymous'>
            <!-- Latest compiled and minified JavaScript -->
            <script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js' integrity='sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa' crossorigin='anonymous'></script>";
        CreateNewUser($dbUser);
        echo
        "<div class='alert alert-danger warning'>
        <strong>Warnung!</strong> Invalide email.
        </div";
        return;
    }
    // check if username is only characters, numbers and _
    if (!preg_match('/^[a-zA-Z0-9_]{1,}$/',$username)) {
        echo
        /* Bootstrap temporary for nice alert */
            "<!-- Latest compiled and minified CSS -->
            <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css' integrity='sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u' crossorigin='anonymous'>
            <!-- Optional theme -->
            <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css' integrity='sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp' crossorigin='anonymous'>
            <!-- Latest compiled and minified JavaScript -->
            <script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js' integrity='sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa' crossorigin='anonymous'></script>";
        CreateNewUser($dbUser);
        echo
        "<div class='alert alert-danger warning'>
        <strong>Warnung!</strong> Der Benutzername darf nur Buchstaben, Zahlen und Unterstriche enthalten.
        </div";
        return;
    }
    else
    {
        $dbUser->RegistrateUser($role_id, $lastname, $firstname, $username, $password, $email, $birthdate);
    }
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
// if submit button with name 'createRole' is pressed
else if (isset($_POST['createRole'])) 
{
    $rolename = $_POST['rolename'];
    $uri = $_POST['uri'];
    SetPermissionsFromForm($guestbookmanagement, $usermanagement, $pagemanagement, $articlemanagement, $guestbookusage, $templateconstruction);
    $dbUser->NewRole($uri, $rolename, $guestbookmanagement, $usermanagement, $pagemanagement, $articlemanagement, $guestbookusage, $templateconstruction);
}

BackendComponentPrinter::PrintHead("Benutzerverwaltung", $jquery=true);
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
    else if(!in_array(Permission::Usermanagment, $_SESSION['permissions']))
    {
        die($config['error']['permissionMissing']);  	  
    }
    		
    BackendComponentPrinter::PrintSidebar($_SESSION['permissions']);
    //*----- Permissions End ----- */

/* Datatables */
BackendComponentPrinter::PrintDatatablesPlugin();

echo
"<main>
    <h1><i class='fa fa-users fontawesome'></i> Benutzerverwaltung</h1>";
BackendComponentPrinter::PrintTableStart(array("Benutzer", "entsperren/sperren", "Rolle", "Aktion"));
// foreach user in database print
$userRows = $dbUser->SelectAllUsers();
while ($row = $dbUser->FetchArray($userRows))
{
    $tableRow1 = $row['username']."<br>".$row['firstname']." ".$row['lastname'];

    //if user is banned/debanned
    $bannedUsers = $dbUser->SelectAllUsersWhichAreBannedNow();
    $banned = false;
    while ($bannedUser = $dbUser->FetchArray($bannedUsers))
    {
        if ($bannedUser['id'] == $row['id'])
        {
            $reason = "";
            $banRows = $dbUser->SelectBanByUserid($row['id']);
            while ($banRow = $dbUser->FetchArray($banRows))
            {
                $reasonRows = $dbUser->SelectAllBan_Reason();
                while ($reasonRow = $dbUser->FetchArray($reasonRows))
                {
                    if ($reasonRow['id'] == $banRow['reason_id'])
                    {
                        $reason = $reasonRow['reason'];
                    }
                }
                // if banenddate is later than now
                $todays_date = date("Y-m-d");
                $exp_date = $banRow['enddatetime'];
                $today = strtotime($todays_date);
                $expiration_date = strtotime($exp_date);
                if ($expiration_date > $today) 
                {
                    if (!$banned)
                    {
                        $banned = true;
                        $tableRow2 = 
                        "<form method='post' action='Usermanagement.php'>
                        <input id='banId' name='banId' type='hidden' value='".$banRow['id']."'>".
                        "<p>".$reason."<p>".
                        "<input id='deban' name='deban' type='submit' value='entsperren'></form>";
                        $tableRow2 .=  "<br>";
                    }
                    else
                    {
                        $tableRow2 .= 
                        "<form method='post' action='Usermanagement.php'>
                        <input id='banId' name='banId' type='hidden' value='".$banRow['id']."'>".
                        "<p>".$reason."<p>".
                        "<input id='deban' name='deban' type='submit' value='entsperren'></form";
                        $tableRow2 .=  "<br>";
                    }
                }
            }
            break;
        }
    }
    if (!$banned)
    {
        $tableRow2 =
        "<form method='post' action='Usermanagement.php'>
        <input id='ban' name='ban' type='submit' value='sperren'>";
    }
    $tableRow2 .=
        "<input id='userId' name='userId' type='hidden' value='".$row['id']."'></form>";
    $tableRow3 = 
        "<form action='Usermanagement.php' method='post'> <label>Rolle: <select name='assignedRole'>".
        "<option></option>";
        $roleRows = $dbUser->SelectAllRoles();
        while ($rolerow = $dbUser->FetchArray($roleRows))
        {
            $tableRow3 .= "<option id='".$rolerow['id']."'";
            if ($rolerow['id'] == $row['role_id'])
            {
                $tableRow3 .= " selected ";
            }
            $tableRow3 .= ">".$rolerow['rolename']."</option>";
        }
    $tableRow3 .=
        "<input id='userId' name='userId' type='hidden' value='".$row['id']."'></select></label><br><br><input id='assignRole' name='assignRole' type='submit' value='Zuweisen'></form>";
    $tableRow4 =
        "<form method='post' action='Usermanagement.php'>"
        ."<input id='details' name='details' type='submit' value='Details'><input id='delete' name='delete' type='submit' value='löschen'>"
        ."<input id='userId' name='userId' type='hidden' value='".$row['id']."'>" 
        ."</form>";
    BackendComponentPrinter::PrintTableRow(array($tableRow1, $tableRow2, $tableRow3, $tableRow4));
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
    $tableRow1 =  $row['rolename'];
    $tableRow2 = 
        "<form method='post' action='Usermanagement.php'><input id='roleDetails' name='roleDetails' type='submit' value='Details'><input id='deleteRole' name='deleteRole' type='submit' value='Rolle löschen'>".
        "<input id='roleId' name='roleId' type='hidden' value='".$row['id']."'></form>";
    BackendComponentPrinter::PrintTableRow(array($tableRow1, $tableRow2));
}
BackendComponentPrinter::PrintTableEnd();
echo
        "<form method='post' action='Usermanagement.php'>
            <input id='newRole' name='newRole' type='submit' value='Neue Rolle'>
        </form>
        </main>
        </body>

        </html>";

/**
* Opens the page for the details of the user
*
*/
function EditUser($userId, $dbUser)
{
    BackendComponentPrinter::PrintHead("Benutzerverwaltung", $jquery=true);
    //*----- Permissions ----- */
    /* Include(s) */
    require_once 'lib/Permission.enum.php';
    require_once 'config/config.php';
    		
    BackendComponentPrinter::PrintSidebar($_SESSION['permissions']);
    //*----- Permissions End ----- */
    
    /* Datatables */
    BackendComponentPrinter::PrintDatatablesPlugin();
    
    echo
            "<main>
            <h1><i class='fa fa-users fontawesome'></i> Benutzerdetails</h1>
                <form method='post' action='Usermanagement.php'>";
    $userRow = $dbUser->FetchArray($dbUser->GetUserInformationById($userId));
    echo
            "<label for='userName'>Benutzername</label>
            <input readonly id='userName' name='userName' type='text' value='".$userRow['username']."'><br><br>";
    echo
            "<label for='name'>Name</label>
            <input readonly id='name' name='name' type='text' value='".$userRow['lastname']."'><br><br>";
    echo    
            "<label for='foreName'>Vorname</label>
            <input readonly id='foreName' name='foreName' type='text' value='".$userRow['firstname']."'><br><br>";
    echo
            "<label for='email'>Email</label>
            <input readonly id='email' name='email' type='text' value='".$userRow['email']."'><br><br>";
    echo
        "<label for='birthdate'>Geburtsdatum</label>
        <input readonly type='text' name='birthdate' id='birthdate' value='".$userRow['birthdate']."'><br><br>";
    // not editable
    echo
            "<label for='registrydate'>Registrierungsdatum</label>
            <input readonly type='text' name='registrydate' id='registrydate' value='".$userRow['registrydate']."'><br><br>".
            "<label for='role'>Rolle</label>";
    $roleRows = $dbUser->SelectAllRoles();
    $roleAssigned = false;
    while ($roleRow = $dbUser->FetchArray($roleRows))
    {
        if ($roleRow['id'] == $userRow['role_id'])
        {
            echo
                "<input readonly id='role' name='role' type='text' value='".$roleRow['rolename']."'><br><br>";
            $roleAssigned = true;
        }
    }
    if (!$roleAssigned)
    {
         echo
                "<input readonly id='role' name='role' type='text' value='"."keine zugewiesen"."'><br><br>";
    }
    echo
            "<input id='userId' name='userId' type='hidden' value='".$userId."'>";
    echo
            "</form>";
    echo
            "<form method='post' action='Usermanagement.php'><input id='back' name='back' type='submit' value='Zurück'><form>";
    echo
        "<h1><i class='fa fa-ban'></i> Sperrungen</h1>";
    BackendComponentPrinter::PrintTableStart(array("Grund", "Beschreibung", "Beginndatum", "Enddatum"));
    // foreach user in database print
    $banRows = $dbUser->SelectAllBansFromAUserByUsername($userRow['username']);
    while ($banRow = $dbUser->FetchArray($banRows))
    {
        $reasonRows = $dbUser->SelectAllBan_Reason();
        while ($reasonRow = $dbUser->FetchArray($reasonRows))
        {
            if ($reasonRow['id'] == $banRow['reason_id'])
            {
                BackendComponentPrinter::PrintTableRow(array($reasonRow['reason'], $banRow['description'], $banRow['begindatetime'], $banRow['enddatetime']));
            }
        }
    }
    BackendComponentPrinter::PrintTableEnd();
    echo
            "</main>
            </body>

            </html>";
}
/**
* Opens the page for editing the role
*
*/
function EditRole($roleId, $dbUser)
{
    BackendComponentPrinter::PrintHead("Benutzerverwaltung");
    //*----- Permissions ----- */
    /* Include(s) */
    require_once 'lib/Permission.enum.php';
    require_once 'config/config.php';
    		
    BackendComponentPrinter::PrintSidebar($_SESSION['permissions']);
    //*----- Permissions End ----- */
    
    echo
            "<main>
            <h1><i class='fa fa-key fontawesome'></i> Rolle bearbeiten</h1>";
    $roleRow = $dbUser->FetchArray($dbUser->SelectRoleById($roleId));
    echo
            "<form method='post' action='Usermanagement.php'>".
            "<input id='roleId' name='roleId' type='hidden' value='".$roleId."'>".
            "<label for='roleName'>Rollenname</label>
            <input required id='roleName' name='roleName' type='text' value='".$roleRow['rolename']."'><br><br>";
    echo
            "<label for='uri'>Uri</label>
            <input required id='uri' name='uri' type='text' value='".$roleRow['uri']."'><br><br>";
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
    echo
            "<form method='post' action='Usermanagement.php'><input id='back' name='back' type='submit' value='Zurück'><form>";
    echo
            "</main>
            </body>

            </html>";
}

/**
* Opens the page to create a new user
*
*/
function CreateNewUser($dbUser)
{
    BackendComponentPrinter::PrintHead("Benutzerverwaltung");
    //*----- Permissions ----- */
    /* Include(s) */
    require_once 'lib/Permission.enum.php';
    require_once 'config/config.php';
    	
    BackendComponentPrinter::PrintSidebar($_SESSION['permissions']);
    //*----- Permissions End ----- */

    /* Datepicker */
    echo
    "
        <link rel='stylesheet' href='//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css'>
        <link rel='stylesheet' href='/resources/demos/style.css'>
        <script src='https://code.jquery.com/jquery-1.12.4.js'></script>
        <script src='https://code.jquery.com/ui/1.12.1/jquery-ui.js'></script>
        <script>
        $( function() {
        $( '#birthdate' ).datepicker({ dateFormat: 'yy-mm-dd' });
        } );
        </script>
    ";

    echo
            "<main>
            <h1><i class='fa fa-users fontawesome'></i> Neuer Benutzer</h1>
            <form method='post' action='Usermanagement.php'>";
    echo
            "<label for='userName'>Benutzername</label>
            <input required id='userName' name='userName' type='text'><br><br>";
    echo
            "<label for='name'>Name</label>
            <input required id='name' name='name' type='text'><br><br>";
    echo    
            "<label for='foreName'>Vorname</label>
            <input required id='foreName' name='foreName' type='text'><br><br>";
    echo
            "<label for='email'>Email</label>
            <input required id='email' name='email' type='text'><br><br>";
    echo
            "<label for='currentPassword'>Passwort</label>
            <input required id='currentPassword' name='currentPassword' type='password'><br><br>";
    echo
            "<label for='assignedRole'>Rolle</label>".
            "<select required name='assignedRole'>";
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
            <input required type='text' name='birthdate' id='birthdate'><br><br>";
    echo
            "<input id='registrateUser' name='registrateUser' type='submit' value='Anwender erstellen'>";
    echo
            "</form>";
    echo
            "<form method='post' action='Usermanagement.php'><input id='back' name='back' type='submit' value='Zurück'><form>";
    echo
            "</main></body></html>";
}

/**
* Opens the page to create a new role
*
*/
function CreateNewRole($dbUser)
{
    BackendComponentPrinter::PrintHead("Benutzerverwaltung");
    //*----- Permissions ----- */
    /* Include(s) */
    require_once 'lib/Permission.enum.php';
    require_once 'config/config.php';
    		
    BackendComponentPrinter::PrintSidebar($_SESSION['permissions']);
    //*----- Permissions End ----- */
    
    echo
            "<main>
            <h1><i class='fa fa-key fontawesome'></i> Rolle erstellen</h1>";
    echo
            "<form method='post' action='Usermanagement.php'>".
            "<label for='rolename'>Rollenname</label>
            <input required id='rolename' name='rolename' type='text'><br><br>";
    echo
            "<label for='uri'>Uri</label>
            <input required id='uri' name='uri' type='text'><br><br>";
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
    echo
            "<form method='post' action='Usermanagement.php'><input id='back' name='back' type='submit' value='Zurück'><form>";
    echo
            "</main></body></html>";
}

/**
* Opens the page to ban a new user
*
*/
function BanUser($userId, $dbUser)
{
    BackendComponentPrinter::PrintHead("Benutzerverwaltung");
    //*----- Permissions ----- */
    /* Include(s) */
    require_once 'lib/Permission.enum.php';
    require_once 'config/config.php';
    		
    BackendComponentPrinter::PrintSidebar($_SESSION['permissions']);
    //*----- Permissions End ----- */

    /* Datepicker */
    echo
    "
        <link rel='stylesheet' href='//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css'>
        <link rel='stylesheet' href='/resources/demos/style.css'>
        <script src='https://code.jquery.com/jquery-1.12.4.js'></script>
        <script src='https://code.jquery.com/ui/1.12.1/jquery-ui.js'></script>
        <script>
        $( function() {
        $( '#begindatetime' ).datepicker({ dateFormat: 'yy-mm-dd 21:48:50' });
        $( '#enddatetime' ).datepicker({ dateFormat: 'yy-mm-dd 21:48:50' });
        } );
        </script>
    ";
    
    echo
            "<main>
            <h1><i class='fa fa-ban'></i> Sperrung</h1>";
    echo
            "<form method='post' action='Usermanagement.php'>".
            "<input id='userId' name='userId' type='hidden' value='".$userId."'><br><br>";
    echo
            "<label for='reasonId'>Grund</label>";
    echo

            "<select required name='reasonId'>";
            $reasonRows = $dbUser->SelectAllBan_Reason();
            while ($reasonRow = $dbUser->FetchArray($reasonRows))
            {
                echo "<option id='".$reasonRow['id']."'"."value='".$reasonRow['id']."'";
                echo ">".$reasonRow['reason']."</option>";
            }
    echo
            "</select><br><br>";
    echo
            "<label for='description'>Beschreibung</label>".
            "<input id='description' required name='description' type='text'><br><br>";
    echo
            "<label for='begindatetime'>Startdatum</label>".
            "<input id='begindatetime' required name='begindatetime' type='text'><br><br>".
            "<label for='enddatetime'>Enddatum</label>".
            "<input id='enddatetime' required name='enddatetime' type='text'><br><br>".
            "<input id='banUser' name='banUser' type='submit' value='Sperrung erstellen'></form>";
    echo
            "<form method='post' action='Usermanagement.php'><input id='back' name='back' type='submit' value='Zurück'><form>";
    echo
            "</main></body></html>";
}

/**
* Opens the page to decide if raelly delete
*
*/
function ReallyDelete($userId)
{
    BackendComponentPrinter::PrintHead("Benutzerverwaltung");
    //*----- Permissions ----- */
    /* Include(s) */
    require_once 'lib/Permission.enum.php';
    require_once 'config/config.php';
    		
    BackendComponentPrinter::PrintSidebar($_SESSION['permissions']);
    //*----- Permissions End ----- */

    echo
            "<main><form method='post' action='Usermanagement.php'>".
            "<input id='userId' name='userId' type='hidden' value='".$userId."'><br><br>".
            "<p>Möchten Sie wirklich löschen?</p>".
            "<p><img src='media/Pictures/Gnome-edit-delete.png' height='auto' width='250px'></p>".
            "<input id='reallyDelete' name='reallyDelete' type='submit' value='Löschen'>";
    echo
            "</form>";
    echo
            "<form method='post' action='Usermanagement.php'><input id='back' name='back' type='submit' value='Zurück'><form>";
    echo
            "</main></body></html>";

}

/**
* Opens the page to decide if really delete role
*
*/
function ReallyDeleteRole($roleId)
{
    BackendComponentPrinter::PrintHead("Benutzerverwaltung");
    //*----- Permissions ----- */
    /* Include(s) */
    require_once 'lib/Permission.enum.php';
    require_once 'config/config.php';
    		
    BackendComponentPrinter::PrintSidebar($_SESSION['permissions']);
    //*----- Permissions End ----- */

    echo
            "<main><form method='post' action='Usermanagement.php'>".
            "<input id='roleId' name='roleId' type='hidden' value='".$roleId."'><br><br>".
            "<p>Möchten Sie wirklich löschen?</p>".
            "<p><img src='media/Pictures/Gnome-edit-delete.png' height='auto' width='250px'></p>".
            "<input id='reallyDeleteRole' name='reallyDeleteRole' type='submit' value='Löschen'>";
    echo
            "</form>";
    echo
            "<form method='post' action='Usermanagement.php'><input id='back' name='back' type='submit' value='Zurück'><form>";
    echo
            "</main></body></html>";

}

/**
* Sets the permissions from the form to variables (call by reference --> &)
*
*/
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
