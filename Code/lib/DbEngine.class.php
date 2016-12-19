<?php
/* namespace */
namespace SemanticCms\DatabaseAbstraction;

/* use namespace(s) */
use Mysqli;

/**
* Provides basic functionality for communication with the database
*/
class DbEngine
{
	require_once("..config/config.php");
	private $conn;	// database connection

	/* ---- Constructor / Destructor ---- */
	/**
	* constructor
	* @params string $dsn database connection string
	*/
	public function __construct($host, $user, $password, $database)
	{
		$this->connect_db($host, $user, $password, $database);
	}

	public function __destruct()
	{
		// Does connection still exist?
		if(empty($this->conn)) {$this->disconnect_db();}
	}

	/* ---- Methods ---- */
	/**
	* connect_db()
	* Establishes database connection
	*/
	private function connect_db($host, $user, $password, $database)
	{
		// Create connection
		$this->conn = new mysqli($host, $user, $password, $database, NULL);

		if ($this->conn->connect_error)
		{
			die("Verbindung zur Datenbank konnte nicht hergestellt werden!(".$conn->connect_errno.")");
		}
	}

	/**
	* disconnect_db()
	* Establishes database connection
	*/
	private function disconnect_db()
	{
		$this->$conn->close();
		unset($this->conn);
	}

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
}
?>
