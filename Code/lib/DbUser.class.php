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
		$registrate = "INSERT INTO user (id, role_id, lastname, firstname, username, password, email, registrydate, birthdate)
				  VALUES (NULL, ?, ?, ?, ?, ? , ? , NOW(), ?);";

		$this->database->PrepareStatement("registrateUser", $registrate);

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

		$assignaRole = "UPDATE user SET role_id = ? WHERE id = ?";
		$this->database->PrepareStatement("assignaRole", $assignaRole);

		$newRole = "INSERT INTO role (id, uri, rolename, guestbookmanagement, usermanagement, pagemanagement, 	articlemanagement, 	guestbookusage, templateconstruction)
				  VALUES (NULL, ?, ?, ?, ?, ? , ? , ?, ?);";
		$this->database->PrepareStatement("newRole", $newRole);

		$selectRoleById = "SELECT * FROM role WHERE id = ?";
		$this->database->PrepareStatement("selectRole", $selectRoleById);

		$selectRoleByRolename = "SELECT * FROM role WHERE rolename = ?";
		$this->database->PrepareStatement("selectRoleByRolename", $selectRoleByRolename);

	// => Mirjam: fuer SaveRoleChanges()
	 	$updateRoleById = "UPDATE role SET uri = ?, rolename = ?, guestbookmanagement = ?, usermanagement = ?, pagemanagement = ?, articlemanagement = ?, guestbookusage = ?, templateconstruction = ?)
						WHERE id = ?";
		$this->database->PrepareStatement("updateRoleById", $updateRoleById);

		$updateUserDifferentNamesById= "UPDATE user SET lastname = ?, firstname = ?, username = ?, email= ? WHERE id = ?";
		$this->database->PrepareStatement("updateUserDifferentNamesById", $updateUserDifferentNamesById);

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

	}

/*Maybe other required function DoesUserAlreadyExist($username, $email)*/
/*
public function DoesUserAlreadyExist($username, $email)
{
	if(yes) return true;
	else return false;
}
*/





public function CheckIfValidEmail($email)
{
	return ( filter_var( $email, FILTER_VALIDATE_EMAIL ) !== false )? true : false;
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

		if(CheckIfValidEmail($email) == true)
		{
					if(CheckIfValidEmail($username) == false)
					{
							$lastname= $this->database->RealEscapeString($lastname);
							$firstname= $this->database->RealEscapeString($firstname);
							$username= $this->database->RealEscapeString($username);
							$password= $this->database->RealEscapeString($password);

							$result = $this->database->ExecutePreparedStatement("registrateUser", array(2, $lastname, $firstname, $username, $password, $email, $birthdate));

							var_dump($result); // TESTEN! => und dann die Abfrage evtl. anpassen.

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
			else
			{
				echo "Ungültige E-Mail-Adresse.";
				return false;
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
		$result = $this->database->ExecutePreparedStatement("assignaRole", array($roleId, $userId));

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
		 $result = $this->database->ExecutePreparedStatement("newRole", array($uri, $rolename, $guestbookmanagement, $usermanagement, $pagemanagement, $articlemanagement, $guestbookusage, $templateconstruction));
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
			$result = $this->database->ExecutePreparedStatement("updateRoleById", array($uri, $rolename, $guestbookmanagement, $usermanagement, $pagemanagement, $articlemanagement, $guestbookusage, $templateconstruction, $id));

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
	public function UpdateUserDifferentNamesById($lastname, $firstname, $username, $email, $id)
	{
			$result = $this->database->ExecutePreparedStatement("updateUserDifferentNamesById", array($lastname, $firstname, $username, $email, $id));

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

	/* werden heute noch alle kommentiert*/



	public function SelectAllUsers()
	{
		return $this->database->ExecutePreparedStatement("selectAllUsers", array());
	}


	public function SelectAllRoles()
	{
		return $this->database->ExecutePreparedStatement("selectAllRoles", array());
	}

	public function SelectAllArticles()
	{
		return $this->database->ExecutePreparedStatement("selectAllArticles", array());
	}


	public function SelectAllTemplates()
	{
		return $this->database->ExecutePreparedStatement("selectAllTemplates", array());
	}

	public function SelectAllPages()
	{
		return $this->database->ExecutePreparedStatement("selectAllPages", array());
	}






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
			if($result==true)
			{
				echo "hallo ich bin richtig";	// Dran denken das zu entfernen wenn nicht mehr gebraucht
				return true;
			}
			else
			{
				echo "hallo ich bin falsch";	// Dran denken das zu entfernen wenn nicht mehr gebraucht
				return false;
			}
	}

	//same function than "Ban()"?
	// Hinweis: Mirjam =>  Datenbankmodell muss morgen nochmals überarbeitet werden => hat aus Wirkung auf diese Funktion.
	// Jonas: ist das gleiche wie ban, also sperrung
	public function BanUser($userId)
	{
	}
	//Jonas: ist das gleiche wie deban, also sperrung
	// Hinweis: Mirjam =>  Datenbankmodell muss morgen nochmals überarbeitet werden => hat aus Wirkung auf diese Funktion.
		//same function than "Deban()"?
 	public function DebanUser($userId)
	{
	}


	// ???
	//Jonas: muss einen neuen user erzeugen und die id von diesem zurückgeben
	public function CreateUser()
	{
		return $userId;
	}



	/*Mirjam => ab hier: kommen noch.*/

	// return users as rows
	//Jonas:
	// muss alle user mit den angegebenen Werten bevorzugt als rows zurückgeben zur Auflistung in der Tabelle
	public function GetUsers()
	{
		//$sql = "SELECT id, role_id, lastname, firstname, username, password, email FROM user";
		// $db->query($sql) as $row)
		return;
	}

	// return roles as rows
	// Jonas: muss alle roles mit den angegebenen Werten bevorzugt als rows zurückgeben zur Auflistung in der Tabelle
	public function GetRoles()
	{
		//$sql = "SELECT id, name FROM role";
		return;
	}

	// Jonas: // checkt ob der user mit der userid gesperrt (gebannt) ist und gibt true oder false zurück
	public function IsUserBanned($userId)
	{
		return false;
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
