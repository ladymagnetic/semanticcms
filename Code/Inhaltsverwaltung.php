<!DOCTYPE html>
<html>

<!-- fuer head wird es wahrscheinlich ebenfalls eine Methode geben: printHead(titel?), diese dann ggf. nutzen -->
<head>
    <meta content="de" http-equiv="Content-Language">
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
    <title>Inhaltsverwaltung</title>
    <link rel="stylesheet" href="css/backend.css">
    <link rel="stylesheet" href="css/font-awesome/css/font-awesome.min.css">
</head>

<body>
<!-- menue -->
<!-- dynamisch erzeugt je nach Rechten -->
<?php
require_once 'lib/BackendComponentPrinter.class.php';
use SemanticCms\ComponentPrinter\BackendComponentPrinter;

BackendComponentPrinter::printSidebar(array()/*Parameter fehlen noch -> Rechte des gerade eingeloggten Nutzers*/);
?>
<section id="main">
    <h1><i class="fa fa-align-justify fontawesome"></i> Inhaltsverwaltung</h1>
    <form id="page" name="page" action="../lib/BackendComponentPrinter.class.php"> <label>Seite: <select name="top5"> <option>Heino</option> <option>Michael Jackson</option> <option>Tom Waits</option> <option>Nina Hagen</option> <option>Marianne Rosenberg</option> </select> </label> </form>
    <table>
        <tr>
            <th>Inhalte</th>
            <th>Veröffentlichungsdatum</th>
            <th>Aktion</th>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>
            <form method="post" action="../lib/BackendComponentPrinter.class.php">
                <input id="delete" name="delete" type="button" value="Löschen"><input name="Button2" type="button" value="Bearbeiten"></form>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
    </table>
    <form method="post" action="../lib/BackendComponentPrinter.class.php">
        <input id="newContent" name="newContent" type="button" value="Neuer Inhalt"></form>
</section>
</body>

</html>
