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

		$selectRoleByRolename = "SELECT * FROM role WHERE name = ?";
		$this->database->PrepareStatement("selectRoleByRolename", $selectRoleByRolename);

	//	$updateRole = "UPDATE role SET " => Mirjam: fuer SaveRoleChanges() => kommt noch!
	//	$this->database->PrepareStatement("updateRole", $updateRole);




	}

/*Maybe other required function DoesUserAlreadyExist($username, $email)*/
/*
public function DoesUserAlreadyExist($username, $email)
{
	if(yes) return true;
	else return false;
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
		*/


		/*Prüfungen der Eingaben => Benutzername, Email-Adresse, Ist das ein Datum?,  ect.*/
		$this->database->ExecutePreparedStatement("registrateUser", array($role_id, $lastname, $firstname, $username, $password, $email, $birthdate));
	}


	/**
	* deleteUserById()
	* Delets a particular User
	* @params int $userId the user's Id
	*/
	public function DeleteUserById($userId)
	{
		$this->database->ExecutePreparedStatement("deleteUserById", array($userId));
	}


	/**
	* deleteUserByUsername()
	* Delets a particular User by a name
	* @params int $userId the user's Id
	*/
	public function DeleteUserByUsername($userId)
	{
		$this->database->ExecutePreparedStatement("deleteUserByUsername", array($userId));
	}


	/**
	* GetUserInformationById()
	* @params int $userId the id of the user
	*/
	public function GetUserInformationById($userId) /*oder:SelectUserById() als anderen Namen?*/
	{
			$this->database->ExecutePreparedStatement("selectUserById", array($userId));
	}


	/**
	* GetUserInformationByUsername()
	* @params string $username the role's name
	*/
	public function GetUserInformationByUsername($username) /*oder:SelectUserByUserName() als anderen Namen?*/
	{
			$this->database->ExecutePreparedStatement("selectUserByUsername", array($username));
	}


	/**
	* DeleteRole()
	* Delets a particular Role
	* @params int $roleId the role's Id
	*/
	public function DeleteRole($roleId)
	{
		$this->database->ExecutePreparedStatement("deleteRole", array($roleId)); // HIER: neu
	}


	/**
	* AssignRole()
	* Assigns a chosen role to a particular user
	* @params int $roleId the role's Id
	* @params int $userId the user's Id
	*/
	public function AssignRole($roleId, $userId)
	{
		$this->database->ExecutePreparedStatement("assignaRole", array($roleId, $userId));
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
		 $this->database->ExecutePreparedStatement("newRole", array($uri, $rolename, $guestbookmanagement, $usermanagement, $pagemanagement, $articlemanagement, $guestbookusage, $templateconstruction));
	}


	/**
	* SelectRoleById()
	* @params int $roleId the role's Id
	*/
	public function SelectRoleById($roleId)  /*oder: GetRoleInfoById() als anderen Namen?*/
	{
		$this->database->ExecutePreparedStatement("selectRole", array($roleId));
	}


	/**
	* SelectRoleByRolename()
	* Selects a particular Role
	* @params string $rolename the role's name
	*/
	public function SelectRoleByRolename($rolename) /*oder: GetRoleInfoByRolename() als anderen Namen?*/
	{
			$this->database->ExecutePreparedStatement("selectRoleByRolename", array($rolename));
	}





	/* ab hier noch die alten => werden noch überarbeitet*/



	/**
	* loginUser()
	* @params string $nameInput the user's username or mail
	* @params string $password the user's password
	* @result User User object if login was successfull otherwise // false / 0 wie auch immer => entscheidnug bei Implementierung
	*/
	public function LoginUser($nameInput, $password)
	{
		echo "Hallo Funktion LoginUser wird aufgerufen :)</br>";


			$nameInput = $this->database->RealEscapeString($nameInput);

			//$stmt = $this->database->ExecuteQuery("SELECT password FROM user WHERE email =".$nameInput." OR username =".$nameInput);
			$stmt = $this->database->ExecuteQuery("SELECT id FROM user WHERE (email =".$nameInput." OR username =".$nameInput.") AND password = ". $password);

			// Rückgabe prüfen => ist der Datensatz auch wirklich vorhanden? Ist es genau EIN Datensatz, der zurück kommt?

/*
			if($stmt == $password)
			{
				echo "hallo ich bin richtig";
				return true;
			}
			else
			{
				echo "hallo ich bin falsch";
				return false;
			}
*/

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



	// speichert alle Informationen der Rolle
	public function SaveRoleChanges($roleId, $rolename, $guestbookmanagement, $usermanagement, $pagemanagement, $articlemanagement, $guestbookusage, $templateconstruction)
	{
		/*Mirjam: Wird noch überarbeitet.

		//Check rolename if exists
		$result = $mysqli->prepare("SELECT role.rolename FROM Role WHERE rolename = ".$rolename);
		$result->bind_param('s', $_POST['rolename']);
		$result->execute();

				if($result != $rolename)
				{
					$rolename 						= $_POST['rolename'];
					$guestbookmanagement  = $_POST['guestbookmanagement'];
					$usermanagement	  		 = $_POST['usermanagement'];
					$pagemanagement 		  = $_POST['pagemanagement'];
					$articlemanagement 	  = $_POST['articlemanagement'];
					$guestbookusage			  = $_POST['guestbookusage'];
					$templateconstruction = $_POST['templateconstruction'];

					$stmt = $mysqli->prepare("INSERT INTO role(rolename, guestbookmanagement, usermanagement, pagemanagement, articlemanagement, guestbookusage, templateconstruction) VALUES (?,?,?,?,?,?,?)");
					$stmt->bind_param("sbbbbbb",
									 $rolename,
									 $guestbookmanagement,
									 $usermanagement,
									 $pagemanagement,
									 $articlemanagement,
									 $guestbookusage,
									 $templateconstruction);
					$stmt->execute();
					$stmt->close();

				}
				*/
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
		return true/false;
	}

	// muss die Informationen des Users (angegebene SQL) zurückgeben
	public function GetUserInformation($userId)
	{
		//$sql = "SELECT username, firstname, lastname, email FROM user";
		//$sql = "SELECT username, firstname, lastname, email, password FROM user";
	}

	// has to save the changes of the user
	// Jonas: speichert die angegebenen Werte in den user mit $userId
	public function ApplyChangesToUser($userId, $userName, $name, $foreName, $email)
	{

	}

	// has to save the changes for the passwords of the user
	// Jonas
	// speichert die Passwortänderung
	// am besten nur, wenn das password mit dem Passwort des Users mit userId übereinstmmt und newPassword == newPasswordRepeat
	public function ApplyPasswordChangesToUser($userId, $password, $newPassword, $newPasswordRepeat)
	{
			// check if password correct --> change of password with newPassword else no change
	}


}

?>
