<?php
//start the session
session_start();
ob_start();

//if not logged in, re-direct to login.php
if (!Utils::isLoggedIn()) {
	header("Location: ../login.php");
}

//include any libraries/classes needed
function __autoload($className) {
	require_once '../classes/' . $className . '.class.php';
}

// sets header for text/xml
header('Content-type: text/xml');

// calls function to display news XML
echo Table::displayXML("SELECT * FROM cms_ads WHERE approved = 1 ORDER BY pubdate DESC LIMIT ?, ?", "cms_ads");

ob_end_flush();
?>