<?php
/* namespace */
namespace SemanticCms\FrontendGenerator;

/* include(s) */
require_once 'CSSComponentPrinter.class.php';
require_once 'HTMLComponentPrinter.class.php';
require_once 'TemplateParser.class.php';
require_once 'DbContent.class.php';
require_once '../config/config.php';

/* namespace use(s) */
use SemanticCms\ComponentPrinter\Frontend\CSSComponentPrinter as CSS;
use SemanticCms\ComponentPrinter\Frontend\HTMLComponentPrinter as HTML;
use SemanticCms\DatabaseAbstraction\DbContent;
use SemanticCms\config;

/**
* Provides functionality for building a frontend page
*/
class FrontendBuilder
{
	private static $db;
	private static $templateParser;
		
	
	/* ---- Constructor / Destructor ---- */
	/**
	* constructor
	* @params paramtype $paramname param description
	*/
 	private function __construct() {}	
	
	/**
	* Initilisation method for FrontendBuilder
	*/
	public static function Init()
	{
		global $config;
		self::$db = new DbContent($config['cms_db']['dbhost'],$config['cms_db']['dbuser'],$config['cms_db']['dbpass'],$config['cms_db']['database']);
		self::$templateParser = new TemplateParser();
	}
	
	
	/* ---- Methods ---- */

	/**
	* MakeNewPage()
	* Generates a new page and - if necessary - the corresponding css file.
	*/
	public static function MakeNewPage($pageName, $templatePath)
	{					
		$cssName = basename($templatePath, ".xml");
		$cssPath = self::cssFilePath($cssName);
		
		self::createPage($pageName, $cssName);
		
		if(!file_exists($cssPath)) { self::createCSS($cssName); }
	}
	
	/**
	*
	*
	*/
	public static function UpdateTemplate($templatePath)
	{
		$cssName = basename($templatePath, ".xml");
		$cssPath = self::cssFilePath($cssName);
		
		if(file_exists($cssPath)) 
		{ 
			unlink($cssFilePath);
			self::createCSS($cssPath); 
			
			// all pages using this template have to be redone due to title field in template
			$result = self::$db->GetAllPagesWithTemplate($cssName);	// cssName == templateName
			
			while($page = self::$db->FetchArray($result))
			{
				$pagePath = self::pageFilePath($page["title"]);
				unlink($pagePath);
				self::createPage($page["title"], $cssName);
			}
		}
	}
	
	/**
	* UpdatePage()
	* Updates a given page if its title changed.
	*
	*/
	public static function UpdatePage($pageName, $newTemplatePath, $oldTemplatePath)
	{
		self::DeletePage($pageName, $oldTemplatePath);
		self::makePage($pageName, $newTemplatePath);
	}
	
	public static function AddLogin()
	{}

	public static function DeleteLogin()
	{}
	
	/**
	* DeletePage()
	* Deletes the given page (and css file corresponding to the template if no other page uses it).
	*	Function checks if the page exists.
	* @params string $pageName name of the page
	* @params string $templatePath full path (incl, its name)  to the template used for the site
	*/
	public static function DeletePage($pageName, $templatePath)
	{
		// pagename pruefen auf string
		// pagename pruefen auf / 
		// templateName pruefen auf string etc.
		
		$pagePath = self::pageFilePath($pageName);
		
		if(file_exists($pagePath))
		{
			unlink($pagePath);
			
			$templateName = basename($templatePath, ".xml");
			$result = self::$db->GetAllPagesWithTemplate($templateName);

			if(self::$db->GetResultCount($result) == 0)
			{
				$cssPath = self::cssFilePath($templateName);
				if(file_exists($cssFilePath)) { unlink($cssFilePath); }
			}
		}
	}
	
	private function createPage($pageName, $cssName)
	{
		$pagePath = self::pageFilePath($pageName);	
		$headerData = self::$templateParser->GetHeader($cssName);
		
		// write HTML + PHP Code
		$handle = fopen($pagePath, "x");
			
			fwrite($handle, HTML::GetPhpStart());
			fwrite($handle, HTML::GetHtmlStart());
			fwrite($handle, HTML::GetHead($pageName, $cssName));
			fwrite($handle, "<body>");
			fwrite($handle, HTML::GetHeader($headerData["Title"]));
			fwrite($handle, HTML::GetMenu($pageName));
			fwrite($handle, HTML::GetArticleContainer($pageName));
			fwrite($handle, HTML::GetFooter());
			//fwrite($handle, " <form action='test.php'> <button> LINK </button> <a href='mep.html'> <button type='button'> LINK </button> </a></form> ");
			fwrite($handle, "\n</body></html>");
		
		fclose($handle);
	}

	private function createCSS($cssName)
	{
		$cssPath = self::cssFilePath($cssName);
		
		$headerData = self::$templateParser->GetHeader($cssName);
		$buttonData = self::$templateParser->GetButton($cssName);
		$footerData = self::$templateParser->GetFooter($cssName);
		$menuData = self::$templateParser->GetMenu($cssName);
		$backgroundData = self::$templateParser->GetBackground($cssName);
		
		var_dump($menuData);
		echo "<br><br><br>";
		var_dump($backgroundData);
		
		// write css Code
		$cssHandle = fopen($cssPath, "x");
		
			fwrite($cssHandle, CSS::GetBackground($backgroundData));
			fwrite($cssHandle, CSS::GetHeader($headerData));
			fwrite($cssHandle, CSS::GetMenu($menuData));
			fwrite($cssHandle, CSS::GetButton($buttonData));
			fwrite($cssHandle, CSS::GetFooter($footerData));
		
		fclose($cssHandle);	
	}
	
	/**
	* cssFilePath()
	* gives the real path to the css file corresponding to the template. Does not check if the file exists.
	* @params string $templateName only the name of the template without any file extension or path information
	* @return string absolute path of the .css file
	*/
	private function cssFilePath($templateName)
	{
		$path = "";
		
		if(strcmp(basename(getcwd()),"lib") === 0) { $path = realpath("../frontend/css");	}
		else { $path = realpath("frontend/css"); }
		
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
		$path = "";
		
		if(strcmp(basename(getcwd()),"lib") === 0) { $path = realpath("../frontend/");	}
		else { $path = realpath("frontend/"); }
		
		return $path."\\".$pageName.".php";		
	}
}
?>