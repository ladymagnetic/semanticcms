<?php
/* namespace */
namespace SemanticCms\ComponentPrinter\Frontend;

/**
* Provides static functions for creating the frontend html page.
*/
class HTMLComponentPrinter
{
	/* ---- Constructor / Destructor ---- */
	/**
	* constructor
	*/
 	private function __construct(){}	
	
	/**
	* @params string $css only the name of the css without any file extension or path information
	*/
	public static function GetHead($title, $css)
	{
		$head =	"<head>".
					"<title>".$title."</title>".
					"<meta content=\"de\" http-equiv=\"Content-Language\">".
					"<meta content=\"text/html. charset=utf-8\" http-equiv=\"Content-Type\">".
					"<link rel=\"stylesheet\" href=\"css\\".$css.".css\">".
				"</head>";
				
		return $head;
	}
	
	public static function GetHtmlStart()
	{
		$html = "<!DOCTYPE html>\n".
				"<html vocab=\"http://schema.org/\" typeof=\"WebPage\" lang=\"de\">";
		
		return $html;
	}
	
	public static function GetHeader($title, $imgSource="")
	{
		// schema.org fehlt noch
		$header =	"<header>";
		
		if(!empty($imgSource)) $header = $header."<img src=\"media\\\">";
		
		$header =	$header."<h1 property=\"name\">".htmlspecialchars($title)."</h1>".
					"</header>";
					
		return $header;
	}
	
	public static function GetMenu(array $PageNames)
	{
		// schema.org fehlt noch
		$menu =	"<nav typeof=\"SiteNavigationElement\"> <ul>";
		
		foreach ($PageNames as $pageName)
		{
			$menu = $menu."<li> <a href=\"".$pageName.".php\">".$pageName."</a></li>";
		}
		
		$menu = $menu."</ul></nav>";
					
		return $menu;
	}
	
	public static function GetFooter()
	{
		// schema.org fehlt noch
		$footer =	"<footer>".
					"</footer>";
					
		return $footer;
	}
	
}
?>