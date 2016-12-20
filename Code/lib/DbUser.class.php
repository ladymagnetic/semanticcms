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
	 public function __construct($dsn)
  	{
	   $this->database = new DbEngine($dsn);
	   $this->prepare_sql();
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
	private function Prepare_sql()
	{
		// put your queries here
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
	public function RegistrateUser()
	{
		//if(!DoesUserAlreadyExist) ...
		$stmt = $mysqli->prepare("INSERT INTO user(username, fistname, lastname, mail, password, birthdate) VALUES (?, ?, ?, ?, ?, ?)");
		$stmt->bind_param('ssssss', $username, $firstname, $lastname, $mail, $password, $birthdate);
							/*Vorsicht => Datentyp für birthdate, es gibt nur integer, double, string, blob
								http://php.net/manual/de/mysqli-stmt.bind-param.php
							*/
		$stmt->execute();
		$stmt->close();
	}

	/**
	* loginUser()
	* @params string $nameInput the user's username or mail
	* @params string $password the user's password
	* @result User User object if login was successfull otherwise // false / 0 wie auch immer => entscheidnug bei Implementierung
	*/
	public function LoginUser($nameInput, $password)
	{
			$stmt = $mysqli->query("SELECT password FROM user WHERE email =" $nameInput "OR username =" $nameInput);

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
	}

	//same function than "Ban()"?
	public function BanUser($userId)
	{
	}
		//same function than "Deban()"?
	public function DebanUser($userId)
	{
	}
	public function DeleteUser($userId)
	{
		$stmt = $mysqli->prepare("DELETE FROM user WHERE id="$userId);
		$stmt->bind_param('i', $_POST['userId']);
		$stmt->execute();
		$stmt->close();
	}

	public function CreateUser()
	{
		return $userId;
	}


	// assignes an existing role to an existing user (user-object given from User.class)
	public function NewRole($roleId, $userId)
	{
		$stmt = $mysqli->prepare("UPDATE User SET role_id = ? WHERE user.id =" $userId);
		$stmt->bind_param("i", $_POST[$roleId]);
		$stmt->execute();
		$stmt->close();
	}

	// creats new role by given attributes (booleans)
	public function DefineRole($rolename, $guestbookmanagement, $usermanagement, $pagemanagement, $articlemanagement, $guestbookusage, $templateconstruction)
	{
		//Check rolename if exists
		$result = mysqli->prepare("SELECT role.rolename FROM Role WHERE rolename = " $rolename);
		$result->bind_param('s', $_POST['rolename']);
		$result->execute();

				if($result != $rolename)
				{
					$rolename 						= $_POST['rolename']);
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
	public function DeleteRole($roleId)
	{
		$stmt = $mysqli->prepare("DELETE FROM role WHERE id="$roleId);
		$stmt->bind_param('i', $_POST['roleID']);
		$stmt->execute();
		$stmt->close();
	}
	// has to save role permissions from "Benutzerverwaltung.php" -> Conny: which role should be saved? A new role or a new assigned role?
	public function SaveRoleChanges()
	{
	}
}

// return users as rows
public function GetUsers()
{
	$sql = "SELECT id, role_id, lastname, firstname, username, password, email FROM user";
	// $db->query($sql) as $row)
	return;
}

// return roles as rows
public function GetRoles()
{
	$sql = "SELECT id, name FROM role";
	return;
}

// return rolerights as rows
public function GetRoleRights()
{

}

public function CheckIfUserIsUnlocked($userId)
{
	return true/false;
}

// has to save the changes of the user
public function ApplyChangesToUser($userId, $userName, $name, $foreName, $email)
{

}

// has to save the changes for the passwords of the user
public function ApplyPasswordChangesToUser($userId, $password, $newPassword, $newPasswordRepeat)
{
		// check if password correct --> change of password with newPassword else no change
}

public function GetUserInformation($userId)
{
	$sql = "SELECT username, firstname, lastname, email FROM user";
}

?>
