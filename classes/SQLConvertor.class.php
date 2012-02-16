<?php

class SQLConvertor {
	/**
	 * Tries to get a date time from the passed in string.
	 * If it cannot, it passes back the current dateTime
	 */
	static function getSQLDateTime($date) {
		// convert to unix time stamp
		$dt = strtotime($date);

		// change to mySQL format-> YYYY-MM-DD HH:mm:SS
		$dt = @date("Y-m-d H:i:s", $dt);

		// if date failed, use current time
		if (!$dt) {
			$dt = @date("Y-m-d H:i:s", time());
		}

		return $dt;
	}

}
?>