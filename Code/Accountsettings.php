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
// if submit button with name 'applyChanges' is pressed
if (isset($_POST['applyChanges']))
{
    $userId = intval($_POST['userId']);
    $name = $_POST['name'];
    $foreName = $_POST['foreName'];
    $email = $_POST['email'];
    $dbUser->UpdateUserDifferentNamesById($name, $foreName, $email, $userId);
}
// if submit button with name 'applyPasswordChanges' is pressed
else if (isset($_POST['applyPasswordChanges']))
{
    $userId = intval($_POST['userId']);
    $password = $_POST['currentPassword'];
    $newPassword = $_POST['newPassword'];
    $newPasswordRepeat = $_POST['newPasswordRepeat'];
    $dbUser->ApplyPasswordChangesToUser($userId, $password, $newPassword, $newPasswordRepeat);
}

BackendComponentPrinter::PrintHead("Konto bearbeiten", $jquery=true);
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
    BackendComponentPrinter::PrintSidebar($_SESSION['permissions']);
    //*----- Permissions End ----- */
echo
        "<body><main>
        <h1><i class='fa fa-user fontawesome'></i> Kontoeinstellungen</h1>
            <form method='post' action='Accountsettings.php'>";
$userRow = $dbUser->FetchArray($dbUser->GetUserInformationByUsername($_SESSION['username']));
echo
        "<label for='userName'>Benutzername</label>
        <input readonly id='userName' name='userName' type='text' value='".$userRow['username']."'><br><br>";
echo
        "<label for='name'>Name</label>
        <input required id='name' name='name' type='text' value='".$userRow['lastname']."'><br><br>";
echo    
        "<label for='foreName'>Vorname</label>
        <input required id='foreName' name='foreName' type='text' value='".$userRow['firstname']."'><br><br>";
echo
        "<label for='email'>Email</label>
        <input required id='email' name='email' type='text' value='".$userRow['email']."'><br><br>";
echo
        "<label for='birthdate'>Geburtsdatum</label>
        <input readonly type='text' name='birthdate' id='birthdate' value='".$userRow['birthdate']."'><br><br>";
// not editable
echo
        "<label for='registrydate'>Registrierungsdatum</label>
        <input readonly type='text' name='registrydate' id='registrydate' value='".$userRow['registrydate']."'><br><br>".
        "<label for='role'>Rolle</label>";
$roleRows = $dbUser->SelectAllRoles();
while ($roleRow = $dbUser->FetchArray($roleRows))
{
    if ($roleRow['id'] == $userRow['role_id'])
    {
        echo
            "<input readonly id='role' name='role' type='text' value='".$roleRow['rolename']."'><br><br>";
    }
}        
echo
        "<input id='userId' name='userId' type='hidden' value='".$userRow['id']."'>".
        "<input id='applyChanges' name='applyChanges' type='submit' value='Änderungen übernehmen'>";
echo
        "</form>
        <h2>Passwort ändern</h2>
        <form method='post' action='Accountsettings.php'>";
echo
        "<label for='currentPassword'>aktuelles Passwort</label>
        <input required id='currentPassword' name='currentPassword' type='password'><br><br>
        <label for='newPassword'>neues Passwort</label>
        <input required id='newPassword' name='newPassword' type='password'><br><br>
        <label for='newPasswordRepeat'>neues Passwort bestätigen</label>
        <input required id='newPasswordRepeat' name='newPasswordRepeat' type='password'><br><br>
        <input id='userId' name='userId' type='hidden' value='".$userRow['id']."'>".
        "<input id='applyPasswordChanges' name='applyPasswordChanges' type='submit' value='Passwort übernehmen'>";
echo
        "</form>
        </main>
        </body>

        </html>";
?>
