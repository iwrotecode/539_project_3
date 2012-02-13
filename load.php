<?php
require_once ("classes/page.class.php");
require_once ("classes/form.class.php");

$passed = false;
var_dump($_GET);

$reqFields = array("table", "filename", "delimiter");

// check to see if the form was submitted
if(isset($_GET['submit'])&&!empty($_GET['submit'])){
	// form was submitted
	
	// check that the required fields were submitted
	
}

// start the page
echo Page::header("load.php");

// add navigation
echo Page::addNav();

// add content
// display a title for the page
echo "<h1>Import Data</h1>";

// check if they passed
if (!$passed) {

	// add the form
	echo "<form method=\"get\" >\n";

	// add table select
	$tables = array("cms_banner", "cms_news", "cms_editorial");
	echo "<label for=\"table\">Table Name: </label>\n";
	echo Form::buildSelect($tables, "table");
	
	echo "<br />\n";
	
	// add the file name
	echo "<label for=\"filename\">File Name: </label>\n";
	echo "<input name=\"filename\" size=\"30\"></input>\n";
	
	echo "<br />\n";
	
	// add the delimiter name
	echo "<label for=\"delimiter\">Delimiter: </label>\n";
	echo "<input name=\"delimiter\" size=\"5\" value=\",\"></input>\n";
	
	echo "<br />\n";
	
	// add the header checkbox
	echo "<input type=\"checkbox\" name=\"hasheaders\"/>";
	echo "<label for=\"hasheaders\">File has a Header row?</label>";

	echo "<br />\n";
	
	// add the get info submit button
	echo "<input type=\"submit\" name=\"submit\" value=\"Get Table Info\"/>\n";
	

	echo "</form>\n";
} else {
	// since they passed, build the other form
	
	// get the column names for the table
	
	
}

// end the page
echo Page::footer();
?>

