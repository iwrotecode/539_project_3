<?php
//start the session
session_start();
ob_start();

//include any required libraries/classes
function __autoload($className) {
	require_once 'classes/' . $className . '.class.php';
}

$errors = "";

//check to see if already logged in, if so, re-direct to admin.php
// do this via the session var
// get session var and value
$sessionVar = Utils::getSessionVar();
$sessionValue = Utils::getSessionVarValue();

// check if it is set and equals the value
if (isset($_SESSION[$sessionVar]) && ($_SESSION[$sessionVar] == $sessionValue)) {
	// they do, so redirect to admin.php
	header("Location: admin.php");
}

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

		$recCount = count($records);

		// makes sure only one record was passed back
		if ($recCount == 1) {
			$record = $records[0];
			// get the password
			$dbPassword = $record['password'];

			// check login credentials in Database
			//don't forget to sha1(inputted password) when building your parameterized query
			if ($dbPassword == sha1($password)) {
				//if valid login credentials, create appropriate session variables and cookies, then
				//redirect to admin.php

				// get session var and value
				$sessionVar = Utils::getSessionVar();
				$sessionValue = Utils::getSessionVarValue();
				
				// set session
				$_SESSION[$sessionVar] = $sessionValue;
				$_SESSION['username'] = $username;
				
				// set cookie
				$test = Utils::setcookie("username", $username);

				// redirect to admin.php
				header("Location: admin.php");

			} else {
				//if invalid login credentials, create error message
				$errors .= "<p>Incorrect username or password</p>\n";
			}

		} else if($recCount > 1){
			// more than one record was found
			$errors .= "<p>There was a database error <span>(more than one person with same username)</span></p>";
		} else{
			// $errors .= "<p>Username does not exist</p>";
			$errors .= "<p>Incorrect username or password</p>\n";
		}// if count recs == 1
	} else {
		//if missing information, create error message
		$errors .= "<p>Please enter a username and password</p>\n";
	} // if credentials are there

}// if submit is correct

// start the page
echo Page::header("login.php");

// add navigation
echo Page::addNav();

// add content container div
echo "<div id='content_container'>";

echo "<h1>Login</h1>";

//create and display form with containing div
echo '<div class="content">' . "\n";
echo '<form id="form1" name="form1" method="post" action="login.php">' . "\n";

// start container per input
echo "\t" . '<div class="form_field_rfloat">' . "\n";
// add label
echo "\t" . "\t" . '<label for="name" class="label">Username:</label>';
// add input
echo '<input type="text" name="username" id="name" class="text" ';
// grab cookie for username
if (isset($_COOKIE['username']) && !empty($_COOKIE['username'])) {
	// add the cookie as a default value
	echo 'value = "' . $_COOKIE['username'] . '"';
}
echo '/>' . "\n";

echo '<input type="submit" name="submit" value="Submit" class="button" />';

// close the div
echo "\t" . '</div>' . "\n";

// start container per input
echo "\t" . '<div class="form_field_rfloat">' . "\n";
// add label
echo "\t" . "\t" . '<label for="password" class="label">Password:</label>';
// add input
echo '<input type="password" name="password" id="password" class="text" />' . "\n";

echo '<input type="reset" name="reset" value="Reset" class="button" />';

// close the div
echo "\t" . '</div>' . "\n";

echo <<<END
	</form>
</div>

END;

// display error
if (!empty($errors)) {
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

ob_end_flush();
?>