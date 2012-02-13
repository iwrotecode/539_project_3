<?php
//start the session
session_start();
ob_start();

//include any required libraries/classes
function __autoload($className) {
	require_once 'classes/' . $className . '.class.php';
}

//check to see if already logged in, if so, re-direct to admin.php

//check form submission and if valid
if (isset($_POST['submit']) && !empty($_POST['submit'])) {
	if (isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['password']) && !empty($_POST['password'])) {

		// everything was passed, save the variables
		$username = $_POST['username'];
		$password = $_POST['password'];

		// check login credentials in Database
		//don't forget to sha1(inputted password) when building your parameterized query

		// get database instance
		$db = Database::getInstance();

		$query = "SELECT username, password FROM cms_user WHERE username = ?";
		$vars = array($username);
		$types = array("s");

		// perform the query
		$db -> doQuery($query, $vars, $types);

		// get the stored password
		$records = $db -> fetch_all_array();

		// makes sure only one record was passed back
		if (count($records) == 1) {
			$record = $records[0];
			// get the password
			$dbPassword = $record['password'];

			// check login credentials in Database
			//don't forget to sha1(inputted password) when building your parameterized query
			if ($dbPassword == sha1($password)) {
				//if valid login credentials, create appropriate session variables and cookies, then
				//redirect to admin.php
				
				// get session var
				$sessionValue = Utils::getSessionVarValue();
				$sessionVar = Utils::getSessionVar();
				
				// set session
				$_SESSION[$sessionVar] = $sessionVar;
				
			} else {
				//if invalid login credentials, create error message
				echo "<p>Incorrect username or password.</p>\n";
				
			}

		} else {

		} // if count recs == 1
	} else {
		//if missing information, create error message
		echo "<p>Please enter a username and Password</p>\n";
	} // if credentials are there

}// if submit is correct

// start the page
echo Page::header("login.php");

// add navigation
echo Page::addNav();

//display any messages

//create and display form

echo <<<END
<form id="form1" name="form1" method="post" action="login.php">
			<div>
				<label for="name">User Name:</label>
				<input type="text" name="username" id="name" />
			</div>
			<div>
				<label for="name">Password:</label>
				<input type="password" name="password" id="password" />
			</div>
			<div>
				<input type="submit" name="submit" value="Submit" />
			</div>
		</form>
END;

// end the page
echo Page::footer();

ob_end_flush();
// end session
session_destroy();
?>