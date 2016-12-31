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




	/*---- Mirjam ----*/

		$allArticles = "SELECT * FROM article";
		$this->database->PrepareStatement("allArticles", $allArticles);

		$allArticlesWithDetailedInformation = "SELECT article.header, article.content, article.publicationdate, article.public, user.username, page.title ".
												"FROM page INNER JOIN article ON page.id = article.page_id INNER JOIN user ON article.author = user.id ".
												"ORDER BY user.username ASC;";
		$this->database->PrepareStatement("allArticlesWithDetailedInformation", $allArticlesWithDetailedInformation);


		$deleteArticleById = "DELETE FROM article WHERE id = ?";
		$this->database->PrepareStatement("deleteArticleById", $deleteArticleById);


		$selectOneArticleById = "SELECT * FROM article WHERE id = ?";
		$this->database->PrepareStatement("selectOneArticleById", $selectOneArticleById);


		
		$selectPageByPagename = "SELECT * FROM page WHERE title = ?";
		$this->database->PrepareStatement("selectPageByPagename", $selectPageByPagename);

		$selectPageById = "SELECT * FROM page WHERE id = ?";
		$this->database->PrepareStatement("selectPageById", $selectPageById);

		$selectTemplateById = "SELECT * FROM template WHERE id = ?";
		$this->database->PrepareStatement("selectTemplateById", $selectTemplateById);

		$selectTemplateByTemplatename = "SELECT * FROM template WHERE templatename = ?";
		$this->database->PrepareStatement("selectTemplateById", $selectTemplateByTemplatename);

		$selectPageIdByPagename = "SELECT id FROM page WHERE title = ?";
		$this->database->PrepareStatement("selectPageIdByPagename", $selectPageIdByPagename);



		$deletePageById =  "DELETE FROM page WHERE id = ?";
		$this->database->PrepareStatement("deletePageById", $deletePageById );

		$deletePageByTitle =  "DELETE FROM page WHERE title = ?";
		$this->database->PrepareStatement("deletePageByTitle", $deletePageByTitle );

		$deleteTemplateById =  "DELETE FROM template WHERE id = ?";
		$this->database->PrepareStatement("deleteTemplateById", $deleteTemplateById );

		$deleteTemplateByTemplatename =  "DELETE FROM template WHERE templatename = ?";
		$this->database->PrepareStatement("deleteTemplateByTemplatename", $deleteTemplateByTemplatename );




		// => anstatt: GetArticleLabels($id)
		$selectAllLable_Article = "SELECT * FROM lable_article";
		$this->database->PrepareStatement("selectAllLable_Article", $selectAllLable_Article );

		$selectAllLablesFromAnArticleById = "SELECT * FROM article INNER JOIN lable_article  ON article.id = lable_article.article_id INNER JOIN Lable ON lable_article.lable_id = lable.id WHERE article.id = ?";
		$this->database->PrepareStatement("selectAllLablesFromAnArticleById", $selectAllLablesFromAnArticleById );

	}

	/**
	* GetAllArticles()
	*/
	public function GetAllArticles()
	{
		return $this->database->ExecutePreparedStatement("allArticles", array());
	}



	/**
	* GetAllArticlesWithDetailedInformation()
	*/
	public function GetAllArticlesWithDetailedInformation()
	{
		return $this->database->ExecutePreparedStatement("allArticlesWithDetailedInformation", array());
	}



	/**
	* DeleteArticleById()
	* @params int $articleId is the id of the article
	*/
	public function DeleteArticleById($articleId)
	{
		$result = $this->database->ExecutePreparedStatement("deleteArticleById", array($articleId));

		$headerOfArticle = $this->database->ExecuteQuery("SELECT header FROM article WHERE id = ".$articleId);

		if($result==true)
		{
			$logUsername = 'Wer ist gerade angemeldet?';
			$logRolename = 'Welche Rolle hat der angemeldete Benutzer?';
			//$logDescription = 'der User: '.$usersName.' wurde gelöscht';
			$logDescription = 'Folgender Article wurde gelöscht: => $headerOfArticle';

			$re = $this->database->InsertNewLog($logUsername, $logRolename, $logDescription);

			return true;
		}
		else
		{
			 return false;
		}
	}



	// neue Funktion für => 	GetArticleInformationById($articleId):
	/**
	* SelectOneArticleById()
	* @params int $articleId the id of the article
	*/
	public function SelectOneArticleById($articleId)
	{
		return $this->database->ExecutePreparedStatement("selectOneArticleById", array($articleId));
	}


	/**
	* InsertNewArticleToPage()
	* @params string $header
	* @params string $content
	* @params string $publicationdate
	* @params int $pageId the id of the page the article is published
	* @params int author the author's id
	* @params string $type
	* @params int $public
	* @params string $description
	*/
	public function InsertNewArticleToPage($header, $content, $publicationdate, $pageId, $author, $type, $public, $description)
	{
		$header = $this->database->RealEscapeString($header);
		$content = $this->database->RealEscapeString($content);
		$type = $this->database->RealEscapeString($type);
		$description = $this->database->RealEscapeString($description);

		$result = $this->database->ExecuteQuery("INSERT INTO article (id, header, content, publicationdate, page_id, author, type, public, description) VALUES (NULL, '".$header."', '".$content."', '".$publicationdate."', ".$pageId.", ".$author.", '".$type."', ".$public.", '".$description."')");

		 if($result==true)
		 {
			$logUsername = 'Wer ist gerade angemeldet?';
			$logRolename = 'Welche Rolle hat der angemeldete Benutzer?';
			$logDescription = 'Welcher Articel wurde neu eingefügt? => $header';

			$re = $this->database->InsertNewLog($logUsername, $logRolename, $logDescription);

			var_dump($re);

			return true;
		 }
		 else
		 {
			 return false;
		 }
	}



	/**
	* UpdateArticleToPage()
	* @params int $articleId the id of the article
	* @params string $header
	* @params string $content
	* @params string $publicationdate
	* @params int $pageId the id of the page the article is published
	* @params int $author
	* @params string $type
	* @params int $public
	* @params string $description
	*/
	public function	UpdateArticleToPage($articleId, $header, $content, $publicationdate, $pageId, $author, $type, $public, $description)
	{
		$header = $this->database->RealEscapeString($header);
		$content = $this->database->RealEscapeString($content);
		$type = $this->database->RealEscapeString($type);
		$description = $this->database->RealEscapeString($description);

		$result = $this->database->ExecuteQuery("UPDATE article SET header ='".$header."', content ='".$content."', publicationdate ='".$publicationdate."', page_id =".$pageId.",  author =".$author.",  type ='".$type."', public =".$public.", description = '".$description."'  WHERE id = ". $articleId."");

		if($result==true)
		{
			$logUsername = 'Wer ist gerade angemeldet?';
			$logRolename = 'Welche Rolle hat der angemeldete Benutzer?';
			$logDescription = 'Welcher Articel wurde geändert? => $header';

			$re = $this->database->InsertNewLog($logUsername, $logRolename, $logDescription);

			var_dump($re);


			return true;
		}
		else
		{
			 return false;
		}
	}






	/*---- vorher ----*/


	/**
	* GetAllPages()
	*/
	// titel, relative_position, templatename
	public function GetAllPages()
	{
		return $this->database->ExecutePreparedStatement("allPages", array());
	}


	/**
	* GetAllPagesWithTemplate()
	* @params string $templatename
	*/
	public function GetAllPagesWithTemplate($templatename)
	{
		return $this->database->ExecutePreparedStatement("allPagesWithTemplate", array($templatename));
	}



	/**
	* GetHighestRelativeNumber()
	* @params string $templatename
	*/
	// braucht man nur intern beim Hochzählen (?) => private
	private function GetHighestRelativeNumber()
	{
		$result = $this->database->ExecuteQuery("SELECT MAX(relativeposition) AS maxpos FROM page;");
		$num = $this->FetchArray($result);

		return  intval($num['maxpos']);
	}



	/**
	* FetchArray()
	* @params string $result
	*/
	public function FetchArray($result)
	{
		return $this->database->FetchArray($result);
	}



	/**
	* GetResultCount()
	* @params string $result
	*/
	public function GetResultCount($result)
	{
		return  $this->database->GetResultCount($result);
	}




	


	/**
	* SelectPageByPagename()
	* @params string $title is the title of the page
	*/
	public function SelectPageByPagename($title)
	{
		return $this->database->ExecutePreparedStatement("selectPageByPagename", array($title));
	}



	/**
	* SelectPageById()
	* @params int $pageId the id of the page
	*/
	public function SelectPageById($pageId)
	{
		 return	$this->database->ExecutePreparedStatement("selectPageById", array($pageId));
	}



	/**
	* SelectTemplateByTemplatename()
	* @params string $templatename is the name of the template
	*/
	public function SelectTemplateByTemplatename($templatename)
	{
		return $this->database->ExecutePreparedStatement("selectTemplateByTemplatename", array($templatename));
	}



	/**
	* SelectTemplateById()
	* @params int $templateId the id of the template
	*/
	public function SelectTemplateById($templateId)
	{
		 return	$this->database->ExecutePreparedStatement("selectTemplateById", array($templateId));
	}



	/**
	* SelectPageIdByPagename()
	* @params string $title the title of the page
	*/
	public function SelectPageIdByPagename($title)
	{
		 return	$this->database->ExecutePreparedStatement("selectPageIdByPagename", array($title));
	}



	/**
	* DeletePageById()
	* @params int $pageId the id of the page
	*/
	public function DeletePageById($pageId)
	{
		$result = $this->database->ExecutePreparedStatement("deletePageById", array($pageId));

		if($result==true)
		{
			$logUsername = 'Wer ist gerade angemeldet?';
			$logRolename = 'Welche Rolle hat der angemeldete Benutzer?';		//pagemanegement muss true sein!
			$logDescription = 'Folgende Page wurde gelöscht:';

			$re = $this->database->InsertNewLog($logUsername, $logRolename, $logDescription);

			var_dump($re);

			return true;
		}
		else
		{
			 return false;
		}
	}



	/**
	* DeletePageByTitle()
	* @params string $title the title of the page
	*/
	public function DeletePageByTitle($title)
	{
		$result = $this->database->ExecutePreparedStatement("deletePageByTitle", array($title));

		if($result==true)
		{
			$logUsername = 'Wer ist gerade angemeldet?';
			$logRolename = 'Welche Rolle hat der angemeldete Benutzer?';		//pagemanegement muss true sein!
			$logDescription = 'Folgende Page wurde gelöscht:';

			$re = $this->database->InsertNewLog($logUsername, $logRolename, $logDescription);

			var_dump($re);

			return true;
		}
		else
		{
			 return false;
		}
	}



	/**
	* DeleteTemplateById()
	* @params int $templateId the id of the template
	*/
	public function DeleteTemplateById($templateId)
	{
		$result = $this->database->ExecutePreparedStatement("deleteTemplateById", array($templateId));

		if($result==true)
		{
			$logUsername = 'Wer ist gerade angemeldet?';
			$logRolename = 'Welche Rolle hat der angemeldete Benutzer?';		//pagemanegement muss true sein!
			$logDescription = 'Folgendes Template wurde gelöscht:';

			$re = $this->database->InsertNewLog($logUsername, $logRolename, $logDescription);

			var_dump($re);

			return true;
		}
		else
		{
			 return false;
		}
	}



	/**
	* DeleteTemplateByTemplatename()
	* @params string $templatename the templatename of the template
	*/
	public function DeleteTemplateByTemplatename($templatename)
	{
		$result = $this->database->ExecutePreparedStatement("deleteTemplateByTemplatename", array($templatename));

		if($result==true)
			{
				$logUsername = 'Wer ist gerade angemeldet?';
				$logRolename = 'Welche Rolle hat der angemeldete Benutzer?';		//pagemanegement muss true sein!
				$logDescription = 'Folgendes Template wurde gelöscht:';

				$re = $this->database->InsertNewLog($logUsername, $logRolename, $logDescription);

				var_dump($re);

				return true;
			}
			else
			{
				 return false;
			}
	}




	/**
	* PagetitleAlreadyExists()
	* @params string $title the title of the page
	* checks whether the title of a page already exists in the database
	*/
	public function PagetitleAlreadyExists($title)
	{
		$result = $this->database->ExecuteQuery("SELECT * FROM page WHERE title = '".$title."'");

		if($result==true && $this->database->GetResultCount($result) > 0)
		{
			return true;	//there is already a page with the same title
		}
		else
		{
			return false;
		}
	}



	/**
	* TemplatenameAlreadyExists()
	* @params string $templatename the templatename of the template
	* checks whether the templatename of a template already exists in the database
	*/
	public function TemplatenameAlreadyExists($templatename)
	{
		$result = $this->database->ExecuteQuery("SELECT * FROM template WHERE templatename = '".$templatename."'");

		if($result==true && $this->database->GetResultCount($result) > 0)
		{
			return true;	//there is already a templatename with the same title
		}
		else
		{
			return false;
		}
	}


		// GetArticleLabels($id)
	/**
	* SelectAllLable_Article()
	*/
	public function SelectAllLable_Article()
	{
		return $this->database->ExecutePreparedStatement("selectAllLable_Article", array());
	}



	/**
	* SelectAllLablesFromAnArticleById()
	* @params int $articleId the id of the article
	*/
	public function SelectAllLablesFromAnArticleById($articleId)
	{
		return $this->database->ExecutePreparedStatement("selectAllLablesFromAnArticleById", array());
	}

	
	
	/**
	* InsertTemplate()
	* @params string $templatename the name of the template
	* @params string $filelink the filelink of the template
	*/
	public function InsertTemplate($templatename, $filelink)
	{
		if(!($this->TemplatenameAlreadyExists($templatename)))
		{
			$templatename = $this->database->RealEscapeString($templatename);
			$result = $this->database->ExecuteQuery("INSERT INTO template (id, templatename, filelink) VALUES (NULL, '".$templatename."', '".$filelink."' ");

			if($result==true)
			{
				$logUsername = 'Wer ist gerade angemeldet? => $username';		// es sollte nicht möglich sein, dass jemand anders da etwas von einer anderen Person ändert.
				$logRolename = 'Welche Rolle hat der angemeldete Benutzer? => $usersRoleName';
				$logDescription = 'Neues Template erstellt.';

				$re = $this->database->InsertNewLog($logUsername, $logRolename, $logDescription);

				return true;
			}
			else
			{
				return false;
			}
		}
		else  return false;
	}


	
	/**
	* InsertPage()
	* @params string $title the title of the template
	* @params string $templateId the id of the used template
	*/
	public function InsertPage($title, $templateId)
	{
		if(!($this->PagetitleAlreadyExists($title)))
		{
			$title = $this->database->RealEscapeString($title);
			$result = $this->database->ExecuteQuery("INSERT INTO page (id, title, template_id) VALUES (NULL, '".$title."', ".$templateId." ");

			if($result==true)
			{
				$logUsername = 'Wer ist gerade angemeldet? => $username';		// es sollte nicht möglich sein, dass jemand anders da etwas von einer anderen Person ändert.
				$logRolename = 'Welche Rolle hat der angemeldete Benutzer? => $usersRoleName';
				$logDescription = 'Neue Page erstellt.';

				$re = $this->database->InsertNewLog($logUsername, $logRolename, $logDescription);

				return true;
			}
			else
			{
				return false;
			}
		}
		else  return false;
	}

	
	
	/**
	* InsertLable()
	* @params string $lablename the name of the lable
	* @params string $uri the uri of the lable
	*/	
	public function InsertLable($lablename, $uri)
	{
		$lablename = $this->database->RealEscapeString($lablename);
		$result = $this->database->ExecuteQuery("INSERT INTO lable (id, lablename, uri) VALUES (NULL, '".$lablename."', '".$uri."') ");

		if($result==true)
		{
			$logUsername = 'Wer ist gerade angemeldet? => $username';		// es sollte nicht möglich sein, dass jemand anders da etwas von einer anderen Person ändert.
			$logRolename = 'Welche Rolle hat der angemeldete Benutzer? => $usersRoleName';
			$logDescription = 'Neues Lable erstellt.';

			$re = $this->database->InsertNewLog($logUsername, $logRolename, $logDescription);

			return true;
		}
		else
		{
			return false;
		}
	}

	
	/**********/
	
	/**
	* UpdateLableByUri()
	* @params string $lablename  
	* @params string $uri  
	*/	
	public function UpdateLableByUri($lablename, $uri)
	{
		$result = $this->database->ExecuteQuery("UPDATE lable SET lablename  = '".$lablename."'  WHERE uri  = '".$uri."'" );

		if($result==true)
		{
			$logUsername = 'Wer ist gerade angemeldet?';
			$logRolename = 'Welche Rolle hat der angemeldete Benutzer?';
			$logDescription = 'Lable geändert.';

			$re = $this->database->InsertNewLog($logUsername, $logRolename, $logDescription);

			return true;
		}
		else
		{
			return false;
		}
	}

	
	
	/**
	* UpdateLableById()
	* @params int $lableId 	
	* @params string $lablename  
	* @params string $uri  
	*/	
	public function UpdateLableById($lableId, $lablename, $uri)
	{
		$result = $this->database->ExecuteQuery("UPDATE lable SET lablename  ='".$lablename."', uri  = '".$uri."'  WHERE id = ". $lableId);

		if($result==true)
		{
			$logUsername = 'Wer ist gerade angemeldet?';
			$logRolename = 'Welche Rolle hat der angemeldete Benutzer?';
			$logDescription = 'Lable geändert.';

			$re = $this->database->InsertNewLog($logUsername, $logRolename, $logDescription);

			return true;
		}
		else
		{
			return false;
		}
	}

	
	
	/**
	* UpdatePageByTitle()
	* @params string $title  
	* @params int $templateId  
	*/	
	public function UpdatePageByTitle($title, $templateId)
	{
		$result = $this->database->ExecuteQuery("UPDATE page SET template_id = ".$templateId."  WHERE templatename = '". $templatename."'");

		if($result==true)
		{		//warum wurde der User gedebannt und wer hat das gemacht?

			$logUsername = 'Wer ist gerade angemeldet?';
			$logRolename = 'Welche Rolle hat der angemeldete Benutzer?';
			$logDescription = 'Das Template mit dem Namen abc wurde geändert.';

			$re = $this->database->InsertNewLog($logUsername, $logRolename, $logDescription);

			return true;
		}
		else
		{
				return false;
		}
	}
		
	
	
	/**
	* UpdatePageById()
	* @params int $pageId  
	* @params string $title  
	* @params int $templateId  
	*/	
	public function UpdatePageById($pageId, $title, $templateId)
	{
		$result = $this->database->ExecuteQuery("UPDATE page SET title  ='".$title."', template_id  = ".$templateId."  WHERE id = ". $pageId);

		if($result==true)
		{
			$logUsername = 'Wer ist gerade angemeldet?';
			$logRolename = 'Welche Rolle hat der angemeldete Benutzer?';
			$logDescription = 'Die Page mit dem Titel abc wurde geändert.';

			$re = $this->database->InsertNewLog($logUsername, $logRolename, $logDescription);

			return true;
		}
		else
		{
			 return false;
		}
	}


	/**
	* UpdateTemplateByTemplatename()
	* @params string $templatename  
	* @params string $filelink  
	*/		
	public function UpdateTemplateByTemplatename($templatename, $filelink)
	{
		$result = $this->database->ExecuteQuery("UPDATE template SET filelink  ='".$filelink."'  WHERE templatename = '". $templatename."'");

		if($result==true)
		{		 
			$logUsername = 'Wer ist gerade angemeldet?';
			$logRolename = 'Welche Rolle hat der angemeldete Benutzer?';
			$logDescription = 'Das Template mit dem Namen abc wurde geändert.';

			$re = $this->database->InsertNewLog($logUsername, $logRolename, $logDescription);

			return true;
		}
		else
		{
			return false;
		}
	}
	
	
	
	/**
	* UpdateTemplateById()
	* @params int $templateId
	* @params string $templatename  
	* @params string $filelink  
	*/	
	public function UpdateTemplateById($templateId, $templatename, $filelink)
	{
		$result = $this->database->ExecuteQuery("UPDATE template SET templatename  = '".$templatename."', filelink  ='".$filelink."'  WHERE id = ". $templateId);

		if($result==true)
		{
			$logUsername = 'Wer ist gerade angemeldet?';
			$logRolename = 'Welche Rolle hat der angemeldete Benutzer?';
			$logDescription = 'Das Template mit dem Namen abc wurde geändert.';

			$re = $this->database->InsertNewLog($logUsername, $logRolename, $logDescription);

			return true;
		}
		 else
		{
			return false;
		}
	}

	
	
	 
	
	
	
	
	/**
	* DeleteLable_ArticleByArticleId()
	* @params int $articleId the id of the article (foreign key)
	*/
	public function DeleteLable_ArticleByArticleId($articleId)
	{
		$result = $this->database->ExecuteQuery("DELETE FROM lable_article WHERE article_id = ".$articleId);

		if($result==true)
		{
			$logUsername = 'Wer ist gerade angemeldet? => $username';
			$logRolename = 'Welche Rolle hat der angemeldete Benutzer? => $usersRoleName';
			$logDescription = 'Alle Verbindungen zwischen Lable und Article mit bestimmter article_id gelöscht.';

			$re = $this->database->InsertNewLog($logUsername, $logRolename, $logDescription);

			return true;
		}
		else
		{
			return false;
		}
	}





	 /**
	 * DeleteLable_ArticleByLableId()
	 * @params int $lableId the id of the lable (foreign key)
	 */
	 public function DeleteLable_ArticleByLableId($lableId)
	 {
	 	$result = $this->database->ExecuteQuery("DELETE FROM lable_article WHERE lable_id = ".$lableId);

	 	if($result==true)
	 	{
	 		$logUsername = 'Wer ist gerade angemeldet? => $username';
	 		$logRolename = 'Welche Rolle hat der angemeldete Benutzer? => $usersRoleName';
	 		$logDescription = 'Alle Verbindungen zwischen Lable und Article mit bestimmter lable_id gelöscht.';

	 		$re = $this->database->InsertNewLog($logUsername, $logRolename, $logDescription);

	 		return true;
	 	}
	 	else
	 	{
	 		return false;
	 	}
	 }


 


	/**
  	* DeleteAllLable_Article()
  	*/
  	public function DeleteAllLable_Article()
  	{
  		$result = $this->database->ExecuteQuery("DELETE FROM lable_Article");

  		if($result==true)
  		{
  			$logUsername = 'Wer ist gerade angemeldet? => $username';
  			$logRolename = 'Welche Rolle hat der angemeldete Benutzer? => $usersRoleName';
  			$logDescription = 'Keinem Artikel ist ein Lable zugewiesen. ';

  			$re = $this->database->InsertNewLog($logUsername, $logRolename, $logDescription);

  			return true;
  		}
  		else
  		{
  			return false;
  		}
  	}



	/**
 	* InsertLable_Article()
 	* @params int $lableId the id of the lable (foreign key)
 	* @params int $articleId the id of the article (foreign key)
 	*/
 	public function InsertLable_Article($lableId, $articleId)
 	{
 		$result = $this->database->ExecuteQuery("INSERT INTO lable (lable_id, article_id) VALUES (NULL, ".$lableId.", ".$articleId.")");
		//$lable = ... SELECT lablename FROM lable WHERE id = ".$lableId."....;

 		if($result==true)
 		{
 			$logUsername = 'Wer ist gerade angemeldet? => $username';
 			$logRolename = 'Welche Rolle hat der angemeldete Benutzer? => $usersRoleName';
 			$logDescription = 'einem Article wurden neue Lables hinzugefügt erstellt.';

 			$re = $this->database->InsertNewLog($logUsername, $logRolename, $logDescription);

 			return true;
 		}
 		else
 		{
 			return false;
 		}
 	}


 

	/**
	* UpdateLable_ArticleByLableId()
	* @params int $lableId the id of the lable (foreign key)
	* @params int $articleId the id of the article (foreign key)
	*/
	public function UpdateLable_ArticleByLableId($lableId, $articleId)
	{

		$result = $this->database->ExecuteQuery("UPDATE lable_article SET article_id  = ".$articleId."  WHERE lable_id " = .$lableId);

		//$lable = ... SELECT lablename FROM lable WHERE id = ".$lableId."....;

		if($result==true)
		{
			$logUsername = 'Wer ist gerade angemeldet? => $username';
			$logRolename = 'Welche Rolle hat der angemeldete Benutzer? => $usersRoleName';
			$logDescription = 'Update.';

			$re = $this->database->InsertNewLog($logUsername, $logRolename, $logDescription);

			return true;
		}
		else
		{
			return false;
		}
	}



	/**
	* UpdateLable_ArticleByArticleId()
	* @params int $lableId the id of the lable (foreign key)
	* @params int $articleId the id of the article (foreign key)
	*/
	public function UpdateLable_ArticleByArticleId($lableId, $articleId)
	{

		$result = $this->database->ExecuteQuery("UPDATE lable_article SET lable_id  = ".$lableId."  WHERE article_id " = .$articleId);

		//$lable = ... SELECT lablename FROM lable WHERE id = ".$lableId."....;

		if($result==true)
		{
			$logUsername = 'Wer ist gerade angemeldet? => $username';
			$logRolename = 'Welche Rolle hat der angemeldete Benutzer? => $usersRoleName';
			$logDescription = 'Update.';

			$re = $this->database->InsertNewLog($logUsername, $logRolename, $logDescription);

			return true;
		}
		else
		{
			return false;
		}
	}


	
	 
	 
	
	
	
}

?>
