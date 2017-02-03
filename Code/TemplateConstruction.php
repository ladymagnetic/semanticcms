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
      $(".ArticleBackgroundColorDiv").hide();
      $(".ArticleBackgroundPicDiv").hide();
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
require_once 'lib/dbContent.class.php';

use SemanticCms\FrontendGenerator\TemplateParser;
use SemanticCms\Model\Permission;
use SemanticCms\DatabaseAbstraction\dbContent;


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

$dbContent = new DbContent($config['cms_db']['dbhost'], $config['cms_db']['dbuser'], $config['cms_db']['dbpass'], $config['cms_db']['database']);

if(isset($_POST['save'])) {
  $templateParser = new TemplateParser();
  $backgroundColor = $_POST['BackgroundColor'];
  $backgroundPicture = 'hallo';//$_POST['BackgroundPicture'];
  $height = $_POST['Height'];
  $font = $_POST['Font'];
  $fontsize = $_POST['Fontsize'];
  $fontColor = $_POST['FontColor'];
  $position = $_POST['Position'];
  $filename = $_FILES['Logo']['name'];
  $filesize = $_FILES['Logo']['size'];
  $filetmpname = $_FILES['Logo']['tmp_name'];
  $filetype = $_FILES['Logo']['type'];
  $logo = "";
  if($filename != "")
  {
  $logo = PictureSave($filename, $filesize, $filetmpname, $filetype);
  }
  $templateParser->SaveHeader($height, $position, $font, $fontsize, $fontColor, $backgroundColor, $backgroundPicture, $logo);
  $webBackgroundcolor = $_POST['WebColor'];
  $webBackgroundpic = 'hallo';//$_POST['WebPicture'];
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
  $articleBackgroundPicture = 'hallo';//$_POST['ArticleBackgroundPicture'];
  $articleWidth = $_POST['ArticleWidth'];
  $templateParser->SaveArticleContainer($articlePosition, $articleNumber, $navigation, $navigationPostion, $navFont, $navFontsize, $navFontColor, $navButtonBackgroundColor, $articleBackColor, $articleBackgroundPicture, $articleWidth);
  $footerHeight = $_POST['FooterHeight'];
  $footerFont = $_POST['FooterFont'];
  $footerFontsize = $_POST['FooterFontsize'];
  $footerFontColor = $_POST['FooterFontColor'];
  $footerBackColorPicker = $_POST['FooterBackColorPicker'];
  $footerBackPicture = 'hallo';//$_POST['FooterBackPicture'];
  $orderFooter = $_POST['OrderFooter'];
  $templateParser->SaveFooter($footerHeight, $footerFont, $footerFontsize, $footerFontColor, $footerBackColorPicker, $footerBackPicture, $orderFooter);
  $buttonRounded = $_POST['ButtonRounded'];
  $button3D = $_POST['Button3D'];
  $buttonFont = $_POST['ButtonFont'];
  $buttonFontsize = $_POST['ButtonFontsize'];
  $buttonFontColor = $_POST['ButtonFontColor'];
  $buttonBackgroundColor = $_POST['ButtonBackgroundColor'];
  $buttonBackgroundPic = 'hallo';//$_POST['ButtonBackgroundPic'];
  $templateParser->SaveButton($buttonRounded, $button3D, $buttonFont, $buttonFontsize, $buttonFontColor, $buttonBackgroundColor, $buttonBackgroundPic);
  $loginBackgroundColor = $_POST['LoginBackgroundColor'];
  $loginForegroundColor = $_POST['LoginForegroundColor'];
  $loginFont = $_POST['LoginFont'];
  $loginFontsize = $_POST['LoginFontsize'];
  $loginFontColor = $_POST['LoginFontColor'];
  $templateParser->SaveLogin($loginBackgroundColor, $loginForegroundColor, $loginFont, $loginFontsize, $loginFontColor);
  $labelBackgroundColor = $_POST['LabelBackgroundColor'];
  $labelfont = $_POST['LabelFont'];
  $labelFontsize = $_POST['LabelFontsize'];
  $labelFontColor = $_POST['LabelFontColor'];
  $labelRounded = $_POST['LabelRounded'];
  $templateParser->SaveLabel($labelBackgroundColor, $labelfont, $labelFontsize, $labelFontColor, $labelRounded);
  $templateName = $_POST['TemplateName'];
  $templateParser->SaveTemplate($templateName);
}
else if (isset($_POST['create'])) {
  CreateTemplate();
  return;
}
else if (isset($_POST['edit'])) {
  //brauch ich alle Informationen über das Template
  $templateParser = new TemplateParser();
  $templateName = intval($_POST['templateName']);
  $header = $templateParser->GetHeader($templateName);
  $background = $templateParser->GetBackground($templateName);
  $menu = $templateParser->GetMenu($templateName);
  $articleContainer = $templateParser->GetArticleContainer($templateName);
  $footer = $templateParser->GetFooter($templateName);
  $button = $templateParser->GetButton($templateName);
  $label = $templateParser->GetLabel($templateName);
  EditTemplate($header, $background, $menu, $articleContainer, $footer, $button, $label);
  return;
}
else if (isset($_POST['delete'])) {
  $templateId = intval($_POST['templateId']);
  $dbContent->DeleteTemplateById($templateId);
  unlink("templates/".$_POST['templateName'].".xml");
}

?>

<main>
    <h1><i></i>Templates</h1>

		<?php
    BackendComponentPrinter::PrintTableStart(array("Templatename", "Aktion"));

		$templates = $dbContent->SelectAllTemplates();
		$i=0;
		while ($row = $dbContent->FetchArray($templates)) {

			if($i<15)
			{
				$tableRow1 = $row['templatename'];
				$tableRow2 =
            "<form method='post' action='TemplateConstruction.php'>"
            ."<input name='edit' type='submit' value='Bearbeiten'><input name='delete' type='submit' value='löschen'>"
            ."<input name='templateName' type='hidden' value='".$row['templatename']."'>"
            ."<input name='templateId' type='hidden' value='".$row['id']."'>"
            ."</form>";

				BackendComponentPrinter::PrintTableRow(array($tableRow1, $tableRow2));
			}
			$i++;

		}

		BackendComponentPrinter::PrintTableEnd();



echo
  "<form action='TemplateConstruction.php' method='post'>
    <br><br><input type='submit' name='create' value='Template erstellen'><br><br><br>
  </form>

</main>
</body>

</html>";
?>


























<?php


function CreateTemplate()
{
  BackendComponentPrinter::printSidebar($_SESSION['permissions']);
  echo
          "<body><main>
        		<h1><i class='fa fa-paint-brush fontawesome'></i> Templates</h1>
            <form enctype='multipart/form-data' action='TemplateConstruction.php' method='post'>
            <h2>Header</h2>
            <label>Position: </label>

              <input type='radio' name='Position' value='right' checked='true'>
              <label for='right'>rechts</label>
              <input type='radio' name='Position' value='center'>
              <label for='center'>mittig</label>
              <input type='radio' name='Position' value='left'>
              <label for='left'>links</label>

            <br><br>

            <label>Hoehe: </label>
              <input type='number' min='10' max='25' name='Height'><label for='Height'>%</label>
              <br><br>
            <label>Hintergrund: </label>

              <input type='radio' name='Background' value='Color' checked='true' onclick='onColorPicker()'>
              <label for='Color'> Farbe</label>
              <div class='Colorpicker' >
                <input type='color' name='BackgroundColor' value='#000000'>
              </div>

              <input type='radio' name='Background' value='Picture' onclick='onPictureUpload()'>
              <label for='Picture'> Bild</label>
              <div class='PictureUpload'>
                <input type='file' name='BackgroundPicture'>
              </div>

            <br><br>";


              BackendComponentPrinter::PrintFontsDropdownList("Schriftart:", "Font");

  echo
            "<br><br>
            <label>Schriftgröße: </label>
              <input type='number' min='2' max='50' name='Fontsize'>
              <br><br>
            <label>Schriftfarbe: </label>
              <input type='color' name='FontColor' value='#000000'>
              <br><br>
            <label>Logo: </label>
              <input name='Logo' type='file' accept='image/jpeg,image/gif,image/x-png'>
              <br><br>
            <h2>Hintergrund von der Website</h2>
              <input type='radio' name='WebsiteBackground' value='WebsiteColor' checked='true' onclick='onWebsiteColorPicker()'>
              <label for='WebsiteColor'> Farbe</label>
              <div class='WebsiteColerPicker'>
                <input type='color' name='WebColor' value='#000000'>
              </div>
              <input type='radio' name='WebsiteBackground' value='WebsitePicture' onclick='onWebsitePictureUpload()'>
              <label for='WebsitePicture'> Bild</label>
              <div class='WebsitePictureUpload'>
                <input type='file' name='WebPicture'>
                <label>Position</label>
                  <input type='radio' name='WebPicPosition' value='right' checked='true'>
                  <label for='right'>rechts</label>
                  <input type='radio' name='WebPicPosition' value='center'>
                  <label for='center'>mittig</label>
                  <input type='radio' name='WebPicPosition' value='left'>
                  <label for='left'>links</label>
              </div><br><br>
              <h2>Menue</h2>
              <label>Breite:</label>
                <input type='number' name='MenuWidth' min='10' max='100'><label for='MenuWidth'>%</label><br><br>
              <label>Höhe:</label>
                <input type='number' name='MenuHeight' min='10' max='100'><label for='MenuHeight'>px</label><br><br>
              <label>Position:</label>
                <input type='radio' name='MenuPosition' value='right' checked='true'>
                <label for='right'>rechts</label>
                <input type='radio' name='MenuPosition' value='center'>
                <label for='center'>mittig unter dem Header</label>
                <input type='radio' name='MenuPosition' value='left'>
                <label for='left'>links</label><br><br>";

                BackendComponentPrinter::PrintFontsDropdownList("Schriftart:", "MenuFont");
  echo
                "<br><br>
              <label>Schriftgröße</label>
                <input type='number' min='2' max='50' name='MenuFontsize'><br><br>
              <label>Schriftfarbe: </label>
                <input type='color' name='MenuFontColor' value='#000000'><br><br>
              <label>Hintergrundfarbe: </label>
                <input type='color' name='MenuBackgroundColor' value='#000000'><br><br>
              <label>Anordnung: </label>
                <input type='radio' name='Order' value='vertical' checked='true'>
                <label for='vertical'>vertikal</label>
                <input type='radio' name='Order' value='horizontal'>
                <label for='horizontal'>horizontal</label><br><br>
              <h2>Artikelcontainer</h2>
              <label>Position:</label>
                <input type='radio' name='ArticlePosition' value='right' checked='true'>
                <label for='right'>rechts</label>
                <input type='radio' name='ArticlePosition' value='center'>
                <label for='center'>mittig</label>
                <input type='radio' name='ArticlePosition' value='left'>
                <label for='left'>links</label><br><br>
              <label>Artikelanzahl auf einer Seite</label><br><br>
                <input type='number' name='Articlenumber' min='1' max='250' onclick='onArticleNavSettings()'>
                <div class='Sitenavigation'>
                  <label>Navigation innerhalb der Artikelseiten:</label><br><br>
                    <label>Auswahl der Navigation:</label>
                      <input type='radio' name='Navigation' value='ArrowButton' checked='true'>
                      <label for='ArrowButton'>Pfeile als Button</label>
                      <input type='radio' name='Navigation' value='ArrowSymbole'>
                      <label for='ArrowSymbole'>Pfeile als Symbol</label>
                      <input type='radio' name='Navigation' value='NumberButton'>
                      <label for='NumberButton'>Nummerierung als Button</label>
                      <input type='radio' name='Navigation' value='NumberSymbole'>
                      <label for='NumberSymbole'>Nummerierung als Symbol</label>
                    <br><br>
                    <label>Position:</label>
                      <input type='radio' name='NavigationPosition' value='top' checked='true'>
                      <label for='top'>oben</label>
                      <input type='radio' name='NavigationPosition' value='bottom'>
                      <label for='bottom'>unten</label>
                      <input type='radio' name='NavigationPosition' value='top/bottom'>
                      <label for='top/bottom'>oben und unten</label><br><br>";

                      BackendComponentPrinter::PrintFontsDropdownList("Schriftart:", "NavFont");
  echo
                      "<br><br>
                    <label>Schriftfarbe:</label>
                      <input type='color' name='NavFontColor' value='#000000'><br><br>
                    <label>Schriftgröße</label>
                      <input type='number' name='NavFontsize' min='2' max='50'><br><br>
                    <label>Buttonhintergrund:</label>
                      <input type='color' name='NavButtonBackgroundColor' value='#000000'><br><br>
                </div><br><br>
                <label>Hintergrund:</label>
                  <input type='radio' name='ArticleBackground' value='ArticleBackgroundColor' checked='true' onclick='onArticleBackColorClick()'>
                  <label for='ArticleBackgroundColor'>Farbe</label>
                    <div class='ArticleBackgroundColorDiv'>
                      <input type='color' name='ArticleBackColor' value='#000000'>
                    </div>
                  <input type='radio' name='ArticleBackground' value='ArticleBackgroundPic' onclick='onArticleBackPicClick()'>
                  <label for='ArticleBackgroundPic'>Bild</label>
                    <div class='ArticleBackgroundPicDiv'>
                      <input type='file' name='ArticleBackgroundPicture'>
                    </div><br><br>
                <label>Artikelcontainerbreite:</label>
                  <input type='number' name='ArticleWidth' min='10' max='100'><label for='ArticleWidth'>%</label><br><br>
            <h2>Footer</h2>
              <label>Hoehe:</label>
                <input type='number' name='FooterHeight' min='10' max='25'><label for='FooterHeight'>%</label><br><br>";

                BackendComponentPrinter::PrintFontsDropdownList("Schriftart:", "FooterFont");
  echo
                "<br><br>
              <label>Schriftgröße:</label>
                <input type='number' name='FooterFontsize' min='2' max='50'><br><br>
              <label>Schriftfarbe:</label>
                <input type='color' name='FooterFontColor' value='#000000'><br><br>
              <label>Hintergrund:</label>
                <input type='radio' name='FooterBackground' value='FooterBackgroundColor' checked='true' onclick='onFooterBackColorClick()'>
                <label for='FooterBackgroundColor'>Farbe</label>
                  <div class='FooterBackColor'>
                    <input type='color' name='FooterBackColorPicker' value='#000000'>
                  </div>
                <input type='radio' name='FooterBackground' value='FooterBackgroundPic' onclick='onFooterBackPicClick()'>
                <label for='FooterBackgroundPic'>Bild</label>
                  <div class='FooterBackPic'>
                    <input type='file' name='FooterBackPicture'>
                  </div><br><br>
              <label>Anordnung innerhalb des Footers:</label>
                <input type='radio' name='OrderFooter' value='Vertical' checked='true'>
                <label for='Vertical'>vertikal</label>
                <input type='radio' name='OrderFooter' value='Horizontal'>
                <label for='Horizontal'>horizontal</label><br><br>
            <h2>Buttondesign</h2>
              <label>abgerundet:</label>
                <input type='radio' name='ButtonRounded' value='Rounded' checked='true'>
                <label for='Rounded'>ja</label>
                <input type='radio' name='ButtonRounded' value='Rectangular'>
                <label for='Rectangular'>nein</label><br><br>
              <label>3D:</label>
                <input type='radio' name='Button3D' value='ja' checked='true'>
                <label for='ja'>ja</label>
                <input type='radio' name='Button3D' value='nein'>
                <label for='nein'>nein</label><br><br>";

                BackendComponentPrinter::PrintFontsDropdownList("Schriftart:", "ButtonFont");
  echo
                "<br><br>
              <label>Schriftgröße:</label>
                <input type='number' name='ButtonFontsize' min='2' max='50'><br><br>
              <label>Schriftfarbe:</label>
                <input type='color' name='ButtonFontColor' value='#000000'><br><br>
              <label>Hintergrund:</label>
                <input type='radio' name='ButtonBackground' value='ButtonBackColor' checked='true' onclick='onBtnBackColorClick()'>
                <label for='ButtonBackColor'>Farbe</label>
                  <div class='BtnBackColor'>
                    <input type='color' name='ButtonBackgroundColor' value='#000000'>
                  </div>
                <input type='radio' name='ButtonBackground' value='ButtonBackPic' onclick='onBtnBackPicClick()'>
                <label for='ButtonBackPic'>Bild</label>
                  <div class='BtnBackPic'>
                    <input type='file' name='ButtonBackgroundPic'>
                  </div><br><br>

            <h2>Loginfeld</h2>
              <label>Hintergrundfarbe: </label>
                <input type='color' name='LoginBackgroundColor' value='#000000'><br><br>
              <label>Vordergrundfarbe: </label>
                <input type='color' name='LoginForegroundColor' value='#000000'><br><br>";

                BackendComponentPrinter::PrintFontsDropdownList("Schriftart:", "LoginFont");
  echo
                "<br><br>
              <label>Schriftgröße: </label>
                <input type='number' name='LoginFontsize' min='2' max='50'><br><br>
              <label>Schriftfarbe:</label>
                <input type='color' name='LoginFontColor' value='#000000'><br><br>
              <h2>Label</h2>
                <label>Hintergrundfarbe: </label>
                  <input type='color' name='LabelBackgroundColor' value='#000000'><br><br>";

                  BackendComponentPrinter::PrintFontsDropdownList("Schriftart:", "LabelFont");
  echo
                  "<br><br>
                <label>Schriftgröße: </label>
                  <input type='number' name='LabelFontsize' min='2' max='50'><br><br>
                <label>Schriftfarbe:</label>
                  <input type='color' name='LabelFontColor' value='#000000'><br><br>
                <label>abgerundet:</label>
                  <input type='radio' name='LabelRounded' value='Rounded' checked='true'>
                  <label for='Rounded'>ja</label>
                  <input type='radio' name='LabelRounded' value='Rectangular'>
                  <label for='Rectangular'>nein</label><br><br><br>
              <label>Name des erstellten Templates:</label>
                <input type='text' name='TemplateName'>
            <input type='submit' name='save' value='speichern'><br><br>
            </form>
        	</main>
        </body>
        </html>";
}

function EditTemplate($header, $background, $menu, $articleContainer, $footer, $button, $Label)
{

}


function PictureSave($filename, $filesize, $filetmpname, $filetype)
{
    $upload_folder = 'frontend/media/';
    $name = pathinfo($filename, PATHINFO_FILENAME);
    $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));




    //Überprüfung der Dateiendung
    $allowed_extensions = array('image/png', 'image/jpg', 'image/jpeg', 'image/gif');
    if(!in_array(strtolower($filetype), $allowed_extensions)) {
     die("Ungültige Dateiendung. Nur png, jpg, jpeg und gif-Dateien sind erlaubt");
   }

    //Überprüfung der Dateigröße
    $max_size = 5*1024*1024;
    if($filesize > $max_size) {
     die("Bitte keine Dateien größer 5Mb hochladen");
    }

    //Überprüfung dass das Bild keine Fehler enthält
    if(function_exists('exif_imagetype')) { //exif_imagetype erfordert die exif-Erweiterung
     $allowed_types = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);
     $detected_type = exif_imagetype($filetmpname);
     if(!in_array($detected_type, $allowed_types)) {
     die("Nur der Upload von Bilddateien ist gestattet");
     }
    }

    //Pfad zum Upload
    $new_path = $upload_folder.$name.'.'.$extension;
    $picturename = $name.'.'.$extension;

    //Neuer Dateiname falls die Datei bereits existiert
    if(file_exists($new_path)) { //Falls Datei existiert, hänge eine Zahl an den Dateinamen
     $id = 1;
     do {
     $new_path = $upload_folder.$name.'_'.$id.'.'.$extension;
     $picturename = $name.'_'.$id.'.'.$extension;
     $id++;
     } while(file_exists($new_path));
    }

    //Alles okay, verschiebe Datei an neuen Pfad
    move_uploaded_file($filetmpname, $new_path);

    return $picturename;


}


?>
