<?php
	require_once("classes/page.class.php");
	require_once("classes/form.class.php");

	$styles = "css/nav.css";

	// start the page
	echo Page::header("load.php", $styles);
	
	// add navigation
	echo Page::addNav();
	
	// add content
	
	// add table select
	$tables = array("cms_banner", "cms_news", "cms_editorial");
	echo Form::buildSelect($tables, "table");
	
	// end the page
	echo Page::footer();
?>

