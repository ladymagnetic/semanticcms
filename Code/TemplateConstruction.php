<?php
  session_start();
 ?>
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

    $(function() {
      $(".Colorpicker").hide();
      $(".PictureUpload").hide();
      $(".WebsiteColerPicker").hide();
      $(".WebsitePictureUpload").hide();
      $(".Sitenavigation").hide();
      $(".NavBackgroundColorDiv").hide();
      $(".NavBackgroundPicDiv").hide();
      $(".FooterBackColor").hide();
      $(".FooterBackPic").hide();
      $(".BtnBackColor").hide();
      $(".BtnBackPic").hide();
    });

    function onColorPicker()
    {
      $(".Colorpicker").show();
      $(".PictureUpload").hide();
    }

    function onPictureUpload()
    {
      $(".PictureUpload").show();
      $(".Colorpicker").hide();
    }

    function onWebsiteColorPicker()
    {
      $(".WebsiteColerPicker").show();
      $(".WebsitePictureUpload").hide();
    }

    function onWebsitePictureUpload()
    {
      $(".WebsitePictureUpload").show();
      $(".WebsiteColerPicker").hide();
    }

    function onArticleNavSettings()
    {
      $(".Sitenavigation").show();
    }

    function onArticleBackColorClick()
    {
      $(".ArticleBackgroundColorDiv").show();
      $(".ArticleBackgroundPicDiv").hide();
    }

    function onArticleBackPicClick()
    {
      $(".ArticleBackgroundPicDiv").show();
      $(".ArticleBackgroundColorDiv").hide();
    }

    function onFooterBackColorClick()
    {
      $(".FooterBackColor").show();
      $(".FooterBackPic").hide();
    }

    function onFooterBackPicClick()
    {
      $(".FooterBackPic").show();
      $(".FooterBackColor").hide();
    }

    function onBtnBackColorClick()
    {
      $(".BtnBackColor").show();
      $(".BtnBackPic").hide();
    }

    function onBtnBackPicClick()
    {
      $(".BtnBackPic").show();
      $(".BtnBackColor").hide();
    }

  </script>



<?php

require_once 'lib/TemplateParser.class.php';
require_once 'lib/Permission.enum.php';
require_once 'config/config.php';

use SemanticCms\FrontendGenerator\TemplateParser;
use SemanticCms\Model\Permission;


/* Check if user is logged in */
if(!isset($_SESSION['username']))
{
  die($config['error']['noLogin']);
}
/* Check if  permissions are set */
else if(!isset($_SESSION['permissions']))
{
  die($config['error']['permissionNotSet']);
}
/*  Check if user has the permission the see this page */
else if(!in_array(Permission::Templateconstruction, $_SESSION['permissions']))
{
  die($config['error']['permissionMissing']);
}

/* menue */
/* dynamisch erzeugt je nach Rechten */
BackendComponentPrinter::printSidebar($_SESSION['permissions']);

if(isset($_POST['save'])) {
  $templateParser = new TemplateParser();
  $title = $_POST['Title'];
  $backgroundColor = $_POST['BackgroundColor'];
  $backgroundPicture = $_POST['BackgroundPicture'];
  $height = $_POST['Height'];
  $font = $_POST['Font'];
  $fontsize = $_POST['Fontsize'];
  $fontColor = $_POST['FontColor'];
  $position = $_POST['Position'];
  $logo = $_POST['Logo'];
  $templateParser->SaveHeader($title, $height, $position, $font, $fontsize, $fontColor, $backgroundColor, $backgroundPicture, $logo);
  $webBackgroundcolor = $_POST['WebColor'];
  $webBackgroundpic = $_POST['WebPicture'];
  $webBackPicPosition = $_POST['WebPicPosition'];
  $templateParser->SaveBackground($webBackgroundcolor, $webBackgroundpic, $webBackPicPosition);
  $menuWidth = $_POST['MenuWidth'];
  $menuHeight = $_POST['MenuHeight'];
  $menuPosition = $_POST['MenuPosition'];
  $menuFont = $_POST['MenuFont'];
  $menuFontsize = $_POST['MenuFontsize'];
  $menuFontColor = $_POST['MenuFontColor'];
  $menuBackgroundColor = $_POST['MenuBackgroundColor'];
  $menuOrder = $_POST['Order'];
  $templateParser->SaveMenu($menuWidth, $menuHeight, $menuPosition, $menuFont, $menuFontsize, $menuFontColor, $menuBackgroundColor, $menuOrder);
  $articlePosition = $_POST['ArticlePosition'];
  $articleNumber = $_POST['Articlenumber'];
  $navigation = $_POST['Navigation'];
  $navigationPostion = $_POST['NavigationPosition'];
  $navFont = $_POST['NavFont'];
  $navFontsize = $_POST['NavFontsize'];
  $navFontColor = $_POST['NavFontColor'];
  $navButtonBackgroundColor = $_POST['NavButtonBackgroundColor'];
  $articleBackColor = $_POST['ArticleBackColor'];
  $articleBackgroundPicture = $_POST['ArticleBackgroundPicture'];
  $articleWidth = $_POST['ArticleWidth'];
  $templateParser->SaveArticleContainer($articlePosition, $articleNumber, $navigation, $navigationPostion, $navFont, $navFontsize, $navFontColor, $navButtonBackgroundColor, $articleBackColor, $articleBackgroundPicture, $articleWidth);
  $footerHeight = $_POST['FooterHeight'];
  $footerFont = $_POST['FooterFont'];
  $footerFontsize = $_POST['FooterFontsize'];
  $footerFontColor = $_POST['FooterFontColor'];
  $footerBackColorPicker = $_POST['FooterBackColorPicker'];
  $footerBackPicture = $_POST['FooterBackPicture'];
  $orderFooter = $_POST['OrderFooter'];
  $templateParser->SaveFooter($footerHeight, $footerFont, $footerFontsize, $footerFontColor, $footerBackColorPicker, $footerBackPicture, $orderFooter);
  $buttonRounded = $_POST['ButtonRounded'];
  $button3D = $_POST['Button3D'];
  $buttonFont = $_POST['ButtonFont'];
  $buttonFontsize = $_POST['ButtonFontsize'];
  $buttonFontColor = $_POST['ButtonFontColor'];
  $buttonBackgroundColor = $_POST['ButtonBackgroundColor'];
  $buttonBackgroundPic = $_POST['ButtonBackgroundPic'];
  $templateParser->SaveButton($buttonRounded, $button3D, $buttonFont, $buttonFontsize, $buttonFontColor, $buttonBackgroundColor, $buttonBackgroundPic);
  $searchField = $_POST['SearchField'];
  $login = $_POST['Login'];
  $templateName = $_POST['TemplateName'];
  $templateParser->SavePlugIns($searchField, $login, $templateName);
}

?>

  <main>
		<h1><i class="fa fa-paint-brush fontawesome"></i> Templates</h1>
    <form  action="TemplateConstruction.php" method="post">
    <h2>Header</h2>
    <label>Titel: <input type="text" name="Title"> </label><br><br>
    <label>Position:

      <input type="radio" name="Position" value="right" checked="true">
      <label for="right">rechts</label>
      <input type="radio" name="Position" value="center">
      <label for="center">mittig</label>
      <input type="radio" name="Position" value="left">
      <label for="left">links</label>

    </label><br><br>

    <label>Hoehe: <input type="number" min="10" max="25" name="Height"><label for="Height">%</label></label><br><br>
    <label>Hintergrund:

      <input type="radio" name="Background" value="Color" checked="true" onclick="onColorPicker()">
      <label for="Color"> Farbe</label>
      <div class="Colorpicker" >
        <input type="color" name="BackgroundColor" value="#000000">
      </div>

      <input type="radio" name="Background" value="Picture" onclick="onPictureUpload()">
      <label for="Picture"> Bild</label>
      <div class="PictureUpload">
        <input type="file" name="BackgroundPicture">
      </div>

    </label><br><br>

    <?php
      BackendComponentPrinter::PrintFontsDropdownList("Schriftart:", "Font");
     ?>

    <br><br>
    <label>Schriftgröße: <input type="number" min="2" max="50" name="Fontsize"></label><br><br>
    <label>Schriftfarbe: <input type="color" name="FontColor" value="#000000"></label><br><br>
    <label>Logo: <input type="file" name="Logo"></label><br><br>
    <h2>Hintergrund von der Website</h2>
      <input type="radio" name="WebsiteBackground" value="WebsiteColor" checked="true" onclick="onWebsiteColorPicker()">
      <label for="WebsiteColor"> Farbe</label>
      <div class="WebsiteColerPicker">
        <input type="color" name="WebColor" value="#000000">
      </div>
      <input type="radio" name="WebsiteBackground" value="WebsitePicture" onclick="onWebsitePictureUpload()">
      <label for="WebsitePicture"> Bild</label>
      <div class="WebsitePictureUpload">
        <input type="file" name="WebPicture">
        <label>Position</label>
          <input type="radio" name="WebPicPosition" value="right" checked="true">
          <label for="right">rechts</label>
          <input type="radio" name="WebPicPosition" value="center">
          <label for="center">mittig</label>
          <input type="radio" name="WebPicPosition" value="left">
          <label for="left">links</label>
      </div><br><br>
      <h2>Menue</h2>
      <label>Breite:</label>
        <input type="number" name="MenuWidth" min="10" max="100"><label for="MenuWidth">%</label><br><br>
      <label>Höhe:</label>
        <input type="number" name="MenuHeight" min="10" max="100"><br><br>
      <label>Position:</label>
        <input type="radio" name="MenuPosition" value="right" checked="true">
        <label for="right">rechts</label>
        <input type="radio" name="MenuPosition" value="center">
        <label for="center">mittig unter dem Header</label>
        <input type="radio" name="MenuPosition" value="left">
        <label for="left">links</label><br><br>
      <?php
        BackendComponentPrinter::PrintFontsDropdownList("Schriftart:", "MenuFont");
      ?><br><br>
      <label>Schriftgröße</label>
        <input type="number" min="2" max="50" name="MenuFontsize"><br><br>
      <label>Schriftfarbe: </label>
        <input type="color" name="MenuFontColor" value="#000000"><br><br>
      <label>Hintergrundfarbe: </label>
        <input type="color" name="MenuBackgroundColor" value="#000000"><br><br>
      <label>Anordnung: </label>
        <input type="radio" name="Order" value="vertical" checked="true">
        <label for="vertical">vertikal</label>
        <input type="radio" name="Order" value="horizontal">
        <label for="horizontal">horizontal</label><br><br>
      <h2>Artikelcontainer</h2>
      <label>Position:</label>
        <input type="radio" name="ArticlePosition" value="right" checked="true">
        <label for="right">rechts</label>
        <input type="radio" name="ArticlePosition" value="center">
        <label for="center">mittig</label>
        <input type="radio" name="ArticlePosition" value="left">
        <label for="left">links</label><br><br>
      <label>Artikelanzahl auf einer Seite</label><br><br>
        <input type="number" name="Articlenumber" min="1" max="250" onclick="onArticleNavSettings()">
        <div class="Sitenavigation">
          <label>Navigation innerhalb der Artikelseiten:</label><br><br>
            <label>Auswahl der Navigation:</label>
              <input type="radio" name="Navigation" value="ArrowButton" checked="true">
              <label for="ArrowButton">Pfeile als Button</label>
              <input type="radio" name="Navigation" value="ArrowSymbole">
              <label for="ArrowSymbole">Pfeile als Symbol</label>
              <input type="radio" name="Navigation" value="NumberButton">
              <label for="NumberButton">Nummerierung als Button</label>
              <input type="radio" name="Navigation" value="NumberSymbole">
              <label for="NumberSymbole">Nummerierung als Symbol</label>
            <br><br>
            <label>Position:</label>
              <input type="radio" name="NavigationPosition" value="top" checked="true">
              <label for="top">oben</label>
              <input type="radio" name="NavigationPosition" value="bottom">
              <label for="bottom">unten</label>
              <input type="radio" name="NavigationPosition" value="top/bottom">
              <label for="top/bottom">oben und unten</label><br><br>
            <?php
              BackendComponentPrinter::PrintFontsDropdownList("Schriftart:", "NavFont");
            ?><br><br>
            <label>Schriftfarbe:</label>
              <input type="color" name="NavFontColor" value="#000000"><br><br>
            <label>Schriftgröße</label>
              <input type="number" name="NavFontsize" min="2" max="50"><br><br>
            <label>Buttonhintergrund:</label>
              <input type="color" name="NavButtonBackgroundColor" value="#000000"><br><br>
        </div><br><br>
        <label>Hintergrund:</label>
          <input type="radio" name="ArticleBackground" value="ArticleBackgroundColor" checked="true" onclick="onArticleBackColorClick()">
          <label for="ArticleBackgroundColor">Farbe</label>
            <div class="ArticleBackgroundColorDiv">
              <input type="color" name="ArticleBackColor" value="#000000">
            </div>
          <input type="radio" name="ArticleBackground" value="ArticleBackgroundPic" onclick="onArticleBackPicClick()">
          <label for="ArticleBackgroundPic">Bild</label>
            <div class="ArticleBackgroundPicDiv">
              <input type="file" name="ArticleBackgroundPicture">
            </div><br><br>
        <label>Artikelcontainerbreite:</label>
          <input type="number" name="ArticleWidth" min="10" max="100"><label for="ArticleWidth">%</label><br><br>
    <h2>Footer</h2>
      <label>Hoehe:</label>
        <input type="number" name="FooterHeight" min="10" max="25"><label for="FooterHeight">%</label><br><br>
      <?php
        BackendComponentPrinter::PrintFontsDropdownList("Schriftart:", "FooterFont");
      ?><br><br>
      <label>Schriftgröße:</label>
        <input type="number" name="FooterFontsize" min="2" max="50"><br><br>
      <label>Schriftfarbe:</label>
        <input type="color" name="FooterFontColor" value="#000000"><br><br>
      <label>Hintergrund:</label>
        <input type="radio" name="FooterBackground" value="FooterBackgroundColor" checked="true" onclick="onFooterBackColorClick()">
        <label for="FooterBackgroundColor">Farbe</label>
          <div class="FooterBackColor">
            <input type="color" name="FooterBackColorPicker" value="#000000">
          </div>
        <input type="radio" name="FooterBackground" value="FooterBackgroundPic" onclick="onFooterBackPicClick()">
        <label for="FooterBackgroundPic">Bild</label>
          <div class="FooterBackPic">
            <input type="file" name="FooterBackPicture">
          </div><br><br>
      <label>Anordnung innerhalb des Footers:</label>
        <input type="radio" name="OrderFooter" value="Vertical" checked="true">
        <label for="Vertical">vertikal</label>
        <input type="radio" name="OrderFooter" value="Horizontal">
        <label for="Horizontal">horizontal</label><br><br>
    <h2>Buttondesign</h2>
      <label>abgerundet:</label>
        <input type="radio" name="ButtonRounded" value="Rounded" checked="true">
        <label for="Rounded">ja</label>
        <input type="radio" name="ButtonRounded" value="Rectangular">
        <label for="Rectangular">nein</label><br><br>
      <label>3D:</label>
        <input type="radio" name="Button3D" value="ja" checked="true">
        <label for="ja">ja</label>
        <input type="radio" name="Button3D" value="nein">
        <label for="nein">nein</label><br><br>
      <?php
        BackendComponentPrinter::PrintFontsDropdownList("Schriftart:", "ButtonFont");
      ?><br><br>
      <label>Schriftgröße:</label>
        <input type="number" name="ButtonFontsize" min="2" max="50"><br><br>
      <label>Schriftfarbe:</label>
        <input type="color" name="ButtonFontColor" value="#000000"><br><br>
      <label>Hintergrund:</label>
        <input type="radio" name="ButtonBackground" value="ButtonBackColor" checked="true" onclick="onBtnBackColorClick()">
        <label for="ButtonBackColor">Farbe</label>
          <div class="BtnBackColor">
            <input type="color" name="ButtonBackgroundColor" value="#000000">
          </div>
        <input type="radio" name="ButtonBackground" value="ButtonBackPic" onclick="onBtnBackPicClick()">
        <label for="ButtonBackPic">Bild</label>
          <div class="BtnBackPic">
            <input type="file" name="ButtonBackgroundPic">
          </div><br><br>
      <label>PlugIns:</label>
        <input type="checkbox" name="SearchField">
        <label for="SearchField">Suchfeld</label>
        <input type="checkbox" name="Login">
        <label for="Login">Login</label>
        <br><br><br>
      <label>Name des erstellten Templates:</label>
        <input type="text" name="TemplateName">
    <input type="submit" name="save" value="speichern">
    </form>
	</main>
</body>
</html>
