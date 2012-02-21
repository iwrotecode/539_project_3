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
echo Table::displayXML("SELECT * FROM cms_news ORDER BY pubDate DESC LIMIT ?, ?", "cms_news", "SELECT ce.editionname FROM cms_edition ce, cms_news_which_edition cnwe WHERE cnwe.edition_id = ce.id AND cnwe.news_id = ?");

ob_end_flush();
?>