<?php

class Page {

	static function header($title = 'untitled', $stylesheet = 'dummy.css') {
		return <<<END
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<title>$title</title>
	<link type="text/css" rel="stylesheet" href="$stylesheet" />
</head>
<body>
END;
	}

	static function footer() {
		return <<<END
</body>
</html>
END;
	}

} // end class Page
?>