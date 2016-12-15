<!DOCTYPE html>
<html>

<head>
<meta content="de" http-equiv="Content-Language">
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<title>Inhalt bearbeiten</title>
<link href="http://netdna.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.css" rel="stylesheet">
<script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.js"></script> 
<script src="http://netdna.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.js"></script> 
<link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.2/summernote.css" rel="stylesheet">
<script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.2/summernote.js"></script>
<link rel="stylesheet" href="BackendCSS.css">
<link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">
</head>

<body>
<nav id="menue">
    <div id="logo"></div>
	<ul>
        <li><a class="contentEditMenue" href="Benutzerverwaltung.php" title="Benutzerverwaltung"><i class="fa fa-user fontawesome"></i> Benutzerverwaltung</a></li>
        <li><a class="contentEditMenue" href="Seitenverwaltung.php" title="Seitenverwaltung"><i class="fa fa-file-text fontawesome"></i> Seitenverwaltung</a></li>
        <li><a class="contentEditMenue" href="Inhaltsverwaltung.php" title="Inhaltsverwaltung"><i class="fa fa-align-justify fontawesome"></i> Inhaltsverwaltung</a></li>
        <li><a class="contentEditMenue" href="Templates.php" title="Templates"><i class="fa fa-paint-brush fontawesome"></i> Templates</a></li>
	</ul>
</nav>
<section id="main">
    <h1>Inhalt bearbeiten</h1>
    <div id="summernote"><p>Hello Summernote</p></div>
      <script>
        $(document).ready(function() {
            $('#summernote').summernote();
        });
      </script>
    <form method="post" action="../lib/BackendComponentPrinter.class.php">
        <input id="publish" name="publish" type="button" value="Publish">
    </form>
</section>
</body>

</html>
