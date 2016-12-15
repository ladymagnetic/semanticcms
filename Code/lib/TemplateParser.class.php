<?php
/* namespace */
namespace SemanticCms\FrontendGenerator;

/* Include(s) */
//require_once 'filename';

/**
* Provides functionality for pasring a template
*/
class TemplateParser
{
	private $filename

	/* ---- Constructor / Destructor ---- */
	// /**
	// * constructor
	// * @params string $filename the filename of the template
	// */
 	 public function __construct(/*$filename*/)
 	 {
			//%this->filename = $filename;
			/*Create a DOMDocument that is a XML-file*/
			$dom = new DOMDocument('1.0', 'utf-8');
			$root = $dom->createElement('Template');
			$dom->appendChild($root);
	 }

	/* ---- Methods ---- */

	/**
	* Save the Header data of the Templateerstellung.php in an XML-file
	*
	*/
	private function SaveHeader($Title, $Height, $Position, $Font, $Fontsize, $Fontcolor, $Backgroundcolor, $Backgroundpic, $Logo)
	{
		$root->appendChild($firstNode) = $dom->createElement("Header");
		$firstNode->appendChild($dom->createElement("Title", $Title));
		$firstNode->appendChild($dom->createElement("Height", $Height));
		$firstNode->appendChild($dom->createElement("Position", $Position));
		$firstNode->appendChild($dom->createElement("Font", $Font));
		$firstNode->appendChild($dom->createElement("Fontsize", $Fontsize));
		$firstNode->appendChild($dom->createElement("Fontcolor", $Fontcolor));
		$firstNode->appendChild($dom->createElement("Font", $Font));
		$firstNode->appendChild($dom->createElement("Backgroundcolor", $Backgroundcolor));
		$firstNode->appendChild($dom->createElement("Backgroundpic", $Backgroundpic));
		$firstNode->appendChild($dom->createElement("Logo", $Logo));/*muss noch irgendwie speichern was für ein Logo*/

	}

	/**
	* Save the Background data of the Templateerstellung.php in an XML-file
	*
	*/
	private function SaveBackground($Color, $Picture)
	{
		$root->appendChild($secondNode) = $dom->createElement("Background");
		$secondNode->appendChild($dom->createElement("Color", $Color));
		$secondNode->appendChild($dom->createElement("Picture", $Picture));//muss noch das Bild gespeichert werden mit verweiß dadrauf
	}

	/**
	* Save the Menu data of the Templateerstellung.php in an XML-file
	*
	*/
	private function SaveMenu($Height, $Width, $Position, $Font, $Fontsize, $Fontcolor, $Backgroundcolor, $Order)
	{
		$root->appendChild($thirdNode) = $dom->createElement("Menu");
		$thirdNode->appendChild($dom->createElement("Height", $Height));
		$thirdNode->appendChild($dom->createElement("Width", $Width));
		$thirdNode->appendChild($dom->createElement("Position", $Position));
		$thirdNode->appendChild($dom->createElement("Font", $Font));
		$thirdNode->appendChild($dom->createElement("Fontsize", $Fontsize));
		$thirdNode->appendChild($dom->createElement("Fontcolor", $Fontcolor));
		$thirdNode->appendChild($dom->createElement("Backgroundcolor", $Backgroundcolor));
		$thirdNode->appendChild($dom->createElement("Order", $Order));
	}

	/**
	* Save the ArticleContainer data of the Templateerstellung.php in an XML-file
	*
	*/
	private function SaveArticleContainer($Position, $Sequence, $NumberOfArticle, $Backgroundcolor, $Backgroundpic, $Width)
	{
		$root->appendChild($fourthNode) = $dom->createElement("ArticleContainer");
		$fourthNode->appendChild($dom->createElement("Position", $Position));
		$fourthNode->appendChild($dom->createElement("Sequence", $Sequence));
		$fourthNode->appendChild($dom->createElement("NumberOfArticle", $NumberOfArticle));//Navigation von Unterseiten fehlt
		$fourthNode->appendChild($dom->createElement("Backgroundcolor", $Backgroundcolor));
		$fourthNode->appendChild($dom->createElement("Backgroundpic", $Backgroundpic));
		$fourthNode->appendChild($dom->createElement("Width", $Width));
	}

	/**
	* Save the Footer data of the Templateerstellung.php in an XML-file
	*
	*/
	private function SaveFooter($Height, $Position, $Font, $Fontsize, $Fontcolor, $Backgroundpic, $Backgroundcolor, $Order)
	{
		$root->appendChild($fifthNode) = $dom->createElement("Footer");
		$fifthNode->appendChild($dom->createElement("Height", $Height));
		$fifthNode->appendChild($dom->createElement("Position", $Position));
		$fifthNode->appendChild($dom->createElement("Font", $Font));
		$fifthNode->appendChild($dom->createElement("Fontsize", $Fontsize));
		$fifthNode->appendChild($dom->createElement("Fontcolor", $Fontcolor));
		$fifthNode->appendChild($dom->createElement("Backgroundpic", $Backgroundpic));
		$fifthNode->appendChild($dom->createElement("Backgroundcolor", $Backgroundcolor));
		$fifthNode->appendChild($dom->createElement("Order", $Order));
	}

	/**
	* Save the Button data of the Templateerstellung.php in an XML-file
	*
	*/
	private function SaveButton($Rounded, $_3D, $Font, $Fontsize, $Fontcolor, $Backgroundcolor)
	{
		$root->appendChild($sixthNode) = $dom->createElement("Button");
		$sixthNode->appendChild($dom->createElement("Rounded", $Rounded));
		$sixthNode->appendChild($dom->createElement("3D", $_3D));
		$sixthNode->appendChild($dom->createElement("Font", $Font));
		$sixthNode->appendChild($dom->createElement("Fontsize", $Fontsize));
		$sixthNode->appendChild($dom->createElement("Fontcolor", $Fontcolor));
		$sixthNode->appendChild($dom->createElement("Backgroundcolor", $Backgroundcolor));

		$dom->save();//Namen bzw Pfad noch angeben
	}
}
?>
