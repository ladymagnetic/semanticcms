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

		// => kommt noch:
		//$selectUserByUsernameOrEmail = "SELECT * FROM user WHERE ( username = ? OR email = ? )";
		//$this->database->PrepareStatement("selectUserByUsernameOrEmail ", $selectUserByUsernameOrEmail );


		$selectAllUsers = "SELECT * FROM user";
		$this->database->PrepareStatement("selectAllUsers", $selectAllUsers);

		$selectAllRoles = "SELECT * FROM role";
		$this->database->PrepareStatement("selectAllRoles", $selectAllRoles);

		$selectAllArticles = "SELECT * FROM article";
		$this->database->PrepareStatement("selectAllArticles", $selectAllArticles);

		$selectAllTemplates = "SELECT * FROM template";
		$this->database->PrepareStatement("selectAllTemplates", $selectAllTemplates);

		$selectAllPages = "SELECT * FROM page";
		$this->database->PrepareStatement("selectAllPages", $selectAllPages);
			
		$selectAllBan_Reason = "SELECT * FROM ban_reason";
		$this->database->PrepareStatement("selectAllBan_Reason", $selectAllBan_Reason);

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
		
		

	}

 




// gibt true zurück wenn email-adresse bereits existiert (User mit dieser E-Mailadresse gibt es schon!).
public function EmailAlreadyExists($email)
{
	$result = $this->database->ExecuteQuery("SELECT * FROM user WHERE email = '".$email."'");

	if($result==true && $this->database->GetResultCount($result) > 0)
	{
		return true;
	}
	else
	{
		return false;
	}

}



public function UsernameAlreadyExists($username)
{
	$result = $this->database->ExecuteQuery("SELECT * FROM user WHERE username = '".$username."'");

	 if($result==true && $this->database->GetResultCount($result) > 0)
	 {
		 return true;
	 }
	 else
	 {
		 return false;
	 }
}



/* eventuell für die Prüfung des Datums beim Registrieren eines User
http://www.selfphp.de/kochbuch/kochbuch.php?code=17
function check_date($date,$format,$sep)
{

    $pos1    = strpos($format, 'd');
    $pos2    = strpos($format, 'm');
    $pos3    = strpos($format, 'Y');

    $check    = explode($sep,$date);

    return checkdate($check[$pos2],$check[$pos1],$check[$pos3]);

}
*/



	/**
	* registrateUser()
	* @params string $username the user's username
	* @params string %firstname the user's firstname
	* @params string $lastname the user's lastname
	* @params string $mail the user's mailaddress
	* @params string $password the user's password
	* @params string $birthdate the user's birthdate as date formatted string
	*/
		public function RegistrateUser($role_id, $lastname, $firstname, $username, $password, $email, $birthdate)
	{
		/*
		INSERT INTO user VALUES (NULL, 2, "Muster", "Johanna", "jojo20", "password1234" , "j.m@web.de" , NOW(), "1996-08-16");
		INSERT INTO user VALUES (NULL, 2, "Muster", "Johanna", "jojo20", "password1234" , "j.m@web.de" , NOW(), "19960816")
		*/


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
		$result = $this->database->ExecutePreparedStatement("deleteUserById", array($userId));

		//var_dump($result); // TESTEN! => und dann die Abfrage evtl. anpassen.

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
	* deleteUserByUsername()
	* Delets a particular User by a name
	* @params int $userId the user's Id
	*/
	public function DeleteUserByUsername($userId)
	{
		$result = $this->database->ExecutePreparedStatement("deleteUserByUsername", array($userId));

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
		$result = $this->database->ExecutePreparedStatement("deleteRole", array($roleId)); // HIER: neu

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
	*/
	public function NewRole($uri, $rolename, $guestbookmanagement, $usermanagement, $pagemanagement, $articlemanagement, $guestbookusage, $templateconstruction)
	{
		$result = $this->database->ExecuteQuery("INSERT INTO role (id, uri, rolename, guestbookmanagement, usermanagement, pagemanagement, articlemanagement, guestbookusage, templateconstruction) VALUES (NULL, '".$uri."', '".$rolename."', ".$guestbookmanagement.", ".$usermanagement.", ".$pagemanagement.", ".$articlemanagement.", ".$guestbookusage.",  ".$templateconstruction.")");

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
	*/
	public function UpdateRoleById($uri, $rolename, $guestbookmanagement, $usermanagement, $pagemanagement, $articlemanagement, $guestbookusage, $templateconstruction, $id)
	{
		$result = $this->database->ExecuteQuery("UPDATE role SET uri ='".$uri."',  rolename ='".$rolename."',  guestbookmanagement ='".$guestbookmanagement."',  usermanagement ='".$usermanagement."', pagemanagement ='".$pagemanagement."', articlemanagement ='".$articlemanagement."', guestbookusage ='".$guestbookusage."' , templateconstruction ='".$templateconstruction."' WHERE id = '". $id."'");
		
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
	* UpdateUserDifferentNamesById => @Jonas: vorher: ApplyChangesToUser()
	* saves role changes
	* @params string $lastname the user's lastname
	* @params string $lastname the user's firstname
	* @params string $lastname the user's username
	* @params string $lastname the user's email
	* @params int $id user's id
	*/
	public function UpdateUserDifferentNamesById($lastname, $firstname, $username, $email, $userId)
	{
			$result = $this->database->ExecuteQuery("UPDATE user SET lastname ='".$lastname."',  firstname ='".$firstname."',  username ='".$username."',  email ='".$email."' WHERE id = '". $userId."'");	

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
	
	
	/**
	* SelectAllPages()
	*/
	public function SelectAllPages()
	{
		return $this->database->ExecutePreparedStatement("selectAllPages", array());
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
			echo "User ist gerade gesperrt";
			return true;
		}
		else
		{
			echo "User ist nicht gesperrt.";
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
	// Datum überprüfen
		$description = $this->database->RealEscapeString($description);
		$result = $this->database->ExecuteQuery("INSERT INTO ban (id, user_id, reason_id, description, begindatetime, enddatetime) VALUES (NULL, ".$user_id.", ".$reason_id.", '".$description."', '".$begindatetime."', '".$enddatetime."')");
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
	public function InsertBan_Reason($reason)
	{
		$reason = $this->database->RealEscapeString($reason);
		$result = $this->database->ExecutePreparedStatement("insertBan_Reason", array($reason));

		//var_dump($result);

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
		// Beispiel: SELECT * FROM ban INNER JOIN user ON ban.user_id = user.id WHERE username = 'M'
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
	
	
	
	
	/** => wird noch überarbeitet
	* SelectAllUsersWhoAreBannedNowForASpecialReasonById()
	* @params int $reason_id the ban's reason_id
	
	public function SelectAllUsersWhoAreBannedNowForASpecialReasonById($reason_id)
	{
		return $this->database->ExecutePreparedStatement("selectAllUsersWhoAreBannedNowForASpecialReasonById", array($reason_id));
	}
	*/
	
	
	
	


	/*Conny => wichtige Funktionen*/
	/*
	CheckIfEmailIsCorrect
	CheckIfUsernameIsCorrect
	HashPassword
	DecodePassword
	*/







	/* ab hier noch die alten => werden noch überarbeitet*/



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

	 
	 	 


	// has to save the changes for the passwords of the user
	// Jonas
	// speichert die Passwortänderung
	// am besten nur, wenn das password mit dem Passwort des Users mit userId übereinstmmt und newPassword == newPasswordRepeat
	public function ApplyPasswordChangesToUser($userId, $password, $newPassword, $newPasswordRepeat)
	{
			// check if password correct --> change of password with newPassword else no change
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
