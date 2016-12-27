<?php
// Start the session
session_start();
?>
<!DOCTYPE html>
<html vocab="https://schema.org/" typeof="WebPage" lang="de">
<?php
/* Include(s) */
require_once 'lib/BackendComponentPrinter.class.php';

/* use namespace(s) */
use SemanticCms\ComponentPrinter\BackendComponentPrinter;

BackendComponentPrinter::PrintHead("Seitenverwaltung", $jquery=true);
?>
<body>
<?php
/* Include(s) */
require_once 'lib/Permission.enum.php';
require_once 'config/config.php';

/* use namespace(s) */
use SemanticCms\Model\Permission;

// todo: wieder auskommentieren, wenn Login geht
///* Check if user is logged in */
//if(!isset($_SESSION['username']))
//{
//    die($config['error']['noLogin']);
//}
///* Check if  permissions are set */
//else if(!isset($_SESSION['permissions']))
//{
//    die($config['error']['permissionNotSet']);
//}
///*  Check if user has the permission to see this page */
//else if(!in_array(Permission::Pagemanagment, $_SESSION['permissions']))
//{
//    die($config['error']['permissionMissing']);
//}
//
//BackendComponentPrinter::PrintSidebar($_SESSION['permissions']);
BackendComponentPrinter::PrintSidebar(array(Permission::Guestbookusage,
    Permission::Articlemanagment, Permission::Pagemanagment, Permission::Templateconstruction,
    Permission::Guestbookmanagment, Permission::Usermanagment));

/* Datatables */
BackendComponentPrinter::PrintDatatablesPlugin();
?>
<main>
    <h1><i class="fa fa-file-text fontawesome"></i> Seitenverwaltung</h1>

    <?php
    /* Print Pages table */
    BackendComponentPrinter::PrintTableStart(array("Seite", "Template", "Aktionen", "Menüposition"));
    $rowValues = array("",
        "<form id='template' name='template' action='../lib/BackendComponentPrinter.class.php'> <label>Template: <select name='top5'> <option>Layout 1</option> <option>Layout 2</option> <option>Layout 3</option> <option>Layout 4</option> <option>Layout 5</option> </select> </label> </form>",
        "<form method='post'>
                <input id='editContent' name='editContent' type='button' value='Löschen'><input name='Button2' type='button' value='Inhalte bearbeiten'></form>",
        "");
    BackendComponentPrinter::PrintTableRow($rowValues);
    BackendComponentPrinter::PrintTableEnd();
    ?>

    <form method="post" action="../lib/BackendComponentPrinter.class.php">
        <input id="newPage" name="newPage" type="button" value="Neue Seite">
        <input id="options" name="options" type="button" value="Optionen">
    </form>
</main>
</body>

</html>
