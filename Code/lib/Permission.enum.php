<?php
/* namespace */
namespace SemanticCms\Model;

/**
* Provides an enumeration replacement for the user permissions.
*/
abstract class Permission
{
	const $Guestbookusage = 0;
	const $Articlemanagment = 1;
	const $Pagemanagment = 2;
	const $Templateconstruction = 3;
	const $Guestbookmanagment = 4;	
	const $Usermanagment = 5;
}
?>