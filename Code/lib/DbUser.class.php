<?php
/* namespace */
namespace SemanticCms\DatabaseAbstraction;

/* Include(s) */
require_once 'DbEngine.class.php';

/**
* Provides functionality for communication with the database according to user data
*/
class DbUser
{
	/** @var DbEngine*/
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

/*		Muster:
		$deleteUser = "...."
		$this->database->PrepareStatement("deleteUser", $deleteUser);
*/

		$selectUserById = "SELECT * FROM user WHERE id = ?";
		$this->database->PrepareStatement("selectUserById", $selectUserById);

		$selectUserByUsername = "SELECT * FROM user WHERE username = ?";
		$this->database->PrepareStatement("selectUserByUsername", $selectUserByUsername);

		$deleteUserById = "DELETE FROM user WHERE id = ?";
		$this->database->PrepareStatement("deleteUserById", $deleteUserById);

		$deleteUserByUsername = "DELETE FROM user WHERE username = ?";
		$this->database->PrepareStatement("deleteUserByUsername", $deleteUserByUsername );


		$deleteRole = "DELETE FROM role WHERE id = ?";
		$this->database->PrepareStatement("deleteRole", $deleteRole);

		$selectRoleById = "SELECT * FROM role WHERE id = ?";
		$this->database->PrepareStatement("selectRole", $selectRoleById);

		$selectRoleByRolename = "SELECT * FROM role WHERE rolename = ?";
		$this->database->PrepareStatement("selectRoleByRolename", $selectRoleByRolename);

		$selectUserByEmail = "SELECT * FROM user WHERE email = ?";
		$this->database->PrepareStatement("selectUserByEmail", $selectUserByEmail);

		$selectRolenameByUsername = "SELECT role.rolename FROM role INNER JOIN user ON role.id = user.role_id WHERE user.username = ? ";
		$this->database->PrepareStatement("selectRolenameByUsername", $selectRolenameByUsername);

		$selectAllUsers = "SELECT * FROM user";
		$this->database->PrepareStatement("selectAllUsers", $selectAllUsers);

		$selectAllRoles = "SELECT * FROM role";
		$this->database->PrepareStatement("selectAllRoles", $selectAllRoles);

		$selectAllBan_Reason = "SELECT * FROM ban_reason";
		$this->database->PrepareStatement("selectAllBan_Reason", $selectAllBan_Reason);

		$selectBan_ReasonById = "SELECT * FROM ban_reason WHERE id = ?";
		$this->database->PrepareStatement("selectBan_ReasonById", $selectBan_ReasonById);


		$selectAllBan= "SELECT * FROM ban";
		$this->database->PrepareStatement("selectAllBan", $selectAllBan);

		$insertBan_Reason = "INSERT INTO ban_reason (id, reason) VALUES (NULL, ?)";
		$this->database->PrepareStatement("insertBan_Reason", $insertBan_Reason);

		$selectBanByUserid = "SELECT * FROM ban where user_id = ? ";
		$this->database->PrepareStatement("selectBanByUserid", $selectBanByUserid);

		$selectAllBansFromAUserByUsername = "SELECT * FROM ban INNER JOIN  user ON  ban.user_id = user.id WHERE username = ?";
		$this->database->PrepareStatement("selectAllBansFromAUserByUsername", $selectAllBansFromAUserByUsername);

		$selectAllUsersWhichAreBannedNow = "SELECT * FROM ban INNER JOIN user ON ban.user_id = user.id WHERE ban.enddatetime > now()";
		$this->database->PrepareStatement("selectAllUsersWhichAreBannedNow", $selectAllUsersWhichAreBannedNow);


		$getUserPermissionByUsername = "SELECT role.guestbookmanagement, role.usermanagement, role.pagemanagement, role.articlemanagement, role.guestbookusage, role.templateconstruction, role.databasemanagement, role.backendlogin FROM role INNER JOIN user ON role.id = user.role_id WHERE user.username = ? ";
		$this->database->PrepareStatement("getUserPermissionByUsername", $getUserPermissionByUsername);

		$whichRoleHasASpecialUser = "SELECT user.username, role.rolename FROM user INNER JOIN role ON user.role_id = role.id WHERE user.username = ?";
		$this->database->PrepareStatement("whichRoleHasASpecialUser", $whichRoleHasASpecialUser);


		$selectAllLogs = "SELECT * FROM logtable ORDER BY logtable.id DESC";
		$this->database->PrepareStatement("selectAllLogs", $selectAllLogs);

		$selectOneLogById = "SELECT * FROM logtable WHERE id = ?";
		$this->database->PrepareStatement("selectOneLogById", $selectOneLogById);


		$selectAllLogsFromASpecialDateByLogdate = "SELECT * FROM logtable WHERE logdate = ? ORDER BY logtable.id DESC";
		$this->database->PrepareStatement("selectAllLogsFromASpecialDateByLogdate", $selectAllLogsFromASpecialDateByLogdate);

		$selectAllLogsFromOneUserByUsername = "SELECT * FROM logtable WHERE logtable.username = ?";
		$this->database->PrepareStatement("selectAllLogsFromOneUserByUsername", $selectAllLogsFromOneUserByUsername);
		
		
		$selectUsernameByBanId = "SELECT user.username FROM user INNER JOIN ban on user.id = ban.user_id WHERE ban.id = ?";
		$this->database->PrepareStatement("selectUsernameByBanId", $selectUsernameByBanId);

	}



	/* --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- for start.php => statistics for admin --- --- --- --- --- --- --- --- --- */
	/**
	* Counts the number of users who are registrated
	* @param void
	* @return int number of registrated users
	*/
	public function CountUsers()
	{
		$result = $this->database->ExecuteQuery("SELECT COUNT(*) AS AnzahlUser FROM user");
		$num = $this->FetchArray($result);
		return ($num['AnzahlUser']);
	}


	/**
	* Counts the number of defined roles in the database
	* @param void
	* @return int number of defined roles
	*/
	public function CountRoles()
	{
		$result = $this->database->ExecuteQuery("SELECT COUNT(*) AS AnzahlRoles FROM role");
		$num = $this->FetchArray($result);
		return ($num['AnzahlRoles']);
	}


	/**
	* Counts the number of bans all users have in total
	* @param void
	* @return int number of bans
	*/
	public function CountBans()
	{
		$result = $this->database->ExecuteQuery("SELECT COUNT(*) AS AnzahlBan FROM ban");
		$num = $this->FetchArray($result);
		return ($num['AnzahlBan']);
	}


	/**
	* Counts the number of articles
	* @param void
	* @return int number of articles
	*/
	public function CountArticles()
	{
		$result = $this->database->ExecuteQuery("SELECT COUNT(*) AS AnzahlArticle FROM article");
		$num = $this->FetchArray($result);
		return ($num['AnzahlArticle']);
	}


	/**
	* Counts the number of pages
	* @param void
	* @return int number of pages
	*/
	public function CountPages()
	{
		$result = $this->database->ExecuteQuery("SELECT COUNT(*) AS AnzahlPages FROM page");
		$num = $this->FetchArray($result);
		return ($num['AnzahlPages']);
	}


	/**
	* Counts the number of templates
	* @param void
	* @return int number of templates
	*/
	public function CountTemplates()
	{
		$result = $this->database->ExecuteQuery("SELECT COUNT(*) AS AnzahlTemplates FROM template");
		$num = $this->FetchArray($result);
		return ($num['AnzahlTemplates']);
	}


	/* END:--- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- for start.php => statistics for admin --- --- --- --- --- --- --- --- --- */


	/**
	* select all logs
	* @param void
	* @return Mysqli\mysqli_result|null Query Result for use with FetchArray(), null if an error occured
	*/
	public function SelectAllLogs()
	{
		return $this->database->ExecutePreparedStatement("selectAllLogs", array());
	}


	/**
	* selects one special log by the id of the log
	* @param int $logtableId the id of the logtable
	* @return Mysqli\mysqli_result|null Query Result for use with FetchArray(), null if an error occured
	*/
	public function SelectOneLogById($logtableId)
	{
		return $this->database->ExecutePreparedStatement("selectOneLogById", array($logtableId));
	}



	/**
	* selects all logs from one user by his username
	* @param string $logtableUsername is the user who changed something => the user who is responsible for the new log in the logtable
	* @return Mysqli\mysqli_result|null Query Result for use with FetchArray(), null if an error occured
	*/
	public function SelectAllLogsFromOneUserByUsername($logtableUsername)
	{
		return $this->database->ExecutePreparedStatement("selectAllLogsFromOneUserByUsername", array($logtableUsername));
	}



	/**
	* select all logs from the logtable by logdate and ordered the result descending by the id of the logtable
	* @param string $logtableLogdate
	* @return Mysqli\mysqli_result|null Query Result for use with FetchArray(), null if an error occured
	*/
	public function SelectAllLogsFromASpecialDateByLogdate($logtableLogdate)
	{
			return $this->database->ExecutePreparedStatement("selectAllLogsFromASpecialDateByLogdate", array($logtableLogdate));
	}





	/* --- ENDE --- --- --- --- --- --- --- --- --- --- --- --- --- für die Startseite: Statistik für Admin --- --- --- --- --- --- --- --- ENDE --- */






	/**
	* checks whether the email adress already exists in database or not
	* @param string $email the user's email
	* @return boolean true|false successful (true) when email already exists, false when not
	*/
	public function EmailAlreadyExists($email)
	{
		$result = $this->database->ExecuteQuery("SELECT * FROM user WHERE email = '".$email."'");

		if($result==true && $this->database->GetResultCount($result) > 0)
		{
			return true;	//there is a user with this email adress
		}
		else
		{
			return false;
		}

	}


	/**
	* checks whether the username already exists in database
	* @param string $username the user's name
	* @return boolean true|false successful (true) when username already exists in database, false when the username doesn't exist
	*/
	public function UsernameAlreadyExists($username)
	{
		$result = $this->database->ExecuteQuery("SELECT * FROM user WHERE username = '".$username."'");

		 if($result==true && $this->database->GetResultCount($result) > 0)
		 {
			 return true;	// there is a user with this username
		 }
		 else
		 {
			 return false;
		 }
	}



	/**
	* insert user in database to registrate a new user
	* @param string $username the user's username
	* @param string $firstname the user's firstname
	* @param string $lastname the user's lastname
	* @param string $mail the user's mailaddress
	* @param string $password the user's unhashed password
	* @param string $birthdate the user's birthdate as date formatted string
	* @return boolean true|false successful (true) when the query could be executed correctly and a new user is registrated
	*/
		public function RegistrateUser($role_id, $lastname, $firstname, $username, $password, $email, $birthdate)
	{

		if(!($this->EmailAlreadyExists($email)))
		{
			if(!($this->UsernameAlreadyExists($username)))
			{
				if (filter_var($email, FILTER_VALIDATE_EMAIL))
				{
					if(!filter_var($username, FILTER_VALIDATE_EMAIL))
					{

					$lastname= $this->database->RealEscapeString($lastname);
					$firstname= $this->database->RealEscapeString($firstname);
					$username= $this->database->RealEscapeString($username);
					$hash = password_hash($password, PASSWORD_BCRYPT, array('cost' => 12));

					$result = $this->database->ExecuteQuery("INSERT INTO user (id, role_id, lastname, firstname, birthdate, username, password, email, registrydate) VALUES (NULL, ".$role_id.", '".$lastname."', '".$firstname."', '".$birthdate."', '".$username."', '".$hash."', '".$email."', NOW())");

						if($result==true)
						{
							return true;
						}
						else
						{
							return false;
						}
					}
				}
				else  return false;

			}

		}

	}




	/**
	* Delets a particular User by its id
	* @param int $userId the user's Id
	* @return boolean true|false successful (true) when the query could be executed correctly and a special user is deleted
	*/
	public function DeleteUserById($userId)
	{
		$logDeletedUser = $this->FetchArray($this->GetUserInformationById($userId))['username'];

		$logUsername = $_SESSION['username'];
		$logRolename = $_SESSION['rolename'];

		if ($logUsername == $logDeletedUser)
		{
			$logDescription = 'Man kann sich nicht selber löschen.';
			$this->database->InsertNewLog($logUsername, $logRolename, $logDescription);
		}
		else
		{
			$result = $this->database->ExecutePreparedStatement("deleteUserById", array($userId));
			if($result==true)
			{
				$logDescription = 'Folgender User wurde gelöscht: <strong>'.$logDeletedUser.'</strong>';
				$this->database->InsertNewLog($logUsername, $logRolename, $logDescription);
				return true;
			}
			else
			{
				 return false;
			}
		}
	}


	/**
	* Delets a particular User by a name
	* @param int $userId the user's Id
	* @return boolean true|false successful (true) when the query could be executed correctly and a special user is deleted
	*/
/*	public function DeleteUserByUsername($username)
	{
		$result = $this->database->ExecutePreparedStatement("deleteUserByUsername", array($username));

		//$usersName = $this->database->ExecuteQuery("SELECT username FROM user WHERE id = ".$userId);

		if($result==true)
		{
			// für Log-Tabelle:
			// Wer wird gelöscht?
			// Wer hat den Löschvorgang durchgeführt? => Usermanagement is true. => alle diese könnnen User löschen!
			// eventuell neuen Parameter bei Funktion mitgeben ($userId von der Person, die löscht.)


			$logUsername = 'Wer ist gerade angemeldet?';
			$logRolename = 'Welche Rolle hat der angemeldete Benutzer?';
			//$logDescription = 'der User: '.$nameOfUser.' wurde gelöscht';
			$logDescription = 'Was wurde verändert?';

			$re = $this->database->InsertNewLog($logUsername, $logRolename, $logDescription);

			echo'Versuch';

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
	* selecte the user by id to get some information
	* @param int $userId the id of the user
	* @return Mysqli\mysqli_result|null Query Result for use with FetchArray(), null if an error occured
	*/
	public function GetUserInformationById($userId)
	{
		return $this->database->ExecutePreparedStatement("selectUserById", array($userId));
	}


	/**
	* selecte the user by username to get some information
	* @param string $username the role's name
	* @return Mysqli\mysqli_result|null Query Result for use with FetchArray(), null if an error occured
	*/
	public function GetUserInformationByUsername($username)
	{
		 return	$this->database->ExecutePreparedStatement("selectUserByUsername", array($username));
	}


	/**
	* deletes a particular Role
	* @param int $roleId the role's Id
	* @return boolean true|false successful (true) when the query could be executed correctly and a special role is deleted
	*/
	public function DeleteRole($roleId)
	{
 		if ($this->CountUsersWithASpecialRoleByRoleId($roleId) > 0)
		{
			echo
	        "<div class='info'>
	        <strong>Info!</strong> Die Rolle wurde nicht gelöscht, weil sie immer noch in Verwendung ist!!!
	        </div>";
			return false;
		}
		else
		{
			$logDeletedRolename = $this->FetchArray($this->SelectRoleById($roleId))['rolename'];

			if(($logDeletedRolename == 'Admin') || ($roleId == 1))
			{
				echo
				"<div class='info'>
				<strong>Info!</strong> Die Rolle Admin kann nicht gelöscht werden!!!
				</div>";
				return false;
			}
			elseif ($logDeletedRolename == 'Administrator')
			{
				echo
				"<div class='info'>
				<strong>Info!</strong> Die Rolle Administrator kann nicht gelöscht werden!!!
				</div>";
				return false;
			}
			else
			{
				$result = $this->database->ExecutePreparedStatement("deleteRole", array($roleId));
				return true;

			}

			if($result==true)
			{
				$logUsername = $_SESSION['username'];
				$logRolename = $_SESSION['rolename'];
				$logDescription = 'Folgende Rolle wurde gelöscht: <strong>'.$logDeletedRolename.'</strong>' ;

				$this->database->InsertNewLog($logUsername, $logRolename, $logDescription);

				return true;
			}
			else
			{
				 return false;
			}
		}
	}


	/**
	* assigns a chosen role to a particular user (update one special user)
	* @param int $roleId the role's Id
	* @param int $userId the user's Id
	* @return boolean true|false successful (true) when the query could be executed correctly and a special role is assinged to a user
	*/
	public function AssignRole($roleId, $userId)
	{
		$result = $this->database->ExecuteQuery("UPDATE user SET role_id ='".$roleId."' WHERE id = '". $userId."'");

		if($result==true)
		{
			$logUsername = $_SESSION['username'];
		  $whoIsAssigned	 = $this->FetchArray($this->GetUserInformationById($userId))['username'];
			$logAssignedRolename = $this->FetchArray($this->SelectRoleById($roleId))['rolename'];
			$logRolename =  $this->FetchArray($this->SelectRolenameByUsername($logUsername))['rolename'];

		 	$logDescription = 'Dem User <strong>'.$whoIsAssigned.'</strong> wurde die Rolle <strong>'.$logAssignedRolename.'</strong> zugewiesen.' ;

	  	$this->database->InsertNewLog($logUsername, $logRolename, $logDescription);

			return true;
		}
		else
		{
			 return false;
		}
	}


	/**
	* creates a new role
	* @param int $uri the role's uri
	* @param string $rolename the name of the role
	* @param bool $guestbookmanagement
	* @param bool $usermanagement
	* @param bool $pagemanagement
	* @param bool $articlemanagement
	* @param bool $guestbookusage
	* @param bool $templateconstruction
	* @param bool $databasemanagement
	* @param bool $backendlogin
	* @return boolean true|false successful (true) when the query could be executed correctly and a new role is generated correctly
	*/
	public function NewRole($uri, $rolename, $guestbookmanagement, $usermanagement, $pagemanagement, $articlemanagement, $guestbookusage, $templateconstruction, $databasemanagement, $backendlogin)
	{
		$result = $this->database->ExecuteQuery("INSERT INTO role (id, uri, rolename, guestbookmanagement, usermanagement, pagemanagement, articlemanagement, guestbookusage, templateconstruction, databasemanagement, backendlogin) VALUES (NULL, '".$uri."', '".$rolename."', ".$guestbookmanagement.", ".$usermanagement.", ".$pagemanagement.", ".$articlemanagement.", ".$guestbookusage.",  ".$templateconstruction.",  ".$databasemanagement.",  ".$backendlogin.")");

		 if($result==true)
		 {
			$logUsername = $_SESSION['username'];
			$logRolename = $_SESSION['rolename'];
			$logDescription = 'Die Rolle <strong>'.$rolename.'</strong> wurde neu angelegt.';
			$this->database->InsertNewLog($logUsername, $logRolename, $logDescription);
			return true;
		 }
		 else
		 {
		 	 return false;
		 }
	}


	/**
	* select one role by id to get some informaion about the role
	* @param int $roleId the role's Id
	* @return Mysqli\mysqli_result|null Query Result for use with FetchArray(), null if an error occured
	*/
	public function SelectRoleById($roleId)
	{
		return $this->database->ExecutePreparedStatement("selectRole", array($roleId));
	}


	/**
	* selects a particular role by rolename to get some information about the role
	* @param string $rolename the role's name
	* @return Mysqli\mysqli_result|null Query Result for use with FetchArray(), null if an error occured
	*/
	public function SelectRoleByRolename($rolename)
	{
		return $this->database->ExecutePreparedStatement("selectRoleByRolename", array($rolename));
	}


	/**
	* saves role changes (update one role by id)
	* @param int $uri the role's uri
	* @param string $rolename the name of the role
	* @param bool $guestbookmanagement
	* @param bool $usermanagement
	* @param bool $pagemanagement
	* @param bool $articlemanagement
	* @param bool $guestbookusage
	* @param bool $templateconstruction
	* @param bool $databasemanagement
	* @param bool $backendlogin
	* @return boolean true|false successful (true) when the query could be executed correctly and the changes in terms of the role are done
	*/
	public function UpdateRoleById($uri, $rolename, $guestbookmanagement, $usermanagement, $pagemanagement, $articlemanagement, $guestbookusage, $templateconstruction, $databasemanagement, $backendlogin, $id)
	{
		$rolenameBevoreUpdate =  $this->FetchArray($this->SelectRoleById($id))['rolename'];
		
		
		//if(($rolenameBevoreUpdate == 'Admin') || ($rolenameBevoreUpdate == 'Administrator')) => beziehen uns nicht auf den Namen sondern die Id.
		if($id == 1)
		{
			echo
			"<div class='info'>
			<strong>Info!</strong> Die Rollenname Admin bzw. Administrator kann nicht geändert werden!!!
			</div>";

			return false;
		}
		else
		{
			$result = $this->database->ExecuteQuery("UPDATE role SET uri ='".$uri."',  rolename ='".$rolename."',  guestbookmanagement ='".$guestbookmanagement."',  usermanagement ='".$usermanagement."', pagemanagement ='".$pagemanagement."', articlemanagement ='".$articlemanagement."', guestbookusage ='".$guestbookusage."' , templateconstruction ='".$templateconstruction."', databasemanagement ='".$databasemanagement."', backendlogin ='".$backendlogin."' WHERE id = '". $id."'");

			if($result==true)
			{
				$logUsername = $_SESSION['username'];
				$logRolename = $_SESSION['rolename'];

				if($rolenameBevoreUpdate == $rolename)
				{
					$rolenameChanged = $rolename;
				}
				else
					{
						$rolenameChanged = $rolenameBevoreUpdate. '(neuer Rollenname: '.$rolename.')';
					}

				$logDescription = 'An der Rolle <strong>'.$rolenameChanged.'</strong> wurden Änderugen vorgenommen.';
				$this->database->InsertNewLog($logUsername, $logRolename, $logDescription);

				return true;
			}
			else
			{
				 return false;
			}
		}
	}


	/**
	* saves role changes (lastname, firstname, email) to apply changes to one special upser
	* @param string $lastname the user's lastname
	* @param string $lastname the user's firstname
	* @param string $lastname the user's username
	* @param string $lastname the user's email
	* @param int $id user's id
	* @return boolean true|false successful (true) when the query could be executed correctly and changes regarding a user are done
	*/
	public function UpdateUserDifferentNamesById($lastname, $firstname, $email, $userId)
	{
		$result = $this->database->ExecuteQuery("UPDATE user SET lastname ='".$lastname."',  firstname ='".$firstname."', email ='".$email."' WHERE id = '". $userId."'");

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
	* select one special user by email
	* @param string $email the user's email
	* @return Mysqli\mysqli_result|null Query Result for use with FetchArray(), null if an error occured
	*/
	public function SelectUserByEmail($email)
	{
		return $this->database->ExecutePreparedStatement("selectUserByEmail", array($email));
	}



	/**
	* select all registrated users
	* @param void
	* @return Mysqli\mysqli_result|null Query Result for use with FetchArray(), null if an error occured
	*/
	public function SelectAllUsers()
	{
		return $this->database->ExecutePreparedStatement("selectAllUsers", array());
	}


	/**
	* select all roles
	* @param void
	* @return Mysqli\mysqli_result|null Query Result for use with FetchArray(), null if an error occured
	*/
	public function SelectAllRoles()
	{
		return $this->database->ExecutePreparedStatement("selectAllRoles", array());
	}




	/**
	* checks via userid if the user is banned
	* @param int $userId the user's id
	* @return boolean true|false successful (true) when the query could be executed correctly and the question (is a special user banned at the moment?) is asked
	*/
	public function IsUserBannedId($userId)
	{
		//zum Testen in Xampp: SELECT * FROM `ban` INNER JOIN user ON ban.user_id = user.id WHERE (user.id = 8 AND ban.end > now())
		$userId= $this->database->RealEscapeString($userId);
		$result = $this->database->ExecuteQuery("SELECT * FROM ban INNER JOIN user ON ban.user_id = user.id WHERE ( user.id =".$userId." AND ban.end > now() )");

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
	* checks via username if the user is banned
	* @param string $username the user's username
	* @return boolean true|false successful (true) when the query could be executed correctly and the question (is a special user banned at the moment?) is asked
	*/
	public function IsUserBannedUsername($username)
	{
		$username = $this->database->RealEscapeString($username);
		$result = $this->database->ExecuteQuery("SELECT * FROM ban INNER JOIN user ON ban.user_id = user.id WHERE ( user.username ='".$username."' AND ban.end > now() )");

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
	*  creates a new ban for a particular user
	* @param int $user_id the user's id
	* @param int $reason_id the banreasons's id
	* @param string $description the ban's description
	* @param datetime $begindatetime the ban's begindatetime
	* @param datetime $enddatetime the ban's enddatetime
	* @return boolean true|false successful (true) when the query could be executed correctly and a special user is banned for a period of time
	*/
	public function InsertBanViaUserId($user_id, $reason_id, $description, $begindatetime, $enddatetime)
	{
		$description = $this->database->RealEscapeString($description);
		$BannedUsername = $this->FetchArray($this->GetUserInformationById($user_id))['username'];
		var_dump($BannedUsername);

		$result = $this->database->ExecuteQuery("INSERT INTO ban (id, user_id, reason_id, description, begindatetime, enddatetime) VALUES (NULL, ".$user_id.", ".$reason_id.", '".$description."', '".$begindatetime."', '".$enddatetime."')");

		if($result==true)
		{
			if($reason_id != 6)
			{
		 	$logUsername = $_SESSION['username'];
			$logRolename = $_SESSION['rolename'];

			$banReason = $this->FetchArray($this->SelectBan_ReasonById($reason_id))['reason'];

		 	$logDescription = 'Der User <strong>'.$BannedUsername.'</strong> wurde gebannt. <br> Grund: <strong>'.$banReason.'</strong>';

			$this->database->InsertNewLog($logUsername, $logRolename, $logDescription);
			}
			return true;
		}
		else
		{
			return false;
		}

	}


	/**
	*  deban one special user
	* @param int $id the ban's id
	* @return boolean
	* @return boolean true|false successful (true) when the query could be executed correctly and a special user isn't banned any more
	*/
	public function DebanUserViaBanId($id)
	{
		$debannedUsername = $this->FetchArray($this->SelectUsernameByBanId($id))['username'];
		$result = $this->database->ExecuteQuery("UPDATE ban SET enddatetime = NOW() WHERE id = ". $id);

		if($result==true)
		{
			$logUsername = $_SESSION['username'];
			$logRolename = $_SESSION['rolename'];

			$logDescription = 'Die Sperre des Users <strong>'.$debannedUsername.'</strong> ist abgelaufen. Er ist ab sofort wieder entsperrt. <br> ';

			$this->database->InsertNewLog($logUsername, $logRolename, $logDescription);

			return true;
		}
		else
		{
			return false;
		}
	}


	/**
	* select a special Ban by an user's id
	* @param int $user_id the users's id
	* @return Mysqli\mysqli_result|null Query Result for use with FetchArray(), null if an error occured
	*/
	public function SelectBanByUserid($user_id)
	{
		return $this->database->ExecutePreparedStatement("selectBanByUserid", array($user_id));
	}


	/**
	* creates a new  ban_reason
	* @param string $reason the reason of a ban_reason
	*/
/*	public function InsertBan_Reason($reason)
	{
		$reason = $this->database->RealEscapeString($reason);
		$result = $this->database->ExecutePreparedStatement("insertBan_Reason", array($reason));

		if($result==true)
		{
			  //wer hat den neuen Ban-Grund erstellt? Usermanagement is true. Was ist die neue Ban-Reason`?

			$logUsername = 'Wer ist gerade angemeldet?';
			$logRolename = 'Welche Rolle hat der angemeldete Benutzer?';
		 	$logDescription = 'Es gibt einen neue Ban-Reason: =>  $reason';

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
	*  select all bans
	* @param void
	* @return Mysqli\mysqli_result|null Query Result for use with FetchArray(), null if an error occured
	*/
	public function SelectAllBan()
	{
		return $this->database->ExecutePreparedStatement("selectAllBan", array());
	}


	/**
	*  select all ban_reasons
	* @param void
	* @return Mysqli\mysqli_result|null Query Result for use with FetchArray(), null if an error occured
	*/
	public function SelectAllBan_Reason()
	{
		return $this->database->ExecutePreparedStatement("selectAllBan_Reason", array());
	}


	/**
	* select all bans from a speacial user by username
	* @param string $username the user's username
	* @return Mysqli\mysqli_result|null Query Result for use with FetchArray(), null if an error occured
	*/
	public function SelectAllBansFromAUserByUsername($username)
	{
	 	return $this->database->ExecutePreparedStatement("selectAllBansFromAUserByUsername", array($username));
	}



	/**
	* select all users who are banned right now independently of reason
	* @param void
	* @return Mysqli\mysqli_result|null Query Result for use with FetchArray(), null if an error occured
	*/
	public function SelectAllUsersWhichAreBannedNow()
	{
		return $this->database->ExecutePreparedStatement("selectAllUsersWhichAreBannedNow", array());
	}




	/**
	* select all users who are banned right now for a special reason
	* @param string $reason the ban_reasons's reason
	*/
	public function SelectAllUsersWhoAreBannedNowForASpecialReasonByReason($reason)
	{
	return $this->database->ExecuteQuery("SELECT * FROM user INNER JOIN  ban ON  user.id = ban.user_id INNER JOIN ban_reason ON ban.reason_id = ban_reason.id WHERE ( ban.enddatetime > now() AND ban_reason.reason = '". $password."')");
	}



	/**
	* apply changes of the password to one user
	* @param string $userId the user's id
	* @param string $password the user's unhashed password
	* @param string $newPassword the user's new unhashed password
	* @param string $newPasswordRepeat the user's the repetition of new unhashed password
	* @return boolean true|false successful (true) when the query could be executed correctly and the changes in terms of the password are done
	*/
	public function ApplyPasswordChangesToUser($userId, $password, $newPassword, $newPasswordRepeat)
	{
		$result = $this->database->ExecuteQuery("SELECT password FROM user WHERE id ='".$userId."'");

		if ($result == true)
		{
			$pwCheck = password_verify($password, $this->database->FetchArray($result)['password']); 

			if($pwCheck && ($newPassword == $newPasswordRepeat))
			{
				$hash = password_hash($newPassword, PASSWORD_BCRYPT, array('cost' => 12));
				
				$changePassword = $this->database->ExecuteQuery("UPDATE user SET password = '".$hash."' WHERE id ='".$userId."'");
				return true;
			}
			return false;
		}
		else
		{ return false; }
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
	* to find out the permissions of one special user
	* @param string $username the user's username
	* @return Mysqli\mysqli_result|null Query Result for use with FetchArray(), null if an error occured
	*/
	public function GetUserPermissionByUsername($username)
	{
		return $this->database->ExecutePreparedStatement("getUserPermissionByUsername", array($username));
	}



	/**
	*  to find out the role of a special user
	* @param string $username the user's username
	* @return Mysqli\mysqli_result|null Query Result for use with FetchArray(), null if an error occured
	*/
	public function WhichRoleHasASpecialUser($username)
	{
		return $this->database->ExecutePreparedStatement("whichRoleHasASpecialUser", array($username));
	}





	/**
	* Method for Login
	*
	* Checks if there is a dataset that matches the given name and password.
	* Important: The user's password may not be hashed before
	*
	* @param string $nameInput the user's username or mail
	* @param string $password the user's unhashed password
	* @return true if login was successful otherwise false
	*/
	public function LoginUser($nameInput, $password)
	{
		$nameInput = $this->database->RealEscapeString($nameInput);
		$result = $this->database->ExecuteQuery("SELECT password FROM user WHERE email ='".$nameInput."' OR username ='".$nameInput."'");

		if($result==true)
		{
			return password_verify($password, $this->database->FetchArray($result)['password']);
		}
		else { return false; }
	}



	/**
	* select the rolename from one user by his username
	* @param string $username the user's username
	* @return Mysqli\mysqli_result|null Query Result for use with FetchArray(), null if an error occured
	*/
	public function SelectRolenameByUsername($username)
	{
			return $this->database->ExecutePreparedStatement("selectRolenameByUsername ", array($username));
	}


	/**
	* select the ban_reason by id
	* @param int $id the ban_ReasonId's Id
	* @return Mysqli\mysqli_result|null Query Result for use with FetchArray(), null if an error occured
	*/
	public function SelectBan_ReasonById($id)
	{
		return $this->database->ExecutePreparedStatement("selectBan_ReasonById", array($id));
	}


	/**
	* to find out how many users have one special role
	* @param int $roleId
	* @return int number of templates
	*/
	public function CountUsersWithASpecialRoleByRoleId($roleId)
	{
	$countUsersWithThisRoleid = $this->database->ExecuteQuery("SELECT COUNT(*) AS NumberOfUserWithASpecialRole FROM USER WHERE role_id =".$roleId);
	$num = $this->FetchArray($countUsersWithThisRoleid);
	return ($num['NumberOfUserWithASpecialRole']);
	}

	
	
	/**
	* selects username by BanId
	* @param string $banId is the id of the ban
	* @return Mysqli\mysqli_result|null Query Result for use with FetchArray(), null if an error occured
	*/
	public function SelectUsernameByBanId($banId)
	{
		return $this->database->ExecutePreparedStatement("selectUsernameByBanId", array($banId));
	}


	
	
	
	
	

	/**
	* download the database
	* @param string $host database host
	* @param string $user database user
	* @param string $password password for database user
	* @param string $db database name
	* @return Mysqli\mysqli_result|null Query Result for use with FetchArray(), null if an error occured
	*/
	/*
	public function DownloadDBUser($dbhost, $dbuser, $dbpwd, $dbname)
	{
     $this->database->DownloadDB($dbhost, $dbuser, $dbpwd, $dbname);
	}
 */


	/**
	* download the database (nur zum Testen)
	* @param void
	* @return Mysqli\mysqli_result|null Query Result for use with FetchArray(), null if an error occured
	*/
	/*
	public function DownloadDBUserTest()
	{
     $this->database->DownloadDBTest();
	}
 */

}
?>
