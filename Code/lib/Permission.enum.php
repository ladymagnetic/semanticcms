<?php
/**
* Contains the abstract class Permission.
* @author Tamara Graf
*/

/* namespace */
namespace SemanticCms\Model;

/**
* Provides an enumeration replacement for the user permissions.
* @author Tamara Graf
*/
abstract class Permission
{
	const Guestbookusage = 0;
	const Backendlogin = 1;
	const Articlemanagement = 2;
	const Pagemanagement = 3;
	const Templateconstruction = 4;
	const Guestbookmanagement = 5;
	const Usermanagement = 6;
	const Databasemanagement = 7;
}
?>