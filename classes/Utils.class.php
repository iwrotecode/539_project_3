<?php

class Utils {
	private static $sessionVar = "cms_session";

	// Cookie variables
	// TODO: Might need this for NOVA
	// private static $path = "/539_project_3/";
	// private static $domain = "localhost";
	// private static $secure = false;
	private static $daysExpire = 3;

	static function getSessionVarValue() {
		// get ip address
		$ip = $_SERVER['REMOTE_ADDR'];

		// get user agent
		$broswer = $_SERVER['HTTP_USER_AGENT'];

		// add salt
		$salt = "pedroANDmatt";

		return $salt . $ip . $broswer;
	}

	static function getSessionVar() {
		return self::$sessionVar;
	}

	/**
	 * Sets a cookie based on the passed in values.
	 *
	 * Defaults are sepecified in the class as properties
	 */
	static function setCookie($name, $value, $expire = null) {
		// , $path=null, $domain=null, $secure=null){

		// check if we have variables passed in, if not, use the defaults defined
		// in the class as properties
		// if(is_null($path)){
		// $path = self::$path;
		// }
		// if(is_null($domain)){
		// $domain = self::$domain;
		// }
		// if(is_null($secure)){
		// $secure = self::$secure;
		// }

		if (is_null($expire) || !is_int($expire)) {
			// seconds in a day: 86400
			$expire = (time() + 86400) * self::$daysExpire;
		}

		// set the cookie
		// return setcookie($name, $value, $expire/*, $path, $domain, $secure*/);
		return setcookie($name, $value, $expire);
	}

	/**
	 * Clears the specified cookie, and changes its expiration to the number of
	 * seconds specified.
	 *
	 * @param name - name of the cookie
	 * @param $expire [optional]- seconds to expire, default is 3 days ago
	 */
	static function expireCookie($name, $expire = null) {
		// if they specified a number for expire, then negate it if not already
		if (is_int($expire) && $expire > 0) {
			$expire = -$expire;
		}

		// expire the cookie and return
		return self::setCookie($name, "", $expire);
	}

	/**
	 * Checks to see if the user is logged.
	 *
	 * @return true if they are
	 */
	static function isLoggedIn() {
		$result = false;
		// get session var and value
		$sessionVar = Utils::getSessionVar();
		$sessionValue = Utils::getSessionVarValue();

		// check if it is set and equals the value
		if (isset($_SESSION[$sessionVar]) && ($_SESSION[$sessionVar] == $sessionValue)) {
			// they do, so redirect to admin.php
			$result = true;
		}

		return $result;
	}

	// grabs all tables in the DB, and returns a form to select them
	static function displayDBTables() {
		if (isset($_POST['selectDatabaseTable'])) {
			$tableName = $_POST['database_table'];
			self::generateEditTableSQL($tableName);
		}

		// grab all tables
		$db = Database::getInstance();
		$tableNames = $db -> getValidTableNames();

		// starts form to select table
		$string = '<form action="admin.php" name="select_database_table" method="post">';
		$string .= '<select name="database_table">';

		// loops through tables as a select
		foreach ($tableNames as $table) {
			$string .= "<option value=$table>$table</option>";
		}

		// closes form
		$string .= '</select>';
		$string .= '<input type="submit" name="selectDatabaseTable" value="Select" />';
		$string .= '</form>';

		return $string;
	}

	// runs the select all query on passed database table
	static function generateEditTableSQL($tableName) {
		$db = Database::getInstance();

		// initializes query
		$sql = "SELECT * FROM $tableName";

		// executes query
		$err = $db -> doQuery($sql);

		// grabs results of query
		$results = $db -> fetch_all_array();

		// calls function to display results in a table
		self::displayDBTableForm($results);
	}

	// formats database table output into a table
	static function displayDBTableForm($results) {
		// checks to see if database table has content
		if (!empty($results)) {
			// starts html table
			$string = "<form name='edit_database_table' action='admin.php' method='post'>";
			$string .= "<table border='1'>";
			$string .= "<tr>";
			
			// creates header for html table
			$header = array_keys(array_pop(array_slice($results, 0, 1)));
	
			// loops through database table for html table header elements
			foreach ($header as $header_element) {
				$string .= "<th>$header_element</th>";
			}
	
			$string .= "<th>actions</th>";
			$string .= "</tr>";
	
			// loops through database table for fields
			foreach ($results as $column => $field) {
				$string .= "<tr>";
	
				foreach ($field as $test => $fieldInfo) {
					$string .= "<td><input type='text' name='$field' value='$fieldInfo'></td>";
				}
				$string .= "<td><input type='submit' name='deleteDBRecord' value='Delete'/><input type='submit' name='modifyDBRecord' value='Modify'/></td>";
				$string .= "</tr>";
			}
	
			$string .= "</table>";
			$string .= "</form>";
	
			echo $string;
		
		// reports if no data in table is found
		} else {
			echo "<p>No data in database table</p>";
		}
	}

}
?>