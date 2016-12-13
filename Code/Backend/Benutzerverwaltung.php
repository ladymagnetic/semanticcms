<!DOCTYPE html>
<html>

<head>
<meta content="de" http-equiv="Content-Language">
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<title>Benutzerverwaltung</title>
<link rel="stylesheet" href="BackendCSS.css">
</head>

<body>
<!-- menue -->
<nav id="menue">
	<ul>
        <li><a href="Benutzerverwaltung.php" title="Struktur">Benutzerverwaltung</a></li>
        <li><a href="Seitenverwaltung.php" title="Darstellung">Seitenverwaltung</a></li>
        <li><a href="Inhaltsverwaltung.php" title="Formulare">Inhaltsverwaltung</a></li>
        <li><a href="Templates.php" title="Verweise">Templates</a></li>
	</ul>
</nav>
<section id="main">
    <h1>Benutzerverwaltung</h1>
    <table>
        <tr>
            <td>Benutzer</td>
            <td>entsperren/sperren</td>
            <td>Rolle</td>
            <td>Aktion</td>
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
