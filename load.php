<?php
//start the session
session_start();
// start output buffering
ob_start();

//include any libraries/classes needed
function __autoload($className) {
	require_once 'classes/' . $className . '.class.php';
}

//if not logged in, re-direct to login.php
if (!Utils::isLoggedIn()) {
	header("Location: login.php");
}

// start the page
echo Page::header("load.php");

// add navigation
echo Page::addNav();

// variables for the submit filename form
$submitFileName = "Get Table Info";
$submitFieldAssoc = "Load Data";

// add content
// display a title for the page
echo "<h1>Import Data</h1>";

// variable to see if we passed form validation
$passed = FALSE;
// error strings
$errors = "";

// check what to display

// the required fields
$filenameFormReqFields = array("table", "filename", "delimiter");

// check to see if the form was submitted
if (isset($_GET['submit']) && !empty($_GET['submit']) && $_GET['submit'] == $submitFileName) {
	// form was submitted

	// check that the required fields were submitted
	if (Utils::arrayContainsVals($_GET, $filenameFormReqFields)) {
		$tableName = $_GET['table'];
		$fileName = $_GET['filename'];
		$delim = $_GET['delimiter'];
		$hasHeaderRow = false;

		// checks if they check the checkbox
		if (Utils::arrayContainsVals($_GET, array("hasheaders")) && $_GET['hasheaders'] == "on") {
			// if so, then they said it has a header row
			$hasHeaderRow = true;
		}

		//check if file exists
		if (file_exists($fileName)) {
			// display the associate field form
			echo addAssociateFieldForm($tableName, $fileName, $delim, $hasHeaderRow);
		} else {
			// ERROR
			$errors .= "<p>File does not exist</p>";
		}
	} else {
		// ERROR: missing one or more required fields
		$errors .= "<p>Missing one or more required fields</p>";
	}
}

// display errors
if (!empty($errors)) {
	// display errors
	echo $errors;
}

// if we didnt pass either of the two forms
if (!$passed) {
	// display the choose filename form
	echo addChooseFileForm();
}

// end the page
echo Page::footer();

// flush out the output
ob_end_flush();
?>

<?php
/**
 * Grabs the column names from the specified table, then tries to associate the
 * fields to columns in the file. The file is split up based on the delim used
 *
 */
function addAssociateFieldForm($tableName, $fileName, $delim, $hasHeaderRow = false) {
	echo "<p>hey, we made it to associate fields</p>";
	
	// pull the column names from the table
	
	// use the delim to load the file in a array
	
	// use the header row to check if we can
	
	// build the field select
	
	// build the form
}

function addChooseFileForm() {
	global $submitFileName;
	$result = "";

	// add the first form
	$result .= "<form method=\"get\" >\n";

	// add table select
	$tables = array("cms_banner", "cms_news", "cms_editorial");
	$result .= "<label for=\"table\">Table Name: </label>\n";
	$result .= Form::buildSelect($tables, "table");

	$result .= "<br />\n";

	// add the file name
	$result .= "<label for=\"filename\">File Name: </label>\n";
	$result .= "<input name=\"filename\" size=\"30\"></input>\n";

	$result .= "<br />\n";

	// add the delimiter name
	$result .= "<label for=\"delimiter\">Delimiter: </label>\n";
	$result .= "<input name=\"delimiter\" size=\"5\" value=\",\"></input>\n";

	$result .= "<br />\n";

	// add the header checkbox
	$result .= "<input type=\"checkbox\" name=\"hasheaders\"/>";
	$result .= "<label for=\"hasheaders\">File has a Header row?</label>";

	$result .= "<br />\n";

	// add the get info submit button
	$result .= "<input type=\"submit\" name=\"submit\" value=\"$submitFileName\"/>\n";

	$result .= "</form>\n";

	return $result;
}
?>

