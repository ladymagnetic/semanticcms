<?php
// Start the session
session_start();
?>
<!DOCTYPE html>
<html vocab="https://schema.org/" typeof="WebPage" lang="de">
	<?php
		require_once 'lib/BackendComponentPrinter.class.php';
		use SemanticCms\ComponentPrinter\BackendComponentPrinter;
		BackendComponentPrinter::PrintHead("Startseite");
	?>
<body>
	<?php
		// Der BackendComponentPrinter ist bereits eingebunden
		/* Include(s) */
		require_once 'lib/Permission.enum.php';
		require_once 'config/config.php';

		/* use namespace(s) */
		use SemanticCms\Model\Permission;
		
		/* Check if user is logged in */
		if(!isset($_SESSION['username'])) 
		{
			die($config['error']['noLogin']);  
		}
		/* Check if  permissions are set */
		else if(!isset($_SESSION['permissions']))
		{
			die($config['error']['permissionNotSet']);  		
		}
		
		// no special permissions required for startpage beside login
		BackendComponentPrinter::PrintSidebar($_SESSION['permissions']);
	?>
<section id="main">
    <h1>Startseite</h1>
    <table>
        <tr>
            <th>Letzte Änderungen</th>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
    </table>
    <form method="post" action="Start.php">
		<button id="exportDatabase" name="action" value="exportDatabase"> Datenbank exportieren </button>
		<button id="importDatabase" name="action" value="importDatabase"> Datenbank importieren </button>
    </form>
	<?php
	// Form Handling nach POST
	if($_SERVER['REQUEST_METHOD']=='POST')
	{
	}
	?>
</section>
</body>

</html>
