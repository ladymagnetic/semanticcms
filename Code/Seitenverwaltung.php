<!DOCTYPE html>
<html>

<body>
<?php
require_once 'lib/BackendComponentPrinter.class.php';
use SemanticCms\ComponentPrinter\BackendComponentPrinter;
printHead("Seitenverwaltung");
/* menue */
/* dynamisch erzeugt je nach Rechten */

BackendComponentPrinter::printSidebar(array()/*Parameter fehlen noch -> Rechte des gerade eingeloggten Nutzers*/);
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
