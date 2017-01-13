<?php
  $conn = mysqli_connect("localhost", "root", "", "simpsons");
  if(!$conn){
    die("connection failed: ".mysqli_connect_error());
  }

  session_start();
  $sid = $_POST["snippet_id"];
  $name = $_SESSION['name'];
	$getId = "SELECT id FROM students WHERE name = '$name';";
	$query = mysqli_query($conn, $getId);
	$newid = mysqli_fetch_array($query);

  $sql = "DELETE FROM snippets WHERE sid = '$sid';";
  $result = mysqli_query($conn, $sql);
  header("Location: snippets.php?userid=".$newid['id']);
?>
