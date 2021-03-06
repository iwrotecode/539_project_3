<?php

class Utils {
	private static $sessionVar = "cms_session";

	// Cookie variables
	// TODO: Might need this for NOVA
	// private static $path = "/539_project_3/";
	// private static $domain = "localhost";
	// private static $secure = false;
	private static $daysExpire = 3;

	private static $adminLevel = 1;

	// variables for loading data
	private static $loadFileLoc = "load_data";

	static function getAdminLevel() {
		return self::$adminLevel;
	}

	/**
	 * updloads the file to the specified directory
	 *
	 * @param file An associative array attained from the $_FILES global array.
	 * Expecting to have the associative keys specified from the $_FILES globabl array.
	 *
	 * @param location the location to move the file
	 *
	 * @return an error string if there were any errors
	 */
	static function uploadFile($file, $location) {
		$errors = "";
		
		// check if there was an error
		if (isset($file['error']) && $file['error'] > 0) {
			// there was an error
			$errors .= "<p>There was an error with the file upload, falling back 
						on the filename choosen in the select.</p>";
		} else {
			// no errors, proceed with upload validation
			if (($file["type"] == "text/plain") || ($file["type"] == "application/vnd.ms-excel") && ($file["size"] < 20000)) {
				// upload to the correct location
				// grab the name
				$name = $file["name"];
				// grab the tmp name
				$tmpName = $file['tmp_name'];
				// grab the new location
				$loc = $location . $name;
				// move to new loaction
				move_uploaded_file($name, $loc);
			} else {
				$errors .= "<p>File uploaded was not a .txt, or larger than 20kb</p>";
			}
		}
		
		return $errors;
	}

	static function getColNames($conn, $tableName) {
		$cols_return = array();
		$cols = mysql_query("SHOW COLUMNS FROM $tableName", $conn);
		if ($cols) {
			while ($col = mysql_fetch_assoc($cols)) {
				$cols_return[] = $col['Field'];
			}
		}
		return $cols_return;
	}

	static function getColInfo($conn, $tableName) {
		$cols_return = array();
		$cols = mysql_query("SHOW COLUMNS FROM $tableName", $conn);
		if ($cols) {
			while ($col = mysql_fetch_assoc($cols)) {
				$cols_return[] = $col;
			}
		}
		return $cols_return;
	}

	static function fileExists($url) {
		//use curl to get status code 404 to make sure file exists
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_NOBODY, true);
		curl_exec($ch);
		$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		//$status_code contains the HTTP status: 200, 404, etc.
		return $status_code;
	}

	//use curl for remote fopen when not allowed
	static function get_contents($url) {
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		// DO NOT RETURN HTTP HEADERS
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		// RETURN THE CONTENTS
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
		$Rec_Data = curl_exec($ch);
		return $Rec_Data;
	}

	static function getLoadFileLoc() {
		return self::$loadFileLoc;
	}

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
	 * Defaults are specified in the class as properties
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

	/**
	 * Make sure that the keys specified exist in the array and its value is not empty
	 *
	 * @param $array - associative array to check
	 * @param keys - keys to look for in the array
	 *
	 * @return true if the keys exist and its value is not empty
	 */
	static function arrayContainsVals($array, $keys) {
		$result = true;

		foreach ($keys as $key) {
			if (!isset($array[$key]) || empty($array[$key])) {
				$result = false;
				break;
			}
		}

		return $result;
	}

	static function return_file_as_array($path) {
		if (file_exists($path) && is_readable($path)) {
			return @file($path, FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
		} else {
			die("<strong>Problem loading file at #path!</strong>");
		}
	}

	/**
	 * returns an array containing the files contained in the directory.
	 *
	 * Null if the specified dir is not a directory
	 */
	static function getFileNames($dir) {
		$result = null;

		if (is_dir($dir) && ($handle = opendir($dir))) {
			$result = array();

			// while we can read a file
			while (false !== ($entry = readdir($handle))) {
				// skip over unix directories
				if ($entry != "." && $entry != "..") {
					// add entry
					$result[] = $entry;
				}
			}
			closedir($handle);
		}

		return $result;
	}

	// detects if string is sha1'd
	static function is_sha1($str) {
		$status = preg_match('/^[0-9a-f]{40}$/i', $str);

		return $status;
	}

	// gets access level of logged in user
	static function getAccessLevel() {
		// set default return value
		$value = 0;

		if (isset($_SESSION['username'])) {
			$username = $_SESSION['username'];

			$sql = "SELECT access FROM cms_user WHERE username = '$username'";

			// get instance
			$db = Database::getInstance();
			// perform query
			$db -> doQuery($sql);

			// get result of query
			$access = $db -> fetch_array();

			// if we get a result set of 1
			if (!is_null($access) && is_array($access) && count($access) == 1) {
				$value = array_pop($access);
			}
		}

		// return
		return $value;
	}

}
?>