<?php
/**
* Contains the class DbEngine.
* @author Tamara Graf
*/

/* namespace */
namespace SemanticCms\DatabaseAbstraction;
/* use namespace(s) */
use Mysqli;

/**
* Provides basic functionality for communication with the database
* @author Tamara Graf
*/
class DbEngine
{
	/** @var Mysqli\mysqli stores information about the database connection */
	private $conn;

	/* ---- Constructor / Destructor ---- */
	/**
	* constructor
	* @param string $host database host
	* @param string $user database user
	* @param string $password password for database user
	* @param string $database database name
	*/
	public function __construct($host, $user, $password, $database)
	{
		$this->connectDB($host, $user, $password, $database);
	}
	/**
	* destructor
	* @param void
	*/
	public function __destruct()
	{
		// Does connection still exist?
		if(empty($this->conn)) {$this->disconnectDB();}
	}

	/* ---- Methods ---- */
	/**
	* Establishes database connection
	* @param string $host database host
	* @param string $user database user
	* @param string $password password for database user
	* @param string $database database name
	* @return void
	*/
	private function connectDB($host, $user, $password, $database)
	{
		// Create connection
		$this->conn = mysqli_connect($host, $user, $password, $database)
			or die("Verbindung zur Datenbank konnte nicht hergestellt werden!(".mysqli_connect_error().")");

		mysqli_set_charset($this->conn, "utf8") or die ("Fehler beim Laden des UTF8-Charaktersets");
	}
	/**
	* disconnect_db()
	* Shutes down the database connection
	* @param void
	* @return void
	*/
	private function disconnectDB()
	{
		mysqli_close($this->conn);
		unset($this->conn);
	}

	/**
	* Prepares one query with a specific name
	* @param string $name name used for the query
	* @param string $query desired query
	* @return boolean success: true (everything went well) / false (error occured)
	*/
	public function PrepareStatement($name, $query)
	{
		$stmt = "PREPARE ".$this->RealEscapeString($name)." FROM '".$query."';";
		$result = $this->ExecuteQuery($stmt);

		if($result === true) { return true;}
		else  {return false;}
	}

	/**
	* Executes a specifc query with the given parameters
	* @param string $name query name (same as used in PrepareStatement())
	* @param array $values query parameter array
	* @return Mysqli\mysqli_result|boolean Result of the query or FALSE on failure
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
				// generates a variable name for SQL
				$varname = "@value_".$counter;

				$set = $varname." = ";

				//sets variable in SQL
				if(is_string($val)) { $set .= "'".$val."'"; }
				else { $set .= "".$val;}
				$this->ExecuteQuery("SET ".$set);

				// generates using string for SQL
				if($counter > 0) $using.=",";	// sets comma
				$using .= " ".$varname." ";

				$counter += 1;
			}
		}

		$stmt = "EXECUTE ".$this->RealEscapeString($name).$using.";";
		return $this->ExecuteQuery($stmt);
	}

	/**
	* Executes a specifc query with the given parameters
	* @param string $query the desired query
	* @return Mysqli\mysqli_result|boolean Result of the query or FALSE on failure
	*/
	public function ExecuteQuery($query)
	{
		return mysqli_query($this->conn, $query);
	}

	/**
	* Escapes a string used for a database query
	* @param string $string string that should be escaped for mysql database
	* @return string|null the escaped string or null if an error occured
	*/
	public function RealEscapeString($string)
	{
		if(!is_string($string)) return null;
		// Evtl. noch weitere Pruefungen erwuenscht
		return mysqli_real_escape_string($this->conn, $string);
	}

	/**
	* Fetches the next result row as an array
	* @param Mysqli\mysqli_result $result Query Result
	* @return array|null array filled with the row's data or null if no next row is available
	*/
	public function FetchArray($result)
	{
		return mysqli_fetch_array ($result);
	}

	/**
	* Returns the number of rows in the result
	* @param Mysqli\mysqli_result $result Query Result
	* @return int number of results (number of rows in $result)
	*/
	public function GetResultCount($result)
	{
		return mysqli_num_rows($result);
	}

	/**
	* Returns last database error
	* @param void
	* @return string errorstring
	*/
	public function GetLastError()
	{
		return "DB-Fehler: ". mysqli_error($this->conn);
	}

	/**
	* Inserts a new logmessage about a change in the log table
	* @param string $username the user who changed something
	* @param string $rolename the user's role
	* @param string $description description of the change
	*/
	public function InsertNewLog($username, $rolename, $description)
	{
		$this->ExecuteQuery("INSERT INTO logtable (id, logdate , username, rolename, description) VALUES (NULL, NOW(), '".$username."', '".$rolename."', '".$description."')");
	}



	/**
	* Download the database
	* @param string $dbhost the host
	* @param string $dbuser the username
	* @param string $dbpwd the password
	* @param string $dbname the databasename
	* @author Mirjam Donhauser
	*/
	public function DownloadDB($dbhost, $dbuser, $dbpwd, $dbname)
	{
		$storagepath = "01_Datenbank/Backup/";
		$pathToMysqldump = "media\mysqldump ";

		$dumpfile = $storagepath .$dbname . "_" . date("Y-m-d_H-i-s") . ".sql";
		passthru("$pathToMysqldump --opt --host=$dbhost --user=$dbuser --password=$dbpwd $dbname > $dumpfile", $rueckgabewert);

		passthru("tail -1 $dumpfile", $rueckgabewert_2);

		if(($rueckgabewert==0)&&($rueckgabewert_2==1))
		{
			echo
			"<div class='info' style='background-color:lime;'>
			<strong>Info!</strong> Die Datenbank wurde erfolgreich exportiert. Sie befindet sich in dem Ordner: ".$dumpfile."
			</div>";
		}
		elseif(($rueckgabewert!=0)||($rueckgabewert_2!=1))
		{
			echo
			"<div class='info' style='background-color:red;'>
			<strong>Info!</strong> Der Export der Datenbank war nicht erfolgreich.
			</div>";
		}
	}



	/**
	* Upload the database
	* @param string $dbhost the host
	* @param string $dbuser the username
	* @param string $dbpwd the password
	* @param string $dbname the databasename
	* @author Mirjam Donhauser
	*/
	public function UploadDB($dbhost, $dbuser, $dbpwd, $dbname)
	{
	  $pathToUploadFile = "01_Datenbank/cms-projekt.sql";
	  $databasename = "cms-projekt";
		$pathToMysql= "media\mysql ";

		//mysql  -h localhost -u root -p DatenbankInPhpMyAdmin < Datenbankname.sql
		passthru("$pathToMysql -h $dbhost  -u $dbuser -p $dbpwd $databasename < $pathToUploadFile", $rueckgabewert);

		if($rueckgabewert == 0)
		{
		echo
		"<div class='info' style='background-color:lime;'>
		<strong>Info!</strong> Diese Datenbank wurde hochgeladen: ".$pathToUploadFile."
		</div>";
		}
		else
		{
			echo
			"<div class='info' style='background-color:red;'>
			<strong>Info!</strong> Der Import der Datenbank ist fehlgeschlagen. ".$pathToUploadFile."
			</div>";
		}

	}



	/**
	* DownloadDBTest()
	* nur für Testzwecke = nicht verwenden!
	* @author Mirjam Donhauser
	*/
	/*
	public function DownloadDBTest()
	{
		$dbhost = 'localhost';
		$dbuser = 'root';
		$dbpwd =  '';
		$dbname =  'cms-projekt';

		$storagepath = "01_Datenbank/Backup/";

		$pathToMysqldump = "media\mysqldump ";

		$dumpfile = $storagepath .$dbname . "_" . date("Y-m-d_H-i-s") . ".sql";

		passthru("$pathToMysqldump --opt --host=$dbhost --user=$dbuser --password=$dbpwd $dbname > $dumpfile");

		echo "$dumpfile "; passthru("tail -1 $dumpfile");
	}
	*/

	/**
	* UploadDBTest()
	* nur für Testzwecke = nicht verwenden!
  * @author Mirjam Donhauser
	*/
 	public function UploadDBTest()
	{
		/*
		$dbhost = 'localhost';
 		$dbuser = 'root';
 		$dbpwd =  '';
 		$dbname =  'cms-projekt';
 		$importpath = "01_Datenbank/cms-projekt.php";

 		$pathToMysql= "media\mysql.exe";
		*/
 		passthru("mysql -h localhost -u root -p cms-projekt < cms-projekt.sql");

		//mysql -h localhost -u root -p DatenbankInPhpMyAdmin < Datenbankname.sql

		echo
		"<div class='info'>
		<strong>Info! </strong> Die Datenbank wurde hochgeladen. </div>";

 	}
}
?>
