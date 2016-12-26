<?php
// Start the session
session_start();
?>
<!DOCTYPE html>
<html>
<?php
/* Include(s) */
require_once 'lib/BackendComponentPrinter.class.php';

/* use namespace(s) */
use SemanticCms\ComponentPrinter\BackendComponentPrinter;

BackendComponentPrinter::PrintHead("Seitenverwaltung");
?>
<body>
<?php
/* Include(s) */
require_once 'lib/Permission.enum.php';
require_once 'config/config.php';

/* use namespace(s) */
use SemanticCms\Model\Permission;

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
/*  Check if user has the permission to see this page */
else if(!in_array(Permission::Pagemanagment, $_SESSION['permissions']))
{
    die($config['error']['permissionMissing']);
}

BackendComponentPrinter::PrintSidebar($_SESSION['permissions']);
?>
<section id="main">
    <h1><i class="fa fa-file-text fontawesome"></i> Seitenverwaltung</h1>
    <table>
        <tr>
            <th>Seite</th>
            <th>Template</th>
            <th>Öffentlich</th>
            <th>Aktionen</th>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>
                <form id="template" name="template" action="../lib/BackendComponentPrinter.class.php"> <label>Template: <select name="top5"> <option>Heino</option> <option>Michael Jackson</option> <option>Tom Waits</option> <option>Nina Hagen</option> <option>Marianne Rosenberg</option> </select> </label> </form>
            </td>
            <td>
            <form method="post" action="../lib/BackendComponentPrinter.class.php">
                <input id="public" name="public" type="checkbox"></form>
            </td>
            <td>
            <form method="post">
                <input id="editContent" name="editContent" type="button" value="Löschen"><input name="Button2" type="button" value="Inhalte bearbeiten"></form>
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
    <form method="post" action="../lib/BackendComponentPrinter.class.php">
        <input id="newPage" name="newPage" type="button" value="Neue Seite">
        <input id="options" name="options" type="button" value="Optionen">
    </form>
</section>
</body>

</html>
