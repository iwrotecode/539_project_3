<?php
//start the session
session_start();
ob_start();

//include any required libraries/classes
function __autoload($className) {
	require_once 'classes/' . $className . '.class.php';
}

$errors = "";

//check to see if already logged in, if so, re-direct to admin.php
// do this via the session var
// get session var and value
$sessionVar = Utils::getSessionVar();
$sessionValue = Utils::getSessionVarValue();

// check if it is set and equals the value
if (isset($_SESSION[$sessionVar]) && ($_SESSION[$sessionVar] == $sessionValue)) {
	// they do, so redirect to admin.php
	header("Location: admin.php");
} else{
	header("Location: login.php");
}

ob_end_flush();
?>