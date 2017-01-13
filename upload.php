<?php
  $conn = mysqli_connect("localhost", "root", "", "simpsons");

	if(!$conn){
  	die("connection failed: ".mysqli_connect_error());
	}

	session_start();

	$name = $_SESSION['name'];
	$getId = "SELECT id FROM students WHERE name = '$name';";
	$query = mysqli_query($conn, $getId);
	$newid = mysqli_fetch_array($query);

  $target_dir = "users/".$newid['id']."/";
  $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
  copy($_FILES["fileToUpload"]["tmp_name"], $target_file);
  header("Location: file.php?userid=".$newid['id']);
?>
