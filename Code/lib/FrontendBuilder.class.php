<?php
/**
* Contains the class FrontendBuilder.
*/

/* namespace */
namespace SemanticCms\FrontendGenerator;

/* include(s) */
require_once 'CSSComponentPrinter.class.php';
require_once 'HTMLComponentPrinter.class.php';
require_once 'TemplateParser.class.php';
require_once 'DbContent.class.php';
require_once 'config/config.php';

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
	/** @var DbContent internal DbContent object */
	private static $db;
	/** @var TemplateParser internal TemplateParser object for retrieving template information */
	private static $templateParser;
	
	/* ---- Constructor / Destructor ---- */
	/**
	* constructor should not be used, instead use the static method Init()
	* @param void
	*/
 	private function __construct() {}	
	
	/**
	* Initilisation method for FrontendBuilder, always use this first!
	*
	* Initializes the FrontendBuilder's internal objects in order to be able to retrieve information of both the database and template files.
	* It is necessary to invoke this method before invoking any other method!
	* @param void
	* @return void
	*/
	public static function Init()
	{
		global $config;
		self::$db = new DbContent($config['cms_db']['dbhost'],$config['cms_db']['dbuser'],$config['cms_db']['dbpass'],$config['cms_db']['database']);
		self::$templateParser = new TemplateParser();
	}
	
	
	/* ---- Methods ---- */

	/**
	* Method for creating a new page.
	*
	* This method creates a new file on the filesystem for the desired page. If the corresponding css file does not exist, it will be created afterwards. 
	* If a page with the given name already exists, this method has no effect. For updating an existing page, please use the 
	* method UpdatePage(). For updating a template, please use the method UpdateTemplate() .
	*
	* @see FrontendBuilder::UpdatePage()		Method for updating an existing page
	* @see FrontendBuilder::UpdateTemplate()	Method for updating a template 
	*
	* @param string $pageName the name of the page (keep in mind that this must be the same as used in the database)
	* @param string $templatePath the path to the template file to be used
	* @param int $websiteId Defaults to 1. Set this if you the page belongs to another website than the default one. May be a numeric string (e.g. "1" instead of 1).
	* @return boolean Success status: false indicates an error with the parameters otherwise true. (Note: This does not indicate if a new file was created or not.)
	*/
	public static function MakeNewPage($pageName, $templatePath, $websiteId=1)
	{					
		if(!is_string($pageName) || !is_string($templatePath) || !is_numeric($websiteId)) return false;
		
		$cssName = basename($templatePath, ".xml");
		$cssPath = self::cssFilePath($cssName);
		
		self::createPage($pageName, $websiteId, $cssName);
		
		if(!file_exists($cssPath)) { self::createCSS($cssName); }
		
		return true;
	}
	
	/**
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
	public static function UpdatePage($oldPageName, $oldTemplatePath, $newPageName, $newTemplatePath, $oldWebsiteId=1, $newWebsiteId=1)
	{
		self::DeletePage($oldPageName, $oldTemplatePath, $oldWebsiteId);
		self::MakeNewPage($newPageName, $newTemplatePath, $newWebsiteId);
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
	public static function DeletePage($pageName, $templatePath, $websiteId=1)
	{
		$websiteData = self::$db->FetchArray(self::$db->GetWebsiteInfoById($websiteId));		
		$pagePath = self::pageFilePath($pageName, $websiteData["headertitle"]);
		
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
		
		// @todo ggf. website löschen
	}
	
	private function createPage($pageName, $websiteId, $cssName)
	{
		$websiteData = self::$db->FetchArray(self::$db->GetWebsiteInfoById($websiteId));
		$pagePath = self::pageFilePath($pageName, $websiteData["headertitle"]);		
		if(file_exists($pagePath)) return;
			
		$sitePath = self::websiteDirPath($websiteData["headertitle"]);
		if(!is_dir($sitePath)) {mkdir($sitePath);}
		
		$technicalPages = self::getTechnicalPageArray($websiteData);
		self::createTechnicalPages($technicalPages, $websiteData["headertitle"], $websiteData["template"]);
		
		// write HTML + PHP Code
		$handle = fopen($pagePath, "x");
			
			fwrite($handle, HTML::GetPhpStart());
			fwrite($handle, HTML::GetHtmlStart());
			fwrite($handle, HTML::GetHead($pageName, $cssName));
			fwrite($handle, "<body>");
			fwrite($handle, HTML::GetHeader($websiteData["headertitle"]));
			fwrite($handle, HTML::GetMenu($pageName));
			fwrite($handle, HTML::GetArticleContainer($pageName));
			fwrite($handle, HTML::GetFooter($technicalPages));
//			fwrite($handle, " <form action='test.php'> <button> LINK </button> <a href='mep.html'> <button type='button'> LINK </button> </a></form> ");
			fwrite($handle, "\n</body></html>");
		
		fclose($handle);
	}
	
	private function createTechnicalPages($technicalPages, $websiteName, $cssName)
	{
		foreach($technicalPages as $page)
		{
			$pagePath = self::pageFilePath($page['filename'], $websiteName);
			if(file_exists($pagePath)) continue;
			
			$handle = fopen($pagePath, "x");
				
				fwrite($handle, HTML::GetPhpStart());
				fwrite($handle, HTML::GetHtmlStart());
				fwrite($handle, HTML::GetHead($page['pagetitle'], $cssName));
				fwrite($handle, "<body>");
				fwrite($handle, HTML::GetHeader($websiteName));
				fwrite($handle, HTML::GetMenu());
				fwrite($handle, HTML::GetMain($page['content'])); 
				fwrite($handle, HTML::GetFooter($technicalPages));
				fwrite($handle, "\n</body></html>");
			
			fclose($handle);		
		}
		
		$cssPath = self::cssFilePath($cssName);
		if(!file_exists($cssPath)) { self::createCSS($cssName); }
	}

	private function createCSS($cssName)
	{
		$cssPath = self::cssFilePath($cssName);
		
		$headerData = self::$templateParser->GetHeader($cssName);
		$buttonData = self::$templateParser->GetButton($cssName);
		$footerData = self::$templateParser->GetFooter($cssName);
		$menuData = self::$templateParser->GetMenu($cssName);
		$articleContainerData = self::$templateParser->GetArticleContainer($cssName);
		$backgroundData = self::$templateParser->GetBackground($cssName);
		$tagData = self::$templateParser->GetTag($cssName);
		
	/*	var_dump($articleContainerData);
		echo "<br><br><br>";
		var_dump($backgroundData);*/
		
		// write css Code
		$cssHandle = fopen($cssPath, "x");
		
			fwrite($cssHandle, CSS::GetBackground($backgroundData));
			fwrite($cssHandle, CSS::GetHeader($headerData));
			fwrite($cssHandle, CSS::GetMenu($menuData));
			fwrite($cssHandle, CSS::GetArticle($tagData));
			fwrite($cssHandle, CSS::GetArticleContainer($articleContainerData));
			fwrite($cssHandle, CSS::GetFooter($footerData));
			fwrite($cssHandle, CSS::GetButton($buttonData));		
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
	private function pageFilePath($pageName, $websiteName)
	{
		$pageName = str_replace(" ", "_", $pageName);
		$path = self::websiteDirPath($websiteName);
		
		return $path."\\".$pageName.".php";		
	}
	
	private function websiteDirPath($websiteName)
	{
		$websiteName = str_replace(" ", "_", $websiteName);
		$path = "";
	
		if(strcmp(basename(getcwd()),"lib") === 0) { $path = realpath("../frontend/");	}
		else { $path = realpath("frontend/"); }

		return $path."\\".$websiteName;
	}
	
	private function getTechnicalPageArray(array $websiteData)
	{
		$tp = array();
		
		if(!is_null($websiteData['contact'])) 
		{
			$tp[TechnicalPage::CONTACT]['pagetitle'] = "Kontakt"; 
			$tp[TechnicalPage::CONTACT]['filename'] = "Kontakt"; 
			$tp[TechnicalPage::CONTACT]['content'] = $websiteData['contact']; 
		}
		if(!is_null($websiteData['imprint'])) 
		{ 
			$tp[TechnicalPage::IMPRINT]['pagetitle'] = "Impressum"; 
			$tp[TechnicalPage::IMPRINT]['filename'] = "Impressum"; 
			$tp[TechnicalPage::IMPRINT]['content'] = $websiteData['imprints']; 
		}
		if(!is_null($websiteData['privacyinformation'])) 
		{
			$tp[TechnicalPage::PRIVACY]['pagetitle'] = "Datenschutzbestimmungen"; 
			$tp[TechnicalPage::PRIVACY]['filename'] = "Datenschutz"; 
			$tp[TechnicalPage::PRIVACY]['content'] = $websiteData['privacyinformation']; 
		}
		if(!is_null($websiteData['gtc'])) 
		{ 
			$tp[TechnicalPage::GTC]['pagetitle'] = "Allgemeine Geschäftsbedingungen"; 
			$tp[TechnicalPage::GTC]['filename'] = "AGBs"; 
			$tp[TechnicalPage::GTC]['content'] = $websiteData['gtc']; 
		}
	
	/*	if($websiteData['login'] == 1 ) { $tp[TechnicalPage::LOGIN] = "Login"; $tp[TechnicalPage::LOGOUT] = "Logout";}
		if($websiteData['guestbook'] == 1 ) { $tp[TechnicalPage::GUESTBOOK] = "Gästebuch"; }
		*/
		return $tp;
	}
}

/**
* Provides an enumeration replacement for technical pages used in class FrontendBuilder
*/
abstract class TechnicalPage
{
	const LOGIN = 'login';
	const LOGOUT = 'logout';
	const CONTACT = 'contact';
	const IMPRINT = 'imprint';
	const GTC = 'gtc';
	const PRIVACY = 'privacyinfo';
	const GUESTBOOK = 'guestbook';
}
?>