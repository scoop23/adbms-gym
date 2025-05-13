<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    // $role = $_POST['role'];

    // Check if user exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Store user information in session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_id'] = $user['user_id'];
            
            // Redirect based on role
            if($user['role'] == 'admin') {
                header("Location: admin.php");
            } elseif ($user['role'] == 'client') {
                header("Location: client.php");
            } elseif ($user['role'] == 'instructor') {
                header("Location: instructor.php");
            }
            exit();
        } else {
            $error = "Invalid username or password!";
        }
    } else {
        $error = "Invalid username or password!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
  <link rel="stylesheet" href="./css/login.css">
</head>

<body>
  <div class="main">
    <div class="login-container">
      <h2 class="login-title">Login</h2>
      <?php if (isset($error)): ?>
        <p style="color:red;"><?= $error; ?></p>
      <?php endif; ?>
      <form class="login-form" method="post" action="login.php">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Login</button>
      </form>
      <br><a href="index.php"><button>Back to Home</button></a>
    </div>
  </div>
</body>

</html>
