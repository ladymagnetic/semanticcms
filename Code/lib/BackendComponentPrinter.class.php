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
	* Prints the sidebar navigation menu incl. the start logo. Menu items will not be displayed if the user does not have the
    * corresponding permission.
	* @params array $permissions The permissions of the currently logged in user. If the array is empty no
     * menu entries are printed and the start logo is not clickable.
	*/
    public static function PrintSidebar(array $permissions)
    {
        if (empty($permissions)) {
            echo
            "<nav id=\"menue\" typeof='SiteNavigationElement'>
			<div id=\"logo\"></div>
			</nav>";
        } else {
            echo
            "<nav id=\"menue\" vocab='https://schema.org/' typeof='SiteNavigationElement'>
			<div id=\"logo\" style=\"cursor: pointer;\" onclick=\"window.location='Start.php';\"></div>
			<ul>";
            // Je nach Rechten bestimmte Menue-Punkte gar nicht erst sichtbar
            if (in_array(Permission::Usermanagment, $permissions)) {
                echo "<li property='name'><a property='url' href=\"Usermanagement.php\" title=\"Benutzerverwaltung\"><i class=\"fa fa-user fontawesome\"></i> Benutzerverwaltung</a></li>";
            }
            if (in_array(Permission::Pagemanagment, $permissions)) {
                echo "<li property='name'><a property='url' href=\"Pagemanagement.php\" title=\"Seitenverwaltung\"><i class=\"fa fa-file-text fontawesome\"></i> Seitenverwaltung</a></li>";
            }
            if (in_array(Permission::Articlemanagment, $permissions)) {
                echo "<li property='name'><a property='url' href=\"Articlemanagement.php\" title=\"Inhaltsverwaltung\"><i class=\"fa fa-align-justify fontawesome\"></i> Inhaltsverwaltung</a></li>";
            }
            if (in_array(Permission::Templateconstruction, $permissions)) {
                echo "<li property='name'><a property='url' href=\"TemplateConstruction.php\" title=\"Templates\"><i class=\"fa fa-paint-brush fontawesome\"></i> Templates</a></li>";
            }
            echo "<li property='name'><a property='url' href=\"Logout.php\" title=\"Logout\"><i class=\"fa fa-paint-brush fontawesome\"></i> Logout</a></li>";

            echo
                "</ul>
    		</nav>";
        }
    }

    /**
     * Prints the head of the current site
     * @param $title The head title
     * @param $jquery true if jquery used otherwise false
     */
    public static function PrintHead($title, $jquery=false)
    {
         // evtl. auch schema.org tags im head einfügen, wenn sinnvoll
        // (z.B. 'description', 'datePublished' (eher für die Blog Artikel geeignet)
         echo
            '<!-- head -->
             <head>
             <meta content="de" http-equiv="Content-Language">
             <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
             <title>'.$title.'</title>
             <link rel="stylesheet" href="media/backend.css">
             <link rel="stylesheet" href="media/font-awesome/css/font-awesome.min.css">';
		 if($jquery) echo '<script src="media/jquery-3.1.1.min.js"> </script>';
         echo '</head>';
    }

    /**
     * Prints the beginning of a table including the header
     * @param array $headerValues The values of the table header
     */
    public static function PrintTableStart(array $headerValues)
    {
        echo "<table>
                <thead>
                    <tr>";

        foreach ($headerValues as $headerValue) {
            echo "<th>$headerValue</th>";
        }

        echo    "</tr>
               </thead>
               <tbody>";
    }

    /**
     * Prints a row to the table body
     * @param array $values The values of the table row
     */
    public static function PrintTableRow(array $values)
    {
        echo "<tr>";
        foreach ($values as $value) {
            echo "<td>$value</td>";
        }
        echo "</tr>";
    }

    /**
     * Prints the end of a table
     */
    public static function PrintTableEnd()
    {
        echo "</tbody>
            </table>";
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
     * Prints a dropdown list together with a label
     * @param $label The corresponding label string
     * @param $name The name attribute of the dropdown list
     * @param array $options The options of the dropdown list
     * @param $size Number of visible options at the same time
     * @param $selected The default selected option string
     */
	public static function PrintDrowpdownList($label, $name, array $options, $size, $selected)
    {
        echo "<label>$label
                <select name='$name' size='$size'>";

        foreach ($options as $option) {
            if ($option == $selected) {
                echo "<option selected='selected'>$option</option>";
            } else {
                echo "<option>$option</option>";
            }
        }

        echo   "</select>
             </label>";
    }

    /**
     * Prints a dropdown list with selectable fonts as options together with a label
     * @param $label The corresponding label string
     * @param $name The name attribute of the dropdown list
     */
    public static function PrintFontsDropdownList($label, $name)
    {
        $fontNames = array(
            "Arial",
            "Arial Black",
            "Comic Sans MS",
            "Courier New",
            "Georgia",
            "Impact",
            "Lucida Console",
            "Lucida Sans Unicode",
            "Palatino Linotype",
            "Tahoma",
            "Times New Roman",
            "Trebuchet MS",
            "Verdana",
            "Symbol",
            "Webdings",
            "Wingdings",
            "MS Sans Serif",
            "MS Serif"
        );

        self::PrintDrowpdownList($label, $name, $fontNames, 1, $fontNames[0]);
    }

    /**
     * Prints the datatables plugin script.
     */
    public static function PrintDatatablesPlugin()
    {
        echo
            "<link rel='stylesheet' type='text/css' href='//cdn.datatables.net/1.10.13/css/jquery.dataTables.css'>
        <script type='text/javascript' charset='utf8' src='//cdn.datatables.net/1.10.13/js/jquery.dataTables.js'></script>".
            "<script>$(document).ready( function () {
            $('table').DataTable({
                'language': {
                    'lengthMenu': '_MENU_ Werte pro Seite',
                    'zeroRecords': 'Nichts gefunden - Entschuldigung',
                    'info': 'Seite _PAGE_ von _PAGES_',
                    'infoEmpty': 'Nichts vorhanden',
                    'infoFiltered': '(gefiltert von _MAX_ gesamt)',
                    'search': 'Suche:',
                    'paginate': {
                        'previous': 'Zurück',
                        'next': 'Weiter',
                    }
                }
            });
        } );</script>";
    }
}
?>