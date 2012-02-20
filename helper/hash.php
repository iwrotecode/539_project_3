<?php
//include any libraries/classes needed
function __autoload($className) {
	require_once '../classes/' . $className . '.class.php';
}

if (isset($_GET['submit']) && !empty($_GET['submit']) && isset($_GET['hash']) && !empty($_GET['hash'])) {

	echo "<p>" . sha1($_GET['hash']) . "</p>";

	echo "<p>" . sha1($_GET['hash']) . " is sha1: " . Utils::is_sha1(sha1($_GET['hash'])) . "</p>";
	echo "<p>" . $_GET['hash'] . " is not sha1: " . Utils::is_sha1($_GET['hash']) . "</p>";
}

echo <<<END
	<form>
		<label for="hash">Value to hash</label>
		<input type="text" name="hash" />	
	
		<input type="submit" name="submit" value="Hash Away!" />
		
	</form>
END;
?>