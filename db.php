<?php
$host = 'localhost';
$user = 'root';
$pass = ''; // adjust if needed
$dbname = 'gym_db';
$port = 3307;

$conn = new mysqli($host, $user, $pass, $dbname, $port);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
} 


function insertUser($conn, $new_user_id, $firstname, $lastname, $email, $username, $password, $phone, $role) {
  $statement = "INSERT INTO users (user_id, first_name, last_name, email, username, password, phone, role) VALUES (?,?,?,?,?,?,?,?)";
  $stmt = $conn->prepare($statement);
  $stmt->bind_param("ssssssss", $new_user_id, $firstname, $lastname, $email, $username, $password, $phone, $role);

  if($stmt->execute()){
    echo "New user created successfully with user_id: $new_user_id";
    session_write_close(); // Save
    header("Location: client.php");
  } else {
    echo "Error: " . $stmt->error;
  }

}
?>
