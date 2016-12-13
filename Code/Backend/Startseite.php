<!DOCTYPE html>
<html>

<head>
<meta content="de" http-equiv="Content-Language">
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<title>Startseite</title>
<link rel="stylesheet" href="BackendCSS.css">
</head>

<body>
<nav id="menue">
    <div id="logo"></div>
	<ul>
	<li><a href="Benutzerverwaltung.php" title="Struktur">Benutzerverwaltung</a></li>
	<li><a href="Seitenverwaltung.php" title="Darstellung">Seitenverwaltung</a></li>
	<li><a href="Inhaltsverwaltung.php" title="Formulare">Inhaltsverwaltung</a></li>
	<li><a href="Templates.php" title="Verweise">Templates</a></li>
	</ul>
</nav>
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
