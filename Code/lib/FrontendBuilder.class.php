<?php
/* namespace */
namespace SemanticCms\FrontendGenerator;

/* include(s) */
require_once 'CSSComponentPrinter.class.php';
require_once 'HTMLComponentPrinter.class.php';
require_once 'TemplateParser.class.php';

/* namespace use(s) */
use SemanticCms\ComponentPrinter\Frontend\{CSSComponentPrinter as CSS, HTMLComponentPrinter as HTML};

/**
* Provides functionality for building a frontend page
*/
class FrontendBuilder
{
	 //private $templateParser		// Template Parser Object
	
	// /* ---- Constructor / Destructor ---- */
	// /**
	// * constructor
	// * @params paramtype $paramname param description
	// */
 	// public function __construct()
 	// {
	// }	
	
	/* ---- Methods ---- */

	/**
	* makePage()
	* generates the page ...
	*/
	public function makePage($pageName, $templatePath)
	{
		$pagePath = pageFilePath($pageName);
		$cssName = basename($templatePath, ".xml");
		$cssPath = cssFilePath($cssName);
		
		// write HTML + PHP Code
		$handle = fopen($pagePath, "x");
		
			fwrite($handle, HTML::getHtmlStart());
			fwrite($handle, HTML::getHead($pageName, $));
			fwrite($handle, HTML::getHeader("Aus Template: Unser toller Beispiel-Blog"));
			fwrite($handle, "\n<\html>");
		
		fclose($handle);
	}
	
	/**
	* deletePage()
	* deletes the given page (and css file corresponding to the template if no other page uses it).
	*	Function checks if the page exists.
	* @params string $pageName name of the page
	* @params string $templatePath full path (incl, its name)  to the template used for the site
	*/
	public function deletePage($pageName, $templatePath)
	{
		// pagename pruefen auf string
		// pagename pruefen auf / 
		// templateName pruefen auf string etc.
		
		$pagePath = pageFilePath($pageName);
		
		if(file_exists($pagePath))
		{
			unlink($pagePath);
			
			/*if( "- template is no longer used -")
			{
				$cssPath = cssFilePath(basename($templatePath, ".xml"));
				if(file_exists($cssFilePath)) { unlink($cssFilePath); }
			}
			*/
		}
	}

	/**
	* cssFilePath()
	* gives the real path to the css file corresponding to the template. Does not check if the file exists.
	* @params string $templateName only the name of the template without any file extension or path information
	* @return string absolute path of the .css file
	*/
	private function cssFilePath($templateName)
	{
		$path = realpath("../frontend/css");
		return $path."\\".$templateName.".css";		
	}
	
	/**
	* pageFilePath()
	* gives the real path to the css file corresponding to the template. Does not check if the file exists.
	* @params string $pageName only the name of the page without any file extension or path information
	* @return string absolute path of the .php file
	*/
	private function pageFilePath($pageName)
	{
		$path = realpath("../frontend");
		return $path."\\".$pageName.".php";		
	}
}
?>