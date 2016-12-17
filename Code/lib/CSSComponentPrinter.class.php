<?php
/* namespace */
namespace SemanticCms\ComponentPrinter\Frontend;

/**
* Provides static functions for creating the frontend css file.
*/
class CSSComponentPrinter
{
	/**
	* Ends the table and prints invisible form with given action.
	* @params string $action action to be performed by the <form>
	*/
	public static function getHeader($backCol, $fontCol)
	{
		$header = 	"header {".
					// Font settings
					"color: ".$fontCol.";".
						// bold, italic, underlined beachten
					"font-family: \"Times New Roman\", \"Georgia\", \"Serif\";".
					"background-color: ".$backCol.";". // Bild beachten
					"}\n";
		
		return $header;
	}
}
?>