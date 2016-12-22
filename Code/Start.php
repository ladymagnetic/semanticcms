<!DOCTYPE html>
<html>

<!-- fuer head wird es wahrscheinlich ebenfalls eine Methode geben: printHead(titel?), diese dann ggf. nutzen -->
<head>
    <meta content="de" http-equiv="Content-Language">
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
    <title>Startseite</title>
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
    <h1>Startseite</h1>
    <table>
        <tr>
            <th>Letzte Änderungen</th>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
    </table>
    <form method="post" action="../lib/BackendComponentPrinter.class.php">
        <input id="exportDatabase" name="exportDatabase" type="button" value="Datenbank exportieren">
        <input id="importDatabase" name="importDatabase" type="button" value="Datenbank importieren">
    </form>
</section>
</body>

</html>
