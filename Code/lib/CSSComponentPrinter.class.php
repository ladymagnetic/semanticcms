<?php
/**
* Contains the class CSSComponentPrinter.
*/

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
	public static function GetHeader(array $data)
	{
		$css = 	"header {".
					// Font settings
					"color: ".$data['Fontcolor'].";".
					"font-size: ".$data['Fontsize']."px;".
					"font-family: \"".$data['Font']."\",\"Serif\";";
		
		if(!empty($data['Backgroundcolor']))
		{
			$css .= "background-color: ".$data['Backgroundcolor'].";";
		}
		else 
		{
			
		}
	
		$css .= "height: ".$data['Height']."%; width: calc(100% - 20px); padding: 0px 10px; margin-bottom: 10px; display: flex; align-items: center;}";					
 
		$css .=	"}\nh1{margin:0;}\n";
		
		return $css;
	}
	
	
	/**
	*
	*
	*
	*/
	public static function GetMenu(array $data)
	{
		$nav = 	"nav{	background-color: ".$data['Backgroundcolor'].";";
				
		$ul =	"nav ul{ list-style-type: none; margin: 0; padding: 0;";
		$li = 	"nav ul li{";
		$a =	"nav li a{	color: ".$data['Fontcolor'].";".
							"font-size: ".$data['Fontsize']."px;".
							"font-family: \"".$data['Font']."\",\"Serif\";".
							"text-decoration: none; display: block;";	
		$special = "";

		// Order
		if(strcmp($data['Order'], "horizontal") == 0)
		{
			$nav .= "display: table; width: ".$data['Width']."%; height:".$data['Height']."px; margin-bottom: 10px;".
					"padding: 0px 5px;";
			$ul .= "display: table-row; text-align: center;";
			$li .= "display: table-cell; height: ".$data['Height']."px; vertical-align: middle;";
			$a .=  "border-left: 1px solid #333;";
			$special .= " nav li:first-child a { border-left: none;}\n";
		}
		else if(strcmp($data['Order'], "vertical") == 0)
		{
			$nav .= "display: flex; width: ".$data['Width']."%; min-height: ".$data['Height']."px;".
					"flex-direction: column; justify-content: center;";
			$ul .= "padding: 5px;";
			$li .= "text-align: center; margin: 2px 0; border-bottom: 1px solid #333;";
			$special .= " nav li:last-child { border-bottom: none;}\n";
		}

		// Position
		if(strcmp($data['Position'], "left") == 0)	
		{
			if(strcmp($data['Order'], "vertical") == 0) {$nav .= "float:left;";}
		}
		else if(strcmp($data['Position'], "center") == 0)
		{
			$nav .= "margin-left: auto; margin-right: auto; margin-bottom: 10px;";
		}
		else if(strcmp($data['Position'], "right") == 0)
		{
			if(strcmp($data['Order'], "vertical") == 0) {$nav .= "float:right;";}
			else { $nav .= "margin-left: auto;"; }
		}				
				
		$nav .=				"}\n";
		$ul .=				"}\n";
		$li .=				"}\n";
		$a .=				"} #currentPage {font-weight: bold; text-decoration: underline;}\n";		
		
		return $nav."".$ul."".$li."".$a."".$special;
	}
	
	/**
	*
	*
	*
	*/
	public static function GetButton(array $data)
	{
		$css = "button {".
				// Font settings
				"color: ".$data['Fontcolor'].";".
				"font-size: ".$data['Fontsize']."px;".
				"font-family: \"".$data['Font']."\",\"Serif\";";
		
		$css .= "cursor: pointer;";
		if(strcmp($data['Rounded'], "Rounded") == 0)	$css .= "border-radius: 4px;";
		
		if(!empty($data['Backgroundcolor'])) { $css .= "background-color: ".$data['Backgroundcolor'].";";}
		//	else { /* BILD */}
		
		if(strcmp($data['Button3D'], "ja") == 0)
		{
			$css .=  "box-shadow:	1px 1px 1px rgba(255,255,255, 0.5),".
									"inset 1px 1px 1px rgba(255,255,255, 0.5),".
									"-1px -1px 1px rgba(0,0,0, 0.2),".
									"inset -1px -1px 1px rgba(0,0,0, 0.5);".
					 "border: 1px solid #333;".
					 "background-image: linear-gradient(rgba(255,255,255, 0.3), rgba(0,0,0, 0.3))";

		}	
		else { $css .= "border: 2px solid #111;";}		
		
		$css .=	"}\n";
		
		return $css;
	}

	public static function GetArticle(array $data)
	{
		$article =	".article {margin-bottom: 10px; padding: 5px; border: solid #555 1px; background-color: beige;}".
					".article:last-child {margin-bottom: 0px;}";
		$headline = ".article h2 {margin: 0; padding: 0;}";
		$infoline = ".article .info {font-size: smaller;font-style: italic;display: inline-block;padding: 0;padding-left: 10px;}".
				    ".article .info span {font-weight:bold;}";
		$lables = 	".article ul {list-style: none;margin: 0;padding: 5px;}".
					".article ul li {display: inline-block; padding: 5px; margin-right: 5px;".
									"border: 1px solid #555;".
									"color: ".$data['Fontcolor'].";".
									"font-size: ".$data['Fontsize']."px;".
									"font-family: \"".$data['Font']."\",\"sans-serif\";";
		if(strcmp($data['Rounded'], "Rounded") == 0)	$lables .= "border-radius: 4px;";
		if(!empty($data['Backgroundcolor'])) { $lables .= "background-color: ".$data['Backgroundcolor'].";";}
		else {}
		$lables .=	"} .article ul li:last-child {margin-right: 0px;}";
					
		$css = $article."\n".$headline."\n".$infoline."\n".$lables."\n";
		return $css;
	}
	
	public static function GetArticleContainer(array $data)
	{
		$main = "main {clear:both; padding: 10px; width: calc(".$data['Width']."% - 20px); margin-bottom: 10px;";
		
		if(strcmp($data['Position'], "left") == 0)	
		{
		}
		else if(strcmp($data['Position'], "center") == 0)
		{
			$main .= "margin-left: auto; margin-right: auto;";
		}
		else if(strcmp($data['Position'], "right") == 0)
		{
			$main .= "float: right;";
		}	
		if(!empty($data['Backgroundcolor'])) { $main .= "background-color: ".$data['Backgroundcolor'].";";}
		//	else { /* BILD */}	

		return $main."}\n";
	}
	
	public static function GetBackground(array $data)
	{
		$css = " body {margin: 10px; padding: 0; height: 100vh; ";
		
		if(!empty($data['Color']))
		{
			$css .= "background-color: ".$data['Color'].";";
		}
		else {}
		
		$css .=	"}\n";
		return $css;
	}
	public static function GetFooter(array $data)
	{
		// order is missing!!!
		
		$css = 	"footer {".
					// Font settings
					"color: ".$data['Fontcolor'].";".
					"font-size: ".$data['Fontsize']."px;".
					"font-family: \"".$data['Font']."\",\"Serif\";";
		
		if(!empty($data['Backgroundcolor']))
		{
			$css .= "background-color: ".$data['Backgroundcolor'].";";
		}
		else 
		{
			
		}
	
		$css .= "height: calc(".$data['Height']."% + 10px); width: calc(100% - 20px); padding: 10px; margin-bottom: 10px;".
				"clear: both;";
 
		$css .=	"}\n";
		
		$css .= "footer ul {padding: 0; margin: 0; list-style-type: none;}\n";
		$css .= "footer ul li a {text-decoration: none;color: inherit;}\n";
		
		return $css;
	}
	
}
?>