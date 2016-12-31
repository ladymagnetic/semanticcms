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
    $userName = utf8_decode($_POST['userName']);
    $name = utf8_decode($_POST['name']);
    $foreName = utf8_decode($_POST['foreName']);
    $email = utf8_decode($_POST['email']);
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
        echo
        "<div class='alert alert-danger warning'>
        <strong>Warnung!</strong> Email existiert bereits.
        </div";
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
        echo
        "<div class='alert alert-danger warning'>
        <strong>Warnung!</strong> Benutzername existiert bereits.
        </div";
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
        echo
        "<div class='alert alert-danger warning'>
        <strong>Warnung!</strong> Invalide email.
        </div";
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
        echo
        "<div class='alert alert-danger warning'>
        <strong>Warnung!</strong> Der Benutzername darf nur Buchstaben, Zahlen und Unterstriche enthalten.
        </div";
    }
    else
    {
        $dbUser->UpdateUserDifferentNamesById($name, $foreName, $userName, $email, $userId);
    }
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
/* menue */
/* dynamisch erzeugt je nach Rechten */
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
// Nicht vergessen nach dem kopieren die wirklich benötigte permission abzufragen!!
else if(!in_array(Permission::Usermanagment, $_SESSION['permissions']))
{
    die($config['error']['permissionMissing']);  	  
}

// Printer Beispiel									
//BackendComponentPrinter::PrintSidebar($_SESSION['permissions']);
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
        <h1><i class='fa fa-user fontawesome'></i> Benutzer bearbeiten</h1>
            <form method='post' action='Usermanagement.php'>";
$userRow = $dbUser->FetchArray($dbUser->GetUserInformationByUsername($_SESSION['username']));
echo
        "<label for='userName'>Benutzername</label>
        <input required id='userName' name='userName' type='text' value='".utf8_encode($userRow['username'])."'><br><br>";
echo
        "<label for='name'>Name</label>
        <input required id='name' name='name' type='text' value='".utf8_encode($userRow['lastname'])."'><br><br>";
echo    
        "<label for='foreName'>Vorname</label>
        <input required id='foreName' name='foreName' type='text' value='".utf8_encode($userRow['firstname'])."'><br><br>";
echo
        "<label for='email'>Email</label>
        <input required id='email' name='email' type='text' value='".$userRow['email']."'><br><br>";
echo
        "<label for='birthdate'>Geburtsdatum</label>
        <input required type='text' name='birthdate' id='birthdate' value='".$userRow['birthdate']."'><br><br>";
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
            "<input readonly id='role' name='role' type='text' value='".utf8_encode($roleRow['rolename'])."'><br><br>";
    }
}        
echo
        "<input id='userId' name='userId' type='hidden' value='".$userRow['id']."'>".
        "<input id='applyChanges' name='applyChanges' type='submit' value='Änderungen übernehmen'>";
echo
        "</form>
        <h2>Passwort ändern</h2>
        <form method='post' action='Usermanagement.php'>";
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
