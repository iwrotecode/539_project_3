<?php

class Form {

	private static $fieldTypeAssoc = array("int" => "i", "text" => "b", "tinyint" => "i");

	// Sanitize data before validation
	static function sanitizeResults(&$results) {
		// sanitize everything in the results
		foreach ($results as &$result) {
			self::sanitize_data($result);
		}
	}

	/**
	 * Sanitizes the passed in value, and updates it
	 */
	static function sanitize_data(&$var) {
		echo "before";
		var_dump($var);

		// only need to sanitize strings
		if (is_string($var)) {
			$var = trim($var);
			$var = stripslashes($var);
			// $var = htmlentities($var);
			$var = strip_tags($var);
			$var = preg_replace('/\r\n/', '<br />', $var);
		}

		echo "after";
		var_dump($var);
	}

	// make sure if the results are valid
	static function validateResults(&$results, $tableName) {
		$errors = "";

		// validate everything

		// setup database connect
		$db = Database::getInstance();
		// get the col info
		$colInfo = $db -> getColInfo($tableName);

		foreach ($results as $key => &$result) {
			// get the type for this field
			$type = $colInfo[$key]['Type'];
			// get if nullable
			$nullable = ($colInfo[$key]['Null'] == "YES" ? true : false);

			// try to validate
			$error = self::validateField($key, $result, $type, $nullable);

			if (!empty($error)) {
				$errors .= "<p>" . $error . "</p>";
			}
		}

		return $errors;
	}

	/**
	 * Checks to see if the value is valid, based on the other field criteria.
	 * 3 step validation:
	 * 		1st - check if its allowed to be empty
	 * 		2nd - make sure it doesnt exceed maximum length
	 * 		3rd - make sure its the right type, if so convert to that type
	 *
	 *
	 * @param fieldName 	Used to specify which field was incorrect
	 * @param value 			Value to check validity, converted to match the type
	 * @param nullable		Determines if field is allowed to be null
	 *
	 * @return empty if there were no errors, something if there was
	 */
	static function validateField($fieldName, &$value, $type, $nullable) {
		// setup error message
		$error = "";

		// check if its allowed to be empty
		if (strlen($value) == 0 && !$nullable) {
			// display error saying this shouldnt be empty
			$error = "The field $fieldName is not allowed to be empty";
		} else {
			// proceed with length validation

			// grab the length - expecting something like: varchar(11)
			$start = stripos($type, "(") + 1;
			$end = stripos($type, ")");
			$maxLen = intval(substr($type, $start, $end - $start));

			// check if value length exceeds maximum length
			if ($maxLen != 0 && strlen($value) > $maxLen) {
				// exceeds length diplay errror
				$error = "The field $fieldName exceeds the maximum length of $maxLen";
			} else {
				// proceed with type validation

				// grab the type
				// check to see if there is a length passed
				$end = stripos($type, "(");

				// if not, then the end is the entire length
				if (!$end) {
					$end = strlen($type);
				}

				$type = substr($type, 0, $end);

				$errType = "";

				// switch based on type
				switch($type) {
					case "varchar" :
					case "text" :
					// check if its a string
						if (!is_string($value)) {
							$errType = "string";
						}
						break;

					case "int" :
					case 'tinyint' :
						if (is_numeric($value)) {
							// convert to integer
							$value = intval($value);
						} else {
							// this should have been an integer
							$errType = "number";
						}
						break;

					case "timestamp" :
					// Check if its a date time by converting to a unix time stamp
						if (strtotime($value)) {
							$value = self::getSQLDateTime($value);
						} else {
							$errType = "date and time value";
						}
						break;

					default :
						$errType = "Unexpected field type";
						break;
				}// switch

				if (!empty($errType)) {
					$error = "The field $fieldName should be a " . $errType;
				}

			} // else;
		}

		return $error;
	}

	/**
	 * Gets the string representation of the type for the mysqli bind param statement
	 *
	 * @param type the type specified by the table
	 */
	static function getParamType($type) {
		$result = "s";

		// get the field by getting text before parenthesis
		$field = substr($type, 0, stripos($type, "("));

		$array = self::$fieldTypeAssoc;

		if (isset($array[$field]) && !empty($array[$field])) {
			$result = $array[$field];
		}

		return $result;
	}

	/**
	 * Builds the textual representation of a select option element based on the
	 * passed in $values and $texts passed in. If no texts specified, will only
	 * the values as text
	 *
	 * @param values - an array of values to use for the options
	 * @param texts [optional] - an array of values to use for the options, must be
	 * same length as values
	 * @param name [optional]- the name for the select
	 * @param id [optional] - id for the select
	 * @param class [optional] - class for the select
	 * @param clas [optional] - boolean specifying if we allow multiple answers
	 * @param size [optional] - integer specifying the num items displayed
	 *
	 * @return null if there are any errors, the select element if everything goes
	 * well
	 */
	static function buildSelect($values, $name = null, $texts = null, $id = null, $class = null, $multiple = null, $size = null) {
		$result = null;

		if (is_array($values) && !empty($values)) {
			$result = "";

			// grab length of values
			$numOptions = count($values);

			// check if texts exist
			if (is_array($texts)) {
				// check if same length of values
				if (count($texts) != $numOptions) {
					// they aren't so leave the funtion
					return null;
				}

			} else {
				// make the texts equal the values
				$texts = $values;
			}

			// start the select
			$result .= "\t<select";
			// add the name
			if (!is_null($name) && is_string($name)) {
				$result .= " name=\"$name\"";
			}
			// add the id
			if (!is_null($id) && is_string($id)) {
				$result .= " id=\"$id\"";
			}
			// add the $class
			if (!is_null($class) && is_string($class)) {
				$result .= " class=\"$class\"";
			}
			// add multiple
			if (is_bool($multiple) && $multiple) {
				$result .= " multiple=\"multiple\"";
			}
			// add the size
			if (is_numeric($size)) {
				$result .= " size=\"$size\"";
			}

			$result .= ">\n";

			// loop thru and create the options
			for ($i = 0; $i < $numOptions; $i++) {

				$result .= "\t\t<option value=\"" . $values[$i] . "\">" . $texts[$i] . "</option>\n";
			}

			// end the select
			$result .= "\t</select>\n";
		}

		return $result;
	}

	/**
	 * Tries to get a date time from the passed in string.
	 * If it cannot, it passes back the current dateTime
	 */
	static function getSQLDateTime($date = "") {
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