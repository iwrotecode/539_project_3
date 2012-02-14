<?php
//start the session
session_start();
ob_start();

//include any libraries/classes needed
function __autoload($className) {
	require_once 'classes/' . $className . '.class.php';
}

//if not logged in, re-direct to login.php
if(!Utils::isLoggedIn()){
	header("Location: login.php");
}

// start the page
echo Page::header("Admin Page");
//for the actual project you might want to check access level at this point

// add navigation
echo Page::addNav();

//output the session variables and cookies
echo "<p>Session Vars</p>";
foreach ($_SESSION as $k => $v) {
	echo "$k=$v<br />";
}

echo "<p>Cookies</p>";
foreach ($_COOKIE as $k => $v) {
	echo "$k=$v<br />";
}

echo Page::footer();

ob_end_flush();
?>