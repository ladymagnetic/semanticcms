<!DOCTYPE html>
<html>

<head>
<meta content="de" http-equiv="Content-Language">
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<title>Seitenverwaltung</title>
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
    <h1>Seitenverwaltung</h1>
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
