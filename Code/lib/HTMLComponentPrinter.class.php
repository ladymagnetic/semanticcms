<?php
/**
* Contains the class HTMLComponentPrinter.
* @author Tamara Graf
*/

/* namespace */
namespace SemanticCms\ComponentPrinter\Frontend;

/**
* Provides static functions for creating the frontend html page.
* @author Tamara Graf
*/
class HTMLComponentPrinter
{
	/* ---- Constructor / Destructor ---- */
	/**
	* Constructor should not be used.
	* The constructor should no be used as all methods are static and class does not require initialisation
	*/
 	private function __construct(){}

	/**
	* Method to get the head tag of the page	
	* @param string $title the title of the website
	* @param string $css only the name of the css without any file extension or path information
	* @return string containing the head tag
	*/
	public static function GetHead($title, $css)
	{
		$head =	"<head>".
					"<title>".$title."</title>".
					"<meta content=\"de\" http-equiv=\"Content-Language\">".
					"<meta content=\"text/html. charset=utf-8\" http-equiv=\"Content-Type\">".
					"<link rel=\"stylesheet\" href=\"..\css\\".$css.".css\">".
				"</head>";

		return $head;
	}

	/**
	* Method to get the start of the html code
	* @return string containing the html root tags
	*/
	public static function GetHtmlStart()
	{
		$html = "<!DOCTYPE html>\n".
				"<html vocab=\"http://schema.org/\" typeof=\"WebPage\" lang=\"de\">";

		return $html;
	}

	/**
	* Method to get the php code needed before any other php code.
	*
	* Method to get the php code for session start, includes and setting the db object.
	* It automatically adds code needed if website has login if first parameter set to true,
	* as well as code needed especially for the login/logout page if second parameter is set to true
	* @param boolean $globallogin	Defaults to false. Indicates if login is enabled on the given website
	* @param boolean $tplogin			Defaults to false. Indicates if page is the login/logout page
	* @return string containing the php necesssary php code for the page
	*/
	public static function GetPhpStart($globallogin=false, $tplogin=false)
	{
		$php =  "<?php ";

		// login/logout page
		if($tplogin)
		{
			$php .=	" session_start();".
					" if(!isset(\$_SESSION['globallogin'])) {\$_SESSION['globallogin']=true;}".
					" if(isset(\$_SESSION['username'])) { session_unset(); session_destroy(); \$logout = true; }".
					" else { session_regenerate_id(); \$logout = false; }".
					" require_once '../lib/DbUser.class.php';require_once '../lib/DbContent.class.php';require_once '../lib/Permission.enum.php';require_once '../config/config.php';".
					" use SemanticCms\DatabaseAbstraction\DbUser;use SemanticCms\Databaseabstraction\DbContent;use SemanticCms\Model\Permission;use SemanticCms\config;".
					" \$db = new DbContent(\$config['cms_db']['dbhost'], \$config['cms_db']['dbuser'], \$config['cms_db']['dbpass'], \$config['cms_db']['database']); ?>";
		}
		// all other pages
		else
		{
			if($globallogin)
			{
				$php .= " session_start(); ".
						" if(!isset(\$_SESSION['globallogin'])) {\$_SESSION['globallogin']=true;}";
			}
			$php .=	" require_once '../lib/DbContent.class.php';".
					" require_once '../config/config.php';".
					" use SemanticCms\Databaseabstraction\DbContent;".
					" use SemanticCms\config;".
					" \$db = new DbContent(\$config['cms_db']['dbhost'], \$config['cms_db']['dbuser'], \$config['cms_db']['dbpass'], \$config['cms_db']['database']);".
					" ?>";
		}
		return $php;
	}

	/**
	* Method to get the header string for a frontend page. A link to an image source is optional.
	* @param string $title title of the website
	* @param string $imgSource Defaults to empty string. File path for the image.
	* @param boolean $globallogin Defaults to false. true enables login navigation
	* @return string header string
	*/
	public static function GetHeader($title, $imgSource="", $globallogin=false)
	{
		$prehead = "";
		if($globallogin)
		{
			$prehead =  "<?php if(isset(\$_SESSION['username'])){ echo \"<a id=\\\"loginOutLink\\\" href=\\\"login_logout.php\\\">Logout</a>\"; }".
						"else { echo \"<a id=\\\"loginOutLink\\\" href=\\\"login_logout.php\\\">Login</a>\";".
						"echo \"<a id=\\\"registerLink\\\" href=\\\"register.php\\\">Registrieren</a>\"; } ?>";
		}
		
		$header =	"<header typeof=\"WPHeader\">";
		if(!empty($imgSource)) $header = $header."<img src=\"..\media\\".$imgSource."\" property=\"image\">";
		$header =	$header."<h1 property=\"name\">".htmlspecialchars($title)."</h1>".
					"</header>";

		return $prehead."\n".$header;
	}


	/**
	* Method to get the nav tag.
	* @param string $pageName Defaults to an empty string. Name of the current page. Will be highlighted in menu.
	* @param int $websiteId Defaults to 1. Set this if you the page belongs to another website than the default one. May be a numeric string (e.g. "1" instead of 1).
	* @return string containing the nav tag (menu of the page)
	*/
	public static function GetMenu($pageName="", $websiteId=1)
	{
		$menu =	"<nav typeof=\"SiteNavigationElement\"> <ul>";

		$menu .= "\n<?php ".
				 " \$result = \$db->GetAllPagesOfWebsite(".$websiteId."); ".
				 " while(\$page = \$db->FetchArray(\$result)) 
				 { ".
				 "\$pagelink = str_replace(array('ß', 'Ä', 'ä', 'Ü', 'ü', 'Ö', 'ö'), array('ss', 'Ae', 'ae', 'Ue', 'ue', 'Oe', 'oe'), \$page[\"title\"]);".
				 "\$pagelink = preg_replace('/[^A-Za-z0-9_\-]/', '_', \$pagelink); ".
				 " if(strcmp(\$page[\"title\"],\"".$pageName."\" ) == 0) {".
				 " echo '<li> <a id=\"currentPage\" href=\"'.\$pagelink.'.php\" itemprop=\"url\">'.\$page[\"title\"].'</a></li>'; } ".
			     " else { echo '<li> <a href=\"'.\$pagelink.'.php\" itemprop=\"url\">'.\$page[\"title\"].'</a></li>';}".
				 " } ?>\n";

		$menu .= "</ul></nav>";

		return $menu;
	}

	/**
	* Method to get the html and php code for the article container (equates the main tag) for non-technical pages
	* @param string $pageName name of the current page
	* @return string containing the main tag with php code (article container) for non-technical pages
	*/
	public static function GetArticleContainer($pageName)
	{
		$article = "<main property=\"mainContentOfPage\" typeof=\"WebPageElement\"><?php ";

		$article .= "\$result = \$db->GetAllArticlesOfPage(\"".$pageName."\");".
					"\$showall = false;if(isset(\$_SESSION['globallogin'])){if(isset(\$_SESSION['username'])){\$showall=true;}}else {\$showall=true;}".
					"while(\$article = \$db->FetchArray(\$result)) { ".
						"\$pubdate = new DateTime(\$article['publicationdate']); \$curdate = new DateTime(date('Y-m-d'));".
						"if (\$pubdate <= \$curdate){".
							" if(\$article['public']==0 && !\$showall) {continue;} ".
							"echo '<section class=\'article\'> <h2 property=\"headline\">'.htmlspecialchars(\$article['header']).'</h2>'.".
								" '<div class=\'info\'> veröffentlicht am <span>'.\$pubdate->format('d. F Y').'</span>'.".
								" ' von <span>'.htmlspecialchars(\$article['author']).'</span></div>'.".
								" '<div class=\'content\'>'.\$article['content'].'</div><ul>';".
								"\$res = \$db->SelectAllLabelsFromAnArticleById(\$article['id']);".
								"if(\$res){while(\$label = \$db->FetchArray(\$res)) {echo '<li>'.htmlspecialchars(\$label['labelname']).'</li>';}}".
							"echo '</ul></section>';}}";

		$article .= "?> </main>";
		return $article;
	}

	/**
	* Method to get the main tag for technical pages (page shown in imprint + login/logout)
	* @param string $content The content of the technical page
	* @param int $specialpage Defaults to 0. Potential values: 0 - regualar technical page, 1 - login/logout page, 2 - register page
	* @return string containing the main tag for the current page
	*/
	public static function GetMain($content, $specialpage=0)
	{
		$main = "<main property=\"mainContentOfPage\" typeof=\"WebPageElement\">";
		switch($specialpage)
		{
			// pages for imprint
			case 0:
				$main .= "<section>".$content."</section> </main>";
				break;
			// login page
			case 1:
				$main .=
					"<?php function PrintLoginField() {".
					"echo \"<h2 property=\\\"headline\\\"> Login </h2>\".".
					"\"<form id=\\\"login\\\" method=\\\"post\\\" action=\\\"login_logout.php\\\">\".".
					"\"<a href=\\\"register.php\\\"><button type=\\\"button\\\"> Registrieren </button></a>\".".
					"\"<p>oder</p><span><span id=\\\"fields\\\">\".".
					"\"<input id=\\\"username\\\" name=\\\"username\\\" type=\\\"text\\\" required=\\\"true\\\" placeholder=\\\"Username oder Email\\\">\".".
					"\"<input id=\\\"password\\\" name=\\\"password\\\" type=\\\"password\\\" required=\\\"true\\\" placeholder=\\\"Passwort\\\"></span>\".".
					"\"<button name=\\\"action\\\" value=\\\"login\\\"> OK </button></span>\".".
					"\"<a href=\\\"ForgotPassword.php\\\">Passwort vergessen?</a></form>\";}".
					"if(\$_SERVER['REQUEST_METHOD']=='POST') { if(\$_POST['action'] == \"login\") {".
					"\$database = new DbUser(\$config['cms_db']['dbhost'],\$config['cms_db']['dbuser'],\$config['cms_db']['dbpass'],\$config['cms_db']['database']);".
					"if(!isset(\$_POST['username']) || !isset(\$_POST['password'])) { die(\"<p> Ein Fehler ist aufgetreten. Bitte versuchen Sie es erneut. </p>\"); }".
					"\$nameInput =  \$_POST[\"username\"]; \$password = \$_POST[\"password\"];\$login = \$database->LoginUser(\$nameInput, \$password);			".
					"if(\$login) {".
					"if (filter_var(\$nameInput, FILTER_VALIDATE_EMAIL)) ".
					"{ \$nameInput = \$database->FetchArray(\$database->SelectUserByEmail(\$nameInput))['username']; }".
					"\$permissions = \$database->FetchArray(\$database->GetUserPermissionByUsername(\$nameInput));".
					"\$_SESSION['username'] = \$nameInput;".
					"\$_SESSION['permissions'] = array();".
					"if(\$permissions[\"guestbookusage\"] == 1) array_push(\$_SESSION['permissions'],Permission::Guestbookusage);".
					"\$_SESSION['rolename'] = \$database->FetchArray(\$database->SelectRolenameByUsername(\$nameInput))['rolename'];	".
					"echo \"<h2 property=\\\"headline\\\"> Login </h2><p> Login erfolgreich. </p>\";}".
					"else { PrintLoginField(); echo \"<p> Username und/oder Passwort sind falsch. Bitte versuchen Sie es erneut. </p>\";}}} else ".
					"{ if(\$logout) {echo \"<h2 property='headline'> Logout</h2><p> Logout erfolgreich. <p>\";}".
					"else { PrintLoginField(); }} ?></main>";
				break;
			// register page
			case 2:
				$main .= self::getRegisterCode()."</main>";				
				break;
		}
		return $main;
	}

	/**
	* Method to get the footer tag.
	* @param array $pages Array containing the technical pages that should be shown in footer
	* @return string containing the footer tag data
	*/
	public static function GetFooter(array $pages)
	{
		$footer =	"<footer typeof=\"WPFooter\"> <ul>";
		foreach($pages as $page)
		{
			$footer .= "<li> <a href=\"".$page["filename"].".php\" itemprop=\"url\">".$page["pagetitle"]."</a></li>";
		}
		$footer .=	"</ul></footer>";

		return $footer;
	}
	
	/** 
	* Method to get the register page main code
	* @return string containing the 
	*/
	private static function getRegisterCode()
	{
		$reg =	"<?php require_once '../lib/DbUser.class.php';use SemanticCms\DatabaseAbstraction\DbUser;". 
				"function printRegisterForm(){".
				"echo \"<h2 property=\\\"headline\\\"> Registrierung </h2> <form id=\\\"register\\\" action=\\\"register.php\\\" method=\\\"POST\\\">\".".
				"\"<span><label for=\\\"username\\\" form=\\\"register\\\">Benutzername:</label>\".".
				"\"<input id=\\\"username\\\" name=\\\"username\\\" type=\\\"text\\\" required=\\\"true\\\"></span>\".".
				"\"<span><label for=\\\"firstname\\\" form=\\\"register\\\">Vorname:</label>\".".
				"\"<input id=\\\"firstname\\\" name=\\\"firstname\\\" type=\\\"text\\\" required=\\\"true\\\" placeholder=\\\"Max\\\"></span>\".".
				"\"<span><label for=\\\"lastname\\\" form=\\\"register\\\">Nachname:</label>\".".
				"\"<input id=\\\"lastname\\\" name=\\\"lastname\\\" type=\\\"text\\\" required=\\\"true\\\" placeholder=\\\"Muster\\\"></span>\".".
				"\"<span><label for=\\\"email\\\" form=\\\"register\\\">E-Mail-Adresse:</label>\".".
				"\"<input id=\\\"email\\\" name=\\\"email\\\" type=\\\"email\\\" required=\\\"true\\\" placeholder=\\\"mail@example.de\\\"></span>\".".
				"\"<span><label for=\\\"date\\\" form=\\\"register\\\">Geburtsdatum:</label>\".".
				"\"<input id=\\\"date\\\" name=\\\"birthdate\\\" type=\\\"date\\\" required=\\\"true\\\" placeholder=\\\"yyyy-mm-dd\\\" pattern=\\\"(19|20)[0-9]{2}-(0[1-9]|1[0-2])-(3[0-1]|[1-2][0-9]|0[1-9])\\\" title=\\\"Datum im Format: yyyy-mm-dd, z.b 2001-12-31\\\"></span>\".".
				"\"<span><label for=\\\"password_1\\\" form=\\\"register\\\">Passwort:</label>\".".
				"\"<input id=\\\"password_1\\\" name=\\\"password_1\\\" type=\\\"password\\\" required=\\\"true\\\"></span>\".".
				"\"<span><label for=\\\"password_2\\\" form=\\\"register\\\">Passwort nochmal:</label>\".".
				"\"<input id=\\\"password_2\\\" name=\\\"password_2\\\" type=\\\"password\\\" required=\\\"true\\\"></span>\".".
				"\"<span><button type=\\\"reset\\\"> Eingabe zurücksetzen </button>\".".
				"\"<button name=\\\"action\\\" value=\\\"register\\\"> Registrieren </button></form></span>\";}".
				"if(\$_SERVER['REQUEST_METHOD']=='POST'){if(\$_POST['action'] == \"register\"){".
				"\$database = new DbUser(\$config['cms_db']['dbhost'],\$config['cms_db']['dbuser'],\$config['cms_db']['dbpass'],\$config['cms_db']['database']);".
				"if(!isset(\$_POST['username'])||!isset(\$_POST['firstname'])||!isset(\$_POST['lastname'])||!isset(\$_POST['email'])||".
				"!isset(\$_POST['birthdate'])||!isset(\$_POST['password_1'])||!isset(\$_POST['password_2']))".
				"{printRegisterForm();die(\"<p>Ein Fehler ist aufgetreten. Bitte versuchen Sie es erneut.</p>\");}else{".
				"if(empty(\$_POST['username'])||empty(\$_POST['firstname'])||empty(\$_POST['lastname'])||empty(\$_POST['email'])||".
				"empty(\$_POST['birthdate'])||empty(\$_POST['password_1'])||empty(\$_POST['password_2']))".
				"{printRegisterForm();die(\"<p>Ein Fehler ist aufgetreten. Bitte versuchen Sie es erneut.</p>\");}".
				"if(strcmp(\$_POST['password_1'],\$_POST['password_2'])==0){".
				"if(\$database->EmailAlreadyExists(\$_POST['email'])){printRegisterForm();die(\"<p>E-Mail-Adresse existiert bereits.</p>\");}".
				"if(\$database->UsernameAlreadyExists(\$_POST['username'])){printRegisterForm();die(\"<p>Username existiert bereits.</p>\");}".
				"if(\$database->RegistrateUser(2, \$_POST['lastname'], \$_POST['firstname'], \$_POST['username'], \$_POST['password_1'], \$_POST['email'], \$_POST['birthdate']))".
				"{echo \"<p> Registrierung erfolgreich <p>\";}else{printRegisterForm();die(\"<p>Ein Fehler ist aufgetreten. Bitte versuchen Sie es erneut.</p>\");}}".
				"else {printRegisterForm();die(\"<p>Passwörter stimmen nicht überein.</p>\");}}}}else{printRegisterForm();}?>";
		return $reg;
	}
}
?>
