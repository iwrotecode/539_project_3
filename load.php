<?php
require_once ("classes/page.class.php");
require_once ("classes/form.class.php");

$passed = false;

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
	echo "<form>\n";

	// add table select
	$tables = array("cms_banner", "cms_news", "cms_editorial");
	echo "<label for=\"table\">Table Name: </label>";
	echo Form::buildSelect($tables, "table");

	// add the file name
	echo "<label for=\"filename\">File Name: </label>";
	echo "<input name=\"filename\" size=\"30\"></input>";

	// add the delimiter name
	echo "<label for=\"filename\">Delimiter: </label>";
	echo "<input name=\"delimiter\" size=\"5\"></input>";

	// add the get info submit button
	echo "<input type=\"submit\" value=\"Get Table Info\"/>";

	echo "</form>\n";
} else {
	// since they passed, build the other form
}

// end the page
echo Page::footer();
?>

