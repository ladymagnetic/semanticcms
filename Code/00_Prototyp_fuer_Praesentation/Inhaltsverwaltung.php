<!DOCTYPE html>
<html>

<head>
<meta content="de" http-equiv="Content-Language">
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<title>Inhaltsverwaltung</title>
<link rel="stylesheet" href="BackendCSS.css">
<link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">
</head>

<body>
<nav id="menue">
    <div id="logo"></div>
	<ul>
	    <li><a href="Benutzerverwaltung.php" title="Benutzerverwaltung"><i class="fa fa-user fontawesome"></i> Benutzerverwaltung</a></li>
        <li><a href="Seitenverwaltung.php" title="Seitenverwaltung"><i class="fa fa-file-text fontawesome"></i> Seitenverwaltung</a></li>
        <li><a href="Inhaltsverwaltung.php" title="Inhaltsverwaltung"><i class="fa fa-align-justify fontawesome"></i> Inhaltsverwaltung</a></li>
        <li><a href="Templates.php" title="Templates"><i class="fa fa-paint-brush fontawesome"></i> Templates</a></li>
	</ul>
</nav>
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
