<?php
/* namespace */
namespace SemanticCms\DatabaseAbstraction;

/* Include(s) */
require_once 'DbEngine.class.php';

/**
* Provides functionality for communication with the database according to page content
*/
class DbContent
{
	private $database;			// DbEngine object

	/* ---- Constructor / Destructor ---- */
	/**
	* constructor
	* @params string $dsn database connection string
	*/
	// public function __construct($dsn)
	// {
	  // $this->database = new DbEngine($dsn);
	  // $this->prepare_sql();
	// }

	// public function __destruct()
	// {
	  // $this->database->__destruct();
	// }
	
	/* ---- Methods ---- */
	/**
	* prepare_sql()
	* Prepares the SQL statements
	*/
	private function prepare_sql()
	{
		// put your queries here
	}
}

?>