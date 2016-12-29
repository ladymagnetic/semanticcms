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
		$this->conn = mysqli_connect($host, $user, $password, $database)
			or die("Verbindung zur Datenbank konnte nicht hergestellt werden!(".mysqli_connect_error().")");
	}
	/**
	* disconnect_db()
	* Establishes database connection
	*/
	private function disconnectDB()
	{
		mysqli_close($this->conn);
		unset($this->conn);
	}

	/**
	* prepare_statement()
	* Prepares one query with a specific name
	* @param string $name name used for the query
	* @param string $query desired query
	* @result success as boolean value: true (everything went well) / false (error occured)
	*/
	public function PrepareStatement($name, $query)
	{
		$stmt = "PREPARE ".$this->RealEscapeString($name)." FROM '".$query."';";
		$result = $this->ExecuteQuery($stmt);
		
		if($result === true) { return true;}
		else  {return false;}
	}

	/**
	* ExecutePreparedStatement()
	* Executes a specifc query with the given parameters
	* @param string $name query name (same as used in PrepareStatement())
	* @param array $params query parameter array
	* @result query result (could be FALSE on failure)
	*/
	public function ExecutePreparedStatement($name, array $values)
	{
		$using = "";
		$counter = 0;
		
		if(!empty($values))
		{
			// USING necessary
			$using = " USING ";

			foreach($values as $val)
			{
				// generates a variable name for sql
				$varname = "@value_".$counter;
				
				$set = $varname." = ";			
				//sets variable in SQL
				if(is_string($val)) { $set .= "'".$val."'"; }
				else { $set .= "".$val;}
				//sets variable in SQL
				$this->ExecuteQuery("SET ".$set);
				
				$using .= " ".$varname." ";
			}
		}
					
		$stmt = "EXECUTE ".$this->RealEscapeString($name).$using.";";
		
		return $this->ExecuteQuery($stmt);
	}

	/**
	* ExecuteQuery()
	* Executes a specifc query with the given parameters
	* @param string $query the desired query
	* @result query result (could be FALSE on failure)
	*/
	public function ExecuteQuery($query)
	{
		$result =  mysqli_query($this->conn, $query);	
		return $result;
	}

	public function RealEscapeString($string)
	{
		// Evtl. noch weitere Pruefungen erwuenscht
		return mysqli_real_escape_string($this->conn, $string);
	}

	/**
	* FetchArray()
	* @params resource $result query result
	* @result query result (could be FALSE on failure)
	*/
	public function FetchArray($result)
	{
		return mysqli_fetch_array ($result);
	}

	/**
	* GetResultCount()
	* Returns the number of rows in the result.
	* @params resource $result query result
	* @result number of results (number of rows in $result)
	*/
	public function GetResultCount($result)
	{
		return mysqli_num_rows($result);
	}
	
	/**
	* GetLastError()
	* Returns last database error
	* @result errorstring
	*/
	public function GetLastError()
	{
		return "DB-Fehler: ". mysqli_error($this->conn);
	}
	
	/**
	* InsertNewLog()
	* @params string $logUsername the user who changed something
	* @params string $logRolename the user's role who changes something
	* @params string $logDescription describes the things which have been changed until now
	*  to log all the changes on the database so that the admin can see all important information at a glance
	*/
	public function InsertNewLog($logUsername, $logRolename, $logDescription)
	{
		$result = $this->ExecuteQuery("INSERT INTO logtable (id, logdate , username, rolename, description) VALUES (NULL, NOW(), '".$logUsername."', '".$logRolename."', '".$logDescription."')");
	}
}
?>