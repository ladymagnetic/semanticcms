<!DOCTYPE html>
<html>

<head>
<meta content="de" http-equiv="Content-Language">
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<title>Kontodaten bearbeiten</title>
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
<h1>Kontodaten bearbeiten</h1>
    <form method="post" action="../lib/BackendComponentPrinter.class.php">
        <label for="userName">Benutzername</label>
        <input id="userName" name="userName" type="text">
        <label for="name">Name</label>
        <input id="name" name="name" type="text">
        <label for="foreName">Vorname</label>
        <input id="foreName" name="foreName" type="text">
        <label for="email">Email</label>
        <input id="email" name="email" type="text">
    </form>
    <h2>Passwort ändern</h2>
    <form method="post" action="../lib/BackendComponentPrinter.class.php">
        <label for="currentPassword">aktuelles Passwort</label>
        <input id="currentPassword" name="currentPassword" type="password">
        <label for="newPassword">neues Passwort</label>
        <input id="newPassword" name="newPassword" type="password">
        <label for="newPasswordRepeat">neues Passwort bestätigen</label>
        <input id="newPasswordRepeat" name="newPasswordRepeat" type="password">
        <input id="applyChanges" name="applyChanges" type="button" value="Änderungen übernehmen">
    </form>
</section>
</body>

</html>
