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

$styles = array("css/styles.css");

// start the page
echo Page::header("load.php");

// add navigation
echo Page::addNav();

// add content container div
echo "<div id='content_container'>";

// variables for the submit fileName form
$submitFileName = "Initialize table";
$submitFieldAssoc = "Import data";

// add content
// display a title for the page
echo "<h1>Import Data</h1>";

// variable to see if we passed form validation
$passed = FALSE;
// error strings
$errors = "";

// if we didnt pass either of the two forms
if (!$passed) {
	// display the choose fileName form
	echo addChooseFileForm();
}

// the required fields
$reqFields = array("tableName", "fileName", "delimiter");

// check to see if the form was submitted
if (isset($_GET['submit']) && !empty($_GET['submit'])) {
	if ($_GET['submit'] == $submitFileName) {
		// file form was submitted, need to build the file associat

		// check that the required fields were submitted
		if (Utils::arrayContainsVals($_GET, $reqFields)) {

			// start processing of getting table info
			$result = processGetTableInfo();
			$errors .= (!empty($result) ? "<p>" . processGetTableInfo() . "</p>" : "");

			// $passed = true;

		} else {
			// ERROR: missing one or more required fields
			$errors .= "<p>Missing one or more required fields</p>";
		}
	} else if ($_GET['submit'] == $submitFieldAssoc) {

		// make sure the required fields were passed
		// add extra required field
		$reqFields[] = "fieldnames";

		if (Utils::arrayContainsVals($_SESSION, $reqFields)) {

			// start processing for import
			$result = processImport();
			$errors .= (!empty($result) ? "<p>" . processGetTableInfo() . "</p>" : "");

			// they passed
			$passed = true;
		}
	}
}

// display errors
if (!empty($errors)) {
	// display errors
	echo <<<END
		<div class="error_message">
			$errors
		</div>

END;
}

// close content container div
echo "</div>";

// end the page
echo Page::footer();

// flush out the output
ob_end_flush();
?>

<?php

function processGetTableInfo() {
	$errors = "";
	$tableName = $_GET['tableName'];
	$fileName = Utils::getLoadFileLoc() . "/" . $_GET['fileName'];
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

	return $errors;
}

function processImport() {
	$error = "";

	echo "<div class='content'>";

	// get all the field names
	$fieldNames = $_SESSION['fieldnames'];
	// get the tableaName
	$tableName = $_SESSION['tableName'];
	// get the file
	$fileName = $_SESSION['fileName'];
	// get the delimiter
	$delim = $_SESSION['delimiter'];
	// grab the has headers
	$hasHeaderRow = $_SESSION['hasHeaders'];

	echo "<h1>Results of Import to $tableName</h1>";

	if (!empty($fieldNames) && !empty($tableName) && !empty($fileName) && !empty($delim)) {
		// get the records from the file
		$records = getRecords($fileName, $delim);

		// grab the field name associations from the GET array
		$fieldAssoc = array();

		// setup array for insertions
		foreach ($fieldNames as $field) {
			$fieldAssoc[$field] = $_GET[$field];
		}

		// perform insertions
		// grab the list of fields
		$fields = implode(",", $fieldNames);

		// build the parameterized query
		$query = "insert into " . $_SESSION['tableName'] . " ($fields) values(";
		// make the question marks
		for ($i = 0, $len = count($fieldNames); $i < $len; $i++) {
			// add a question mark for the field
			$query .= "?";
			// if not the last field
			if ($i < $len - 1) {
				// add a comma
				$query .= ",";
			}
		}
		// close the query
		$query .= ")";

		$start = intval($hasHeaderRow);
		$end = count($records);

		$db = Database::getInstance();

		$colInfo = $db -> getColInfo($tableName);
		
		// TODO: Delete test
		// var_dump($colInfo);

		// start inserting the records
		for ($i = $start; $i < $end; $i++) {
			// grab a record, which is an array of the columns from a single line
			$record = $records[$i];

			// setup a data array
			$data = array();
			// setup types array
			$types = array();

			// build the data and types array
			foreach ($fieldNames as $field) {
				// for each field grab the perspective column
				$col = $fieldAssoc[$field];

				// make sure the col is not empty and a number
				if ($field != "pubdate" && strlen($col) > 0) {
					$col = intval($col);

					$item = trim($record[$col]);

					// change the formatting for pubdate
					if ($field == "pubdate") {
						$item = Form::getSQLDateTime($item);
					}

					$data[$field] = $item;
				} else {
					// since the string was empty
					// just insert a blank 
					$data[$field] = "";
				}

				// get the type
				$types[$field] = Form::getParamType($colInfo[$field]['Type']);
				
			}
			

			// insert into array
			// $db = Database::getInstance();
			// $queryError = $db->doQuery($query, $data, $types);

			if (empty($queryError)) {
				echo "<p>Record $i was added!</p>";
			} else {
				echo "<p>Record $i could not be added! Reason: $queryError</p>";
			}

			// $error .= $queryError;

		}
	} else {
		$error .= "Something went wrong, missing one or more required fields";
	}

	echo "</div>";

	return $error;
}

function importData($tableName, $fileName, $delim, $hasHeaderRow) {

}

function getRecords($fileName, $delim) {
	$records = null;

	// check if fileName is a file, and is readable
	if (file_exists($fileName) && is_readable($fileName)) {
		// use the delim to load the file in a array
		$lines = Utils::return_file_as_array($fileName);

		$records = array();
		// go thru the lines of the file
		foreach ($lines as $line) {
			// for each line, explode it based on the delim and add the array to records
			$records[] = explode($delim, $line);
		}
	}

	return $records;
}

/**
 * Grabs the column names from the specified table, then tries to associate the
 * fields to columns in the file. The file is split up based on the delim used
 *
 */
function addAssociateFieldForm($tableName, $fileName, $delim, $hasHeaderRow = false) {
	// pull the column names from the table
	// get database instance
	$db = Database::getInstance();
	$fieldNames = $db -> getColNames($tableName);
	// returns an array

	// use the delim to load the file in a array
	$records = getRecords($fileName, $delim);

	// get the number of columns
	$numColumns = count($records[0]);

	// use the header row to check if we can
	$headers = array();
	$values = array();

	// fill the headers for the select
	if ($hasHeaderRow) {
		foreach ($records[0] as $headerField) {
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
	array_unshift($values, "");

	// ******************* BUILD THE FORM ***********************
	// build the form
	$result = buildFieldAssocForm($tableName, $fileName, $delim, $hasHeaderRow, $fieldNames, $headers, $values);

	return $result;
}

/**
 * Builds the form that allows the user to associate fields to columns in their
 * data file.
 */
function buildFieldAssocForm($tableName, $fileName, $delim, $hasHeaderRow, $fieldNames, $headers, $values) {
	global $submitFieldAssoc;
	$result = "";
	// ******************* BUILD THE FORM ***********************

	// build the form
	$result = "<div class='content_results' >\n";
	// add a header
	$result .= "<h2>Import ".basename($fileName)." to $tableName</h2>";
	
	$result .= "<form method=\"get\">\n";

	// instead of hidden elements, lets build session variables to store essential info
	$_SESSION['tableName'] = $tableName;
	$_SESSION['fileName'] = $fileName;
	$_SESSION['delimiter'] = $delim;
	$_SESSION['hasHeaders'] = $hasHeaderRow;
	$_SESSION['fieldnames'] = $fieldNames;

	// build the field select
	// the name should be the field name, then the value should be the column number
	foreach ($fieldNames as $field) {
		// enclose in container
		$result .= "<div class='form_field_rfloat'>";

		// build the label
		$result .= "<label for='$field'>$field</label>";

		// build the select
		$result .= Form::buildSelect($values, $field, $headers, null, "fileAssocSelect");

		$result .= "</div>";
	}

	// add the reset button
	$result .= "<div class='form_field_rfloat'>";
	$result .= "<input type='reset' />";

	// add the get info submit button
	$result .= "<input type=\"submit\" name=\"submit\" value=\"$submitFieldAssoc\"/>\n";

	$result .= "</div>\n";
	$result .= "</form>\n";
	$result .= "</div>\n";

	return $result;
}

/**
 * creates form that allows users to choose which data file to upload to which table,
 * as well as what the delimeter is.
 */
function addChooseFileForm() {
	global $submitFileName;
	$result = "";

	// add the first form
	$result .= "<div class='content'>\n";
	$result .= "<form method=\"get\" >\n";

	// add table select
	$tables = array("cms_banner", "cms_news", "cms_editorial");
	$result .= "<div class='form_field_rfloat'>";

	$result .= "<label for=\"tableName\">Table Name: </label>\n";
	$result .= Form::buildSelect($tables, "tableName");
	$result .= "</div>\n";

	// add the file name select
	// get the file names in the load data directory
	$fileNames = Utils::getFileNames(Utils::getLoadFileLoc());

	$result .= "<div class='form_field_rfloat'>";
	$result .= "<label for=\"fileName\">File Name: </label>\n";
	$result .= Form::buildSelect($fileNames, "fileName");
	$result .= "</div>\n";

	// add the header checkbox
	$result .= "<div class='form_field_rfloat'>";
	$result .= "<label for=\"hasHeaders\">File contains header row</label>";
	$result .= "<input type=\"checkbox\" name=\"hasheaders\"/>";
	$result .= "</div>\n";

	// add the delimiter name
	$result .= "<div class='form_field_rfloat'>";
	$result .= "<label for=\"delimiter\">Delimiter: </label>\n";
	$result .= "<input name=\"delimiter\" size=\"5\" value=\"|\" style='width:.5em;'></input>\n";

	// add the reset button
	// $result .= "<input type='reset' />";

	// add the get info submit button
	$result .= "<input type=\"submit\" name=\"submit\" value=\"$submitFileName\"/>\n";
	$result .= "</div>\n";

	$result .= "</form>\n";
	$result .= "</div>\n";

	return $result;
}
?>