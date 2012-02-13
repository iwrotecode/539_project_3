<?php
//start the session

//if not logged in, re-direct to login.php

//include any libraries/classes needed

echo Page::header("Admin Page");
//for the actual project you might want to check access level at this point

echo Page::navigation();

//output the session variables and cookies

echo Page::footer();
?>