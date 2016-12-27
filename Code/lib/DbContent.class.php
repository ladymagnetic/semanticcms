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
		// Pages
		$allPages = "SELECT page.title, page.relativeposition, template.templatename ".
					"FROM page INNER JOIN template ON page.template_id = template.id ".
					"ORDER BY page.relativeposition ASC;";
		
		$allPagesWithTemplate = "SELECT page.title ".
								"FROM page INNER JOIN template ON page.template_id = template.id ".
								"WHERE templatename = ?;";
								
		if(!$this->database->PrepareStatement("allPages", $allPages)) die("Abfrage konnte nicht erstellt werden.");				
		if(!$this->database->PrepareStatement("allPagesWithTemplate", $allPagesWithTemplate)) die("Abfrage konnte nicht erstellt werden.");
	
	//	var_dump($this->database->GetLastError());
		// Article

		
	}
	
	/*
		titel, relative_position, templatename
	*/
	public function GetAllPages()
	{
		return $this->database->ExecutePreparedStatement("allPages", array());
	}
	
	public function GetAllPagesWithTemplate($templatename)
	{
		return $this->database->ExecutePreparedStatement("allPagesWithTemplate", array($templatename));
	}
	
	// braucht man nur intern beim Hochzählen (?) => private
	private function GetHighestRelativeNumber()
	{
		$result = $this->database->ExecuteQuery("SELECT MAX(relativeposition) AS maxpos FROM page;");
		$num = $this->FetchArray($result);
		
		return  intval($num['maxpos']);
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