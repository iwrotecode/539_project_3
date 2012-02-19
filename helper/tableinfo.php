<?php

//include any libraries/classes needed
function __autoload($className) {
	require_once '../classes/' . $className . '.class.php';
}

// get valid table names to build select
$db = Database::getInstance();
// get table names
$tableNames = $db -> getValidTableNames();

// build the select
$select = form::buildSelect($tableNames, "tableName");


echo <<<END
	<form>
		<label for="tableName">Value to hash</label>
END;

// echo the select
echo $select;

echo <<<END
		<input type="submit" name="submit" value="Get Table Info" />
		
	</form>
END;

// if the form was submitted
if (isset($_GET['submit']) && !empty($_GET['submit']) && isset($_GET['tableName']) && !empty($_GET['tableName'])) {
	// display the table info
	echo displayColInfo($_GET['tableName']);
}
?>

<?php

/**
 * Returns the html for a table consisting of the column info for the specified table
 */
function displayColInfo($tableName) {
	$result = "";

	//get a singleton instance of the database class
	$db = Database::getInstance();

	$colsInfo = $db -> getColInfo($tableName);
	

	if (!$colsInfo) {
		return "No Column Info was returned: " . $db -> getError();
	}

	$result .= "<h2>Column Info for: $tableName</h2>\n";

	// setup the table
	$result .= "<table border='1'>\n";

	// setup the table headers
	$result .= "\t<tr>\n";
	$result .= "\t\t<th>Column</th>\n";

	// var_dump(array_pop(array_slice($colsInfo,0,1)));

	// get the keys
	// by getting the first element, array
	// then get that arrays keys
	$keys = array_keys(array_pop(array_slice($colsInfo, 0, 1)));

	foreach ($keys as $key) {
		$result .= "\t\t<th>$key</th>\n";
	}

	$result .= "\t</tr>\n";

	// fill the table
	foreach ($colsInfo as $field => $colInfo) {
		$result .= "\t<tr>\n";
		$result .= "\t\t<td>$field</th>\n";
		foreach ($colInfo as $col) {
			$result .= "\t\t<td>" . $col . "</td>\n";
		}
		$result .= "\t</tr>\n";
	}

	// setup the table
	$result .= "</table>\n";

	return $result;
}
?>