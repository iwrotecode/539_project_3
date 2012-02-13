<?php

class Form {

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
	static function buildSelect($values, $name = null, $texts = null, $id = null, 
	$class = null, $multiple = null, $size = null) {
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
			if(!is_null($name) && is_string($name)){
				$result .= " name=\"$name\"";
			}
			// add the id
			if(!is_null($id) && is_string($id)){
				$result .= " name=\"$id\"";
			}
			// add the $class
			if(!is_null($class) && is_string($class)){
				$result .= " name=\"$class\"";
			}
			// add multiple
			if(is_bool($multiple) && $multiple){
				$result .= " multiple=\"multiple\"";
			}
			// add the size
			if(is_numeric($size)){
				$result .= " size=\"$size\"";
			}
			
			$result .= ">\n";
			
			// loop thru and create the options
			for ($i = 0; $i < $numOptions; $i++) {
				
				$result .= "\t\t<option value=\"".$values[$i]."\">".$texts[$i]."</option>\n";
			}

			// end the select
			$result .= "\t</select>\n";
		}

		return $result;
	}

}
?>