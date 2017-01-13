<?php
  $conn = mysqli_connect("localhost", "root", "", "simpsons");
  if(!$conn){
    die("connection failed: ".mysqli_connect_error());
  }
  session_start();
  $userid = $_POST["userid"];
  $username = ucwords($_POST["username"]);
  if($username == "") {
    $username = $_SESSION["name"];
  }
	
	if (isset($_GET["password"])) {
		$n_pw = $_GET["password"];  
	} else {
		if (isset($_POST["new_password"])) {
			$n_pw = $_POST["new_password"]; 
		}
	}
	
if(!is_new_user($username) && $username != $_SESSION["name"]) {
  header("Location: profile.php?userid=".$userid."&re=0");
} else {
  if(strlen($n_pw) == 0)
	$sql = "UPDATE students SET name='$username' WHERE id='$userid';";
  else
	$sql = "UPDATE students SET name='$username', password='$n_pw' WHERE id='$userid';";
	$result = mysqli_query($conn, $sql);
  if(!$result)
	die("Could not update data: ". mmysql_error());
  $_SESSION["name"] = $username;
	header("Location: profile.php?userid=".$userid."&re=17");
}

  function is_correct_password($id, $pw) {
  	$db = new PDO("mysql:dbname=simpsons", "root", "");
  	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  	$rows = $db->query("SELECT password FROM students WHERE id = '$id';");
  	foreach ($rows as $row) {
  		$correct_password = $row["password"];
  		if ($pw == $correct_password) {
  			return TRUE;
  		}
  	}
  	return FALSE;
  }

  function is_new_user($username) {
    $db = new PDO("mysql:dbname=simpsons", "root", "");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $rows = $db->query("SELECT name FROM students WHERE name = '$username'");
    $i = 0;
    foreach ($rows as $row) {
      $i+=1;
    }
    return !($i==1);
  }
?>
