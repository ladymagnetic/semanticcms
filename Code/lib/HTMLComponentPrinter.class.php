<?php
/* namespace */
namespace SemanticCms\ComponentPrinter\Frontend;

/**
* Provides static functions for creating the frontend html page.
*/
class HTMLComponentPrinter
{
	/**
	*/
	public static function getHead($title, $css)
	{
		$head =	"<head>".
					"<title>".$title."</title>".
					"<meta content=\"de\" http-equiv=\"Content-Language\">".
					"<meta content=\"text/html. charset=utf-8\" http-equiv=\"Content-Type\">".
					"<link rel=\"stylesheet\" href=\"css\\".$css."\">".
				"</head>";
	}
	
	public static function getHtmlStart()
	{
		$html = "<!DOCTYPE html>\n".
				"<html itemscope=\"\" itemtype=\"http://schema.org/WebPage\" lang=\"de\">";
		
		return $html;
	}
	
	public static function getHeader($title, [$imgSource])
	{
		// schema.org fehlt noch
		$header =	"<header>";
		
		if(isset($imgSource)) $header = $header."<img src=\"\">";
		
		$header =	$header."<h1 itemprop=\"name\">".htmlspecialchars($title)."</h1>".
					"</header>";
					
		return $header;
	}
}
?>