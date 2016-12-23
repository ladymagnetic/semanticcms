<?php
// Start the session
session_start();
// remove all session variables
$_SESSION = array(); 			// evtl. auch session_unset();
// destroy the session
session_destroy(); 
?>
<!DOCTYPE HTML>
<html vocab="https://schema.org/" typeof="WebPage" lang="de">
<?php
	/* Include(s) */
	require_once 'lib/BackendComponentPrinter.class.php';
	/* use namespace(s) */
	use SemanticCms\ComponentPrinter\BackendComponentPrinter;
	BackendComponentPrinter::PrintHead("Logout");
?>
<body>
	<!-- Main -->
	<main id="logout">
	<h1 property="headline"> <i class='fa fa-key fontawesome'></i> Logout</h1>
	<p> Logout erfolgreich <p>
	<a href="Index.php" property="url"> Weiter zum Login </a>
	</main>
</body>
</html>