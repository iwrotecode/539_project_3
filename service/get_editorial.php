<?php
//start the session
session_start();
// start output buffering
ob_start();

//include any libraries/classes needed
function __autoload($className) {
	require_once '../classes/' . $className . '.class.php';
}

// returns the current editorial content. Get the lastest current one.

// grab an instance of the database connection
$db = Database::getInstance();

// build the query to get the editorial
$query = "select content from cms_editorial where current=1 order by current DESC, pubdate DESC limit 0, 1";
// execute the query
$db -> doQuery($query);

// grab the result
$content = $db -> fetch_array();

if ($content) {
	// grab the first element (there should only be one anyways)
	echo(array_pop($content));
}

// flush out the output
ob_end_flush();
?>