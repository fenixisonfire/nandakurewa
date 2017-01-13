#register-submit.php
<?php
  $conn = mysqli_connect("localhost", "root", "", "simpsons");

  if(!$conn){
    die("connection failed: ".mysqli_connect_error());
  }

  session_start();
  $name = ucwords($_POST['name']);
  $email = $_POST['email'];
  $password = $_POST['password'];
  if(!is_new_user($name)) {
    header("Location: register.php?re=0");
  } else {
    $sql = "INSERT INTO students (name, email, password, id) VALUES ('$name', '$email', '$password', '')";
    $result = mysqli_query($conn, $sql);

    $getId = "SELECT id FROM students WHERE name = '$name';";
    $query = mysqli_query($conn, $getId);
    $newid = mysqli_fetch_array($query);
    mkdir('users/'.$newid['id'],0777,true);
    header("Location: start.php");
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
