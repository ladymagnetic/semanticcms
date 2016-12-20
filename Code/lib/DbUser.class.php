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
	   $this->prepareSQL();
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
	private function prepareSQL()
	{
		$registrate = "INSERT INTO user (id, role_id, lastname, firstname, username, password, email, registrydate, birthdate)
				  VALUES (NULL, ?, ?, ?, ?, ? , ? , NOW(), ?);";

		$this->database->PrepareStatement("registrateUser", $registrate);

/*
		$deleteUser = "...."
		$this->database->PrepareStatement("deleteUser", $deleteUser);
*/
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
			$stmt = $this->database->ExecuteQuery("SELECT id FROM user WHERE (email =".$nameInput." OR username =".$nameInput.") AND password = ". $password));

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
	// Jonas: ist das gleiche wie ban, also sperrung
	public function BanUser($userId)
	{
	}
	//Jonas: ist das gleiche wie deban, also sperrung
		//same function than "Deban()"?
 public function DebanUser($userId)
	{
	}
	public function DeleteUser($userId)
	{
		/* aufbauen wie Registrieren  => siehe oben §deleteUser*/
		$stmt = $mysqli->prepare("DELETE FROM user WHERE id=".$userId);
		$stmt->bind_param('i', $_POST['userId']);
		$stmt->execute();
		$stmt->close();
	}
//Jonas: muss einen neuen user erzeugen und die id von diesem zurückgeben
	public function CreateUser()
	{
		return $userId;
	}


	// assignes an existing role to an existing user (user-object given from User.class)
	// neue blanke Rolle erstellen und die id davon zurückgeben
	public function NewRole($roleId)
	{

	}
	// Rolle zuweisen muss ich erst noch einbringen
	public function AssignRole($roleId, $userId)
	{
		$stmt = $mysqli->prepare("UPDATE User SET role_id = ? WHERE user.id =".$userId);
		$stmt->bind_param("i", $_POST[$roleId]);
		$stmt->execute();
		$stmt->close();
		return $roleId;
	}

	public function GetRoleInfo($roleId)
	{
		// alle Werte von role zurückgeben
	}

	// speichert alle Informationen der Rolle
	public function SaveRoleChanges($roleId, $rolename, $guestbookmanagement, $usermanagement, $pagemanagement, $articlemanagement, $guestbookusage, $templateconstruction)
	{
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
	}
	// Jonas:
	// Rolle mit roleId löschen
	public function DeleteRole($roleId)
	{
		$stmt = $mysqli->prepare("DELETE FROM role WHERE id=".$roleId);
		$stmt->bind_param('i', $_POST['roleID']);
		$stmt->execute();
		$stmt->close();
	}

// return users as rows
//Jonas:
// muss alle user mit den angegebenen Werten bevorzugt als rows zurückgeben zur Auflistung in der Tabelle
public function GetUsers()
{
	$sql = "SELECT id, role_id, lastname, firstname, username, password, email FROM user";
	// $db->query($sql) as $row)
	return;
}

// return roles as rows
// Jonas: muss alle roles mit den angegebenen Werten bevorzugt als rows zurückgeben zur Auflistung in der Tabelle
public function GetRoles()
{
	$sql = "SELECT id, name FROM role";
	return;
}

// Jonas: // checkt ob der user mit der userid gesperrt (gebannt) ist und gibt true oder false zurück
public function CheckIfUserIsUnlocked($userId)
{
	return true/false;
}


public function GetUserInformation($userId)
{
	$sql = "SELECT username, firstname, lastname, email FROM user";
}

// has to save the changes of the user
// Jonas: speichert die angegebenen Werte in den user mit $userId
public function ApplyChangesToUser($userId, $userName, $name, $foreName, $email)
{

}

// has to save the changes for the passwords of the user
// Jonas
// speichert die Passwortänderung
// am besten nur, wenn das password mit dem Passwort des Users mit userId übereinstmmt und newP
public function ApplyPasswordChangesToUser($userId, $password, $newPassword, $newPasswordRepeat)
{
		// check if password correct --> change of password with newPassword else no change
}

// Jonas// Holt die userInformation mit den aufgelisteten Werten des users mit userId zur Anzeige im Editiermodus des Users
public function GetUserInformation($userId)
{
	$sql = "SELECT username, firstname, lastname, email, password FROM user";
}





}

?>
