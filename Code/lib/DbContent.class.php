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
	 public function __construct($host, $user, $password, $db)
  	{
	   $this->database = new DbEngine($host, $user, $password, $db);
	   $this->PrepareSQL();
    }

		public function __destruct()
		{
			$this->database->__destruct();
		}

	/* ---- Methods ---- */
	/**
	* prepare_sql()
	* Prepares the SQL statements
	*/
	private function PrepareSQL()
	{
	}
	
	/*
		titel, relative_position, templatename
	*/
	public function GetAllPages()
	{
		// Alle Seiten aus der Datenbank sortiert nach Relativer Position aufsteigend 
		
		// SELECT page.titel, page.relativeposition, template.templatename 
		// FROM page INNER JOIN template 
		// ON page.template_id = template.id
		// ORDER BY page.relativeposition ASCENDING
	}
	
	public function GetAllPagesWithTemplate($templatename)
	{
		// Alle Seiten aus der Datenbank, die das Template $templatename verwenden

		// SELECT page.titel 
		// FROM page INNER JOIN template 
		// ON page.template_id = template.id
		// WHERE templatename = ?
	}
	
	// braucht man nur intern (?) => private
	private function GetHighestRelativeNumber()
	{
		// SELECT MAX(relative_position) FROM page
		$result = $this->database->ExecuteQuery("SELECT MAX(relativeposition) FROM page;");
		
	}
	
	public function FetchArray($result)
	{
		return $this->database->FetchArray($result);
	}

	public function GetResultCount($result)
	{
		return  $this->database->GetResultCount($result);
	}
}

?>