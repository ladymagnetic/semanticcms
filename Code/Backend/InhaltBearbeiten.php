<!DOCTYPE html>
<html>

<head>
<meta content="de" http-equiv="Content-Language">
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<title>Inhalt bearbeiten</title>
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
<section id="main">
    <h1>Inhalt bearbeiten</h1>
    <form method="post">
        <input id="addMedia" name="addMedia" type="button" value="Medien hinzufügen"></form>
    <form method="post">
        <textarea cols="20" id="contentText" name="contentText" rows="2"></textarea></form>
    <form method="post">
        <input id="publish" name="publish" type="button" value="Publish">
    </form>
</section>
</body>

</html>
