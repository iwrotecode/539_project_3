<?php

class Table {

	// grabs all tables in the DB, and returns a form to select them
	static function displayDBTables() {
		// grab all tables
		$db = Database::getInstance();
		$tableNames = $db -> getValidTableNames();

		// starts form to select table
		$string = "<div class='content'>";
		$string .= '<form action="admin.php" method="get">';
		$string .= '<select name="database_table">';

		// loops through tables as a select
		foreach ($tableNames as $table) {
			$string .= "<option value=$table>$table</option>";
		}

		// closes form
		$string .= '</select>';
		$string .= '<input type="submit" name="selectDatabaseTable" value="Select" />';
		$string .= '</form>';
		$string .= "</div>";

		echo $string;

		if (isset($_GET['database_table'])) {
			$tableName = $_GET['database_table'];
			self::generateEditTableSQL($tableName);
		}
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
		self::displayDBTableForm($results, $tableName);
	}

	// formats database table output into a table
	static function displayDBTableForm($results, $tableName) {
		// checks if delete was sent
		if (isset($_POST['deleteDBTableRecord'])) {
			self::deleteDBTableRecord($results, $tableName);
		}

		// checks if modify was sent
		if (isset($_POST['modifyDBTableRecord'])) {
			// self::modifyDBTableRecord($results, $tableName);
		}

		// checks if add record was sent
		if (isset($_POST['addDBTableRecord'])) {
			self::addDBTableRecord($results, $tableName);
		}

		// checks to see if database table has content
		if (!empty($results)) {
			// starts html table
			$string = "<div class='content_results'>";
			$string .= "<div class='table'>";
			$string .= "<div class='tr'>";

			// creates header for html table
			$header = array_keys(array_pop(array_slice($results, 0, 1)));

			// loops through database table for html table header elements
			foreach ($header as $header_element) {
				$string .= "<div class='th'>$header_element</div>";
			}

			$string .= "<div class='th'>actions</div>";
			$string .= "</div>";

			// loops through database table for fields & configures form for each record
			foreach ($results as $column => $field) {
				$string .= "<div class='tr'>";
				$string .= "<form action='admin.php?database_table=$tableName' method='post'>";

				foreach ($field as $fieldType => $fieldInfo) {
					$string .= "<div class='td'><input type='text' name='$fieldType' value='$fieldInfo' readonly></div>";

				}

				$string .= "<div class='td'><input type='submit' name='deleteDBTableRecord' value='Delete'/><input type='submit' name='modifyDBTableRecord' value='Modify'/></div>";
				$string .= "</form>";
				$string .= "</div>";
			}

			// loops through database table for blank field & configures 2nd form for data input
			$string .= "<div class='tr'>";
			$string .= "<form action='admin.php?database_table=$tableName' method='post'>";

			foreach ($field as $fieldType => $fieldInfo) {
				$string .= "<div class='td'><input type='text' name='$fieldType' value=''></div>";
			}

			$string .= "<div class='td'><input type='submit' name='addDBTableRecord' value='Add Record' style='width:8.75em; margin:0 auto'/></div></div>";
			$string .= "</form>";
			$string .= "</div>";
			$string .= "</div>";

			// reports if no data in table is found
		} else {
			$string = "<div class='error_message'>No data in database table</div>";
		}

		echo $string;
	}

	// deletes a DB Table record
	static function deleteDBTableRecord($results, $tableName) {
		// initializes sql statement
		$sql = "DELETE FROM $tableName WHERE ";

		// initializes counter for AND sql statement variable
		$i = 1;

		// removes the submit value from post array
		$fields = $_POST;
		array_pop($fields);

		// loops through the post array to create sql statement
		foreach ($fields as $field => $value) {
			if ($i < 2) {
				$sql .= "$field='$value'";

				$i++;
			}

		}

		// runs generated sql statement
		$db = Database::getInstance();
		$err = $db -> doQuery($sql);

		// refreshes page to show item was deleted
		header("Location: admin.php?database_table=$tableName");
	}

	// adds a DB Table record
	static function addDBTableRecord($results, $tableName) {
		// initializes sql statement
		$sql = "INSERT INTO $tableName ";
		$sql .= "VALUES (";

		// removes the submit value from post array
		$fields = $_POST;
		array_pop($fields);

		// gets the number of fields
		$number_of_fields = count($fields);

		for ($x = 0; $x < $number_of_fields; $x++) {
			if (($number_of_fields - $x) > 1) {
				$sql .= "?, ";
			}
			if (($number_of_fields - $x) == 1) {
				$sql .= "?";
			}
		}

		$sql .= ")";

		echo $sql;

		// initializes vars & types array
		$vars = array();
		$types = array();

		// loops through the post array to create vars array
		// TODO: need to call a field validation function here
		foreach ($fields as $field => $value) {
			$vars[] = $value;
			$types[] = substr(gettype($value), 0, 1);
		}

		// runs generated sql statement
		$db = Database::getInstance();
		$err = $db -> doQuery($sql, $vars, $types);

		// refreshes page to show item was added
		header("Location: admin.php?database_table=$tableName");
	}

}
?>