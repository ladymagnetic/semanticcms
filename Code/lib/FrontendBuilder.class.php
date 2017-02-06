<?php
/**
* Contains the class FrontendBuilder and the class TechnicalPage
* @author Tamara Graf
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
* Provides functionality for building frontend pages and websites with css files.
* @author Tamara Graf
*/
class FrontendBuilder
{
	/************************/
	/** ----- Member ----- **/
	/************************/

	/** @var DbContent internal DbContent object */
	private static $db;
	/** @var TemplateParser internal TemplateParser object for retrieving template information */
	private static $templateParser;

	/*******************************/
	/** ----- Initilisation ----- **/
	/*******************************/

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
	/*************************/
	/** ----- Methods ----- **/
	/*************************/

	/**
	* Method to create a new webpage
	*
	* @param int $websiteId id of the website to be . May be a numeric string (e.g. "1" instead of 1).
	*
	*/
	public static function MakeNewSite($websiteId)
	{
		if(!is_numeric($websiteId)) return false;

		$websiteData = self::$db->FetchArray(self::$db->GetWebsiteInfoById($websiteId));
		$technicalPages = self::getTechnicalPageArray($websiteData);

		// create site folder (it should not exist but i check anyway)
		$sitePath = self::websiteDirPath($websiteData["headertitle"]);
		if(!is_dir($sitePath)) {mkdir($sitePath);}

		// create css if it does not exists
		$cssPath = self::cssFilePath($websiteData["template"]);
		if(!file_exists($cssPath)) { self::createCSS($websiteData["template"]); }

		// create technical pages
		self::createTechnicalPages($technicalPages, $websiteData["headertitle"], $websiteId, $websiteData["template"]);
	}

	/**
	* Method for creating a new web page.
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
	* Updates the css file for the given template.
	* Checks if a css file exists for the given template and updates the css file.
	* @param string $templatePath Path to the template file
	* @return void
	*/
	public static function UpdateTemplate($templatePath)
	{
		$cssName = basename($templatePath, ".xml");
		$cssPath = self::cssFilePath($cssName);

		if(file_exists($cssPath))
		{
			unlink($cssFilePath);
			self::createCSS($cssPath);

		/*	// all pages using this template have to be redone due to title field in template
			$result = self::$db->GetAllPagesWithTemplate($cssName);	// cssName == templateName
		*/
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


	/**
	* Creates the technical pages of a website.
	* Creates all technical pages of a given website: login/logout/register, guestbook (not implemented), contact, imprint, gtc and privacyinformation.
	*
	*
	*/
	private function createTechnicalPages($technicalPages, $websiteName, $websiteId, $cssName)
	{
		$globallogin = false;
		$guestbook = false;

		if(array_search("login", array_column($technicalPages, 'pagetitle')) != NULL )
			{$globallogin = true; unset($technicalPages[TechnicalPage::LOGIN]);}
		if(array_search("guestbook", array_column($technicalPages, 'pagetitle')) != NULL )
			{$guestbook = true; unset($technicalPages[TechnicalPage::GUESTBOOK]);}

		// technical pages for footer
		foreach($technicalPages as $page)
		{
			$pagePath = self::pageFilePath($page['filename'], $websiteName);
			if(file_exists($pagePath)) continue;

			$handle = fopen(utf8_decode($pagePath), "x");

				fwrite($handle, HTML::GetPhpStart($globallogin));
				fwrite($handle, HTML::GetHtmlStart());
				fwrite($handle, HTML::GetHead($page['pagetitle'], $cssName));
				fwrite($handle, "<body>");
				fwrite($handle, HTML::GetHeader($websiteName));
				fwrite($handle, HTML::GetMenu("", $websiteId));
				fwrite($handle, HTML::GetMain($page['content']));
				fwrite($handle, HTML::GetFooter($technicalPages));
				fwrite($handle, "\n</body></html>");

			fclose($handle);
		}

		// login and guestbook
		if($globallogin)
		{
			// login
			$pagePath = self::pageFilePath("login_logout", $websiteName);
			$loginhandle = fopen(utf8_decode($pagePath), "x");

				fwrite($loginhandle, HTML::GetPhpStart(true, true));
				fwrite($loginhandle, HTML::GetHtmlStart());
				fwrite($loginhandle, HTML::GetHead("Login/Logout", $cssName));
				fwrite($loginhandle, "<body>");
				fwrite($loginhandle, HTML::GetHeader($websiteName, "", true));
				fwrite($loginhandle, HTML::GetMenu("", $websiteId));
				fwrite($loginhandle, HTML::GetMain('',1));
				fwrite($loginhandle, HTML::GetFooter($technicalPages));
				fwrite($loginhandle, "\n</body></html>");

			fclose($loginhandle);

			// register
			$pagePath = self::pageFilePath("register", $websiteName);
			$registerhandle = fopen(utf8_decode($pagePath), "x");

				fwrite($registerhandle, HTML::GetPhpStart(true, false));
				fwrite($registerhandle, HTML::GetHtmlStart());
				fwrite($registerhandle, HTML::GetHead("Registrierung", $cssName));
				fwrite($registerhandle, "<body>");
				fwrite($registerhandle, HTML::GetHeader($websiteName, "", true));
				fwrite($registerhandle, HTML::GetMenu("", $websiteId));
				fwrite($registerhandle, HTML::GetMain('',2));
				fwrite($registerhandle, HTML::GetFooter($technicalPages));
				fwrite($registerhandle, "\n</body></html>");

			fclose($registerhandle);
		}

		if($guestbook)
		{
			// not implemented
		}
	}

	/**
	* Method to create the file for the page in the filesystem
	*
	*/
	private function createPage($pageName, $websiteId, $cssName)
	{
		$websiteData = self::$db->FetchArray(self::$db->GetWebsiteInfoById($websiteId));
		$pagePath = self::pageFilePath($pageName, $websiteData["headertitle"]);
		if(file_exists($pagePath)) return;

		$sitePath = self::websiteDirPath($websiteData["headertitle"]);
		if(!is_dir($sitePath)) {mkdir($sitePath);} // sitePath should exists but check anyway

		$technicalPages = self::getTechnicalPageArray($websiteData);

		$globallogin = false;
		$guestbook = false;
		if(array_search("login", array_column($technicalPages, 'pagetitle')) != NULL )
		{$globallogin = true; unset($technicalPages[TechnicalPage::LOGIN]);}
		if(array_search("guestbook", array_column($technicalPages, 'pagetitle')) != NULL )
		{$guestbook = true; unset($technicalPages[TechnicalPage::GUESTBOOK]);}
		// do not care about guestbook atm


		// write HTML + PHP Code
		$handle = fopen(utf8_decode($pagePath), "x");

			fwrite($handle, HTML::GetPhpStart($globallogin));
			fwrite($handle, HTML::GetHtmlStart($globallogin, false));
			fwrite($handle, HTML::GetHead($pageName, $cssName));
			fwrite($handle, "<body>");
			fwrite($handle, HTML::GetHeader($websiteData["headertitle"], "", $globallogin));
			fwrite($handle, HTML::GetMenu($pageName, $websiteId));
			fwrite($handle, HTML::GetArticleContainer($pageName));
			fwrite($handle, HTML::GetFooter($technicalPages));
 			fwrite($handle, "\n</body></html>");

		fclose($handle);
	}

	/**
	* Method to create the css file for the template in the filesystem
	* @param string $cssName	name for the css file
	* @return void
	*/
	private function createCSS($cssName)
	{
		$cssPath = self::cssFilePath($cssName);

		$headerData = self::$templateParser->GetHeader($cssName);
		$backgroundData = self::$templateParser->GetBackground($cssName);
		$menuData = self::$templateParser->GetMenu($cssName);
		$articleContainerData = self::$templateParser->GetArticleContainer($cssName);
		$footerData = self::$templateParser->GetFooter($cssName);
		$buttonData = self::$templateParser->GetButton($cssName);
		$loginFieldData = self::$templateParser->GetLogin($cssName);
		$tagData = self::$templateParser->GetLabel($cssName);

		var_dump($headerData);
		echo "<br><br><br>";
		var_dump($footerData);
		echo "<br><br><br>";
		var_dump($menuData);
		echo "<br><br><br>";
		var_dump($articleContainerData);
		echo "<br><br><br>";
		var_dump($backgroundData);
		echo "<br><br><br>";
		var_dump($tagData);
		echo "<br><br><br>";
		echo "Loginfeld: <br>";
		var_dump($loginFieldData);

		// write css Code
		$cssHandle = fopen(utf8_decode($cssPath), "x");
			fwrite($cssHandle, CSS::GetBackground($backgroundData));
			fwrite($cssHandle, CSS::GetHeader($headerData));
			fwrite($cssHandle, CSS::GetMenu($menuData));
			fwrite($cssHandle, CSS::GetArticle($tagData));
			fwrite($cssHandle, CSS::GetArticleContainer($articleContainerData));
			fwrite($cssHandle, CSS::GetFooter($footerData));
			fwrite($cssHandle, CSS::GetButton($buttonData));
			fwrite($cssHandle, CSS::GetLoginField($loginFieldData));
		fclose($cssHandle);
	}

	/****************************/
	/* ----- Helper methods -----*
	/****************************/

	/**
	* gives the real path to the css file corresponding to the template. Does not check if the file exists.
	* @param string $templateName only the name of the template without any file extension or path information
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
	* gives the real path to the css file corresponding to the template. Does not check if the file exists.
	* @param string $pageName only the name of the page without any file extension or path information
	* @return string absolute path of the .php file
	*/
	private function pageFilePath($pageName, $websiteName)
	{
		$pageName = self::fileEscape($pageName);
		$path = self::websiteDirPath($websiteName);

		return $path."\\".$pageName.".php";
	}

	/**
	* gives the real path for the website folder. Does not check if the folder exists.
	* @param string $websiteName only the name of the site without any path information
	* @return string absolute path of the website folder
	*/
	private function websiteDirPath($websiteName)
	{
		$websiteName = self::fileEscape($websiteName);
		$path = "";

		if(strcmp(basename(getcwd()),"lib") === 0) { $path = realpath("../frontend/");	}
		else { $path = realpath("frontend/"); }

		return $path."\\".$websiteName;
	}

	/**
	* Method to escape string for filename
	* @param string $string string to be escaped
	* @return string usable for filename
	*/
	private static function fileEscape($string)
	{
		// change special german characters
		$string = str_replace(array('ß', 'Ä', 'ä', 'Ü', 'ü', 'Ö', 'ö'), array('ss', 'Ae', 'ae', 'Ue', 'ue', 'Oe', 'oe'), $string);
		// replace all that is not a letter, a number or an underscore
		$string = preg_replace('/[^A-Za-z0-9_\-]/', '_', $string);
		return $string;
	}

	/**
	* helper method to get technical page data
	* @param array website data array from database
	* @return array technical page data with pagetitle, filename and content
	*/
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
			$tp[TechnicalPage::IMPRINT]['content'] = $websiteData['imprint'];
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
		if($websiteData['login'] == 1 ) { $tp[TechnicalPage::LOGIN]['pagetitle'] = "login";}
		if($websiteData['guestbook'] == 1 ) { $tp[TechnicalPage::GUESTBOOK]['pagetitle'] = "guestbook";}

		return $tp;
	}
}

/**
* Provides an enumeration replacement for technical pages used in class FrontendBuilder
* @author Tamara Graf
*/
abstract class TechnicalPage
{
	const LOGIN = 'login';
	const CONTACT = 'contact';
	const IMPRINT = 'imprint';
	const GTC = 'gtc';
	const PRIVACY = 'privacyinfo';
	const GUESTBOOK = 'guestbook';
}
?>
