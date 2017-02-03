<?php
/* namespace */
namespace SemanticCms\config;

/* settings */
$config =
[
	/* database specific settings */
	'cms_db' =>
	[
		 // database-name
		 'database' => 'cms-projekt',
		 // username
		 'dbuser' => 'cms',
		 // password
		 'dbpass' => 'pleasechange',
		 // servername (database host)
		 'dbhost' => 'localhost'

	],

	/* error */
	'error' =>
	[
		// login
		'noLogin' => '<!-- Main --> <main><p> Bitte erst einloggen.</p><a href="Index.php" property="url"> Hier geht\'s zum Login </a></main>',
		// permissions not set
		'permissionNotSet' => '<!-- Main --> <main><p> Ein Fehler ist aufgetreten. (PermissionsNotSet)</p></main>',
		// permission missing
		'permissionMissing' => '<!-- Main --> <main><p> Berechtigung für diese Seite fehlt.</p><a href="Start.php" property="url"> Hier geht\'s zurück zur Hauptseite </a></main>'
	],

	'cms_db_export' =>
	[
		 // database-name
		 'database' => 'cms-projekt',
		 // username
		 'dbuser' => 'root',
		 // password
		 'dbpass' => '',
		 // servername (database host)
		 'dbhost' => 'localhost'
	]
];
?>
