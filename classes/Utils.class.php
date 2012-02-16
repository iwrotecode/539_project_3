<?php

class Utils {
	private static $sessionVar = "cms_session";

	// Cookie variables
	// TODO: Might need this for NOVA
	// private static $path = "/539_project_3/";
	// private static $domain = "localhost";
	// private static $secure = false;
	private static $daysExpire = 3;

	// variables for loading data
	private static $loadFileLoc = "load_data";

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

}
?>