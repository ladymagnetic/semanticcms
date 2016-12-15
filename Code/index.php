<?php
/* Include(s) */
require_once 'lib/DbEngine.class.php';
require_once 'lib/BackendComponentPrinter.class.php';
require_once 'config/config.php';

/* use namespace(s) */
use SemanticCms\config;
use SemanticCms\DatabaseAbstraction\DbEngine;
use SemanticCms\ComponentPrinter\BackendComponentPrinter;

$db = new DbEngine($config['cms_db']['dbhost'],$config['cms_db']['dbuser'],$config['cms_db']['dbpass'],$config['cms_db']['database']);

echo "<br><br> var_dump db <br><br>";
var_dump($db);
echo "<br>";

echo "IT WORKS";

// Printer Beispiel
BackendComponentPrinter::start_table();
BackendComponentPrinter::end_table("index.php");
?>