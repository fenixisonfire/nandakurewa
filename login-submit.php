<?php
	$name = ucwords($_POST["name"]);
	$pw = $_POST["password"];

	if (is_correct_password($name, $pw)) {
		# redirect?
		session_start();
		$_SESSION["name"] = get_name($name);
		header("Location: index.php");
		die();
	} else {
		header("Location: login.php?re=0");
	}
	
	// ' or 1=1 #

	# query database to see if user typed the right password
	function is_correct_password($name, $pw) {
		$db = new PDO("mysql:dbname=simpsons", "root", "");
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$rows = $db->query("SELECT id FROM students WHERE name = '$name' and password = '$pw'");
		foreach ($rows as $row) {
			// $correct_password = $row["password"];
			// if ($pw == $correct_password) {
			//	return TRUE;
			// }
			return TRUE;
		}
		return FALSE;
	}

	function get_name($username) {
    $db = new PDO("mysql:dbname=simpsons", "root", "");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $rows = $db->query("SELECT name FROM students WHERE name = '$username'");
		foreach ($rows as $row) {
			$session_name = $row["name"];
			return $session_name;
		}
  }
?>
