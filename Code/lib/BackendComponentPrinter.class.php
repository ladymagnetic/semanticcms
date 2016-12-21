<?php
/* namespace */
namespace SemanticCms\ComponentPrinter;

/* includes */
// require_once 'filename';
require_once 'lib/Permission.enum.php';

use SemanticCms\Model\Permission;

/**
* Provides static functions for creating the backend page.
*/
class BackendComponentPrinter
{
	/* -------- BEISPIEL METHODEN - BITTE LÖSCHEN WENN NICHT MEHR BENÖTIGT --------- */

	// /**
	// * print_showall_navigation()
	// * Print back and next navigation buttons
	// * @params string $action .php-script used
	// * @params int $page current page
	// * @params boolean $next true: draw next button, false: no next button needed
	// */
	// public static function print_showall_navigation($action, $page, $next)
	// {
	 // echo "<section id=\"tablenav\">";
	 // if($page > 0)
	 // {
	  // echo   "<form class=\"inline\" method=\"POST\" action=\"".$action."\">".
			 // "<button class=\"btn navbtn\" type=\"submit\" name=\"showall\">Seite zurück</button>".
			 // "<input type=\"hidden\" name=\"pagenum\" value=\"".($page-1)."\">".
			 // "</form>";
	 // }

	 // if($next)
	 // {
	  // echo   "<form class=\"inline\" method=\"POST\" action=\"".$action."\">".
			 // "<button class=\"btn navbtn\" type=\"submit\" name=\"showall\">Seite vor</button>".
			 // "<input type=\"hidden\" name=\"pagenum\" value=\"".($page+1)."\">".
			 // "</form>";
	 // }
	 // echo "</section>";
	// }

	/**
	* Start the quarantine table and print table head
	* BEISPIEL AUCH HIER!!!!!!
	* @params string $search search result to display
	*/
	public static function start_table($search="")
	{
		echo "<section id=\"mailtable\">";
		echo "<p>".$search."</p>";
		echo "<table id=\"quar_table\"class=\"sortierbar\">
			<thead>
				<tr>
					<th id=\"qcont\" class=\"b_bott sortierbar\">Inhalt</th>
					<th id=\"qscore\" class=\"b_bott b_left sortierbar\">Score</th>
					<th id=\"qfrom\" class=\"b_bott b_left sortierbar\">Von</th>
					<th id=\"qto\" class=\"b_bott b_left sortierbar\">An</th>
					<th id=\"qsubj\" class=\"b_bott b_left sortierbar\">Betreff</th>
					<th id=\"qdate\" class=\"b_bott b_left sortierbar\">Datum</th>
					<th id=\"qfree\" class=\"b_bott b_left\"></th>
				</tr>
			</thead>";
	}

	/**
	* Prints the sidebar navigation menu. Menu items will not be displayed if the user does not have the
    * corresponding permission.
	* @params array $permissions The permissions of the currently logged in user.
	*/
    public static function printSidebar(array $permissions)
    {
        echo
        "<nav id=\"menue\">
			<div id=\"logo\" style=\"cursor: pointer;\" onclick=\"window.location='Index.php';\"></div>
			<ul>";
        // Je nach Rechten bestimmte Menue-Punkte gar nicht erst sichtbar
        if (in_array(Permission::Usermanagment, $permissions)) {
            echo "<li><a href=\"Benutzerverwaltung.php\" title=\"Benutzerverwaltung\"><i class=\"fa fa-user fontawesome\"></i> Benutzerverwaltung</a></li>";
        }
        if (in_array(Permission::Pagemanagment, $permissions)) {
            echo "<li><a href=\"Seitenverwaltung.php\" title=\"Seitenverwaltung\"><i class=\"fa fa-file-text fontawesome\"></i> Seitenverwaltung</a></li>";
        }
        if (in_array(Permission::Articlemanagment, $permissions)) {
            echo "<li><a href=\"Inhaltsverwaltung.php\" title=\"Inhaltsverwaltung\"><i class=\"fa fa-align-justify fontawesome\"></i> Inhaltsverwaltung</a></li>";
        }
        if (in_array(Permission::Templateconstruction, $permissions)) {
            echo "<li><a href=\"Templates.php\" title=\"Templates\"><i class=\"fa fa-paint-brush fontawesome\"></i> Templates</a></li>";
        }

        echo
        "</ul>
		</nav>";
    }

    /**
     * Prints the head of the current site
     * @param $title The head title
     */
    public static function printHead($title)
    {
        // RDF-Tags oder schema.org oder so mit einfuegen => dazu steht unter Allgemeines/SemanticWeb/ was
         echo
         '<head>
             <meta content="de" http-equiv="Content-Language">
             <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
             <title>'.$title.'</title>
             <link rel="stylesheet" href="css/backend.css">
             <link rel="stylesheet" href="css/font-awesome/css/font-awesome.min.css">
         </head>';
    }

	// /**
	// * Prints a table row with the given values to quarantine table
	// * @params array $values Values to print to the table (Please use the following keys: content, score, from, to, sub, date and id!)
	// * @result status 0 (error) / 1 (success)
	// */
	// public static function print_table_row(array $values)
	// {
		// // is $valus an array?
		// if(!is_array($values)) { return 0; }
		// // does $values have the key and values[key] is not set to NULL?
		// if(!(      isset($values['content'])
			// && isset($values['score'])
			// && isset($values['from'])
			// && isset($values['to'])
			// && isset($values['sub'])
			// && isset($values['date'])
			// && isset($values['id'])
		  // )) { return 0; }

		// // convert date into day-month-year hour-minute-second format
		// $date = strftime('%d.%m.%Y %H:%M:%S', $values['date']);

		// // print table row
		// echo "<tr ".parent::get_content_col_class($values['content'])." >";
			// echo "<td class=\"b_above\"><a href=\"header.php?id=".$values['id']."\" title=\"Header dieser Email anzeigen.\">".parent::get_content_readable($values['content']). "</a></td>";
			// echo "<td class=\"b_above b_left\">".$values['score']. "</td>";
			// echo "<td class=\"b_above b_left\">".htmlspecialchars($values['from']). "</td>";
			// echo "<td class=\"b_above b_left\">".htmlspecialchars($values['to']). "</td>";
			// echo "<td class=\"b_above b_left\"><a href=\"header.php?id=".$values['id']."\" title=\"Header dieser Email anzeigen.\">".htmlspecialchars($values['sub']). "</a></td>";
			// echo "<td class=\"b_above b_left\">".$date."</td>";
			// echo "<td class=\"b_above b_left\">";
				// echo "<button class=\"btn\" title=\"Diese E-Mail freigeben.\" form=\"rel\" type=\"submit\" name=\"release\" value=\"".$values['id'].":".$values['to']."\">Freigeben</button>";
			// echo "</td>";
		// echo "</tr>";

		// return 1;
	// }

	/**
	* Ends the table and prints invisible form with given action.
	* BEISPIEL BITTE WIEDER LÖSCHEN WENN NICHT GEBRAUCHT
	* @params string $action action to be performed by the <form>
	*/
	public static function end_table($action)
	{
		echo "</table>";
		echo "<form id=\"rel\" method=\"post\" action=\"".$action."\"></form>";
		echo "</section>";
	}
}
?>