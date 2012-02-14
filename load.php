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

$styles = array("css/load.css");

// start the page
echo Page::header("load.php", $styles);

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
		$fileName = Utils::getLoadFileLoc() . "/" . $_GET['filename'];
		$delim = $_GET['delimiter'];
		$hasHeaderRow = false;

		// checks if they check the checkbox
		if (Utils::arrayContainsVals($_GET, array("hasheaders")) && $_GET['hasheaders'] == "on") {
			// if so, then they said it has a header row
			$hasHeaderRow = true;
		}

		//check if file exists
		if (file_exists($fileName)) {
			$passed = true;
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

function importData($tableName, $fileName, $delim, $hasHeaderRow){
	
}

/**
 * Grabs the column names from the specified table, then tries to associate the
 * fields to columns in the file. The file is split up based on the delim used
 *
 */
function addAssociateFieldForm($tableName, $fileName, $delim, $hasHeaderRow = false) {
	echo "<p>hey, we made it to associate fields</p>";

	// pull the column names from the table
	// get database instance
	$db = Database::getInstance();
	$fieldNames = $db -> getColNames($tableName);
	// returns an array
	
	// use the delim to load the file in a array
	$lines = Utils::return_file_as_array($fileName);

	$records = array();
	// go thru the lines of the file
	foreach ($lines as $line) {
		// for each line, explode it based on the delim and add the array to records
		$records[] = explode($delim, $line);
	}

	// get the number of columns
	$numColumns = count($records[0]);

	// use the header row to check if we can
	$headers = array();
	$values = array();
	
	// fill the headers for the select
	if ($hasHeaderRow) {
		foreach($records[0] as $headerField){
			$headers[] = $headerField;
		}
	} else {
		for ($i = 0; $i < $numColumns; $i++) {
			// build the headers
			$headers[] = "col $i";
			
			// build the values array
			$values[] = $i;
		}
	}
	
	// fill the values array, since it wasnt done for the headers
	if ($hasHeaderRow) {
		for ($i = 0; $i < $numColumns; $i++) {
			$values[] = $i;
		}
	}
	
	// prepend both arrays with a dummy field
	array_unshift($headers, "--none--");
	array_unshift($values, "na");
	
	// ******************* BUILD THE FORM ***********************
	
	// build the form
	$result = "<form method=\"get\">\n";
	
	// add a hidden field for the table name and the file name, add if they have a 
	// field header
	$result .= "<input type='hidden' name='table' value='$tableName' />";
	$result .= "<input type='hidden' name='filename' value='$fileName' />";
	$result .= "<input type='hidden' name='delimiter' value='$delim' />";
	$result .= "<input type='hidden' name='hasheaders' value='$hasHeaderRow' />";
	
	// build the field select
	// the name should be the field name, then the value should be the column number
	foreach($fieldNames as $field){
		// build the label
		$result .= "<label for='$field'>$field</label>";	
					
		// build the select
		$result .= Form::buildSelect($values, $field, $headers, null, "fileAssocSelect");
	}
	
	$result .= "</form>\n";
	
	return $result;
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

	// add the file name select
	// get the file names in the load data directory
	$fileNames = Utils::getFileNames(Utils::getLoadFileLoc());

	$result .= "<label for=\"filename\">File Name: </label>\n";
	// $result .= "<input name=\"filename\" size=\"30\"></input>\n";
	$result .= Form::buildSelect($fileNames, "filename");

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

