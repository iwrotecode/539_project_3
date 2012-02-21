<?php
//start the session
session_start();
ob_start();

//include any libraries/classes needed
function __autoload($className) {
	require_once '../classes/' . $className . '.class.php';
}

// sets header for text/xml
header('Content-type: text/xml');

// calls function to display news XML
// display xml (query for ads, ads table, query for ads edition)
echo Table::displayXML("SELECT * FROM cms_ads WHERE approved = 1 ORDER BY pubdate DESC LIMIT ?, ?", "cms_ads", "SELECT ce.editionname FROM cms_edition ce, cms_ads_which_edition cawe WHERE cawe.edition_id = ce.id AND cawe.ads_id = ?");

ob_end_flush();
?>