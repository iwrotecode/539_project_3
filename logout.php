<?php
session_start();

//include any required libraries/classes
function __autoload($className) {
	require_once 'classes/' . $className . '.class.php';
}

unset($_SESSION);
session_destroy();

// destroy the cookie
Utils::expireCookie("username");

// unset the cookie
unset($_COOKIE['username']);

header("Location: login.php");
?>