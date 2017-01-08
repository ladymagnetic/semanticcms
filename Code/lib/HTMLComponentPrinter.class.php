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
	
	public static function GetPhpStart($technicalPage=false, $login=false)
	{
		$php =  "<?php ".
				" session_start(); ".
				" require_once 'lib/DbContent.class.php';".
				" require_once 'config/config.php';".
				" use SemanticCms\Databaseabstraction\DbContent;".
				" use SemanticCms\config;".
				" \$db = new DbContent(\$config['cms_db']['dbhost'], \$config['cms_db']['dbuser'], \$config['cms_db']['dbpass'], \$config['cms_db']['database']);".
				" ?>";
		return $php;
	}
	
	/**
	* GetHeader()
	* Returns the header-string for a frontend page. A link to an image source is optional.
	* @return string header string
	*/
	public static function GetHeader($title, $imgSource="")
	{
		$header =	"<header>";
		if(!empty($imgSource)) $header = $header."<img src=\"media\\".$imgSource."\" property=\"image\">";
		$header =	$header."<h1 property=\"name\">".htmlspecialchars($title)."</h1>".
					"</header>";
					
		return $header;
	}
	
	
	public static function GetMenu($pageName)
	{
		$menu =	"<nav typeof=\"SiteNavigationElement\"> <ul>";

		$menu .= "\n<?php ".
				 " \$result = \$db->GetAllPages(); ".
				 " while(\$page = \$db->FetchArray(\$result)) { ".
				 " if(strcmp(\$page[\"title\"],\"".$pageName."\" ) == 0) {".	
				 " echo '<li> <a id=\"currentPage\" href=\"'.\$page[\"title\"].'.php\" itemprop=\"url\">'.\$page[\"title\"].'</a></li>'; } ".
			     " else { echo '<li> <a href=\"'.\$page[\"title\"].'.php\" itemprop=\"url\">'.\$page[\"title\"].'</a></li>';}".
				 " } ?>\n";
				
		$menu .= "</ul></nav>";
					
		return $menu;
	}
	
	public static function GetArticleContainer($pageName)
	{
		$article = "<main><?php ";
		
		$article .= "\$result = \$db->GetAllArticlesOfPage(\"".$pageName."\");".
					"while(\$article = \$db->FetchArray(\$result)) { ".
						"\$pubdate = new DateTime(\$article['publicationdate']); \$curdate = new DateTime(date('Y-m-d'));".
						"if (\$pubdate <= \$curdate){".
							"echo '<section class=\'article\'> <h2>'.htmlspecialchars(\$article['header']).'</h2>'.".
								" '<div class=\'info\'> veröffentlicht am <span>'.\$pubdate->format('d. F Y').'</span>'.". 
								" ' von <span>'.htmlspecialchars(\$article['author']).'</span></div>'.".
								" '<div class=\'content\'>'.\$article['content'].'</div><ul>';".
								"\$res = \$db->SelectAllLablesFromAnArticleById(\$article['id']);".
								"while(\$label = \$db->FetchArray(\$res)) {echo '<li>'.htmlspecialchars(\$label['lablename']).'</li>';}".
							"echo '</ul></section>';}}";
							
		$article .= "?> </main>";		
		return $article;
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