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
	<ul>
	<li><a href="Benutzerverwaltung.php" title="Struktur">Benutzerverwaltung</a></li>
	<li><a href="Seitenverwaltung.php" title="Darstellung">Seitenverwaltung</a></li>
	<li><a href="Inhaltsverwaltung.php" title="Formulare">Inhaltsverwaltung</a></li>
	<li><a href="Templates.php" title="Verweise">Templates</a></li>
	</ul>
</nav>
<h1>Seitenverwaltung</h1>
<table>
	<tr>
		<td>Seite</td>
		<td>Template</td>
		<td>Öffentlich</td>
		<td>Aktionen</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
            <form action="select.html"> <label>Künstler(in): <select name="top5" size="5"> <option>Heino</option> <option>Michael Jackson</option> <option>Tom Waits</option> <option>Nina Hagen</option> <option>Marianne Rosenberg</option> </select> </label> </form>
        </td>
		<td>
		<form method="post">
			<input name="Checkbox1" type="checkbox"></form>
		</td>
		<td>
		<form method="post">
			<input name="Button1" type="button" value="Löschen"><input name="Button2" type="button" value="Inhalte bearbeiten"></form>
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
<form method="post">
	<input name="Button3" type="button" value="Neue Seite"></form>
<form method="post">
	<input name="Button4" type="button" value="Optionen"></form>

</body>

</html>
