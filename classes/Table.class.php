<?php

class Table {

	// grabs all tables in the DB, and returns a form to select them
	static function displayDBTables() {
		// get user access level
		$accessLevel = Utils::getAccessLevel();

		// grab all tables
		$db = Database::getInstance();
		$tableNames = $db -> getValidTableNames();

		// starts form to select table
		$string = "<div class='content'>";
		$string .= '<form action="admin.php" method="get">';
		$string .= '<select name="database_table">';

		// modifies available tables based on user access level
		switch ($accessLevel) {
			// admin access
			case 1 :
			// do nothing different
				break;

			// editor access
			case 2 :
				foreach ($tableNames as $key => $table) {
					if ($table == "cms_user" || $table == "cms_edition" || $table == "cms_user_type") {
						unset($tableNames[$key]);
					}
				}
				break;

			// advertiser access
			case 3 :
				foreach ($tableNames as $key => $table) {
					if ($table != "cms_ads") {
						unset($tableNames[$key]);
					}
				}
				break;

			// equiv to case 0 (no user)
			default :
				$error = "<div class='error_message'>No access granted!</div>";
				die($error);
		}

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
			$sql = "SELECT * FROM $tableName";

			$results = self::executeSQL($sql);

			self::displayDBTableForm($results, $tableName);
		}
	}

	// runs the select all query on passed database table
	static function executeSQL($sql = "", $vars = "", $types = "") {
		$db = Database::getInstance();

		// executes query
		if (isset($vars) && is_array($vars) && isset($types) && is_array($types)) {
			$err = $db -> doQuery($sql, $vars, $types);
		} else {
			$err = $db -> doQuery($sql);
		}

		// grabs results of query
		$results = $db -> fetch_all_array();

		return $results;
	}

	// displays paginated results in xml form
	static function displayXML($sql = "", $tableName = "", $edition_sql = "", $page = 1, $countPerPage = 5) {
		// checks if custom countPerPage is set via GET
		if (isset($_GET['count'])) {
			// validates custom countPerPage
			if (is_numeric($_GET['count'])) {
				if ($_GET['count'] > 0) {
					$countPerPage = ceil($_GET['count']);
				}
			}
		}

		// calculates total pages
		$total_items_array = self::executeSQL("SELECT COUNT( id ) AS total_items FROM $tableName", $tableName);
		$total_items = array_pop(array_pop($total_items_array));
		$total_pages = ceil($total_items / $countPerPage);

		// checks if custom pageNumber is set via GET
		if (isset($_GET['page'])) {
			// validates custom pageNumber
			if (is_numeric($_GET['page'])) {
				if ($_GET['page'] > $total_pages) {
					$page = $total_pages;
				} else if ($_GET['page'] < 1) {

				} else {
					$page = ceil($_GET['page']);
				}
			}
		}

		// calculates page offset
		$offset = ($page - 1) * $countPerPage;

		// stores variables in array for sql query
		$vars = array($offset, $countPerPage);
		$types = array("i", "i");

		// calls function to execute sql
		$results = self::executeSQL($sql, $vars, $types);

		// initializes xml file
		$string = "<?xml version='1.0'?>\n";
		$string .= "<page pageNumber='$page' totalPages='$total_pages' numberPerPage='$countPerPage'>\n";

		// loops through results, creating xml output
		foreach ($results as $column => $field) {
			$string .= "\t<item>\n";

			// continues the loop, detects if fieldType is content, then use cdata
			foreach ($field as $fieldType => $fieldInfo) {
				if ($fieldType == 'content') {
					$string .= "\t\t<$fieldType><![CDATA[$fieldInfo]]></$fieldType>\n";
				} else {
					$string .= "\t\t<$fieldType>$fieldInfo</$fieldType>\n";
				}
			}

			// stores variables in array for sql editions query
			$vars = array($field['id']);
			$types = array("i");

			// calls function to execute edition sql
			$edition_results = self::executeSQL($edition_sql, $vars, $types);
			
			if (is_array($edition_results) && !empty($edition_results)) {
				$string .= "\t<editions>\n";
				
				// loops through results, creating xml output for editions
				foreach ($edition_results as $column => $field) {
						foreach ($field as $key => $data) {
							$string .= "\t<edition>$data</edition>\n";
						}
				}
				
				$string .= "\t</editions>\n";
			} else {
				$string .= "\t<editions />\n";
			}

			$string .= "\t</item>\n";
		}

		$string .= "</page>\n";

		echo $string;
	}

	// formats database table output into a table
	static function displayDBTableForm($results, $tableName) {
		// checks if delete was sent
		if (isset($_POST['deleteDBTableRecord'])) {
			self::deleteDBTableRecord($results, $tableName);
		}

		// checks if modify was sent
		if (isset($_POST['modifyDBTableRecord'])) {
			self::modifyDBTableRecord($results, $tableName);
		}

		// checks if add record was sent
		if (isset($_POST['addDBTableRecord'])) {
			self::addDBTableRecord($results, $tableName);
		}

		// checks to see if database table has content
		if (!empty($results)) {
			// starts html table
			$string = "<p class='titles'>$tableName</p>";
			$string .= "<div class='content_results'>";
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
					$string .= "<div class='td'><input type='text' name='$fieldType' value='$fieldInfo' /></div>";

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

	// updates a DB Table record
	static function modifyDBTableRecord($results, $tableName) {
		// removes the submit value from post array
		$fields = $_POST;
		array_pop($fields);

		// Sanitize data before validation
		Form::sanitizeResults($fields);
		
		// make sure if the results are valid
		$error = Form::validateResults($fields, $tableName);

		// check if there were any validation errors
		if (!empty($error)) {
			// do something
			echo "<div class='error_message'>$error</div>";
		} else {
			// initializes sql statement
			$sql = "UPDATE $tableName ";

			// initializes counters
			$x = 1;
			$i = 1;

			// gets the number of fields
			$number_of_fields = count($fields);

			$sql .= "SET ";

			// loops to create the values placeholder
			foreach ($fields as $field => $value) {
				if (($number_of_fields - $x) < 1) {
					$sql .= "$field = ? ";
				}
				if ((($number_of_fields - $x) >= 1) && ($x != 1)) {
					$sql .= "$field = ?, ";
				}

				$x++;
			}

			$sql .= "WHERE ";

			// loops through the post array to get the where value
			foreach ($fields as $field => $value) {
				if ($i < 2) {
					$sql .= "$field = $value ";

					$i++;
				}

			}

			// initializes vars & types array
			$vars = array();
			$types = array();

			// resets x counter
			$x = 1;

			// loops through the post array to create vars array
			foreach ($fields as $field => $value) {
				if (($number_of_fields - $x) < 1) {
					if ($field == 'password') {
						if (Utils::is_sha1($value)) {
							$vars[] = $value;
							$types[] = substr(gettype($value), 0, 1);
						} else {
							$vars[] = sha1($value);
							$types[] = substr(gettype($value), 0, 1);
						}
					} else {
						$vars[] = $value;
						$types[] = substr(gettype($value), 0, 1);
					}
				}
				if ((($number_of_fields - $x) >= 1) && ($x != 1)) {
					if ($field == 'password') {
						if (Utils::is_sha1($value)) {
							$vars[] = $value;
							$types[] = substr(gettype($value), 0, 1);
						} else {
							echo Utils::is_sha1($number_of_fields);
							$vars[] = sha1($value);
							$types[] = substr(gettype($value), 0, 1);
						}
					} else {
						$vars[] = $value;
						$types[] = substr(gettype($value), 0, 1);
					}
				}

				$x++;
			}

			// runs generated sql statement
			$db = Database::getInstance();
			$err = $db -> doQuery($sql, $vars, $types);

			// refreshes page to show item was added
			header("Location: admin.php?database_table=$tableName");
		}
	}

	// adds a DB Table record
	static function addDBTableRecord($results, $tableName) {

		// removes the submit value from post array
		$fields = $_POST;
		array_pop($fields);

		// Sanitize data before validation
		Form::sanitizeResults($fields);

		// make sure if the results are valid
		$error = Form::validateResults($fields, $tableName);

		// check if there were any validation errors
		if (!empty($error)) {
			// do something
			echo "<div class='error_message'>$error</div>";
		} else {
			// passed validation

			// initializes sql statement
			$sql = "INSERT INTO $tableName ";
			$sql .= "VALUES (";

			// gets the number of fields
			$number_of_fields = count($fields);

			// loops to create the values placeholder
			for ($x = 0; $x < $number_of_fields; $x++) {
				if (($number_of_fields - $x) > 1) {
					$sql .= "?, ";
				}
				if (($number_of_fields - $x) == 1) {
					$sql .= "?";
				}
			}

			$sql .= ")";

			// initializes vars & types array
			$vars = array();
			$types = array();

			// loops through the post array to create vars array
			foreach ($fields as $field => $value) {
				if (($number_of_fields - $x) < 1) {
					if ($field == 'password') {
						if (Utils::is_sha1($value)) {
							$vars[] = $value;
							$types[] = substr(gettype($value), 0, 1);
						} else {
							$vars[] = sha1($value);
							$types[] = substr(gettype($value), 0, 1);
						}
					} else {
						$vars[] = $value;
						$types[] = substr(gettype($value), 0, 1);
					}
				}
				if ((($number_of_fields - $x) >= 1) && ($x != 1)) {
					if ($field == 'password') {
						if (Utils::is_sha1($value)) {
							$vars[] = $value;
							$types[] = substr(gettype($value), 0, 1);
						} else {
							echo Utils::is_sha1($number_of_fields);
							$vars[] = sha1($value);
							$types[] = substr(gettype($value), 0, 1);
						}
					} else {
						$vars[] = $value;
						$types[] = substr(gettype($value), 0, 1);
					}
				}
			}

			// runs generated sql statement
			$db = Database::getInstance();
			$err = $db -> doQuery($sql, $vars, $types);

			// refreshes page to show item was added
			header("Location: admin.php?database_table=$tableName");
		}

	}

}
?>