<?php
	require_once("classes/page.class.php");

	$styles = "css/nav.css";

	// start the page
	echo Page::header("load.php", $styles);
	
	// add navigation
	echo Page::addNav();
	
	// add content
	
	// end the page
	echo Page::footer();
?>

