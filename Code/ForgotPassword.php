<?php
// Start the session
session_start();
?>
<!DOCTYPE html>
<html vocab="https://schema.org/" typeof="WebPage" lang="de">
	<?php


		/* Include(s) */
		require_once 'lib/DbEngine.class.php';
		require_once 'lib/BackendComponentPrinter.class.php';
		require_once 'config/config.php';
		require_once 'lib/DbUser.class.php';
		require_once 'lib/Permission.enum.php';

		/* use namespace(s) */
		use SemanticCms\config;
		use SemanticCms\DatabaseAbstraction\DbEngine;
		use SemanticCms\ComponentPrinter\BackendComponentPrinter;
		use SemanticCms\DatabaseAbstraction\DbUser;
		use SemanticCms\Model\Permission;



		BackendComponentPrinter::PrintHead("Passwort vergessen");

		$database = new DbUser($config['cms_db']['dbhost'],$config['cms_db']['dbuser'],$config['cms_db']['dbpass'],$config['cms_db']['database']);
	?>
<body>
	<?php


		/* Check if user is logged in */
		/*if(!isset($_SESSION['username']))
		{
			die($config['error']['noLogin']);
		}
		/* Check if  permissions are set */
		/*else if(!isset($_SESSION['permissions']))
		{
			die($config['error']['permissionNotSet']);
		}

		// no special permissions required for startpage beside login
		BackendComponentPrinter::PrintSidebar($_SESSION['permissions']);*/
	?>

	<?php


function random_string() {
 if(function_exists('random_bytes')) {
 $bytes = random_bytes(16);
 $str = bin2hex($bytes);
 } else if(function_exists('openssl_random_pseudo_bytes')) {
 $bytes = openssl_random_pseudo_bytes(16);
 $str = bin2hex($bytes);
 } else if(function_exists('mcrypt_create_iv')) {
 $bytes = mcrypt_create_iv(16, MCRYPT_DEV_URANDOM);
 $str = bin2hex($bytes);
 } else {
 //Bitte euer_geheim_string durch einen zufälligen String mit >12 Zeichen austauschen
 $str = md5(uniqid('euer_geheimer_string', true));
 }
 return $str;
}




if(isset($_POST['send']) ) {
	$empfaenger = "th.schuster12@web.de";
	$betreff = "Passwort vergessen";
	$text = "blablabla";

	mail($empfaenger, $betreff, $text);





 echo "Ein Link um dein Passwort zurückzusetzen wurde an deine E-Mail-Adresse gesendet.";

 //}
 //}
}

?>
<main>
	<h1>Passwort vergessen</h1>
Gib hier deine E-Mail-Adresse ein, um ein neues Passwort anzufordern.<br><br>

<?php
if(isset($error) && !empty($error)) {
echo $error;
}
?>

<form action="ForgotPassword.php" method="post">
E-Mail:<br>
<input type="email" name="email" value="<?php echo isset($_POST['email']) ? htmlentities($_POST['email']) : ''; ?>"><br>
<input type="submit" name="send" value="Neues Passwort">
</form>
	<?php
	// Form Handling nach POST
	/*if($_SERVER['REQUEST_METHOD']=='POST')
	{
	}*/
	?>
</main>
</body>


</html>
