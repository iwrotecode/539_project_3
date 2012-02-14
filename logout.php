<?php
session_start();

//include any required libraries/classes
function __autoload($className) {
	require_once 'classes/' . $className . '.class.php';
}

unset($_SESSION);
session_destroy();

header("Location: login.php");
?>