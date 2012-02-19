<?php
//start the session
session_start();
ob_start();

//if not logged in, re-direct to login.php
if (!Utils::isLoggedIn()) {
	header("Location: login.php");
}

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
// echo "<p><strong>Session Vars</strong></p>";
// foreach ($_SESSION as $k => $v) {
	// echo "$k=$v<br />";
// }
// 
// echo "<p><strong>Cookies</strong></p>";
// foreach ($_COOKIE as $k => $v) {
	// echo "$k=$v<br />";
// }
echo "<div id='content_container'>";

echo "<h1>Administration</h1>";

echo Table::displayDBTables();

echo "</div>";

echo Page::footer();

ob_end_flush();
?>