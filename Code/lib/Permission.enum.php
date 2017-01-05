<?php
/* namespace */
namespace SemanticCms\Model;

/**
* Provides an enumeration replacement for the user permissions.
*/
abstract class Permission
{
	const Guestbookusage = 0;
	const Backendlogin = 1;
	const Articlemanagment = 2;
	const Pagemanagment = 3;
	const Templateconstruction = 4;
	const Guestbookmanagment = 5;
	const Usermanagment = 6;
	const Databasemanagement = 7;
}
?>