<!DOCTYPE html>
<html>


<!-- Head -->
<?php
  /* Include(s) */
  require_once 'lib/BackendComponentPrinter.class.php';

  /* use namespace(s) */
  use SemanticCms\ComponentPrinter\BackendComponentPrinter;

  BackendComponentPrinter::PrintHead("Templates", true);
?>

<body>

  <script>
    $(".Color").on("click", function(){
      $(".Colorpicker").show();
    })
  </script>

<!-- menue -->
<!-- dynamisch erzeugt je nach Rechten -->
<?php

require_once 'lib/TemplateParser.class.php';


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
    <form  action="TemplateConstruction.php" method="post">
    <h2>Header</h2>
    <label>Titel: <input type="text" name="Title"> </label><br><br>
    <label>Position:

      <input type="radio" name="Position" value="right">
      <label for="right">rechts</label>
      <input type="radio" name="Position" value="center">
      <label for="center">mittig</label>
      <input type="radio" name="Position" value="left">
      <label for="left">links</label>

    </label><br><br>

    <label>Hoehe: <input type="number" min="10" max="25" name="Height"><label>%</label></label><br><br>
    <label>Hintergrund:
      <input class="Color" type="radio" name="Background" value="Color">
      <label for="Color"> Farbe</label>
      <!--<div class="Colorpicker" style="display: none">
        <input type="color" name="Backgroundcolor" value="#000000">
      </div>
      <input type="radio" name="Background" value="Picture">
      <label for="Picture"> Bild</label>

    </label><br><br>-->
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
