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
      if(document.getElementById('HeaderBackgroundColor').checked)
      {
        $(".Colorpicker").show();
        $(".PictureUpload").hide();
      }
      if(document.getElementById('HeaderBackgroundPic').checked)
      {
        $(".PictureUpload").show();
        $(".Colorpicker").hide();
      }
      if(document.getElementById('WebsiteBackgroundColor').checked)
      {
        $(".WebsiteColerPicker").show();
        $(".WebsitePictureUpload").hide();
      }
      if(document.getElementById('WebsiteBackgroundPic').checked)
      {
        $(".WebsitePictureUpload").show();
        $(".WebsiteColerPicker").hide();
      }
      if(document.getElementById('ArticleBackColorPicker').checked)
      {
        $(".ArticleBackgroundColorDiv").show();
        $(".ArticleBackgroundPicDiv").hide();
      }
      if(document.getElementById('ArticleBackPicPicker').checked)
      {
        $(".ArticleBackgroundPicDiv").show();
        $(".ArticleBackgroundColorDiv").hide();
      }
      if(document.getElementById('FooterBackColPicker').checked)
      {
        $(".FooterBackColor").show();
        $(".FooterBackPic").hide();
      }
      if(document.getElementById('FooterBackPicPicker').checked)
      {
        $(".FooterBackPic").show();
        $(".FooterBackColor").hide();
      }
      if(document.getElementById('ButtonBackgroundColorPicker').checked)
      {
        $(".BtnBackColor").show();
        $(".BtnBackPic").hide();
      }
      if(document.getElementById('ButtonBackgroundPicPicker').checked)
      {
        $(".BtnBackPic").show();
        $(".BtnBackColor").hide();
      }


      $(".Sitenavigation").hide();
    });

    function onColorPicturePicker()
    {
      if(document.getElementById('HeaderBackgroundColor').checked)
      {

        document.getElementsByName('BackgroundPicture').value = "";

        $(".Colorpicker").show();
        $(".PictureUpload").hide();


      }
      if(document.getElementById('HeaderBackgroundPic').checked)
      {

        $(".PictureUpload").show();
        $(".Colorpicker").hide();

      }
      if(document.getElementById('WebsiteBackgroundColor').checked)
      {
        document.getElementsByName('WebPicture').value = "";
        $(".WebsiteColerPicker").show();
        $(".WebsitePictureUpload").hide();
      }
      if(document.getElementById('WebsiteBackgroundPic').checked)
      {

        $(".WebsitePictureUpload").show();
        $(".WebsiteColerPicker").hide();
      }
      if(document.getElementById('ArticleBackColorPicker').checked)
      {
        document.getElementsByName('ArticleBackgroundPicture').value = "";
        $(".ArticleBackgroundColorDiv").show();
        $(".ArticleBackgroundPicDiv").hide();
      }
      if(document.getElementById('ArticleBackPicPicker').checked)
      {
        $(".ArticleBackgroundPicDiv").show();
        $(".ArticleBackgroundColorDiv").hide();
      }
      if(document.getElementById('FooterBackColPicker').checked)
      {
        document.getElementsByName('FooterBackPicture').value = "";
        $(".FooterBackColor").show();
        $(".FooterBackPic").hide();
      }
      if(document.getElementById('FooterBackPicPicker').checked)
      {
        $(".FooterBackPic").show();
        $(".FooterBackColor").hide();
      }
      if(document.getElementById('ButtonBackgroundColorPicker').checked)
      {
        document.getElementsByName('ButtonBackgroundPic').value = "";
        $(".BtnBackColor").show();
        $(".BtnBackPic").hide();
      }
      if(document.getElementById('ButtonBackgroundPicPicker').checked)
      {
        $(".BtnBackPic").show();
        $(".BtnBackColor").hide();
      }
    }

    function onNavigation()
    {
      $(".Sitenavigation").show();


      if(document.getElementById('Sitenumber').value == "0")
      {
      document.getElementById('Sitenumber').value = "";


        $(".Sitenavigation").hide();
      }
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
  $backgroundPicture = "";
  $filename = $_FILES['BackgroundPicture']['name'];
  $filesize = $_FILES['BackgroundPicture']['size'];
  $filetmpname = $_FILES['BackgroundPicture']['tmp_name'];
  $filetype = $_FILES['BackgroundPicture']['type'];
  if($filename != "")
  {
  $backgroundPicture = PictureSave($filename, $filesize, $filetmpname, $filetype);
  }
  else
  {
  $backgroundPicture = $_POST['HeaderBackground'];
  }
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
  $webBackgroundpic = '';
  $filename = $_FILES['WebPicture']['name'];
  $filesize = $_FILES['WebPicture']['size'];
  $filetmpname = $_FILES['WebPicture']['tmp_name'];
  $filetype = $_FILES['WebPicture']['type'];
  if($filename != "")
  {
  $webBackgroundpic = PictureSave($filename, $filesize, $filetmpname, $filetype);
  }
  else
  {
  $webBackgroundpic = $_POST['WebsiteBack'];
  }
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
  $articleBackgroundPicture = '';
  $filename = $_FILES['WebPicture']['name'];
  $filesize = $_FILES['WebPicture']['size'];
  $filetmpname = $_FILES['WebPicture']['tmp_name'];
  $filetype = $_FILES['WebPicture']['type'];
  if($filename != "")
  {
  $articleBackgroundPicture = PictureSave($filename, $filesize, $filetmpname, $filetype);
  }
  else
  {
  $articleBackgroundPicture = $_POST['ArticleBack'];
  }
  $articleWidth = $_POST['ArticleWidth'];
  $templateParser->SaveArticleContainer($articlePosition, $articleNumber, $navigation, $navigationPostion, $navFont, $navFontsize, $navFontColor, $navButtonBackgroundColor, $articleBackColor, $articleBackgroundPicture, $articleWidth);
  $footerHeight = $_POST['FooterHeight'];
  $footerFont = $_POST['FooterFont'];
  $footerFontsize = $_POST['FooterFontsize'];
  $footerFontColor = $_POST['FooterFontColor'];
  $footerBackColorPicker = $_POST['FooterBackColorPicker'];
  $footerBackPicture = '';
  $filename = $_FILES['WebPicture']['name'];
  $filesize = $_FILES['WebPicture']['size'];
  $filetmpname = $_FILES['WebPicture']['tmp_name'];
  $filetype = $_FILES['WebPicture']['type'];
  if($filename != "")
  {
  $footerBackPicture = PictureSave($filename, $filesize, $filetmpname, $filetype);
  }
  else
  {
  $footerBackPicture = $_POST['FooterBack'];
  }
  $orderFooter = $_POST['OrderFooter'];
  $templateParser->SaveFooter($footerHeight, $footerFont, $footerFontsize, $footerFontColor, $footerBackColorPicker, $footerBackPicture, $orderFooter);
  $buttonRounded = $_POST['ButtonRounded'];
  $button3D = $_POST['Button3D'];
  $buttonFont = $_POST['ButtonFont'];
  $buttonFontsize = $_POST['ButtonFontsize'];
  $buttonFontColor = $_POST['ButtonFontColor'];
  $buttonBackgroundColor = $_POST['ButtonBackgroundColor'];
  $buttonBackgroundPic = '';
  $filename = $_FILES['WebPicture']['name'];
  $filesize = $_FILES['WebPicture']['size'];
  $filetmpname = $_FILES['WebPicture']['tmp_name'];
  $filetype = $_FILES['WebPicture']['type'];
  if($filename != "")
  {
  $buttonBackgroundPic = PictureSave($filename, $filesize, $filetmpname, $filetype);
  }
  else
  {
  $buttonBackgroundPic = $_POST['ButtonBack'];
  }
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
else if(isset($_POST['save2']))
{

    $templateParser = new TemplateParser();
    $backgroundColor = $_POST['BackgroundColor'];
    $backgroundPicture = "";
    $filename = $_FILES['BackgroundPicture']['name'];
    $filesize = $_FILES['BackgroundPicture']['size'];
    $filetmpname = $_FILES['BackgroundPicture']['tmp_name'];
    $filetype = $_FILES['BackgroundPicture']['type'];
    if($filename != "")
    {
    $backgroundPicture = PictureSave($filename, $filesize, $filetmpname, $filetype);
    }

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
    $webBackgroundpic = '';
    $filename = $_FILES['WebPicture']['name'];
    $filesize = $_FILES['WebPicture']['size'];
    $filetmpname = $_FILES['WebPicture']['tmp_name'];
    $filetype = $_FILES['WebPicture']['type'];
    if($filename != "")
    {
    $webBackgroundpic = PictureSave($filename, $filesize, $filetmpname, $filetype);
    }

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
    $articleBackgroundPicture = '';
    $filename = $_FILES['WebPicture']['name'];
    $filesize = $_FILES['WebPicture']['size'];
    $filetmpname = $_FILES['WebPicture']['tmp_name'];
    $filetype = $_FILES['WebPicture']['type'];
    if($filename != "")
    {
    $articleBackgroundPicture = PictureSave($filename, $filesize, $filetmpname, $filetype);
    }

    $articleWidth = $_POST['ArticleWidth'];
    $templateParser->SaveArticleContainer($articlePosition, $articleNumber, $navigation, $navigationPostion, $navFont, $navFontsize, $navFontColor, $navButtonBackgroundColor, $articleBackColor, $articleBackgroundPicture, $articleWidth);
    $footerHeight = $_POST['FooterHeight'];
    $footerFont = $_POST['FooterFont'];
    $footerFontsize = $_POST['FooterFontsize'];
    $footerFontColor = $_POST['FooterFontColor'];
    $footerBackColorPicker = $_POST['FooterBackColorPicker'];
    $footerBackPicture = '';
    $filename = $_FILES['WebPicture']['name'];
    $filesize = $_FILES['WebPicture']['size'];
    $filetmpname = $_FILES['WebPicture']['tmp_name'];
    $filetype = $_FILES['WebPicture']['type'];
    if($filename != "")
    {
    $footerBackPicture = PictureSave($filename, $filesize, $filetmpname, $filetype);
    }
    $orderFooter = $_POST['OrderFooter'];
    $templateParser->SaveFooter($footerHeight, $footerFont, $footerFontsize, $footerFontColor, $footerBackColorPicker, $footerBackPicture, $orderFooter);
    $buttonRounded = $_POST['ButtonRounded'];
    $button3D = $_POST['Button3D'];
    $buttonFont = $_POST['ButtonFont'];
    $buttonFontsize = $_POST['ButtonFontsize'];
    $buttonFontColor = $_POST['ButtonFontColor'];
    $buttonBackgroundColor = $_POST['ButtonBackgroundColor'];
    $buttonBackgroundPic = '';
    $filename = $_FILES['WebPicture']['name'];
    $filesize = $_FILES['WebPicture']['size'];
    $filetmpname = $_FILES['WebPicture']['tmp_name'];
    $filetype = $_FILES['WebPicture']['type'];
    if($filename != "")
    {
    $buttonBackgroundPic = PictureSave($filename, $filesize, $filetmpname, $filetype);
    }

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
  $templateName = $_POST['templateName'];
  $header = $templateParser->GetHeader($templateName);
  $background = $templateParser->GetBackground($templateName);
  $menu = $templateParser->GetMenu($templateName);
  $articleContainer = $templateParser->GetArticleContainer($templateName);
  $footer = $templateParser->GetFooter($templateName);
  $button = $templateParser->GetButton($templateName);
  $login = $templateParser->GetLogin($templateName);
  $label = $templateParser->GetLabel($templateName);
  EditTemplate($header, $background, $menu, $articleContainer, $footer, $button, $login, $label, $templateName);
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
            <pre><label>Position: </label>

              <input type='radio' name='Position' value='right' checked='true'> <label for='right'>rechts</label>

              <input type='radio' name='Position' value='center'> <label for='center'>mittig</label>

              <input type='radio' name='Position' value='left'> <label for='left'>links</label>

            <br><br></pre>

            <label>Hoehe: </label>
              <input type='number' min='10' max='25' name='Height'><label for='Height'>%</label>
              <br><br>
            <label>Hintergrund: </label>

              <input type='radio' name='Background' id='HeaderBackgroundColor' value='Color' checked='true' onclick='onColorPicturePicker()'>
              <label for='Color'> Farbe</label>
              <div class='Colorpicker' >
                <input type='color' name='BackgroundColor' value='#000000'>
              </div>

              <input type='radio' name='Background' id='HeaderBackgroundPic' value='Picture' onclick='onColorPicturePicker()'>
              <label for='Picture'> Bild</label>
              <div class='PictureUpload'>
                <input type='file' name='BackgroundPicture'>
              </div>

            <br><br>

            <pre>";
              BackendComponentPrinter::PrintFontsDropdownList("Schriftart:", "Font");

  echo
            "<br><br></pre>
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
            <pre>
              <input type='radio' name='WebsiteBackground' id='WebsiteBackgroundColor' value='WebsiteColor' checked='true' onclick='onColorPicturePicker()'>
              <label for='WebsiteColor'> Farbe</label>
              <div class='WebsiteColerPicker'>
                <input type='color' name='WebColor' value='#000000'>
              </div>
              <input type='radio' name='WebsiteBackground' id='WebsiteBackgroundPic' value='WebsitePicture' onclick='onColorPicturePicker()'>
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
              </div><br><br></pre>
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
              <label>Hintergrund:</label>
                <input type='radio' name='ArticleBackground' id='ArticleBackColorPicker' value='ArticleBackgroundColor' checked='true' onclick='onColorPicturePicker()'>
                <label for='ArticleBackgroundColor'>Farbe</label>
                  <div class='ArticleBackgroundColorDiv'>
                    <input type='color' name='ArticleBackColor' value='#000000'>
                  </div>
                <input type='radio' name='ArticleBackground' id='ArticleBackPicPicker' value='ArticleBackgroundPic' onclick='onColorPicturePicker()'>
                <label for='ArticleBackgroundPic'>Bild</label>
                  <div class='ArticleBackgroundPicDiv'>
                    <input type='file' name='ArticleBackgroundPicture'>
                  </div><br><br>
              <label>Artikelcontainerbreite:</label>
                <input type='number' name='ArticleWidth' min='10' max='100'><label for='ArticleWidth'>%</label><br><br>
              <label>Artikelanzahl auf einer Seite</label><br><br>
                <input type='number' name='Articlenumber'  id='Sitenumber' min='0' max='250' onchange='onNavigation()'>
                <div class='Sitenavigation'>
                  <h4>Navigation innerhalb der Artikelseiten:</h4><br><br>
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
                <input type='radio' name='FooterBackground' id='FooterBackColPicker' value='FooterBackgroundColor' checked='true' onclick='onColorPicturePicker()'>
                <label for='FooterBackgroundColor'>Farbe</label>
                  <div class='FooterBackColor'>
                    <input type='color' name='FooterBackColorPicker' value='#000000'>
                  </div>
                <input type='radio' name='FooterBackground' id='FooterBackPicPicker' value='FooterBackgroundPic' onclick='onColorPicturePicker()'>
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
                <input type='radio' name='ButtonBackground' id='ButtonBackgroundColorPicker' value='ButtonBackColor' checked='true' onclick='onColorPicturePicker()'>
                <label for='ButtonBackColor'>Farbe</label>
                  <div class='BtnBackColor'>
                    <input type='color' name='ButtonBackgroundColor' value='#000000'>
                  </div>
                <input type='radio' name='ButtonBackground' id='ButtonBackgroundPicPicker' value='ButtonBackPic' onclick='onColorPicturePicker()'>
                <label for='ButtonBackPic'>Bild</label>
                  <div class='BtnBackPic'>
                    <input type='file' name='ButtonBackgroundPic'>
                  </div><br><br>

            <h2>Loginfeld/Registrierung</h2>
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
            <input type='submit' name='save2' value='speichern'><br><br>
            </form>
        	</main>
        </body>
        </html>";
}

function EditTemplate($header, $background, $menu, $articleContainer, $footer, $button, $login, $label, $templateName)
{
  BackendComponentPrinter::printSidebar($_SESSION['permissions']);
  echo
          "<body><main>
        		<h1><i class='fa fa-paint-brush fontawesome'></i> Templates</h1>
            <form enctype='multipart/form-data' action='TemplateConstruction.php' method='post'>
            <h2>Header</h2>";


            if($header['Position'] == 'right')
            {
              echo
                    "<pre><label>Position: </label>

                    <input type='radio' name='Position' value='right' checked='true'> <label for='right'>rechts</label>
                    <input type='radio' name='Position' value='center'> <label for='center'>mittig</label>
                    <input type='radio' name='Position' value='left'> <label for='left'>links</label>

                  <br><br></pre>";
            }
            else if($header['Position'] == 'center')
            {
              echo
                    "<pre><label>Position: </label>

                    <input type='radio' name='Position' value='right' > <label for='right'>rechts</label>
                    <input type='radio' name='Position' value='center' checked='true'> <label for='center'>mittig</label>
                    <input type='radio' name='Position' value='left'> <label for='left'>links</label>

                  <br><br></pre>";
            }
            else if($header['Position'] == 'left')
            {
              echo
                    "<pre><label>Position: </label>

                    <input type='radio' name='Position' value='right' > <label for='right'>rechts</label>
                    <input type='radio' name='Position' value='center'> <label for='center'>mittig</label>
                    <input type='radio' name='Position' value='left' checked='true'> <label for='left'>links</label>

                  <br><br></pre>";
            }


    echo
            "<pre><label>Hoehe: </label>

              <input type='number' min='10' max='25' name='Height' value='".$header['Height']."'><label for='Height'>%</label>

              <br><br></pre>";

              if($header['Backgroundpic'] == '')
              {
                echo "<pre><label>Hintergrund: </label>

                  <input type='radio' name='Background' id='HeaderBackgroundColor' value='Color' checked='true' onclick='onColorPicturePicker()'> <label for='Color'> Farbe</label>
                    <div class='Colorpicker' >
                      <input type='color' name='BackgroundColor' id='BackgroundColor' value='".$header['Backgroundcolor']."'>
                    </div>

                  <input type='radio' name='Background' id='HeaderBackgroundPic' value='Picture' onclick='onColorPicturePicker()'> <label for='Picture'> Bild</label>
                    <div class='PictureUpload'>
                      <input type='file' name='BackgroundPicture' accept='image/jpeg,image/gif,image/x-png'>
                    </div>

                <br><br></pre>";
              }
              else if ($header['Backgroundpic'] != '')
              {
                echo "<pre><label>Hintergrund: </label>

                  <input type='radio' name='Background' id='HeaderBackgroundColor' value='Color'  onclick='onColorPicturePicker()'> <label for='Color'> Farbe</label>
                    <div class='Colorpicker' >
                      <input type='color' name='BackgroundColor' value='#ffffff'>
                    </div>

                  <input type='radio' name='Background' id='HeaderBackgroundPic' value='Picture' checked='true' onclick='onColorPicturePicker()'> <label for='Picture'> Bild</label>
                    <div class='PictureUpload'>
                      <label>ausgewähltes Bild:<input name='HeaderBackground' type='hidden' value='".$header['Backgroundpic']."'>                                 ".$header['Backgroundpic']."</label>

                      <label>neues Hintergrundbild auswählen:  </label>                  <input type='file' name='BackgroundPicture' accept='image/jpeg,image/gif,image/x-png'>
                    </div>

                <br><br></pre>";
              }

  echo
            "<pre>";
              BackendComponentPrinter::PrintFontsDropdownList("Schriftart:", "Font", $header['Font']);

  echo
            "<br><br></pre>
            <pre><label>Schriftgröße: </label>

                <input type='number' min='2' max='50' name='Fontsize' value='".$header['Fontsize']."'>
                <br><br></pre>
            <pre><label>Schriftfarbe: </label>

                <input type='color' name='FontColor' value='".$header['Fontcolor']."'>
                <br><br></pre>
            <pre><label>Logo: </label>

                <label>ausgewähltes Bild:<input name='HeaderLogo' type='hidden' value='".$header['Logo']."'>                                 ".$header['Logo']."</label>

                <label>neues Logo auswählen:  </label>                  <input name='Logo' type='file' accept='image/jpeg,image/gif,image/x-png'>
                <br><br></pre>
            <h2>Hintergrund von der Website</h2>";
            if($background['Picture'] == '')
            {
              echo
                  "<pre>
                    <input type='radio' name='WebsiteBackground' id='WebsiteBackgroundColor' value='WebsiteColor' checked='true' onclick='onColorPicturePicker()'><label for='WebsiteColor'> Farbe</label>
                      <div class='WebsiteColerPicker'>
                          <input type='color' name='WebColor' value='".$background['Color']."'>
                      </div>

                    <input type='radio' name='WebsiteBackground' id='WebsiteBackgroundPic' value='WebsitePicture' onclick='onColorPicturePicker()'><label for='WebsitePicture'> Bild</label>
                    <div class='WebsitePictureUpload'>
                        <input type='file' name='WebPicture' accept='image/jpeg,image/gif,image/x-png'>
                        <label>Position</label>

                          <input type='radio' name='WebPicPosition' value='right' checked='true'>
                          <label for='right'>rechts</label>
                          <input type='radio' name='WebPicPosition' value='center'>
                          <label for='center'>mittig</label>
                          <input type='radio' name='WebPicPosition' value='left'>
                          <label for='left'>links</label>
                    </div><br><br></pre>";
            }
            else if($background['Picture'] != '')
            {
              echo
                        "<pre>
                          <input type='radio' name='WebsiteBackground' id='WebsiteBackgroundColor' value='WebsiteColor' checked='true' onclick='onColorPicturePicker()'><label for='WebsiteColor'> Farbe</label>
                            <div class='WebsiteColerPicker'>
                              <input type='color' name='WebColor' value='#000000'>
                            </div>

                          <input type='radio' name='WebsiteBackground' id='WebsiteBackgroundPic' value='WebsitePicture' onclick='onColorPicturePicker()'><label for='WebsitePicture'> Bild</label>
                          <div class='WebsitePictureUpload'>

                              <label>ausgewähltes Bild:<input name='WebsiteBack' type='hidden' value='".$background['Picture']."'>                                 ".$background['Picture']."</label>

                              <label>neues Hintergrundbild auswählen:  </label>                  <input type='file' name='WebPicture' accept='image/jpeg,image/gif,image/x-png'>";
                            if($background['PicturePosition'] == 'right')
                            {
                              echo
                                    "<label>Position</label>

                                        <input type='radio' name='WebPicPosition' value='right' checked='true'> <label for='right'>rechts</label>
                                        <input type='radio' name='WebPicPosition' value='center'> <label for='center'>mittig</label>
                                        <input type='radio' name='WebPicPosition' value='left'> <label for='left'>links</label>
                                </div><br><br></pre>";
                            }
                            else if($background['PicturePosition'] == 'center')
                            {
                              echo
                                    "<label>Position</label>

                                        <input type='radio' name='WebPicPosition' value='right' > <label for='right'>rechts</label>
                                        <input type='radio' name='WebPicPosition' value='center' checked='true'> <label for='center'>mittig</label>
                                        <input type='radio' name='WebPicPosition' value='left'> <label for='left'>links</label>
                                  </div><br><br></pre>";
                            }
                            else if($background['PicturePosition'] == 'left')
                            {
                              echo
                                    "<label>Position</label>

                                        <input type='radio' name='WebPicPosition' value='right'> <label for='right'>rechts</label>
                                        <input type='radio' name='WebPicPosition' value='center'> <label for='center'>mittig</label>
                                        <input type='radio' name='WebPicPosition' value='left' checked='true'> <label for='left'>links</label>
                                  </div><br><br></pre>";
                            }

            }
echo
              "<h2>Menue</h2>
              <pre><label>Breite:</label>

                <input type='number' name='MenuWidth' min='10' max='100' value='".$menu['Width']."'><label for='MenuWidth'>%</label><br><br></pre>
              <pre><label>Höhe:</label>

                <input type='number' name='MenuHeight' min='10' max='100' value='".$menu['Height']."'><label for='MenuHeight'>px</label><br><br></pre>";
              if($menu['Position'] == 'right')
              {
                echo
                      "<pre><label>Position:</label>

                        <input type='radio' name='MenuPosition' value='right' checked='true'> <label for='right'>rechts</label>
                        <input type='radio' name='MenuPosition' value='center'> <label for='center'>mittig unter dem Header</label>
                        <input type='radio' name='MenuPosition' value='left'> <label for='left'>links</label><br><br></pre>";
              }
              else if($menu['Position'] == 'center')
              {
                echo
                      "<pre><label>Position:</label>

                        <input type='radio' name='MenuPosition' value='right' > <label for='right'>rechts</label>
                        <input type='radio' name='MenuPosition' value='center' checked='true'> <label for='center'>mittig unter dem Header</label>
                        <input type='radio' name='MenuPosition' value='left'> <label for='left'>links</label><br><br></pre>";
              }
              else if($menu['Postion'] == 'left')
              {
                echo
                      "<pre><label>Position:</label>

                        <input type='radio' name='MenuPosition' value='right' > <label for='right'>rechts</label>
                        <input type='radio' name='MenuPosition' value='center'> <label for='center'>mittig unter dem Header</label>
                        <input type='radio' name='MenuPosition' value='left' checked='true'> <label for='left'>links</label><br><br></pre>";

              }
  echo            "<pre>";
                BackendComponentPrinter::PrintFontsDropdownList("Schriftart:", "MenuFont", $menu['Font']);
  echo
                "<br><br></pre>
              <pre><label>Schriftgröße</label>

                <input type='number' min='2' max='50' name='MenuFontsize' value='".$menu['Fontsize']."'><br><br></pre>
              <pre><label>Schriftfarbe: </label>

                <input type='color' name='MenuFontColor' value='".$menu['Fontcolor']."'><br><br></pre>
              <pre><label>Hintergrundfarbe: </label>

                <input type='color' name='MenuBackgroundColor' value='".$menu['Backgroundcolor']."'><br><br></pre>";
              if($menu['Order'] == 'vertical')
              {
                echo
                    "<pre><label>Anordnung: </label>

                      <input type='radio' name='Order' value='vertical' checked='true'> <label for='vertical'>vertikal</label>
                      <input type='radio' name='Order' value='horizontal'> <label for='horizontal'>horizontal</label><br><br></pre>";
              }
              else if($menu['Order'] == 'horizontal')
              {
                echo
                    "<pre><label>Anordnung: </label>

                      <input type='radio' name='Order' value='vertical' > <label for='vertical'>vertikal</label>
                      <input type='radio' name='Order' value='horizontal' checked='true'> <label for='horizontal'>horizontal</label><br><br></pre>";
              }
echo
              "<h2>Artikelcontainer</h2>";
              if($articleContainer['Position'] == 'right')
              {
                echo
                    "<pre><label>Position:</label>

                      <input type='radio' name='ArticlePosition' value='right' checked='true'> <label for='right'>rechts</label>
                      <input type='radio' name='ArticlePosition' value='center'> <label for='center'>mittig</label>
                      <input type='radio' name='ArticlePosition' value='left'> <label for='left'>links</label><br><br></pre>";
              }
              else if($articleContainer['Position'] == 'center')
              {
                echo
                    "<pre><label>Position:</label>

                      <input type='radio' name='ArticlePosition' value='right' > <label for='right'>rechts</label>
                      <input type='radio' name='ArticlePosition' value='center' checked='true'> <label for='center'>mittig</label>
                      <input type='radio' name='ArticlePosition' value='left'> <label for='left'>links</label><br><br></pre>";
              }
              else if($articleContainer['Position'] == 'left')
              {
                echo
                    "<pre><label>Position:</label>

                      <input type='radio' name='ArticlePosition' value='right' > <label for='right'>rechts</label>
                      <input type='radio' name='ArticlePosition' value='center'> <label for='center'>mittig</label>
                      <input type='radio' name='ArticlePosition' value='left' checked='true'> <label for='left'>links</label><br><br></pre>";
              }

              if($articleContainer['Backgroundpic'] == '')
              {
                echo
                    "<pre><label>Hintergrund:</label>

                    <input type='radio' name='ArticleBackground' id='ArticleBackColorPicker' value='ArticleBackgroundColor' checked='true' onclick='onColorPicturePicker()'> <label for='ArticleBackgroundColor'>Farbe</label>
                      <div class='ArticleBackgroundColorDiv'>
                        <input type='color' name='ArticleBackColor' value='".$articleContainer['Backgroundcolor']."'>
                      </div>
                    <input type='radio' name='ArticleBackground' id='ArticleBackPicPicker' value='ArticleBackgroundPic' onclick='onColorPicturePicker()'> <label for='ArticleBackgroundPic'>Bild</label>
                      <div class='ArticleBackgroundPicDiv'>
                        <input type='file' name='ArticleBackgroundPicture' accept='image/jpeg,image/gif,image/x-png'>
                      </div><br><br></pre>";
              }
              else if($articleContainer['Backgroundpic'] != '')
              {
                echo
                    "<pre><label>Hintergrund:</label>

                    <input type='radio' name='ArticleBackground' id='ArticleBackColorPicker' value='ArticleBackgroundColor'  onclick='onColorPicturePicker()'> <label for='ArticleBackgroundColor'>Farbe</label>
                      <div class='ArticleBackgroundColorDiv'>
                        <input type='color' name='ArticleBackColor' value='#000000'>
                      </div>
                    <input type='radio' name='ArticleBackground' id='ArticleBackPicPicker' value='ArticleBackgroundPic' checked='true' onclick='onColorPicturePicker()'> <label for='ArticleBackgroundPic'>Bild</label>
                      <div class='ArticleBackgroundPicDiv'>

                        <label>ausgewähltes Bild:<input name='ArticleBack' type='hidden' value='".$articleContainer['Backgroundpic']."'>                                 ".$articleContainer['Backgroundpic']."</label>

                        <label>neues Hintergrundbild auswählen:  </label>                  <input type='file' name='ArticleBackgroundPicture' accept='image/jpeg,image/gif,image/x-png'>
                      </div><br><br></pre>";
              }
echo
              "<pre><label>Artikelcontainerbreite:</label>

                <input type='number' name='ArticleWidth' value='".$articleContainer['Width']."' min='10' max='100'><label for='ArticleWidth'>%</label><br><br></pre>
              <pre><label>Artikelanzahl auf einer Seite</label>

                <input type='number' name='Articlenumber'  id='Sitenumber' min='0' max='250' onchange='onNavigation()'><br><br>
                <div class='Sitenavigation'>
                  <h4>Navigation innerhalb der Artikelseiten:</h4><br><br>";
                  if($articleContainer['Navigation'] == 'ArrowButton')
                  {
                    echo
                        "<label>Auswahl der Navigation:</label>

                          <input type='radio' name='Navigation' value='ArrowButton' checked='true'> <label for='ArrowButton'>Pfeile als Button</label>
                          <input type='radio' name='Navigation' value='ArrowSymbole'> <label for='ArrowSymbole'>Pfeile als Symbol</label>
                          <input type='radio' name='Navigation' value='NumberButton'> <label for='NumberButton'>Nummerierung als Button</label>
                          <input type='radio' name='Navigation' value='NumberSymbole'> <label for='NumberSymbole'>Nummerierung als Symbol</label>
                        <br><br>";
                  }
                  else if($articleContainer['Navigation'] == 'ArrowSymbole')
                  {
                    echo
                        "<label>Auswahl der Navigation:</label>

                          <input type='radio' name='Navigation' value='ArrowButton'> <label for='ArrowButton'>Pfeile als Button</label>
                          <input type='radio' name='Navigation' value='ArrowSymbole'  checked='true'> <label for='ArrowSymbole'>Pfeile als Symbol</label>
                          <input type='radio' name='Navigation' value='NumberButton'> <label for='NumberButton'>Nummerierung als Button</label>
                          <input type='radio' name='Navigation' value='NumberSymbole'> <label for='NumberSymbole'>Nummerierung als Symbol</label>
                        <br><br>";
                  }
                  else if($articleContainer['Navigation'] == 'NumberButton')
                  {
                    echo
                        "<label>Auswahl der Navigation:</label>

                          <input type='radio' name='Navigation' value='ArrowButton' > <label for='ArrowButton'>Pfeile als Button</label>
                          <input type='radio' name='Navigation' value='ArrowSymbole'> <label for='ArrowSymbole'>Pfeile als Symbol</label>
                          <input type='radio' name='Navigation' value='NumberButton' checked='true'> <label for='NumberButton'>Nummerierung als Button</label>
                          <input type='radio' name='Navigation' value='NumberSymbole'> <label for='NumberSymbole'>Nummerierung als Symbol</label>
                        <br><br>";
                  }
                  else if($articleContainer['Navigation'] == 'NumberSymbole')
                  {
                    echo
                        "<label>Auswahl der Navigation:</label>

                          <input type='radio' name='Navigation' value='ArrowButton'> <label for='ArrowButton'>Pfeile als Button</label>
                          <input type='radio' name='Navigation' value='ArrowSymbole'> <label for='ArrowSymbole'>Pfeile als Symbol</label>
                          <input type='radio' name='Navigation' value='NumberButton'> <label for='NumberButton'>Nummerierung als Button</label>
                          <input type='radio' name='Navigation' value='NumberSymbole' checked='true'> <label for='NumberSymbole'>Nummerierung als Symbol</label>
                        <br><br>";
                  }

                  if($articleContainer['NavigationPosition'] == 'top')
                  {
                    echo
                        "<label>Position:</label>

                          <input type='radio' name='NavigationPosition' value='top' checked='true'> <label for='top'>oben</label>
                          <input type='radio' name='NavigationPosition' value='bottom'> <label for='bottom'>unten</label>
                          <input type='radio' name='NavigationPosition' value='top/bottom'> <label for='top/bottom'>oben und unten</label><br><br>";
                  }
                  else if($articleContainer['NavigationPosition'] == 'bottom')
                  {
                    echo
                        "<label>Position:</label>

                          <input type='radio' name='NavigationPosition' value='top'> <label for='top'>oben</label>
                          <input type='radio' name='NavigationPosition' value='bottom'  checked='true'> <label for='bottom'>unten</label>
                          <input type='radio' name='NavigationPosition' value='top/bottom'> <label for='top/bottom'>oben und unten</label><br><br>";
                  }
                  else if($articleContainer['NavigationPosition'] == 'top/bottom')
                  {
                    echo
                        "<label>Position:</label>

                          <input type='radio' name='NavigationPosition' value='top'> <label for='top'>oben</label>
                          <input type='radio' name='NavigationPosition' value='bottom'> <label for='bottom'>unten</label>
                          <input type='radio' name='NavigationPosition' value='top/bottom' checked='true'> <label for='top/bottom'>oben und unten</label><br><br>";

                  }


                      BackendComponentPrinter::PrintFontsDropdownList("Schriftart:", "NavFont", $articleContainer['NavFont']);
  echo
                      "<br><br>
                    <pre><label>Schriftfarbe:</label>

                      <input type='color' name='NavFontColor' value='".$articleContainer['NavFontColor']."'><br><br></pre>
                    <pre><label>Schriftgröße</label>

                      <input type='number' name='NavFontsize' min='2' max='50' value='".$articleContainer['NavFontsize']."'><br><br></pre>
                    <pre><label>Buttonhintergrund:</label>

                      <input type='color' name='NavButtonBackgroundColor' value='".$articleContainer['NavButtonBackground']."'><br><br>
                </div><br><br></pre>

            <h2>Footer</h2>
              <pre><label>Hoehe:</label>

                <input type='number' name='FooterHeight' min='10' max='25' value='".$footer['Height']."'><label for='FooterHeight'>%</label><br><br></pre><pre>";

                BackendComponentPrinter::PrintFontsDropdownList("Schriftart:", "FooterFont", $footer['Font']);
  echo
                "<br><br></pre>
              <pre><label>Schriftgröße:</label>

                <input type='number' name='FooterFontsize' min='2' max='50' value='".$footer['Fontsize']."'><br><br></pre>
              <pre><label>Schriftfarbe:</label>

                <input type='color' name='FooterFontColor' value='".$footer['Fontcolor']."'><br><br></pre>";
              if($footer['Backgroundpic'] == '')
              {
                echo
                    "<pre><label>Hintergrund:</label>

                      <input type='radio' name='FooterBackground' id='FooterBackColPicker' value='FooterBackgroundColor' checked='true' onclick='onColorPicturePicker()'> <label for='FooterBackgroundColor'>Farbe</label>
                        <div class='FooterBackColor'>
                          <input type='color' name='FooterBackColorPicker' value='".$footer['Backgroundcolor']."'>
                        </div>
                      <input type='radio' name='FooterBackground' id='FooterBackPicPicker' value='FooterBackgroundPic' onclick='onColorPicturePicker()'> <label for='FooterBackgroundPic'>Bild</label>
                        <div class='FooterBackPic'>
                          <input type='file' name='FooterBackPicture' accept='image/jpeg,image/gif,image/x-png'>
                        </div><br><br></pre>";
              }
              else if($footer['Backgroundpic'] != '')
              {
                echo
                    "<pre><label>Hintergrund:</label>

                      <input type='radio' name='FooterBackground' id='FooterBackColPicker' value='FooterBackgroundColor'  onclick='onColorPicturePicker()'> <label for='FooterBackgroundColor'>Farbe</label>
                        <div class='FooterBackColor'>
                          <input type='color' name='FooterBackColorPicker' value='#000000'>
                        </div>
                      <input type='radio' name='FooterBackground' id='FooterBackPicPicker' value='FooterBackgroundPic' checked='true' onclick='onColorPicturePicker()'> <label for='FooterBackgroundPic'>Bild</label>
                        <div class='FooterBackPic'>

                          <label>ausgewähltes Bild:<input name='FooterBack' type='hidden' value='".$footer['Backgroundpic']."'>                                 ".$footer['Backgroundpic']."</label>

                          <label>neues Hintergrundbild auswählen:  </label>                  <input type='file' name='FooterBackPicture' accept='image/jpeg,image/gif,image/x-png'>
                        </div><br><br></pre>";
              }
              if($footer['Order'] == 'Vertical')
              {
                echo
                    "<pre><label>Anordnung innerhalb des Footers:</label>

                      <input type='radio' name='OrderFooter' value='Vertical' checked='true'> <label for='Vertical'>vertikal</label>
                      <input type='radio' name='OrderFooter' value='Horizontal'> <label for='Horizontal'>horizontal</label><br><br></pre>";
              }
              else if($footer['Order'] == 'Horizontal')
              {
                echo
                    "<pre><label>Anordnung innerhalb des Footers:</label>
                      <input type='radio' name='OrderFooter' value='Vertical' > <label for='Vertical'>vertikal</label>
                      <input type='radio' name='OrderFooter' value='Horizontal' checked='true'> <label for='Horizontal'>horizontal</label><br><br></pre>";
              }

  echo
            "<h2>Buttondesign</h2>";
            if($button['Rounded'] == 'Rounded')
            {
              echo
                  "<pre><label>abgerundet:</label>

                    <input type='radio' name='ButtonRounded' value='Rounded' checked='true'> <label for='Rounded'>ja</label>
                    <input type='radio' name='ButtonRounded' value='Rectangular'> <label for='Rectangular'>nein</label><br><br></pre>";
            }
            else if($button['Rounded'] == 'Rectangular')
            {
              echo
                  "<pre><label>abgerundet:</label>
                    <input type='radio' name='ButtonRounded' value='Rounded' checked='true'> <label for='Rounded'>ja</label>
                    <input type='radio' name='ButtonRounded' value='Rectangular'> <label for='Rectangular'>nein</label><br><br></pre>";
            }

            if($button['Button3D'] == 'ja')
            {
              echo
                  "<pre><label>3D:</label>
                    <input type='radio' name='Button3D' value='ja' checked='true'> <label for='ja'>ja</label>
                    <input type='radio' name='Button3D' value='nein'> <label for='nein'>nein</label><br><br></pre>";
            }
            else if($button['Button3D'] == 'nein')
            {
              echo
                  "<pre><label>3D:</label>
                    <input type='radio' name='Button3D' value='ja'> <label for='ja'>ja</label>
                    <input type='radio' name='Button3D' value='nein'  checked='true'> <label for='nein'>nein</label><br><br></pre>";
            }
  echo      "<pre>";

                BackendComponentPrinter::PrintFontsDropdownList("Schriftart:", "ButtonFont", $button['Font']);
  echo
                "<br><br></pre>
              <pre><label>Schriftgröße:</label>

                <input type='number' name='ButtonFontsize' min='2' max='50' value='".$button['Fontsize']."'><br><br></pre>
              <pre><label>Schriftfarbe:</label>

                <input type='color' name='ButtonFontColor' value='".$button['Fontcolor']."'><br><br></pre>";
              if($button['Backgroundpicture'] == '')
              {
                echo
                    "<pre><label>Hintergrund:</label>

                      <input type='radio' name='ButtonBackground' id='ButtonBackgroundColorPicker' value='ButtonBackColor' checked='true' onclick='onColorPicturePicker()'> <label for='ButtonBackColor'>Farbe</label>
                        <div class='BtnBackColor'>
                          <input type='color' name='ButtonBackgroundColor' value='".$button['Backgroundcolor']."'>
                        </div>
                      <input type='radio' name='ButtonBackground' id='ButtonBackgroundPicPicker' value='ButtonBackPic' onclick='onColorPicturePicker()'> <label for='ButtonBackPic'>Bild</label>
                        <div class='BtnBackPic'>
                          <input type='file' name='ButtonBackgroundPic' accept='image/jpeg,image/gif,image/x-png'>
                        </div><br><br></pre>";
              }
              else if($button['Backgroundpicture'] != '')
              {
                echo
                    "<pre><label>Hintergrund:</label>

                      <input type='radio' name='ButtonBackground' id='ButtonBackgroundColorPicker' value='ButtonBackColor' checked='true' onclick='onColorPicturePicker()'> <label for='ButtonBackColor'>Farbe</label>
                        <div class='BtnBackColor'>
                          <input type='color' name='ButtonBackgroundColor' value='#000000'>
                        </div>
                      <input type='radio' name='ButtonBackground' id='ButtonBackgroundPicPicker' value='ButtonBackPic' onclick='onColorPicturePicker()'> <label for='ButtonBackPic'>Bild</label>
                        <div class='BtnBackPic'>

                          <label>ausgewähltes Bild:<input name='ButtonBack' type='hidden' value='".$button['Backgroundpicture']."'>                                 ".$button['Backgroundpicture']."</label>

                          <label>neues Hintergrundbild auswählen:  </label>                  <input type='file' name='ButtonBackgroundPic' accept='image/jpeg,image/gif,image/x-png'>
                        </div><br><br></pre>";
              }

 echo
            "<h2>Loginfeld/Registrierung</h2>
              <pre><label>Hintergrundfarbe: </label>

                <input type='color' name='LoginBackgroundColor' value='".$login['Backgroundcolor']."'><br><br></pre>
              <pre><label>Vordergrundfarbe: </label>

                <input type='color' name='LoginForegroundColor' value='".$login['ForegroundColor']."'><br><br></pre><pre>";


                BackendComponentPrinter::PrintFontsDropdownList("Schriftart:", "LoginFont", $login['Font']);
  echo
                "<br><br></pre>
              <pre><label>Schriftgröße: </label>

                <input type='number' name='LoginFontsize' min='2' max='50' value='".$login['Fontsize']."'><br><br><pre>
              <pre><label>Schriftfarbe:</label>

                <input type='color' name='LoginFontColor' value='".$login['Fontcolor']."'><br><br></pre>
              <h2>Label</h2>
                <pre><label>Hintergrundfarbe: </label>

                  <input type='color' name='LabelBackgroundColor' value='".$label['Backgroundcolor']."'><br><br></pre><pre>";

                  BackendComponentPrinter::PrintFontsDropdownList("Schriftart:", "LabelFont", $label['Font']);
  echo
                  "<br><br></pre>
                <pre><label>Schriftgröße: </label>

                  <input type='number' name='LabelFontsize' min='2' max='50' value='".$label['Fontsize']."'><br><br></pre>
                <pre><label>Schriftfarbe:</label>

                  <input type='color' name='LabelFontColor' value='".$label['Fontcolor']."'><br><br></pre>";
                if($label['Rounded'] == 'Rounded')
                {
                  echo
                      "<pre><label>abgerundet:</label>

                        <input type='radio' name='LabelRounded' value='Rounded' checked='true'> <label for='Rounded'>ja</label>
                        <input type='radio' name='LabelRounded' value='Rectangular'> <label for='Rectangular'>nein</label><br><br><br></pre>";
                }
                else if($label['Rounded'] == 'Rectangular')
                {
                  echo
                      "<pre><label>abgerundet:</label>

                        <input type='radio' name='LabelRounded' value='Rounded' checked='true'> <label for='Rounded'>ja</label>
                        <input type='radio' name='LabelRounded' value='Rectangular'> <label for='Rectangular'>nein</label><br><br><br></pre>";
                }
 echo

              "<label>Name des erstellten Templates:</label>               <input type='text' name='TemplateName' value='".$templateName."' ><br><br>
            <input type='submit' name='save' value='speichern'><br><br>
            </form>
        	</main>
        </body>
        </html>";
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
