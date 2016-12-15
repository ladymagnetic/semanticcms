<!DOCTYPE html>
<html>

<head>
<meta content="de" http-equiv="Content-Language">
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<title>Benutzerverwaltung</title>
<link rel="stylesheet" href="BackendCSS.css">
<link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">
</head>

<body>
<!-- menue -->
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
    <h1><i class="fa fa-user fontawesome"></i> Benutzerverwaltung</h1>
    <table>
        <tr>
            <th>Benutzer</th>
            <th>entsperren/sperren</th>
            <th>Rolle</th>
            <th>Aktion</th>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>
            <form method="post" action="../lib/BackendComponentPrinter.class.php">
                <input id="unlock" name="unlock" type="button" value="entsperren"></form>
            </td>
            <td>&nbsp;</td>
            <td>
            <form method="post" action="../lib/BackendComponentPrinter.class.php">
                <input id="details" name="details" type="button" value="Details"><input id="delete" name="delete" type="button" value="löschen"></form>
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
        <input id="newUser" name="newUser" type="button" value="Neuer Benutzer">
        <input id="defineRole" name="defineRole" type="button" value="Rollen definieren">
    </form>
    <h2>Rollen definieren</h2>
    <h3>Rollenname</h3>
    <table>
        <tr>
            <td>Admin</td>
        </tr>
        <tr>
            <td>Redakteur</td>
        </tr>
        <tr>
            <td>Gast</td>
        </tr>
    </table>
    <form method="post" action="../lib/BackendComponentPrinter.class.php">
        <input id="newRole" name="newRole" type="button" value="Neue Rolle">
        <input id="deleteRole" name="deleteRole" type="button" value="Rolle löschen">
    </form>
    <h3>Rechte</h3>
    <form method="post" action="../lib/BackendComponentPrinter.class.php">
        <input id="right1" name="right1" type="checkbox">
        <input id="right2" name="right2" type="checkbox">
        <input id="saveChanges" name="saveChanges" type="button" value="Änderungen speichern">
    </form>
</section>
</body>

</html>
