<?php
//start the session
session_start();
ob_start();

//if not logged in, re-direct to login.php

//include any libraries/classes needed
function __autoload($className) {
	require_once 'classes/' . $className . '.class.php';
}


// start the page
echo Page::header("Admin Page");
//for the actual project you might want to check access level at this point

// add navigation
echo Page::addNav();

//output the session variables and cookies

echo Page::footer();

ob_end_flush();
?>