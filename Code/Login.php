<!DOCTYPE html>
<html>

<!-- fuer head wird es wahrscheinlich ebenfalls eine Methode geben: printHead(titel?), diese dann ggf. nutzen -->
<head>
    <meta content="de" http-equiv="Content-Language">
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
    <title>Login</title>
    <link rel="stylesheet" href="css/backend.css">
    <link rel="stylesheet" href="css/font-awesome/css/font-awesome.min.css">
</head>

<body>
<section id="login">
<h1>Login</h1>
<form method="post" action="../lib/BackendComponentPrinter.class.php">
	<input id="username" name="username" type="text">
	<input id="password" name="password" type="password">
	<input id="ok" name="ok" type="button" value="OK">
	<input id="forgotPassword" name="forgotPassword" type="button" value="Passwort vergessen">
</form>
</section>

<?php
// hier Logik für logIn rein (Funktionsaufruf LoginUser.php)
  $nameInput =  $_POST["username"];
  $password = $_POST["password"];
  loginUser($nameInput, $password);
?>



</body>

</html>
