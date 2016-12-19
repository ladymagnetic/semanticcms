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
	// public function __construct($dsn)
	// {
	  // $this->database = new DbEngine($dsn);
	  // $this->prepare_sql();
	// }

	// public function __destruct()
	// {
	  // $this->database->__destruct();
	// }
	
	/* ---- Methods ---- */
	/**
	* prepare_sql()
	* Prepares the SQL statements
	*/
	private function Prepare_sql()
	{
		// put your queries here
	}
	
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
	}
	
	/**
	* loginUser()
	* @params string $nameInput the user's username or mail
	* @params string $password the user's password
	* @result User User object if login was successfull otherwise // false / 0 wie auch immer => entscheidnug bei Implementierung
	*/
	public function LoginUser()
	{
	}
	public function LockUser($userId)
	{
	}
	public function UnlockUser($userId)
	{
	}
	public function DeleteUser($userId)
	{
	}
	public function CreateUser()
	{
		return $userId;
	}
	// perhaps other class
	public function NewRole($roleId)
	{
	}
	public function DefineRole($roleId)
	{
	}
	public function DeleteRole($roleId)
	{
	}
	// has to save role permissions from "Benutzerverwaltung.php"
	public function SaveRoleChanges()
	{
	}	
}

?>