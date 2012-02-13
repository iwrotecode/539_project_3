<?php

class Page {

	static function header($title = "Untitled", $styles = null, $scripts = null) {
		$string = <<<END
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta charset="utf-8" />
	<title>$title</title>
END;

		if (is_array($styles)) {
			foreach ($styles as $style) {
				$string .= "<link type='text/css' rel='stylesheet' href='$style' />\n";
			}
		} else if (is_string($styles)) {
			$string .= "<link type='text/css' rel='stylesheet' href='$styles' />\n";
		}

		if (is_array($scripts)) {
			foreach ($scripts as $script) {
				$string .= "<script type='text/javascript' src='$script'></script>\n";
			}
		} else if (is_string($scripts)) {
			$string .= "<script type='text/javascript' src='$scripts'></script>\n";
		}

		$string .= <<<END
</head>
<body>
END;

		return $string;
	}

	static function footer() {
		return <<<END
</body>
</html>
END;
	}

	static function addNav() {
		$result = file_get_contents("nav.html");

		if ($result) {
			return $result;
		} else {
			return "";
		}
	}

	static function checkAccess($which = array(0)) {
		$ok = false;
		if (!isset($_SESSION['user']) || !isset($_SESSION['access'])) {
			echo "You are not logged in.  Please <a href='login.php'>login</a>";
		} else if (!in_array($_SESSION['access'], $which) == 1) {
			echo "You don't have proper access.  Please <a href='login.php'>login</a> again.";
		}//logged in with not correct access
		else
			$ok = true;
		return $ok;

	}  //checkAccess

} // end class Page
?>