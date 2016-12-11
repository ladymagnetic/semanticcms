<!DOCTYPE html>
<html>

<head>
<meta content="de" http-equiv="Content-Language">
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<title>Kontodaten bearbeiten</title>
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
<h1>Kontodaten bearbeiten</h1>
<p>
<form method="post">
    <label for="userName">Benutzername</label>
	<input name="userName" type="text">
    <label for="name">Name</label>
	<input name="name" type="text">
    <label for="foreName">Vorname</label>
	<input name="email" type="text">
    <label for="email">Email</label>
	<input name="Password4" type="text">
</form>
<h2>Passwort ändern</h2>
<form method="post">
    <label for="currentPassword">aktuelles Passwort</label>
	<input name="currentPassword" type="password">
    <label for="newPassword">neues Passwort</label>
	<input name="newPassword" type="password">
    <label for="newPasswordRepeat">neues Passwort bestätigen</label>
	<input name="newPasswordRepeat" type="password">
	<input name="applyChanges" type="button" value="Änderungen übernehmen">
</form>

</body>

</html>
