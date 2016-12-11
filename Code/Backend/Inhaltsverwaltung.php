<!DOCTYPE html>
<html>

<head>
<meta content="de" http-equiv="Content-Language">
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<title>Inhaltsverwaltung</title>
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
<h1>Inhaltsverwaltung</h1>
<form action="select.html"> <label>Künstler(in): <select name="top5" size="5"> <option>Heino</option> <option>Michael Jackson</option> <option>Tom Waits</option> <option>Nina Hagen</option> <option>Marianne Rosenberg</option> </select> </label> </form>
<table>
	<tr>
		<td>Inhalte</td>
		<td>Veröffentlichungsdatum</td>
		<td>Aktion</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>
		<form method="post">
			<input name="Button1" type="button" value="Löschen"><input name="Button2" type="button" value="Bearbeiten"></form>
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
<form method="post">
	<input name="Button3" type="button" value="Neuer Inhalt"></form>

</body>

</html>
