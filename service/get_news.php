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
	require_once '../classes/' . $className . '.class.php';
}
// calls function to display news XML
echo Table::getNews();

echo Page::footer();

ob_end_flush();
?>