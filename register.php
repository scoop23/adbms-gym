<?php
session_start();
include "db.php";

function insertUser($conn, $new_user_id, $firstname, $lastname, $email, $username, $password, $phone, $role) {
  $sql = "SELECT MAX(CAST(SUBSTRING(user_id, 2) AS UNSIGNED)) AS last_user_id FROM users";
  $result = $conn->query($sql);

  if($result->num_rows > 0){
  $row = $result->fetch_assoc();
  $last_user_id = $row['last_user_id'] ? $row['last_user_id'] : 0;
  $new_user_id = 'u' . ($last_user_id + 1);
  } else {
    $new_user_id = "u1";
  }

  $statement = "INSERT INTO users (user_id, first_name, last_name, email, username, password, phone, role) VALUES (?,?,?,?,?,?,?,?)";
  $stmt = $conn->prepare($statement);
  $stmt->bind_param("ssssssss", $new_user_id, $firstname, $lastname, $email, $username, $password, $phone, $role);

  if($stmt->execute()){
    echo "New user created successfully with user_id: $new_user_id";
    session_write_close(); // Save
    header("Location: client.php");
    exit;
  } else {
    echo "Error: " . $stmt->error;
  }

  $conn->close();
}




if($_SERVER['REQUEST_METHOD'] === "POST"){
  $firstname = $_POST['first_name'];
  $lastname = $_POST['last_name'];
  $username = $_POST['username'];
  $email = $_POST['email'];
  $phone = $_POST['phone'];
  $password = password_hash($_POST["confirm_password"], PASSWORD_DEFAULT);
  $role = $_POST["role"];
  
  // Set session
  $_SESSION['username'] = $username;
  var_dump($_SESSION);

  insertUser($conn, $new_user_id, $firstname, $lastname, $email, $username, $password, $phone, $role);
  
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Register | Gym Portal</title>
  <link href="./css/register.css" rel="stylesheet">
</head>
<body>
  <div class="main-container">
    <h1 class="title">Register</h1>
    <form class="register-form" method="post" action="register.php">
      
      <label for="First Name">First  Name</label>
      <input type="text" id="first_name" name="first_name" required>

      <label for="Last Name">Last Name</label>
      <input type="text" id="last_name" name="last_name" required>

      <label for="username">Username</label>
      <input type="text" id="username" name="username" required>

      <label for="email">Email</label>
      <input type="email" id="email" name="email" required>

      <label for="phone">Phone</label>
      <input type="text" id="phone" name="phone" required>

      <label for="password">Password</label>
      <input type="password" id="password" name="password" required>

      <label for="confirm_password">Confirm Password</label>
      <input type="password" id="confirm_password" name="confirm_password" required>

      <select class="dropdown" name="role" id="role">
        <option value="client">Client</option>
        <option value="instructor">Instructor</option>
      </select>

      <button type="submit">Register</button>
    </form>
    <div class="links">
      <a href="login.php">Already have an account? Login</a>
    </div>
  </div>
</body>
</html>
