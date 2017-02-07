<?php
/**
* File containing the class DbContent
* @author Mirjam Donhauser
* @author Cornelia Ott
* @author Tamara Graf
*/

/* namespace */
namespace SemanticCms\DatabaseAbstraction;

/* Include(s) */
require_once 'DbEngine.class.php';


/**
* Provides functionality for communication with the database according to page content.
* @author Mirjam Donhausers
* @author Cornelia Ott
* @author Tamara Graf
*/
class DbContent
{
	/** @var DbEngine DbEngine object fro database connection*/
	private $database;


	/* ---- Constructor / Destructor ---- */
	/**
	* constructor
	* @param string $host database host
	* @param string $user database user
	* @param string $password password for database user
	* @param string $db database name
	*/
	 public function __construct($host, $user, $password, $db)
  	{
	   $this->database = new DbEngine($host, $user, $password, $db);
	   $this->PrepareSQL();
    }


	/**
	* destructor
	* @param void
	*/
	public function __destruct()
	{
		$this->database->__destruct();
	}


	/* ---- Methods ---- */
	/**
	* prepare_sql()
	* Prepares the SQL statements
	* @param void
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

		$allPagesWithTemplate = "SELECT page.title, page.website_id ".
								"FROM page INNER JOIN template ON page.template_id = template.id ".
								"WHERE templatename = ?;";

		$allSitesWithTemplate = "SELECT website.id ".
								"FROM website INNER JOIN template ON website.template_id = template.id ".
								"WHERE templatename = ?;";

		if(!$this->database->PrepareStatement("allPages", $allPages)) die("Abfrage konnte nicht erstellt werden.");
		if(!$this->database->PrepareStatement("allPagesOfSite", $allPagesSite)) die("Abfrage konnte nicht erstellt werden.");
		if(!$this->database->PrepareStatement("allPagesWithTemplate", $allPagesWithTemplate)) die("Abfrage konnte nicht erstellt werden.");
		if(!$this->database->PrepareStatement("allSitesWithTemplate", $allSitesWithTemplate)) die("Abfrage konnte nicht erstellt werden.");

		// Website
		$websiteById =  "SELECT website.headertitle, website.contact, website.imprint, website.privacyinformation, website.gtc, website.login, website.guestbook, template.templatename AS template ".
						"FROM website INNER JOIN template ON website.template_id = template.id ".
						"WHERE website.id = ?";

		if(!$this->database->PrepareStatement("websiteById", $websiteById)) die("Abfrage konnte nicht erstellt werden.");



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



		$selectAllLabel_Article = "SELECT * FROM label_article";
		$this->database->PrepareStatement("selectAlllabel_Article", $selectAllLabel_Article );

		$selectAllLabelsFromAnArticleById = "SELECT * FROM article INNER JOIN label_article  ON article.id = label_article.article_id INNER JOIN label ON label_article.label_id = label.id WHERE article.id = ?";
		$this->database->PrepareStatement("selectAllLabelsFromAnArticleById", $selectAllLabelsFromAnArticleById );




		$allArticles = "SELECT * FROM article";			//Mirjam: Wird noch überprüft, welche davon verwendet wird.
		$this->database->PrepareStatement("allArticles", $allArticles);



		$selectAllArticles = "SELECT * FROM article"; 	//Mirjam: Wird noch überprüft, welche davon verwendet wird.
		$this->database->PrepareStatement("selectAllArticles", $selectAllArticles);

		$selectAllTemplates = "SELECT * FROM template";
		$this->database->PrepareStatement("selectAllTemplates", $selectAllTemplates);



		$selectAllLabel_User = "SELECT * FROM label_user";
		$this->database->PrepareStatement("selectAllLabel_User", $selectAllLabel_User);



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



		$selectLabelByLabelId = "SELECT * FROM label WHERE id = ?";
		$this->database->PrepareStatement("selectLabelByLabelId", $selectLabelByLabelId);

		$selectLabelIdByLabelname = "SELECT id FROM label WHERE labelname = ?";
		$this->database->PrepareStatement("selectLabelIdByLabelname", $selectLabelIdByLabelname);

		$selectArticleByHeader = "SELECT * FROM article WHERE header = ?";
		$this->database->PrepareStatement("selectArticleByHeader", $selectArticleByHeader);

		$selectAllLabels = "SELECT * FROM label";
		$this->database->PrepareStatement("selectAllLabels", $selectAllLabels);


		$selectAllWebsiteByHeadertitle = "SELECT * FROM website WHERE headertitle = ?";
		$this->database->PrepareStatement("selectAllWebsiteByHeadertitle", $selectAllWebsiteByHeadertitle);



		//*nur für Testzwecke*//
		//DELETE website.*, page.*, article.* FROM website INNER JOIN page on website.id = page.website_id INNER JOIN article ON article.page_id = page.id WHERE website.id = ?
		//$deleteWebsiteAndPageAndArticle = "DELETE website.*, page.*, article.* FROM website INNER JOIN page on website.id = page.website_id INNER JOIN article ON article.page_id = page.id";
		//$this->database->PrepareStatement("deleteWebsiteAndPageAndArticle", $deleteWebsiteAndPageAndArticle );



		$deleteWebsiteAndPageAndArticleByWebsiteId = "DELETE website.*, page.*, article.* ".
													 "FROM website ".
													 "INNER JOIN page on website.id = page.website_id ".
													 "INNER JOIN article ON article.page_id = page.id ".
													 "WHERE website.id = ?";
		$this->database->PrepareStatement("deleteWebsiteAndPageAndArticleByWebsiteId", $deleteWebsiteAndPageAndArticleByWebsiteId );

	}

	/**
	* select all articles
	* @param void
	* @return Mysqli\mysqli_result|null Query Result for use with FetchArray(), null if an error occured
	*/
	public function GetAllArticles()
	{
		return $this->database->ExecutePreparedStatement("allArticles", array());
	}



	/**
	* select all articles with detailed information
	* @param void
	* @return Mysqli\mysqli_result|null Query Result for use with FetchArray(), null if an error occured
	*/
	public function GetAllArticlesWithDetailedInformation()
	{
		return $this->database->ExecutePreparedStatement("allArticlesWithDetailedInformation", array());
	}



	/**
	* delete one article by id
	* @param int $articleId is the id of the article
	* @return boolean true|false successful (true) when the query could be executed correctly and the article is deleted
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
	* select one article by id to get information about the special article
	* @param int $articleId the id of the article
	* @return Mysqli\mysqli_result|null Query Result for use with FetchArray(), null if an error occured
	*/
	public function SelectOneArticleById($articleId)
	{
		return $this->database->ExecutePreparedStatement("selectOneArticleById", array($articleId));
	}


	/**
	* creates a new article
	* @param string $header
	* @param string $content
	* @param string $publicationdate
	* @param int $pageId the id of the page the article is published
	* @param int author the author's id
	* @param string $type
	* @param int $public
	* @param string $description
	* @return boolean true|false successful (true) when the query could be executed correctly and the article is generated and assigned to a page
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
	* assignes one article to a page
	* @param int $articleId the id of the article
	* @param string $header
	* @param string $content
	* @param string $publicationdate
	* @param int $pageId the id of the page the article is published
	* @param int $author
	* @param string $type
	* @param int $public
	* @param string $description
	* @return boolean true|false successful (true) when the query could be executed correctly and the article is updated and also assigned to a page
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
	* @author Tamara Graf
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
	* @author Tamara Graf
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
	* @author Tamara Graf
	*/
	public function GetAllPagesWithTemplate($templatename)
	{
		if(!is_string($templatename)) return null;
		return $this->database->ExecutePreparedStatement("allPagesWithTemplate", array($templatename));
	}

	/**
	* Method to get all website ids of websites that use the given template.
	*
	* Selects the id of all websites that use a specific template
	* @param string $templatename Name of the template
	* @return Mysqli\mysqli_result|null Query Result for use with FetchArray(), null if an error occured
	* @author Tamara Graf
	*/
	public function GetAllSitesWithTemplate($templatename)
	{
		if(!is_string($templatename)) return null;
		return $this->database->ExecutePreparedStatement("allSitesWithTemplate", array($templatename));
	}


	/**
	* Selects information of a given website.
	* Selects the fields website.headertitle, website.contact, website.imprint, website.privacyinformation, website.gtc, website.login, website.guestbook
	* and template.templatename (named template in the row array)
	* @param int $id id of the website. May be a numeric string ("1" instead of 1)
	* @return Mysqli\mysqli_result|null Query Result for use with FetchArray(), null if an error occured
	* @author Tamara Graf
	*/
	public function GetWebsiteInfoById($id)
	{
		if(!is_numeric($id)) return null;
		return $this->database->ExecutePreparedStatement("websiteById", array($id));
	}



	/**
	* GetHighestRelativeNumber()
	* @param
	* @return int number of templates
	*/
	public function GetHighestRelativeNumber()
	{
		$result = $this->database->ExecuteQuery("SELECT MAX(relativeposition) AS maxpos FROM page;");
		$num = $this->FetchArray($result);

		return  intval($num['maxpos']);
	}



	/**
	* Fetches the next result row as an array
	* @param string $result is the result of an query
	* @return Mysqli\mysqli_result|null Query Result for use with FetchArray(), null if an error occured
	*/
	public function FetchArray($result)
	{
		return $this->database->FetchArray($result);
	}



	/**
	* get result count
	* @param string $result
	* @return Mysqli\mysqli_result|null Query Result for use with FetchArray(), null if an error occured
	*/
	public function GetResultCount($result)
	{
		return  $this->database->GetResultCount($result);
	}



	/**
	* select one page by the title of the page
	* @param string $title is the title of the page
	* @return Mysqli\mysqli_result|null Query Result for use with FetchArray(), null if an error occured
	*/
	public function SelectPageByPagename($title)
	{
		return $this->database->ExecutePreparedStatement("selectPageByPagename", array($title));
	}



	/**
	* select one page by the id of the page
	* @param int $pageId the id of the page
	* @return Mysqli\mysqli_result|null Query Result for use with FetchArray(), null if an error occured
	*/
	public function SelectPageById($pageId)
	{
		 return	$this->database->ExecutePreparedStatement("selectPageById", array($pageId));
	}



	/**
    * select one template by the templatename
	* @param string $templatename is the name of the template
	* @return Mysqli\mysqli_result|null Query Result for use with FetchArray(), null if an error occured
	*/
	public function SelectTemplateByTemplatename($templatename)
	{
		return $this->database->ExecutePreparedStatement("selectTemplateByTemplatename", array($templatename));
	}



	/**
	* select one template by id
	* @param int $templateId the id of the template
	* @return Mysqli\mysqli_result|null Query Result for use with FetchArray(), null if an error occured
	*/
	public function SelectTemplateById($templateId)
	{
		 return	$this->database->ExecutePreparedStatement("selectTemplateById", array($templateId));
	}



	/**
	* select the id of the page by the name of the page
	* @param string $title the title of the page
	* @return Mysqli\mysqli_result|null Query Result for use with FetchArray(), null if an error occured
	*/
	public function SelectPageIdByPagename($title)
	{
		 return	$this->database->ExecutePreparedStatement("selectPageIdByPagename", array($title));
	}



	/**
	* delete one special page by id
	* @param int $pageId the id of the page
		* @return boolean true|false successful (true) when the query could be executed correctly and the page is deleted
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
	* delete one special template by id
	* @param int $templateId the id of the template
	* @return boolean true|false successful (true) when the query could be executed correctly and the template is deleted
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
	* checks whether the title of a page already exists in the database
	* @param string $title the title of the page
	* @return boolean true|false successful (true) when the query could be executed correctly and there is already a page with this special title
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
	* checks whether the templatename of a template already exists in the database
	* @param string $templatename the templatename of the template
	* @return boolean true|false successful (true) when the query could be executed correctly and there is already a template with this name
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



	/**
	* select all label_article
	* @param void
	* @return Mysqli\mysqli_result|null Query Result for use with FetchArray(), null if an error occured
	*/
	public function SelectAllLabel_Article()
	{
		return $this->database->ExecutePreparedStatement("selectAllLabel_Article", array());
	}



	/**
	* select all label from an article by id
	* @param int $articleId the id of the article
	* @return Mysqli\mysqli_result|null Query Result for use with FetchArray(), null if an error occured
	*/
	public function SelectAllLabelsFromAnArticleById($articleId)
	{
		return $this->database->ExecutePreparedStatement("selectAllLabelsFromAnArticleById", array($articleId));
	}



	/**
	* creates a new template
	* @param string $templatename the name of the template
	* @param string $filelink the filelink of the template
	* @return boolean true|false successful (true) when the query could be executed correctly and the template is generated
	*/
	public function InsertTemplate($templatename, $filelink)
	{
		if(!($this->TemplatenameAlreadyExists($templatename)))
		{
			if(	$templatename == '')
			{

				echo
						"<div class='info' style='background-color:red;'>
						<strong>Info!</strong> Das Template muss einen Namen haben!!!
						</div>";
				return false;

			}
			else
			{
			$templatename = $this->database->RealEscapeString($templatename);
			$result = $this->database->ExecuteQuery("INSERT INTO template (id, templatename, filelink) VALUES (NULL, '".$templatename."', '".$filelink."') ");
			}

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
		else
		{
			return false;
		}
	}



	/**
	* creates a new page
	* @param string $title the title of the template
	* @param int $relativeposition
	* @param int $templateId the id of the used template
	* @return boolean true|false successful (true) when the query could be executed correctly and page is generated
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
	* creates a new label
	* @param string $labelname the name of the label
	* @param string $uri the uri of the label
	* @return boolean true|false successful (true) when the query could be executed correctly and label is generated
	*/
	public function InsertLabel($labelname, $uri)
	{
		$labelname = $this->database->RealEscapeString($labelname);
		$result = $this->database->ExecuteQuery("INSERT INTO label (id, labelname, uri) VALUES (NULL, '".$labelname."', '".$uri."') ");

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
	* updates a special label by uri
	* @param string $labelname
	* @param string $uri
	* @return boolean true|false successful (true) when the query could be executed correctly and the changes in terms of the label are done
	*/
	public function UpdateLabelByUri($labelname, $uri)
	{
		$result = $this->database->ExecuteQuery("UPDATE label SET labelname  = '".$labelname."'  WHERE uri  = '".$uri."'" );

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
	* updates a special label by id
	* @param int $labelId
	* @param string $labelname
	* @param string $uri
	* @return boolean true|false successful (true) when the query could be executed correctly and the changes in terms of the label are done
	*/
	public function UpdateLabelById($labelId, $labelname, $uri)
	{
		$result = $this->database->ExecuteQuery("UPDATE label SET labelname  ='".$labelname."', uri  = '".$uri."'  WHERE id = ". $labelId);

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
	* updates a special page by id
	* @param int $pageId
	* @param string $title
	* @param int $relativeposition
	* @param int $templateId
	* @param int $websiteId
	* @return boolean true|false successful (true) when the query could be executed correctly and the changes in terms of the page are done
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
	* updates template by id
	* @param int $templateId
	* @param string $templatename
	* @param string $filelink
	* @return boolean true|false successful (true) when the query could be executed correctly and the changes in terms of the template are done
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
	* delets label_articles
	* @param int $articleId the id of the article (foreign key)
	* @return boolean true|false successful (true) when the query could be executed correctly and the label_article is deleted
	*/
	public function DeleteLabel_ArticleByArticleId($articleId)
	{
		$result = $this->database->ExecuteQuery("DELETE FROM label_article WHERE article_id = ".$articleId);

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
	 * delets label_articles
	 * @param int $labelId the id of the label (foreign key)
	 * @return boolean true|false successful (true) when the query could be executed correctly and the label_article is deleted
	 */
	 public function DeleteLabel_ArticleByLabelId($labelId)
	 {
	 	$result = $this->database->ExecuteQuery("DELETE FROM label_article WHERE label_id = ".$labelId);

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
	* delets all label_articles
	* @return boolean true|false successful (true) when the query could be executed correctly and all label_articles are deleted
  	*/
  	public function DeleteAllLabel_Article()
  	{
  		$result = $this->database->ExecuteQuery("DELETE FROM label_Article");

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
 	* creates new label_articles
 	* @param int $labelId the id of the label (foreign key)
 	* @param int $articleId the id of the article (foreign key)
	* @return boolean true|false successful (true) when the query could be executed correctly and a label_article is created
 	*/
 	public function InsertLabel_Article($labelId, $articleId)
 	{
 		$result = $this->database->ExecuteQuery("INSERT INTO label_article (label_id, article_id) VALUES (".$labelId.", ".$articleId.")");

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
	* updates label_articles by label_id
	* @param int $labelId the id of the label (foreign key)
	* @param int $articleId the id of the article (foreign key)
	* @return boolean true|false successful (true) when the query could be executed correctly and a label_article is updated
	*/
	public function UpdateLabel_ArticleByLabelId($labelId, $articleId)
	{
		$result = $this->database->ExecuteQuery("UPDATE label_article SET article_id  = ".$articleId."  WHERE label_id  = " .$labelId);

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
	* updates label_articles by label_id by article_id
	* @param int $labelId the id of the label (foreign key)
	* @param int $articleId the id of the article (foreign key)
	* @return boolean true|false successful (true) when the query could be executed correctly and a label_article is updated
	*/
	public function UpdateLabel_ArticleByArticleId($labelId, $articleId)
	{
		$result = $this->database->ExecuteQuery("UPDATE label_article SET label_id  = ".$labelId."  WHERE article_id  = " .$articleId);

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
	* select all pages
	* @param void
	* @return Mysqli\mysqli_result|null Query Result for use with FetchArray(), null if an error occured
	*/
	public function SelectAllPages()
	{
		return $this->database->ExecutePreparedStatement("selectAllPages", array());
	}



	/**
	* select all articles
	* @param void
	* @return Mysqli\mysqli_result|null Query Result for use with FetchArray(), null if an error occured
	*/
	public function SelectAllArticles()
	{
		return $this->database->ExecutePreparedStatement("selectAllArticles", array());
	}


	/**
	* select all templates
	* @param void
	* @return Mysqli\mysqli_result|null Query Result for use with FetchArray(), null if an error occured
	*/
	public function SelectAllTemplates()
	{
		return $this->database->ExecutePreparedStatement("selectAllTemplates", array());
	}




	/**
	* select all label_user
	* @param void
	* @return Mysqli\mysqli_result|null Query Result for use with FetchArray(), null if an error occured
	*/
	public function SelectAllLabel_User()
	{
		return $this->database->ExecutePreparedStatement("selectAllLabel_User", array());
	}



	/**
	* delete all label_user
	* @param void
	* @return boolean true|false successful (true) when the query could be executed correctly and all label_users are deleted
	*/
	public function DeleteAllLabel_User()
	{
		$result = $this->database->ExecuteQuery("DELETE FROM label_user");

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
	* delete label_user by article_id
	* @param int $userId the user's id (foreign key)
	* @return boolean true|false successful (true) when the query could be executed correctly and a label_users is deleted
	*/
	public function DeleteLabel_UserByArticleId($userId)
	{
		$result = $this->database->ExecuteQuery("DELETE FROM label_user WHERE user_id = ".$userId);

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
	* delete label_user by labe-_id
	* @param int $labelId the id of the label (foreign key)
	* @return boolean true|false successful (true) when the query could be executed correctly and a label_users is deleted
	*/
	public function DeleteLabel_UserByLabelId($labelId)
	{
		$result = $this->database->ExecuteQuery("DELETE FROM label_user WHERE label_id = ".$labelId);

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
	* creates label_user
	* @param int $labelId the id of the label (foreign key)
	* @param int $userId the user's id (foreign key)
	* @return boolean true|false successful (true) when the query could be executed correctly and a label_users is created
	*/
	public function InsertLabel_User($labelId, $userId)
	{
		$result = $this->database->ExecuteQuery("INSERT INTO label_user (label_id, user_id) VALUES (".$labelId.", ".$userId.")");

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
	* update label_user by label_id
	* @param int $labelId the id of the label (foreign key)
	* @param int $userId the user's id (foreign key)
	* @return boolean true|false successful (true) when the query could be executed correctly and a label_users is updated
	*/
	public function UpdateLabel_UserByLabelId($labelId, $userId)
	{

		$result = $this->database->ExecuteQuery("UPDATE label_user SET user_id  = ".$userId."  WHERE label_id  = " .$labelId);

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
	* update label_user by article_id
	* @param int $labelId the id of the label (foreign key)
	* @param int $userId the user's id (foreign key)
	* @return boolean true|false successful (true) when the query could be executed correctly and a label_users is updated
	*/
	public function UpdateLabel_UserByArticleId($labelId, $userId)
	{
		$result = $this->database->ExecuteQuery("UPDATE label_user SET label_id  = ".$labelId."  WHERE user_id  = " .$userId);

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
	* Select all websites
	* @param void
	* @return Mysqli\mysqli_result|null Query Result for use with FetchArray(), null if an error occured
	*/
	public function SelectAllWebsite()
	{
		 return	$this->database->ExecutePreparedStatement("selectAllWebsite", array());
	}

	/**
	* select all websites by id
	* @param int $id the id of the website
	* @return Mysqli\mysqli_result|null Query Result for use with FetchArray(), null if an error occured
	*/
	public function SelectWebsiteById($id)
	{
		 return	$this->database->ExecutePreparedStatement("selectWebsiteById", array($id));
	}

	/**
	* delete one website, the pages and the articles by website_id
	* @param int $website_id the id of the website
	* @return boolean true|false successful (true) when the query could be executed correctly and a website, the pages and the article is deleted
	*/
	public function DeleteWebsiteById($id)
	{
		 $logDeletedWebsite = $this->FetchArray($this->SelectWebsiteById($website_id))['headertitle'];
		 $result = $this->database->ExecutePreparedStatement("deleteWebsiteAndPageAndArticleByWebsiteId", array($website_id));

			if($result==true)
			{
				$logUsername = $_SESSION['username'];
				$logRolename = $_SESSION['rolename'];
				$logDescription = 'Folgende Website incl. Seiten und Artikeln wurde gelöscht: <strong>'.$logDeletedWebsite.'</strong>';
				$this->database->InsertNewLog($logUsername, $logRolename, $logDescription);
				return true;
			}
			else
			{
				 return false;
			}
	 }


	/**
	* update one website by id
	* @param int $websiteId
	* @param string $headertitle
	* @param string $contact
	* @param string $imprint
	* @param string $privacyinformation
	* @param string $gtc
	* @param bool $login
	* @param bool $guestbook
	* @param int $template_id
	* @return boolean true|false successful (true) when the query could be executed correctly and a website is updated
	*/
	public function UpdateWebsiteById($websiteId, $headertitle, $contact, $imprint, $privacyinformation, $gtc, $login, $guestbook, $template_id)
	{
		$websiteHeadertitleBevoreUpdate = $this->FetchArray($this->SelectWebsiteById($headertitle))['headertitle'];
		$set = "SET headertitle ='".$headertitle."', ";
		if(is_null($contact)) $set .= "contact = NULL, "; else $set .= "contact='".$contact."', ";
		if(is_null($imprint)) $set .= "imprint = NULL, "; else $set .= "imprint='".$imprint."', ";
		if(is_null($privacyinformation)) $set .= "privacyinformation = NULL, "; else $set .= "privacyinformation='".$privacyinformation."', ";
		if(is_null($gtc)) $set .= " gtc = NULL, "; else $set .= "gtc = '".$gtc."', ";
		$set .= "login = ".$login.", guestbook = ".$guestbook.", template_id = ".$template_id;

		$result = $this->database->ExecuteQuery("UPDATE website ".$set." WHERE id = ". $websiteId);

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
	* creates new websites
	* @param string $headertitle
	* @param string $contact
	* @param string $imprint
	* @param string $privacyinformation
	* @param string $gtc
	* @param bool $login
	* @param bool $guestbook
	* @param int $template_id
	* @return boolean true|false successful (true) when the query could be executed correctly and a website is created
	*/
	public function InsertWebsite($headertitle, $contact, $imprint, $privacyinformation, $gtc, $login, $guestbook, $template_id)
	{
		$val = "VALUES ( NULL, '".$headertitle."', ";
		if(is_null($contact)) $val .= "NULL, "; else $val .= "'".$contact."', ";
		if(is_null($imprint)) $val .= "NULL, "; else $val .= "'".$imprint."', ";
		if(is_null($privacyinformation)) $val .= "NULL, "; else $val .= "'".$privacyinformation."', ";
		if(is_null($gtc)) $val .= "NULL, "; else $val .= "'".$gtc."', ";
		$val .= $login.", ".$guestbook.",  ".$template_id.")";

		$result = $this->database->ExecuteQuery("INSERT INTO website (id, headertitle, contact, imprint, privacyinformation, gtc, login, guestbook, template_id) ".$val);

		if($result==true)
		 {
			$logUsername = $_SESSION['username'];
			$logRolename = $_SESSION['rolename'];
			$logDescription = 'Die Website <strong>'.$headertitle.'</strong> wurde neu erstellt.';
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
	* selects all labels by labelid
	* @param int $labelId the id of the label
	* @return Mysqli\mysqli_result|null Query Result for use with FetchArray(), null if an error occured
	*/
	public function SelectLabelByLabelId($labelId)
	{
		return $this->database->ExecutePreparedStatement("selectLabelByLabelId", array($labelId));
	}

	/**
	* selects all labels by labelname
	* @param string $labelname the labelname of the label
	* @return Mysqli\mysqli_result|null Query Result for use with FetchArray(), null if an error occured
	*/
	public function SelectLabelIdByLabelname($labelname)
	{
		return $this->database->ExecutePreparedStatement("selectLabelIdByLabelname", array($labelname));
	}

	/**
	* selects all articles by header
	* @param string $header the header of the article
	* @return Mysqli\mysqli_result|null Query Result for use with FetchArray(), null if an error occured
	*/
	public function SelectArticleByHeader($header)
	{
		return $this->database->ExecutePreparedStatement("selectArticleByHeader", array($header));
	}

	/**
	* select all labels
	* @param void
	* @return Mysqli\mysqli_result|null Query Result for use with FetchArray(), null if an error occured
	*/
	public function SelectAllLabels()
	{
		return $this->database->ExecutePreparedStatement("selectAllLabels", array());
	}

	/**
	* selects all websites by headertitle
	* @param string $headertitle the headertitle of the website
	* @return Mysqli\mysqli_result|null Query Result for use with FetchArray(), null if an error occured
	*/
	public function SelectAllWebsiteByHeadertitle($headertitle)
	{
		return $this->database->ExecutePreparedStatement("selectAllWebsiteByHeadertitle", array($headertitle));
	}

	/**
	* download the database
	* @param string $host database host
	* @param string $user database user
	* @param string $password password for database user
	* @param string $db database name
	* @return Mysqli\mysqli_result|null Query Result for use with FetchArray(), null if an error occured
	*/
	public function DownloadDBContent($dbhost, $dbuser, $dbpwd, $dbname)
	{
     $this->database->DownloadDB($dbhost, $dbuser, $dbpwd, $dbname);
	}

	/**
	* download the database (nur zum Testen)
	* @param void
	* @return Mysqli\mysqli_result|null Query Result for use with FetchArray(), null if an error occured
	*/
	public function DownloadDBContentTest()
	{
     $this->database->DownloadDBTest();
	}
}
?>
