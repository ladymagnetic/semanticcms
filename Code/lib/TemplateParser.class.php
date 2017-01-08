<?php
/* namespace */
namespace SemanticCms\FrontendGenerator;

use DOMDocument;

require_once 'lib/dbContent.class.php';
require_once 'config/config.php';
use SemanticCms\DatabaseAbstraction\dbContent;
use SemanticCms\config;


/**
* Provides functionality for pasring a template
*/
class TemplateParser
{
	private $dom;
	private $root;
	private $dbContent;


	/* ---- Constructor / Destructor ---- */
	// /**
	// * constructor
	// *
	// */

 	 public function __construct()
 	 	{
			global $config;
			$this->dbContent = new DbContent($config['cms_db']['dbhost'],$config['cms_db']['dbuser'],$config['cms_db']['dbpass'],$config['cms_db']['database']);
			/*Create a DOMDocument that is a XML-file*/
			$this->dom = new DOMDocument('1.0', 'utf-8');
			$this->root = $this->dom->createElement('Template');
			$this->dom->appendChild($this->root);
	 	}

	/* ---- Methods ---- */

	/**
	* Save the Header data of the TemplateConstruction.php in an XML-file
	*
	*/
	public function SaveHeader($Height, $Position, $Font, $Fontsize, $Fontcolor, $Backgroundcolor, $Backgroundpic, $Logo)
	{

		$this->root->appendChild($firstNode = $this->dom->createElement("Header"));
		$firstNode->appendChild($this->dom->createElement('Height', $Height));
		$firstNode->appendChild($this->dom->createElement('Position', $Position));
		$firstNode->appendChild($this->dom->createElement('Font', $Font));
		$firstNode->appendChild($this->dom->createElement('Fontsize', $Fontsize));
		$firstNode->appendChild($this->dom->createElement('Fontcolor', $Fontcolor));
		$firstNode->appendChild($this->dom->createElement('Backgroundcolor', $Backgroundcolor));
		$firstNode->appendChild($this->dom->createElement('Backgroundpic', $Backgroundpic));
		$firstNode->appendChild($this->dom->createElement('Logo', $Logo));/*muss noch irgendwie speichern was für ein Logo*/



	}

	/**
	* Save the Background data of the TemplateConstruction.php in an XML-file
	*
	*/
	public function SaveBackground($Color, $Picture, $PicturePosition)
	{
		$this->root->appendChild($secondNode = $this->dom->createElement("Background"));
		$secondNode->appendChild($this->dom->createElement("Color", $Color));
		$secondNode->appendChild($this->dom->createElement("Picture", $Picture));//muss noch das Bild gespeichert werden mit verweiß dadrauf
		$secondNode->appendChild($this->dom->createElement("PicturePosition", $PicturePosition));
	}

	/**
	* Save the Menu data of the TemplateConstruction.php in an XML-file
	*
	*/
	public function SaveMenu($Width, $Height, $Position, $Font, $Fontsize, $Fontcolor, $Backgroundcolor, $Order)
	{
		$this->root->appendChild($thirdNode = $this->dom->createElement("Menu"));
		$thirdNode->appendChild($this->dom->createElement("Width", $Width));
		$thirdNode->appendChild($this->dom->createElement("Height", $Height));
		$thirdNode->appendChild($this->dom->createElement("Position", $Position));
		$thirdNode->appendChild($this->dom->createElement("Font", $Font));
		$thirdNode->appendChild($this->dom->createElement("Fontsize", $Fontsize));
		$thirdNode->appendChild($this->dom->createElement("Fontcolor", $Fontcolor));
		$thirdNode->appendChild($this->dom->createElement("Backgroundcolor", $Backgroundcolor));
		$thirdNode->appendChild($this->dom->createElement("Order", $Order));
	}

	/**
	* Save the ArticleContainer data of the TemplateConstruction.php in an XML-file
	*
	*/
	public function SaveArticleContainer($Position, $NumberOfArticle, $Navigation, $NavigationPosition, $NavFont, $NavFontsize, $NavFontColor, $NavButtonBackground, $Backgroundcolor, $Backgroundpic, $Width)
	{
		$this->root->appendChild($fourthNode = $this->dom->createElement("ArticleContainer"));
		$fourthNode->appendChild($this->dom->createElement("Position", $Position));
		$fourthNode->appendChild($this->dom->createElement("NumberOfArticle", $NumberOfArticle));
		$fourthNode->appendChild($this->dom->createElement("Navigation", $Navigation));
		$fourthNode->appendChild($this->dom->createElement("NavigationPosition", $NavigationPosition));
		$fourthNode->appendChild($this->dom->createElement("NavFont", $NavFont));
		$fourthNode->appendChild($this->dom->createElement("NavFontsize", $NavFontsize));
		$fourthNode->appendChild($this->dom->createElement("NavFontColor", $NavFontColor));
		$fourthNode->appendChild($this->dom->createElement("NavButtonBackground", $NavButtonBackground));
		$fourthNode->appendChild($this->dom->createElement("Backgroundcolor", $Backgroundcolor));
		$fourthNode->appendChild($this->dom->createElement("Backgroundpic", $Backgroundpic));
		$fourthNode->appendChild($this->dom->createElement("Width", $Width));
	}

	/**
	* Save the Footer data of the TemplateConstruction.php in an XML-file
	*
	*/
	public function SaveFooter($Height, $Font, $Fontsize, $Fontcolor, $Backgroundcolor, $Backgroundpic, $Order)
	{
		$this->root->appendChild($fifthNode = $this->dom->createElement("Footer"));
		$fifthNode->appendChild($this->dom->createElement("Height", $Height));
		$fifthNode->appendChild($this->dom->createElement("Font", $Font));
		$fifthNode->appendChild($this->dom->createElement("Fontsize", $Fontsize));
		$fifthNode->appendChild($this->dom->createElement("Fontcolor", $Fontcolor));
		$fifthNode->appendChild($this->dom->createElement("Backgroundcolor", $Backgroundcolor));
		$fifthNode->appendChild($this->dom->createElement("Backgroundpic", $Backgroundpic));
		$fifthNode->appendChild($this->dom->createElement("Order", $Order));
	}

	/**
	* Save the Button data of the TemplateConstruction.php in an XML-file
	*
	*/
	public function SaveButton($Rounded, $button3D, $Font, $Fontsize, $Fontcolor, $Backgroundcolor, $Backgroundpic)
	{
		$this->root->appendChild($sixthNode = $this->dom->createElement("Button"));
		$sixthNode->appendChild($this->dom->createElement("Rounded", $Rounded));
		$sixthNode->appendChild($this->dom->createElement('Button3D', $button3D));
		$sixthNode->appendChild($this->dom->createElement("Font", $Font));
		$sixthNode->appendChild($this->dom->createElement("Fontsize", $Fontsize));
		$sixthNode->appendChild($this->dom->createElement("Fontcolor", $Fontcolor));
		$sixthNode->appendChild($this->dom->createElement("Backgroundcolor", $Backgroundcolor));
		$sixthNode->appendChild($this->dom->createElement("Backgroundpicture", $Backgroundpic));


	}


	/**
	* Save the Tag data of the TemplateConstruction.php in an XML-file
	*
	*/
	public function SaveLogin($BackgroundColor, $ForegroundColor, $Font, $Fontsize, $Fontcolor)
	{
		$this->root->appendChild($seventhNode = $this->dom->createElement("Login"));
		$seventhNode->appendChild($this->dom->createElement("Backgroundcolor", $BackgroundColor));
		$seventhNode->appendChild($this->dom->createElement("ForegroundColor", $ForegroundColor));
		$seventhNode->appendChild($this->dom->createElement('Font', $Font));
		$seventhNode->appendChild($this->dom->createElement("Fontsize", $Fontsize));
		$seventhNode->appendChild($this->dom->createElement("Fontcolor", $Fontcolor));


	}



	/**
	* Save the Tag data of the TemplateConstruction.php in an XML-file
	*
	*/
	public function SaveTag($BackgroundColor, $Font, $Fontsize, $Fontcolor, $Rounded)
	{
		$this->root->appendChild($eighthNode = $this->dom->createElement("Tag"));
		$eighthNode->appendChild($this->dom->createElement("Backgroundcolor", $BackgroundColor));
		$eighthNode->appendChild($this->dom->createElement('Font', $Font));
		$eighthNode->appendChild($this->dom->createElement("Fontsize", $Fontsize));
		$eighthNode->appendChild($this->dom->createElement("Fontcolor", $Fontcolor));
		$eighthNode->appendChild($this->dom->createElement("Rounded", $Rounded));


	}


	public function SaveTemplate($TemplateName)
	{
			$this->dom->save("templates/".$TemplateName.".xml");
			$this->dbContent->InsertTemplate($TemplateName, "templates/".$TemplateName.".xml");
	}





	/**
	* Gives the Header data of the TemplateName.xml
	*
	*/
	public function GetHeader($TemplateName)
	{

			$doc = new DOMDocument();
			$doc->load("templates/".$TemplateName.".xml");


  		$headerArray;
			$i=0;
			while(is_object($header = $doc->getElementsByTagName("Header")->item($i)))
			{
				foreach($header->childNodes as $nodename)
			  {
			    $headerArray[$nodename->nodeName] = $nodename->nodeValue;
			  }
			  $i++;
			}
			return $headerArray;
	}


	/**
	* Gives the Background data of the TemplateName.xml
	*
	*/
	public function GetBackground($TemplateName)
	{

			$doc = new DOMDocument();
			$doc->load("templates/".$TemplateName.".xml");


  		$backgroundArray;
			$i=0;
			while(is_object($background = $doc->getElementsByTagName("Background")->item($i)))
			{
				foreach($background->childNodes as $nodename)
			  {
			    $backgroundArray[$nodename->nodeName] = $nodename->nodeValue;
			  }
			  $i++;
			}
			return $backgroundArray;
	}


	/**
	* Gives the Menu data of the TemplateName.xml
	*
	*/
	public function GetMenu($TemplateName)
	{

			$doc = new DOMDocument();
			$doc->load("templates/".$TemplateName.".xml");


  		$menuArray;
			$i=0;
			while(is_object($menu = $doc->getElementsByTagName("Menu")->item($i)))
			{
				foreach($menu->childNodes as $nodename)
			  {
			    $menuArray[$nodename->nodeName] = $nodename->nodeValue;
			  }
			  $i++;
			}
			return $menuArray;
	}


	/**
	* Gives the ArticleContainer data of the TemplateName.xml
	*
	*/
	public function GetArticleContainer($TemplateName)
	{

			$doc = new DOMDocument();
			$doc->load("templates/".$TemplateName.".xml");


  		$articleContainerArray;
			$i=0;
			while(is_object($articleContainer = $doc->getElementsByTagName("ArticleContainer")->item($i)))
			{
				foreach($articleContainer->childNodes as $nodename)
			  {
			    $articleContainerArray[$nodename->nodeName] = $nodename->nodeValue;
			  }
			  $i++;
			}
			return $articleContainerArray;
	}


	/**
	* Gives the Footer data of the TemplateName.xml
	*
	*/
	public function GetFooter($TemplateName)
	{

			$doc = new DOMDocument();
			$doc->load("templates/".$TemplateName.".xml");


  		$footerArray;
			$i=0;
			while(is_object($footer = $doc->getElementsByTagName("Footer")->item($i)))
			{
				foreach($footer->childNodes as $nodename)
			  {
			    $footerArray[$nodename->nodeName] = $nodename->nodeValue;
			  }
			  $i++;
			}
			return $footerArray;
	}


	/**
	* Gives the Button data of the TemplateName.xml
	*
	*/
	public function GetButton($TemplateName)
	{

			$doc = new DOMDocument();
			$doc->load("templates/".$TemplateName.".xml");


  		$buttonArray;
			$i=0;
			while(is_object($button = $doc->getElementsByTagName("Button")->item($i)))
			{
				foreach($button->childNodes as $nodename)
			  {
			    $buttonArray[$nodename->nodeName] = $nodename->nodeValue;
			  }
			  $i++;
			}
			return $buttonArray;
	}


	/**
	* Gives the Login data of the TemplateName.xml
	*
	*/
	public function GetLogin($TemplateName)
	{

			$doc = new DOMDocument();
			$doc->load("templates/".$TemplateName.".xml");


  		$loginArray;
			$i=0;
			while(is_object($login = $doc->getElementsByTagName("Tag")->item($i)))
			{
				foreach($login->childNodes as $nodename)
			  {
			    $loginArray[$nodename->nodeName] = $nodename->nodeValue;
			  }
			  $i++;
			}
			return $loginArray;
	}


	/**
	* Gives the Tag data of the TemplateName.xml
	*
	*/
	public function GetTag($TemplateName)
	{

			$doc = new DOMDocument();
			$doc->load("templates/".$TemplateName.".xml");


  		$tagArray;
			$i=0;
			while(is_object($tag = $doc->getElementsByTagName("Tag")->item($i)))
			{
				foreach($tag->childNodes as $nodename)
			  {
			    $tagArray[$nodename->nodeName] = $nodename->nodeValue;
			  }
			  $i++;
			}
			return $tagArray;
	}






}
?>
