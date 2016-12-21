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
	private $conn;	// database connection

	/* ---- Constructor / Destructor ---- */
	/**
	* constructor
	* @params string $dsn database connection string
	*/
	public function __construct($host, $user, $password, $database)
	{
		$this->connectDB($host, $user, $password, $database);
	}
	public function __destruct()
	{
		// Does connection still exist?
		if(empty($this->conn)) {$this->disconnectDB();}
	}
	/* ---- Methods ---- */
	/**
	* connect_db()
	* Establishes database connection
	*/
	private function connectDB($host, $user, $password, $database)
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
	private function disconnectDB()
	{
		$this->conn->close();
		unset($this->conn);
	}

	/**
	* prepare_statement()
	* Prepares one query with a specific name
	* @param string $name name used for the query
	* @param string $query desired query
	* @result success 0 (false) / 1 (true)
	*/
	public function PrepareStatement($name, $query)
	{
		// $this->$statements[$name] = $this->$conn->prepare($query);

		$this->ExecuteQuery($query);


		// FEHLER Abfragen

		// SELBER
	}


	public function ExecutePreparedStatement($name, array $values)
	{

		// SELBER

		// FEHLER Abfragen
	}


	public function ExecuteQuery($query)
	{
		$this->conn->query($query);
	}

	public function RealEscapeString($string)
	{
		return $this->conn->real_escape_string($string);
	}


}
?>
