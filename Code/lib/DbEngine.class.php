<?php
/* namespace */
namespace SemanticCms\DatabaseAbstraction;

/**
* Provides basic functionality for communication with the database
*/
class DbEngine
{
	/* ------ BEISPIEL CODE ------*/
	// private $conn;	// database connection

	/* ---- Constructor / Destructor ---- */
	// /**
	// * constructor
	// * @params string $dsn database connection string
	// */
	// public function __construct($dsn) { $this->connect_db($dsn); }
	// public function __destruct()
	// {
	 // // Does connection still exist?
	 // if(empty($this->conn)) {$this->disconnect_db();}
	// }
	
	/* ---- Methods ---- */	
	// /**
	// * connect_db($dsn)
	// * Establishes database connection
	// * @param string $dsn connection string
	// */
	// private function connect_db($dsn)
	// {
	 // $this->conn = pg_connect($dsn)
	       // or die ("Verbindung zur Datenbank konnte nicht hergestellt werden!");
	// }
	
	// /**
	// * prepare_statement()
	// * Prepares one query with a specific name
	// * @param string $name name used for the query
	// * @param string $query desired query
	// * @result success 0 (false) / 1 (true)
	// */
	// public function prepare_statement($name, $query)
	// {
	 // if(!pg_prepare($this->conn, $name, $query)) { return 0; }
	 // else {return 1;}
	// }
	
?>