<?php
/* namespace */
namespace SemanticCms\ComponentPrinter;

/* includes */
// require_once 'filename';

/**
* Provides static functions for creating the backend page.
*/
abstract class BackendComponentPrinter
{

	print_navigation_menue()
	{
		echo
			"<!-- menue -->
			<nav id="menue">
			    <div id="logo"></div>
				<ul>
			        <li><a href="Benutzerverwaltung.php" title="Benutzerverwaltung"><i class="fa fa-user fontawesome"></i> Benutzerverwaltung</a></li>
			        <li><a href="Seitenverwaltung.php" title="Seitenverwaltung"><i class="fa fa-file-text fontawesome"></i> Seitenverwaltung</a></li>
			        <li><a href="Inhaltsverwaltung.php" title="Inhaltsverwaltung"><i class="fa fa-align-justify fontawesome"></i> Inhaltsverwaltung</a></li>
			        <li><a href="Templates.php" title="Templates"><i class="fa fa-paint-brush fontawesome"></i> Templates</a></li>
				</ul>
			</nav>";
	}
	/* -------- BEISPIEL METHODEN - BITTE L�SCHEN WENN NICHT MEHR BEN�TIGT --------- */

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
			 // "<button class=\"btn navbtn\" type=\"submit\" name=\"showall\">Seite zur�ck</button>".
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

	// /**
	// * Start the quarantine table and print table head
	// * @params string $search search result to display
	// */
	// public static function start_table($search="")
	// {
		// echo "<section id=\"mailtable\">";
		// echo "<p>".$search."</p>";
		// echo "<table id=\"quar_table\"class=\"sortierbar\">
			// <thead>
				// <tr>
					// <th id=\"qcont\" class=\"b_bott sortierbar\">Inhalt</th>
					// <th id=\"qscore\" class=\"b_bott b_left sortierbar\">Score</th>
					// <th id=\"qfrom\" class=\"b_bott b_left sortierbar\">Von</th>
					// <th id=\"qto\" class=\"b_bott b_left sortierbar\">An</th>
					// <th id=\"qsubj\" class=\"b_bott b_left sortierbar\">Betreff</th>
					// <th id=\"qdate\" class=\"b_bott b_left sortierbar\">Datum</th>
					// <th id=\"qfree\" class=\"b_bott b_left\"></th>
				// </tr>
			// </thead>";
	// }

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

	// /**
	// * Ends the table and prints invisible form with given action.
	// * @params string $action action to be performed by the <form>
	// */
	// public static function end_table($action)
	// {
		// echo "</table>";
		// echo "<form id=\"rel\" method=\"post\" action=\"".$action."\"></form>";
		// echo "</section>";
	// }
}
?>
