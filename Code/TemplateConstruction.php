<!DOCTYPE html>
<html>

<!-- fuer head wird es wahrscheinlich ebenfalls eine Methode geben: printHead(titel?), diese dann ggf. nutzen -->
<head>
    <meta content="de" http-equiv="Content-Language">
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
    <title>Templates</title>
    <link rel="stylesheet" href="css/backend.css">
    <link rel="stylesheet" href="css/font-awesome/css/font-awesome.min.css">
</head>

<body>
<!-- menue -->
<!-- dynamisch erzeugt je nach Rechten -->
<?php
require_once 'lib/BackendComponentPrinter.class.php';
require_once 'lib/TemplateParser.class.php';

use SemanticCms\ComponentPrinter\BackendComponentPrinter;
use SemanticCms\FrontendGenerator\TemplateParser;

BackendComponentPrinter::printSidebar(array()/*Parameter fehlen noch -> Rechte des gerade eingeloggten Nutzers*/);

if(isset($_POST['save'])) {
  $templateParser = new TemplateParser();
  $title = $_POST['Title'];
  $background = $_POST['Background'];
  $height = $_POST['Height'];
  $font = $_POST['Fonts'];
  $fontsize = $_POST['Fontsize'];
  $fontColor = $_POST['FontColor'];
  $position = "rechts";
  $logo = "toll";
  $templateParser->SaveHeader($title, $height, $position, $font, $fontsize, $fontColor, $background, $background, $logo);
}

?>

  <section id="main">
		<h1><i class="fa fa-paint-brush fontawesome"></i> Templates</h1>
    <form  action="Templateerstellung.php" method="post">
    <h2>Header</h2>
    <label>Titel: <input type="text" name="Title"> </label><br><br>
    <label>Hoehe: <input type="number" min="10" max="25" name="Height"><label>%</label></label><br><br>
    <label>Hintergrund:
        <input type="radio" name="Background" value="Color">
        <label for="Color"> Farbe</label>
        <input type="radio" name="Background" value="Picture">
        <label for="Picture"> Bild</label>
    </label><br><br>
    <label>Schriftart:
      <?php
        //BackendComponentPrinter::printFonts();
       ?>
       <select name="Fonts">
         <option>Times New Roman</option>
         <option>Arial</option>
       </select>
    </label><br><br>
    <label>Schriftgröße: <input type="number" min="2" max="50" name="Fontsize"></label><br><br>
    <label>Schriftfarbe: <input type="color" name="FontColor" value="#000000"></label><br><br>
    <input type="submit" name="save" value="speichern">
    </form>
	</section>
</body>
</html>
