<?php 
	$userid = $_GET["userid"]; 
	$conn = mysqli_connect("localhost", "root", "", "simpsons"); 
	$sql = "UPDATE students SET isadmin=1 WHERE id='$userid';"; 
	$result = mysqli_query($conn, $sql); 
?> 