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
	* @param string $dsn database connection string
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

		$allPagesSite = "SELECT page.title, page.relativeposition, template.templatename ".
						"FROM page INNER JOIN template ON page.template_id = template.id ".
						"WHERE website_id = ? ".
						"ORDER BY page.relativeposition ASC;";			
					
		$allPagesWithTemplate = "SELECT page.title ".
								"FROM page INNER JOIN template ON page.template_id = template.id ".
								"WHERE templatename = ?;";

		if(!$this->database->PrepareStatement("allPages", $allPages)) die("Abfrage konnte nicht erstellt werden.");
		if(!$this->database->PrepareStatement("allPagesOfSite", $allPagesSite)) die("Abfrage konnte nicht erstellt werden.");
		if(!$this->database->PrepareStatement("allPagesWithTemplate", $allPagesWithTemplate)) die("Abfrage konnte nicht erstellt werden.");
	
		// Website
		$websiteById =  "SELECT website.headertitle, website.contact, website.imprint, website.privacyinformation, website.gtc, website.login, website.guestbook, template.templatename AS template ".
						"FROM website INNER JOIN template ON website.template_id = template.id ".
						"WHERE website.id = ?";
			
		if(!$this->database->PrepareStatement("websiteById", $websiteById)) die("Abfrage konnte nicht erstellt werden.");

		// Article




	/*---- Mirjam ----*/

		$allArticlesWithDetailedInformation = "SELECT article.header, article.content, article.publicationdate, article.public, user.username, page.title ".
												"FROM page INNER JOIN article ON page.id = article.page_id INNER JOIN user ON article.author = user.id ".
												"ORDER BY user.username ASC;";
		$this->database->PrepareStatement("allArticlesWithDetailedInformation", $allArticlesWithDetailedInformation);


		$deleteArticleById = "DELETE FROM article WHERE id = ?";
		$this->database->PrepareStatement("deleteArticleById", $deleteArticleById);


		$selectOneArticleById = "SELECT * FROM article WHERE id = ?";
		$this->database->PrepareStatement("selectOneArticleById", $selectOneArticleById);


		$selectAllPages = "SELECT * FROM page ORDER BY page.relativeposition ASC";
		$this->database->PrepareStatement("selectAllPages", $selectAllPages);

		$selectPageByPagename = "SELECT * FROM page WHERE title = ?";
		$this->database->PrepareStatement("selectPageByPagename", $selectPageByPagename);

		$selectPageById = "SELECT * FROM page WHERE id = ?";
		$this->database->PrepareStatement("selectPageById", $selectPageById);

		$selectTemplateById = "SELECT * FROM template WHERE id = ?";
		$this->database->PrepareStatement("selectTemplateById", $selectTemplateById);

		$selectTemplateByTemplatename = "SELECT * FROM template WHERE templatename = ?";
		$this->database->PrepareStatement("selectTemplateByTemplatename", $selectTemplateByTemplatename);

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




		$allArticles = "SELECT * FROM article";			//Mirjam: Wird noch überprüft, welche davon verwendet wird.
		$this->database->PrepareStatement("allArticles", $allArticles);

		// aus DBUser

		$selectAllArticles = "SELECT * FROM article"; 	//Mirjam: Wird noch überprüft, welche davon verwendet wird.
		$this->database->PrepareStatement("selectAllArticles", $selectAllArticles);

		$selectAllTemplates = "SELECT * FROM template";
		$this->database->PrepareStatement("selectAllTemplates", $selectAllTemplates);


		//neue Funktion
		$selectAllLable_User = "SELECT * FROM lable_user";
		$this->database->PrepareStatement("selectAllLable_User", $selectAllLable_User);


		//neu
		$selectAllWebsite = "SELECT * FROM website";
		$this->database->PrepareStatement("selectAllWebsite", $selectAllWebsite);

		$selectWebsiteById = "SELECT * FROM website WHERE id = ?";
		$this->database->PrepareStatement("selectWebsiteById", $selectWebsiteById);

		$deleteWebsiteById =  "DELETE FROM website WHERE id = ?";
		$this->database->PrepareStatement("deleteWebsiteById", $deleteWebsiteById );
		
		
	
		$articleOfPage = "SELECT article.id, article.header, article.content, article.publicationdate, article.public, article.description, article.type, user.username AS author ".
						 "FROM article INNER JOIN user ON user.id = article.author ".
						 "INNER JOIN PAGE ON page.id = article.page_id ".
						 "WHERE page.title = ? ".
						 "ORDER BY article.publicationdate DESC;";

		if(!$this->database->PrepareStatement("allArticlesOfPage", $articleOfPage)) die("Abfrage konnte nicht erstellt werden.");
		
		
		
		$selectLableByLableId = "SELECT * FROM lable WHERE id = ?";
		$this->database->PrepareStatement("selectLableByLableId", $selectLableByLableId);

		$selectLableIdByLablename = "SELECT id FROM lable WHERE lablename = ?";
		$this->database->PrepareStatement("selectLableIdByLablename", $selectLableIdByLablename);

		$selectArticleByHeader = "SELECT * FROM article WHERE header = ?";
		$this->database->PrepareStatement("selectArticleByHeader", $selectArticleByHeader);
		
		$selectAllLables = "SELECT * FROM lable";
		$this->database->PrepareStatement("selectAllLables", $selectAllLables);

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
	* @param int $articleId is the id of the article
	*/
	public function DeleteArticleById($articleId)
	{
		$headerOfArticle = $this->FetchArray($this->SelectOneArticleById($articleId))['header'];
		$result = $this->database->ExecutePreparedStatement("deleteArticleById", array($articleId));

		if($result==true)
		{
			$logUsername = $_SESSION['username'];
			$logRolename = $_SESSION['rolename'];
			$logDescription = 'Folgender Artikel wurde gelöscht: <br> <strong>'.$headerOfArticle.'</strong>';

			$this->database->InsertNewLog($logUsername, $logRolename, $logDescription);

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
	* @param int $articleId the id of the article
	*/
	public function SelectOneArticleById($articleId)
	{
		return $this->database->ExecutePreparedStatement("selectOneArticleById", array($articleId));
	}


	/**
	* InsertNewArticleToPage()
	* @param string $header
	* @param string $content
	* @param string $publicationdate
	* @param int $pageId the id of the page the article is published
	* @param int author the author's id
	* @param string $type
	* @param int $public
	* @param string $description
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
			$logUsername = $_SESSION['username'];
 			$logRolename = $_SESSION['rolename'];
			$logDescription = 'Folgender Artikel wurde neu eingefügt: <br> <strong>'.$header.'</strong>';

			$this->database->InsertNewLog($logUsername, $logRolename, $logDescription);

			return true;
		 }
		 else
		 {
			 return false;
		 }
	}



	/**
	* UpdateArticleToPage()
	* @param int $articleId the id of the article
	* @param string $header
	* @param string $content
	* @param string $publicationdate
	* @param int $pageId the id of the page the article is published
	* @param int $author
	* @param string $type
	* @param int $public
	* @param string $description
	*/
	public function	UpdateArticleToPage($articleId, $header, $content, $publicationdate, $pageId, $author, $type, $public, $description)
	{
		$header = $this->database->RealEscapeString($header);
		$content = $this->database->RealEscapeString($content);
		$type = $this->database->RealEscapeString($type);
		$description = $this->database->RealEscapeString($description);

		$articleheaderBevoreUpdate =  $this->FetchArray($this->SelectOneArticleById($articleId))['header'];
		$result = $this->database->ExecuteQuery("UPDATE article SET header ='".$header."', content ='".$content."', publicationdate ='".$publicationdate."', page_id =".$pageId.",  author =".$author.",  type ='".$type."', public =".$public.", description = '".$description."'  WHERE id = ". $articleId."");

		if($result==true)
		{
			$logUsername = $_SESSION['username'];
			$logRolename = $_SESSION['rolename'];

			if($articleheaderBevoreUpdate == $header)
			{
				$articleHeaderChanged = $header;
			}
			else
				{
					$articleHeaderChanged = $articleheaderBevoreUpdate. '(neuer Artikelheader: '.$header.')';
				}

			$logDescription = 'An dem Artikel <strong>'.$articleHeaderChanged.'</strong> wurden Änderugen vorgenommen.';

 			$this->database->InsertNewLog($logUsername, $logRolename, $logDescription);

 			return true;
		}
		else
		{
			 return false;
		}
	}






	/**
	* Selects all available pages
	*
	* Selects all pages with title, relativeposition and templatename orderd by their relative position ascending
	* @param void
	* @return Mysqli\mysqli_result Query Result for use with FetchArray()
	*/
	public function GetAllPages()
	{
		return $this->database->ExecutePreparedStatement("allPages", array());
	}

	/**
	* Selects all available pages of a particular website
	*
	* Selects all pages with title, relativeposition and templatename orderd by their relative position ascending
	* @param int websiteId Id indicating the website and defaults to 1 (May contain a numeric string, e.g. "1" instead of 1)
	* @return Mysqli\mysqli_result|null Query Result for use with FetchArray(), null if an error occured
	*/
	public function GetAllPagesOfWebsite($websiteId=1)
	{
		if(!is_numeric($websiteId)) return null;
		return $this->database->ExecutePreparedStatement("allPagesOfSite", array($websiteId));
	}

	/**
	* Method to get all page names of pages that use the given template.
	*
	* Selects the title of all pages that use a specific template
	* @param string $templatename Name of the template
	* @return Mysqli\mysqli_result|null Query Result for use with FetchArray(), null if an error occured
	*/
	public function GetAllPagesWithTemplate($templatename)
	{
		if(!is_string($templatename)) return null;
		return $this->database->ExecutePreparedStatement("allPagesWithTemplate", array($templatename));
	}

	/**
	* Selects information of a given website.
	* Selects the fields website.headertitle, website.contact, website.imprint, website.privacyinformation, website.gtc, website.login, website.guestbook 
	* and template.templatename (named template in the row array)
	* @param int $id id of the website. May be a numeric string ("1" instead of 1)
	* @return Mysqli\mysqli_result|null Query Result for use with FetchArray(), null if an error occured
	*/
	public function GetWebsiteInfoById($id)
	{
		if(!is_numeric($id)) return null;
		return $this->database->ExecutePreparedStatement("websiteById", array($id));
	}
	


	/**
	* GetHighestRelativeNumber()
	* @param string $templatename
	*/
	public function GetHighestRelativeNumber()
	{
		$result = $this->database->ExecuteQuery("SELECT MAX(relativeposition) AS maxpos FROM page;");
		$num = $this->FetchArray($result);

		return  intval($num['maxpos']);
	}



	/**
	* FetchArray()
	* @param string $result
	*/
	public function FetchArray($result)
	{
		return $this->database->FetchArray($result);
	}



	/**
	* GetResultCount()
	* @param string $result
	*/
	public function GetResultCount($result)
	{
		return  $this->database->GetResultCount($result);
	}







	/**
	* SelectPageByPagename()
	* @param string $title is the title of the page
	*/
	public function SelectPageByPagename($title)
	{
		return $this->database->ExecutePreparedStatement("selectPageByPagename", array($title));
	}



	/**
	* SelectPageById()
	* @param int $pageId the id of the page
	*/
	public function SelectPageById($pageId)
	{
		 return	$this->database->ExecutePreparedStatement("selectPageById", array($pageId));
	}



	/**
	* SelectTemplateByTemplatename()
	* @param string $templatename is the name of the template
	*/
	public function SelectTemplateByTemplatename($templatename)
	{
		return $this->database->ExecutePreparedStatement("selectTemplateByTemplatename", array($templatename));
	}



	/**
	* SelectTemplateById()
	* @param int $templateId the id of the template
	*/
	public function SelectTemplateById($templateId)
	{
		 return	$this->database->ExecutePreparedStatement("selectTemplateById", array($templateId));
	}



	/**
	* SelectPageIdByPagename()
	* @param string $title the title of the page
	*/
	public function SelectPageIdByPagename($title)
	{
		 return	$this->database->ExecutePreparedStatement("selectPageIdByPagename", array($title));
	}



	/**
	* DeletePageById()
	* @param int $pageId the id of the page
	*/
	public function DeletePageById($pageId)
	{
		$pageTitle = $this->FetchArray($this->SelectPageById($pageId))['title'];
		$result = $this->database->ExecutePreparedStatement("deletePageById", array($pageId));

		if($result==true)
		{
			$logUsername = $_SESSION['username'];
			$logRolename = $_SESSION['rolename'];
			$logDescription = 'Folgende Seite wurde gelöscht: <br> <strong>'.$pageTitle;

			 $this->database->InsertNewLog($logUsername, $logRolename, $logDescription);

			return true;
		}
		else
		{
			 return false;
		}
	}



	/**
	* DeletePageByTitle()
	* @param string $title the title of the page
	*/
/*	public function DeletePageByTitle($title)
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
*/


	/**
	* DeleteTemplateById()
	* @param int $templateId the id of the template
	*/
	public function DeleteTemplateById($templateId)
	{
		$templatename = $this->FetchArray($this->SelectTemplateById($templateId))['templatename'];
		$result = $this->database->ExecutePreparedStatement("deleteTemplateById", array($templateId));

		if($result==true)
		{
			$logUsername = $_SESSION['username'];
			$logRolename = $_SESSION['rolename'];
			$logDescription = 'Folgendes Template wurde gelöscht: <br> <strong>'.$templatename;
 		  $this->database->InsertNewLog($logUsername, $logRolename, $logDescription);

			return true;
		}
		else
		{
			 return false;
		}
	}



	/**
	* DeleteTemplateByTemplatename()
	* @param string $templatename the templatename of the template
	*/
/*	public function DeleteTemplateByTemplatename($templatename)
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


*/

	/**
	* PagetitleAlreadyExists()
	* @param string $title the title of the page
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
	* @param string $templatename the templatename of the template
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
	* @param int $articleId the id of the article
	*/
	public function SelectAllLablesFromAnArticleById($articleId)
	{
		return $this->database->ExecutePreparedStatement("selectAllLablesFromAnArticleById", array($articleId));
	}



	/**
	* InsertTemplate()
	* @param string $templatename the name of the template
	* @param string $filelink the filelink of the template
	*/
	public function InsertTemplate($templatename, $filelink)
	{
		if(!($this->TemplatenameAlreadyExists($templatename)))
		{
			$templatename = $this->database->RealEscapeString($templatename);
			$result = $this->database->ExecuteQuery("INSERT INTO template (id, templatename, filelink) VALUES (NULL, '".$templatename."', '".$filelink."') ");

			if($result==true)
			{
				$logUsername = $_SESSION['username'];
				$logRolename = $_SESSION['rolename'];
				$logDescription = 'Folgendes Template wurde erstellt: <br> <strong>'.$templatename;

				$this->database->InsertNewLog($logUsername, $logRolename, $logDescription);

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
	* @param string $title the title of the template
	* @param int $relativeposition
	* @param int $templateId the id of the used template
	*/
	public function InsertPage($title, $relativeposition, $templateId, $websiteId)
	{
		if(!($this->PagetitleAlreadyExists($title)))
		{
			$title = $this->database->RealEscapeString($title);
			$result = $this->database->ExecuteQuery("INSERT INTO page (id, title, relativeposition, template_id, website_id) VALUES (NULL, '".$title."', ".$relativeposition.", ".$templateId." , ".$websiteId.") ");

			if($result==true)
			{
				$logUsername = $_SESSION['username'];
				$logRolename = $_SESSION['rolename'];
				$logDescription = 'Folgende Seite wurde erstellt: <br> <strong>'.$title;

				$this->database->InsertNewLog($logUsername, $logRolename, $logDescription);

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
	* @param string $lablename the name of the lable
	* @param string $uri the uri of the lable
	*/
	public function InsertLable($lablename, $uri)
	{
		$lablename = $this->database->RealEscapeString($lablename);
		$result = $this->database->ExecuteQuery("INSERT INTO lable (id, lablename, uri) VALUES (NULL, '".$lablename."', '".$uri."') ");

		if($result==true)
		{

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
	* @param string $lablename
	* @param string $uri
	*/
	public function UpdateLableByUri($lablename, $uri)
	{
		$result = $this->database->ExecuteQuery("UPDATE lable SET lablename  = '".$lablename."'  WHERE uri  = '".$uri."'" );

		if($result==true)
		{

			return true;
		}
		else
		{
			return false;
		}
	}



	/**
	* UpdateLableById()
	* @param int $lableId
	* @param string $lablename
	* @param string $uri
	*/
	public function UpdateLableById($lableId, $lablename, $uri)
	{
		$result = $this->database->ExecuteQuery("UPDATE lable SET lablename  ='".$lablename."', uri  = '".$uri."'  WHERE id = ". $lableId);

		if($result==true)
		{
		  	return true;
		}
		else
		{
			return false;
		}
	}



	/**
	* UpdatePageByTitle()
	* @param string $title
	* @param int $relativeposition
	* @param int $templateId
	* @param int $websiteId
	*/
/*	public function UpdatePageByTitle($title, $relativeposition, $templateId, $websiteId)
	{
		$result = $this->database->ExecuteQuery("UPDATE page SET relativeposition = ".$relativeposition.", template_id = ".$templateId.",  website_id = ".$websiteId." WHERE templatename = '". $templatename."'");

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
*/


	/**
	* UpdatePageById()
	* @param int $pageId
	* @param string $title
	* @param int $relativeposition
	* @param int $templateId
	* @param int $websiteId
	*/
	public function UpdatePageById($pageId, $title, $relativeposition, $templateId, $websiteId)
	{
		$pageTitleBevoreUpdate = $this->FetchArray($this->SelectPageById($pageId))['title'];
		$result = $this->database->ExecuteQuery("UPDATE page SET title  ='".$title."', relativeposition = ".$relativeposition.", template_id  = ".$templateId.",  website_id = ".$websiteId."  WHERE id = ". $pageId);

		if($result==true)
		{
			$logUsername = $_SESSION['username'];
			$logRolename = $_SESSION['rolename'];

			if($pageTitleBevoreUpdate == $title)
			{
				$pageTitleChanged = $title;
			}
			else
				{
					$pageTitleChanged = $pageTitleBevoreUpdate. '(neue Überschrift: '.$title.')';
				}

			$logDescription = 'Folgende Seite wurde geändert: <br> <strong>'.$pageTitleChanged;

			$this->database->InsertNewLog($logUsername, $logRolename, $logDescription);



			return true;
		}
		else
		{
			 return false;
		}
	}


	/**
	* UpdateTemplateByTemplatename()
	* @param string $templatename
	* @param string $filelink
	*/
/*	public function UpdateTemplateByTemplatename($templatename, $filelink)
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
*/


	/**
	* UpdateTemplateById()
	* @param int $templateId
	* @param string $templatename
	* @param string $filelink
	*/
	public function UpdateTemplateById($templateId, $templatename, $filelink)
	{
		$templatenameBevoreUpdate = $this->FetchArray($this->SelectTemplateById($templateId))['templatename'];
		$result = $this->database->ExecuteQuery("UPDATE template SET templatename  = '".$templatename."', filelink  ='".$filelink."'  WHERE id = ". $templateId);

		if($result==true)
		{
			$logUsername = $_SESSION['username'];
			$logRolename = $_SESSION['rolename'];

			if($templatenameBevoreUpdate == $templatename)
			{
				$templatenameChanged = $templatename;
			}
			else
				{
					$templatenameChanged = $templatenameBevoreUpdate. '(neuer Templatename: '.$templatename.')';
				}

			$logDescription = 'Folgendes Template wurde geändert: <br> <strong>'.$templatenameChanged;

			$this->database->InsertNewLog($logUsername, $logRolename, $logDescription);


			return true;
		}
		 else
		{
			return false;
		}
	}








	/**
	* DeleteLable_ArticleByArticleId()
	* @param int $articleId the id of the article (foreign key)
	*/
	public function DeleteLable_ArticleByArticleId($articleId)
	{
		$result = $this->database->ExecuteQuery("DELETE FROM lable_article WHERE article_id = ".$articleId);

		if($result==true)
		{

			return true;
		}
		else
		{
			return false;
		}
	}





	 /**
	 * DeleteLable_ArticleByLableId()
	 * @param int $lableId the id of the lable (foreign key)
	 */
	 public function DeleteLable_ArticleByLableId($lableId)
	 {
	 	$result = $this->database->ExecuteQuery("DELETE FROM lable_article WHERE lable_id = ".$lableId);

	 	if($result==true)
	 	{

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
  		 	return true;
  		}
  		else
  		{
  			return false;
  		}
  	}



	/**
 	* InsertLable_Article()
 	* @param int $lableId the id of the lable (foreign key)
 	* @param int $articleId the id of the article (foreign key)
 	*/
 	public function InsertLable_Article($lableId, $articleId)
 	{
 		$result = $this->database->ExecuteQuery("INSERT INTO lable_article (lable_id, article_id) VALUES (".$lableId.", ".$articleId.")");
		//$lable = ... SELECT lablename FROM lable WHERE id = ".$lableId."....;

 		if($result==true)
 		{
 				return true;
 		}
 		else
 		{
 			return false;
 		}
 	}




	/**
	* UpdateLable_ArticleByLableId()
	* @param int $lableId the id of the lable (foreign key)
	* @param int $articleId the id of the article (foreign key)
	*/
	public function UpdateLable_ArticleByLableId($lableId, $articleId)
	{
		$result = $this->database->ExecuteQuery("UPDATE lable_article SET article_id  = ".$articleId."  WHERE lable_id  = " .$lableId);

		if($result==true)
		{
			return true;
		}
		else
		{
			return false;
		}
	}



	/**
	* UpdateLable_ArticleByArticleId()
	* @param int $lableId the id of the lable (foreign key)
	* @param int $articleId the id of the article (foreign key)
	*/
	public function UpdateLable_ArticleByArticleId($lableId, $articleId)
	{

		$result = $this->database->ExecuteQuery("UPDATE lable_article SET lable_id  = ".$lableId."  WHERE article_id  = " .$articleId);

		if($result==true)
		{
			return true;
		}
		else
		{
			return false;
		}
	}



	/**
	* SelectAllPages()
	*/
	public function SelectAllPages()
	{
		return $this->database->ExecutePreparedStatement("selectAllPages", array());
	}






	// aus DBUser
	/**
	* SelectAllArticles()
	*/
	public function SelectAllArticles()
	{
		return $this->database->ExecutePreparedStatement("selectAllArticles", array());
	}


		/**
	* SelectAllTemplates()
	*/

	public function SelectAllTemplates()
	{
		return $this->database->ExecutePreparedStatement("selectAllTemplates", array());
	}





	//neue Funktionen
	/**
	* SelectAllLable_User()
	*/
	public function SelectAllLable_User()
	{
		return $this->database->ExecutePreparedStatement("selectAllLable_User", array());
	}



	/**
	* DeleteAllLable_User()
	*/
	public function DeleteAllLable_User()
	{
		$result = $this->database->ExecuteQuery("DELETE FROM lable_user");

		if($result==true)
		{

			return true;
		}
		else
		{
			return false;
		}
	}




	/**
	* DeleteLable_UserByArticleId()
	* @param int $userId the user's id (foreign key)
	*/
	public function DeleteLable_UserByArticleId($userId)
	{
		$result = $this->database->ExecuteQuery("DELETE FROM lable_user WHERE user_id = ".$userId);

		if($result==true)
		{
				return true;
		}
		else
		{
			return false;
		}
	}




	/**
	* DeleteLable_UserByLableId()
	* @param int $lableId the id of the lable (foreign key)
	*/
	public function DeleteLable_UserByLableId($lableId)
	{
		$result = $this->database->ExecuteQuery("DELETE FROM lable_user WHERE lable_id = ".$lableId);

		if($result==true)
		{
			return true;
		}
		else
		{
			return false;
		}
	}



	/**
	* InsertLable_User()
	* @param int $lableId the id of the lable (foreign key)
	* @param int $userId the user's id (foreign key)
	*/
	public function InsertLable_User($lableId, $userId)
	{
		$result = $this->database->ExecuteQuery("INSERT INTO lable_user (lable_id, user_id) VALUES (".$lableId.", ".$userId.")");

		if($result==true)
		{

			return true;
		}
		else
		{
			return false;
		}
	}




	/**
	* UpdateLable_UserByLableId()
	* @param int $lableId the id of the lable (foreign key)
	* @param int $userId the user's id (foreign key)
	*/
	public function UpdateLable_UserByLableId($lableId, $userId)
	{

		$result = $this->database->ExecuteQuery("UPDATE lable_user SET user_id  = ".$userId."  WHERE lable_id  = " .$lableId);

		//$lable = ... SELECT lablename FROM lable WHERE id = ".$lableId."....;

		if($result==true)
		{
		 	return true;
		}
		else
		{
			return false;
		}
	}



	/**
	* UpdateLable_UserByArticleId()
	* @param int $lableId the id of the lable (foreign key)
	* @param int $userId the user's id (foreign key)
	*/
	public function UpdateLable_UserByArticleId($lableId, $userId)
	{
		$result = $this->database->ExecuteQuery("UPDATE lable_user SET lable_id  = ".$lableId."  WHERE user_id  = " .$userId);

		if($result==true)
		{
			return true;
		}
		else
		{
			return false;
		}
	}








	/**
	* SelectAllWebsite()
	*/
	public function SelectAllWebsite($templateId)
	{
		 return	$this->database->ExecutePreparedStatement("selectAllWebsite", array());
	}



	/**
	* SelectWebsiteById()
	* @param int $id the id of the website
	*/
	public function SelectWebsiteById($id)
	{
		 return	$this->database->ExecutePreparedStatement("selectWebsiteById", array($id));
	}



	/**
	* DeleteWebsiteById()
	* @param int $id the id of the website
	*/
	public function DeleteWebsiteById($id)
	{
		$logDeletedWebsite = $this->FetchArray($this->SelectWebsiteById($id))['headertitle'];
		$result = $this->database->ExecutePreparedStatement("deleteWebsiteById", array($id));

		 if($result==true)
		 {
			 $logUsername = $_SESSION['username'];
			 $logRolename = $_SESSION['rolename'];
			 $logDescription = 'Folgende Website wurde gelöscht: <strong>'.$logDeletedWebsite.'</strong>';
			 $this->database->InsertNewLog($logUsername, $logRolename, $logDescription);
			 return true;
		 }
		 else
		 {
				return false;
		 }
	}




	/**
	* UpdateWebsiteById()
	* @param int $websiteId
	* @param string $headertitle
	* @param string $contact
	* @param string $imprint
	* @param string $privacyinformation
	* @param string $gtc
	* @param bool $login
	* @param bool $guestbook
	* @param int $template_id
	*/
	public function UpdateWebsiteById($websiteId, $headertitle, $contact, $imprint, $privacyinformation, $gtc, $login, $guestbook, $template_id)
	{
		$websiteHeadertitleBevoreUpdate = $this->FetchArray($this->SelectWebsiteById($headertitle))['headertitle'];
		$result = $this->database->ExecuteQuery("UPDATE website SET headertitle  ='".$headertitle."', contact = '".$contact."', imprint  = '".$imprint."',  privacyinformation = '".$privacyinformation."', gtc ='".$gtc."', login = ".$login.", guestbook = ".$guestbook.", template_id = ".$template_id." WHERE id = ". $websiteId);

		if($result==true)
		{
			$logUsername = $_SESSION['username'];
			$logRolename = $_SESSION['rolename'];

			if($websiteHeadertitleBevoreUpdate == $headertitle)
			{
				$websiteHeadertitleChanged = $headertitle;
			}
			else
				{
					$websiteHeadertitleChanged = $websiteHeadertitleBevoreUpdate. '(neue Überschrift: '.$headertitle.')';
				}

			$logDescription = 'Folgende Website wurde geändert: <br> <strong>'.$websiteHeadertitleChanged;

			$this->database->InsertNewLog($logUsername, $logRolename, $logDescription);



			return true;
		}
		else
		{
			 return false;
		}
	}



	/**
	* InsertWebsite()
	* @param int $websiteId
	* @param string $headertitle
	* @param string $contact
	* @param string $imprint
	* @param string $privacyinformation
	* @param string $gtc
	* @param bool $login
	* @param bool $guestbook
	* @param int $template_id
	*/
	public function InsertWebsite($websiteId, $headertitle, $contact, $imprint, $privacyinformation, $gtc, $login, $guestbook, $template_id)
	{
		$result = $this->database->ExecuteQuery("INSERT INTO website (id, headertitle, contact, imprint, privacyinformation, gtc, login, guestbook, template_id) VALUES (NULL, '".$headertitle."', '".$contact."', '".$imprint."', '".$privacyinformation."', '".$gtc."', ".$login.", ".$guestbook.",  ".$template_id.")");

		 if($result==true)
		 {
			$logUsername = $_SESSION['username'];
			$logRolename = $_SESSION['rolename'];
			$logDescription = 'Die Website <strong>'.$rolename.'</strong> wurde neu erstellt.';
			$this->database->InsertNewLog($logUsername, $logRolename, $logDescription);
			return true;
		 }
		 else
		 {
			 return false;
		 }
	}

	
	
	/**
	* Returns all articles of a particular page with id, header, content, publicationdate, description and author
	* as well as information about being public/private (field name: public) and type
	* The articles are sorted by publication date (newest first)
	* @param string pagename title of the page which articles you want to retrieve
	* @return Mysqli\mysqli_result|null Query Result for use with FetchArray(), null if an error occured
	*/
	public function GetAllArticlesOfPage($pagename)
	{
		if(!is_string($pagename)) return null;
		return $this->database->ExecutePreparedStatement("allArticlesOfPage", array($pagename));
	}

	
	
	/**
	* SelectLableByLableId()
	* @param int $lableId the id of the lable
	*/
	public function SelectLableByLableId($lableId)
	{
		return $this->database->ExecutePreparedStatement("selectLableByLableId", array($lableId));
	}

	

	/**
	* SelectLableIdByLablename()
	* @param string $lablename the lablename of the lable
	*/
	public function SelectLableIdByLablename($lablename)
	{
		return $this->database->ExecutePreparedStatement("selectLableIdByLablename", array($lablename));
	}
	
	
	
	/**
	* SelectArticleByHeader()
	* @param string $header the header of the article
	*/
	public function SelectArticleByHeader($header)
	{
		return $this->database->ExecutePreparedStatement("selectArticleByHeader", array($header));
	}
	
	
	
	/**
	* SelectAllLables()
	*/
	public function SelectAllLables()
	{
		return $this->database->ExecutePreparedStatement("selectAllLables", array());
	}

}

?>
