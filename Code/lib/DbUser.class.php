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


		// Role
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

		// => kommt noch:
		//$selectUserByUsernameOrEmail = "SELECT * FROM user WHERE ( username = ? OR email = ? )";
		//$this->database->PrepareStatement("selectUserByUsernameOrEmail ", $selectUserByUsernameOrEmail );


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

/*noch nicht eintragen => in Arbeit*/
		//$selectAllUsersWhoAreBannedNowForASpecialReasonById() = "SELECT * FROM ban INNER JOIN user ON ban.user_id = user.id WHERE ( ban.enddatetime > now() AND ban.reason_id = ? )";
		//$this->database->PrepareStatement("selectAllUsersWhoAreBannedNowForASpecialReasonById", $selectAllUsersWhoAreBannedNowForASpecialReasonById);
		// SELECT * FROM ban INNER JOIN user ON ban.user_id = user.id WHERE ( ban.enddatetime > now() AND ban.reason_id = 2)


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

	}



	/* --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- für die Startseite: Statistik für Admin --- --- --- --- --- --- --- --- --- */
	/**
	* CountUsers()
	*/
	public function CountUsers()
	{
		$result = $this->database->ExecuteQuery("SELECT COUNT(*) AS AnzahlUser FROM user");
		$num = $this->FetchArray($result);
		return ($num['AnzahlUser']);
	}


	/**
	* CountRoles()
	*/
	public function CountRoles()
	{
		$result = $this->database->ExecuteQuery("SELECT COUNT(*) AS AnzahlRoles FROM role");
		$num = $this->FetchArray($result);
		return ($num['AnzahlRoles']);
	}


	/**
	* CountBans()
	* macht in der Form nicht unbedingt so viel Sinn. => besser: auf einen bestimmten Zeitraum beziehen
	*/
	public function CountBans()
	{
		$result = $this->database->ExecuteQuery("SELECT COUNT(*) AS AnzahlBan FROM ban");
		$num = $this->FetchArray($result);
		return ($num['AnzahlBan']);
	}


	/**
	* CountArticles()
	*/
	public function CountArticles()
	{
		$result = $this->database->ExecuteQuery("SELECT COUNT(*) AS AnzahlArticle FROM article");
		$num = $this->FetchArray($result);
		return ($num['AnzahlArticle']);
	}


	/**
	* CountPages()
	*/
	public function CountPages()
	{
		$result = $this->database->ExecuteQuery("SELECT COUNT(*) AS AnzahlPages FROM page");
		$num = $this->FetchArray($result);
		return ($num['AnzahlPages']);
	}


	/**
	* CountTemplates()
	*/
	public function CountTemplates()
	{
		$result = $this->database->ExecuteQuery("SELECT COUNT(*) AS AnzahlTemplates FROM template");
		$num = $this->FetchArray($result);
		return ($num['AnzahlTemplates']);
	}




	/* ENDE: --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- für die Startseite: Statistik für Admin --- --- --- --- --- --- --- --- --- */

	/**
	* SelectAllLogs()
	*/
	public function SelectAllLogs()
	{
		return $this->database->ExecutePreparedStatement("selectAllLogs", array());
	}


	/**
	* SelectOneLogById()
	* @params int $logtableId the id of the logtable
	*/
	public function SelectOneLogById($logtableId)
	{
			return $this->database->ExecutePreparedStatement("selectOneLogById", array($logtableId));
	}



	/**
	* SelectAllLogsFromOneUserByUsername()
	* @params string $logtableUsername is the user who changed something => the user who is responsible for the new log in the logtable
	*/
	public function SelectAllLogsFromOneUserByUsername($logtableUsername)
	{
			return $this->database->ExecutePreparedStatement("selectAllLogsFromOneUserByUsername", array($logtableUsername));
	}



	/**
	* SelectAllLogsFromASpecialDateByLogdate()
	* @params string $logtableLogdate
	*/
	public function SelectAllLogsFromASpecialDateByLogdate($logtableLogdate)
	{
			return $this->database->ExecutePreparedStatement("selectAllLogsFromASpecialDateByLogdate", array($logtableLogdate));
	}





	/* --- ENDE --- --- --- --- --- --- --- --- --- --- --- --- --- für die Startseite: Statistik für Admin --- --- --- --- --- --- --- --- ENDE --- */






	/**
	* EmailAlreadyExists()
	* @params string $email the user's email
	* checks whether the email adress already exists in database or not
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
	* UsernameAlreadyExists()
	* @params string $username the user's name
	* checks whether the username already exists in database
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
	* registrateUser()
	* @params string $username the user's username
	* @params string $firstname the user's firstname
	* @params string $lastname the user's lastname
	* @params string $mail the user's mailaddress
	* @params string $password the user's password
	* @params string $birthdate the user's birthdate as date formatted string
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
					$password= $this->database->RealEscapeString($password);

					$result = $this->database->ExecuteQuery("INSERT INTO user (id, role_id, lastname, firstname, birthdate, username, password, email, registrydate) VALUES (NULL, ".$role_id.", '".$lastname."', '".$firstname."', '".$birthdate."', '".$username."', '".$password."', '".$email."', NOW())");

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
	* deleteUserById()
	* Delets a particular User
	* @params int $userId the user's Id
	*/
	public function DeleteUserById($userId)
	{
		$logDeletedUser = $this->FetchArray($this->GetUserInformationById($userId))['username'];
		$result = $this->database->ExecutePreparedStatement("deleteUserById", array($userId));

		if($result==true)
		{
			$logUsername = $_SESSION['username'];
			$logRolename = $_SESSION['rolename'];
  		$logDescription = 'Folgender User wurde gelöscht: <strong>'.$logDeletedUser.'</strong>';
			$this->database->InsertNewLog($logUsername, $logRolename, $logDescription);
			return true;
		}
		else
		{
			 return false;
		}
	}


	/**
	* deleteUserByUsername()
	* Delets a particular User by a name
	* @params int $userId the user's Id
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
	* GetUserInformationById()
	* @params int $userId the id of the user
	*/
	public function GetUserInformationById($userId) /*oder:SelectUserById() als anderen Namen?*/
	{
			return $this->database->ExecutePreparedStatement("selectUserById", array($userId));
	}


	/**
	* GetUserInformationByUsername()
	* @params string $username the role's name
	*/
	public function GetUserInformationByUsername($username) /*oder:SelectUserByUserName() als anderen Namen?*/
	{
		 return	$this->database->ExecutePreparedStatement("selectUserByUsername", array($username));
	}


	/**
	* DeleteRole()
	* Delets a particular Role
	* @params int $roleId the role's Id
	*/
	public function DeleteRole($roleId)
	{
		$logDeletedRolename = $this->FetchArray($this->SelectRoleById($roleId))['rolename'];
		$result = $this->database->ExecutePreparedStatement("deleteRole", array($roleId));

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


	/**
	* AssignRole()
	* Assigns a chosen role to a particular user
	* @params int $roleId the role's Id
	* @params int $userId the user's Id
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
	* NewRole()
	* creates a new role
	* @params int $uri the role's uri
	* @params string $rolename the name of the role
	* @params bool $guestbookmanagement
	* @params bool $usermanagement
	* @params bool $pagemanagement
	* @params bool $articlemanagement
	* @params bool $guestbookusage
	* @params bool $templateconstruction
	* @params bool $databasemanagement
	* @params bool $backendlogin
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
	* SelectRoleById()
	* @params int $roleId the role's Id
	*/
	public function SelectRoleById($roleId)  /*oder: GetRoleInfoById() als anderen Namen?*/
	{
		return $this->database->ExecutePreparedStatement("selectRole", array($roleId));
	}


	/**
	* SelectRoleByRolename()
	* Selects a particular Role
	* @params string $rolename the role's name
	*/
	public function SelectRoleByRolename($rolename) /*oder: GetRoleInfoByRolename() als anderen Namen?*/
	{
		return $this->database->ExecutePreparedStatement("selectRoleByRolename", array($rolename));
	}


	/**
	* UpdateRoleById()  => @Jonas: vorher: SaveRoleChanges()
	* saves role changes
	* @params int $uri the role's uri
	* @params string $rolename the name of the role
	* @params bool $guestbookmanagement
	* @params bool $usermanagement
	* @params bool $pagemanagement
	* @params bool $articlemanagement
	* @params bool $guestbookusage
	* @params bool $templateconstruction
	* @params bool $databasemanagement
	* @params bool $backendlogin
	*/
	public function UpdateRoleById($uri, $rolename, $guestbookmanagement, $usermanagement, $pagemanagement, $articlemanagement, $guestbookusage, $templateconstruction, $databasemanagement, $backendlogin, $id)
	{
		$rolenameBevoreUpdate =  $this->FetchArray($this->SelectRoleById($id))['rolename'];
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


	/**
	* UpdateUserDifferentNamesById => @Jonas: vorher: ApplyChangesToUser()
	* saves role changes
	* @params string $lastname the user's lastname
	* @params string $lastname the user's firstname
	* @params string $lastname the user's username
	* @params string $lastname the user's email
	* @params int $id user's id
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
	* SelectUserByEmail()
	* @params string $lastname the user's email
	*/
	public function SelectUserByEmail($email)
	{
			return $this->database->ExecutePreparedStatement("selectUserByEmail", array($email));
	}



	/**
	* SelectAllUsers()
	*/
	public function SelectAllUsers()
	{
		return $this->database->ExecutePreparedStatement("selectAllUsers", array());
	}


	/**
	* SelectAllRoles()
	*/
	public function SelectAllRoles()
	{
		return $this->database->ExecutePreparedStatement("selectAllRoles", array());
	}








	/**
	* IsUserBannedId()
	* checks via userid if the user is banned
	* @params int $userId the user's id
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
	*  IsUserBannedUsername()
	* checks via username if the user is banned
	* @params string $username the user's username
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
	*  InsertBanViaUserId()
	* @params int $user_id the user's id
	* @params int $reason_id the banreasons's id
	* @params string $description the ban's description
	* @params datetime $begindatetime the ban's begindatetime
	* @params datetime $enddatetime the ban's enddatetime
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
	*  DebanUserViaBanId()
	* checks via username if the user is banned
	* @params int $id the ban's id
	*/
	public function DebanUserViaBanId($id)
	{
		$result = $this->database->ExecuteQuery("UPDATE ban SET enddatetime = NOW() WHERE id = ". $id);

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
	*  SelectBanByUserid()
	* select a special Ban by an user's id
	* @params int $id the users's id
	*/
	public function SelectBanByUserid($user_id)
	{
		return $this->database->ExecutePreparedStatement("selectBanByUserid", array($user_id));
	}


	/**
	*  InsertBan_Reason()
	* creates a new  ban_reason
	* @params string $reason the reason of a ban_reason
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
	*  SelectAllBan()
	*/
	public function SelectAllBan()
	{
		return $this->database->ExecutePreparedStatement("selectAllBan", array());
	}


	/**
	*  SelectAllBan_Reason()
	*/
	public function SelectAllBan_Reason()
	{
		return $this->database->ExecutePreparedStatement("selectAllBan_Reason", array());
	}


	/**
	* SelectAllBansFromAUserByUsername($username)
	* @params string $username the user's username
	*/
	public function SelectAllBansFromAUserByUsername($username)
	{
	 	return $this->database->ExecutePreparedStatement("selectAllBansFromAUserByUsername", array($username));
	}



	/**
	* SelectAllUsersWhichAreBannedNow ()
	*/
	public function SelectAllUsersWhichAreBannedNow()
	{
		return $this->database->ExecutePreparedStatement("selectAllUsersWhichAreBannedNow", array());
	}




	/**
	* SelectAllUsersWhoAreBannedNowForASpecialReasonByReason()
	* @params string $reason the ban_reasons's reason
	*/
	public function SelectAllUsersWhoAreBannedNowForASpecialReasonByReason($reason)
	{
	return $this->database->ExecuteQuery("SELECT * FROM user INNER JOIN  ban ON  user.id = ban.user_id INNER JOIN ban_reason ON ban.reason_id = ban_reason.id WHERE ( ban.enddatetime > now() AND ban_reason.reason = '". $password."')");
	}



	/**
	* ApplyPasswordChangesToUser()
	* @params string $userId the user's id
	* @params string $password the user's password
	* @params string $newPassword the user's new password
	* @params string $newPasswordRepeat the user's the repetition of new password
	*/
	public function ApplyPasswordChangesToUser($userId, $password, $newPassword, $newPasswordRepeat)
	{
		//$password = $this->database->RealEscapeString($password);

		echo $userId;
		$result = $this->database->ExecuteQuery("SELECT password FROM user WHERE id ='".$userId."'");

		var_dump($result);

		//echo $result;

		if ($result == $password)
		{
			if($newPassword == $newPasswordRepeat)
			{
				$changePassword = $this->database->ExecuteQuery("UPDATE user SET password = '.$newPassword.'");
				return true;
			}

			return false;
		}
		else
		{

			return false;
		}

	}




	/**
	* FetchArray()
	* @params string $result is the result of an query
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
	*  GetUserPermissionByUsername()
	* @params string $username the user's username
	*/
	public function GetUserPermissionByUsername($username)
	{
		return $this->database->ExecutePreparedStatement("getUserPermissionByUsername", array($username));
	}



	/**
	*  WhichRoleHasASpecialUser()
	* @params string $username the user's username
	*/
	public function WhichRoleHasASpecialUser($username)
	{
		return $this->database->ExecutePreparedStatement("whichRoleHasASpecialUser", array($username));
	}





	/**
	* loginUser()
	* @params string $nameInput the user's username or mail
	* @params string $password the user's password
	* @result true if login was successfull otherwise false
	*/
	public function LoginUser($nameInput, $password)
	{
		$nameInput = $this->database->RealEscapeString($nameInput);

		//$stmt = $this->database->ExecuteQuery("SELECT password FROM user WHERE email =".$nameInput." OR username =".$nameInput);

		// Fehlende '' eingefügt -> jetzt gibts auch keine Fehler mehr
		$result = $this->database->ExecuteQuery("SELECT id FROM user WHERE (email ='".$nameInput."' OR username ='".$nameInput."') AND password = '". $password."'");

		// Rückgabe prüfen => ist der Datensatz auch wirklich vorhanden? Ist es genau EIN Datensatz, der zurück kommt?
		// Quellcode dafür ist implementiert, ich habs hier mal als Beispiel auch umgesetzt
		if($result==true && $this->database->GetResultCount($result) == 1)
		{ return true; }
		else
		{ return false;	}
	}



		/**
		* selectRolenameByUsername ()
		* @params string $username the user's username
		*/
		public function SelectRolenameByUsername($username)
		{
				return $this->database->ExecutePreparedStatement("selectRolenameByUsername ", array($username));
		}


		/**
		* SelectBan_ReasonById()
		* @params int $id the ban_ReasonId's Id
		*/
		public function SelectBan_ReasonById($id)
		{
			return $this->database->ExecutePreparedStatement("selectBan_ReasonById", array($id));
		}

}
?>
