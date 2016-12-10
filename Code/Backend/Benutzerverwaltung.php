<!DOCTYPE html>
<html>

<head>
<meta content="de" http-equiv="Content-Language">
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<title>Benutzerverwaltung</title>
<link rel="stylesheet" href="BackendCSS.css">
</head>

<body>

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
		<form method="post">
			<input name="Button3" type="button" value="entsperren"></form>
		</td>
		<td>&nbsp;</td>
		<td>
		<form method="post">
			<input name="Button4" type="button" value="Details"><input name="Button5" type="button" value="löschen"></form>
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
	<input name="Button1" type="button" value="Neuer Benutzer"></form>
<form method="post">
	<input name="Button2" type="button" value="Rollen definieren"></form>
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
<form method="post">
	<input name="Button6" type="button" value="Neue Rolle"></form>
<form method="post">
	<input name="Button7" type="button" value="Rolle löschen"></form>
<h3>Rechte</h3>
<form method="post">
	<input name="Checkbox1" type="checkbox"></form>
<form method="post">
	<input name="Checkbox2" type="checkbox"></form>
<form method="post">
	<input name="Button8" type="button" value="Änderungen speichern"></form>

</body>

</html>
