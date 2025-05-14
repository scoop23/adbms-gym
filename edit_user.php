<?php 
session_start();
include "db.php";

// Only allow admins
if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin'){
  header("Location: login.php");
  exit();
}

if(!isset($_GET['user_id'])) {
    header("Location: admin.php");
    exit();
}

$user_id = $_GET['user_id'];
$message = "";

// Fetch user data
$stmt = $conn->prepare("SELECT first_name, last_name, email, username, phone, role FROM users WHERE user_id = ?");
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if(!$user) {
    $message = "User not found.";
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $phone = $_POST['phone'];
    $role = $_POST['role'];

    $update = $conn->prepare("UPDATE users SET first_name=?, last_name=?, email=?, username=?, phone=?, `role`=? WHERE user_id=?");
    $update->bind_param("sssssss", $first_name, $last_name, $email, $username, $phone, $role, $user_id);

    if($update->execute()) {
        $message = "User updated successfully!";
        // Refresh user data
        $user = [
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'username' => $username,
            'phone' => $phone,
            'role'=> $role
        ];
    } else {
        $message = "Update failed: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit User | Admin</title>
    <link rel="stylesheet" href="./css/edit_user.css?php echo time(); ?>">
</head>
<body>
    <div class="edit-user-container">
        <h1 class="edit-user-title">Edit User</h1>
        <?php if($message): ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <?php if($user): ?>
        <form class="edit-user-form" method="POST">
            <label for="first_name">First Name</label>
            <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>" required>

            <label for="last_name">Last Name</label>
            <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

            <label for="username">Username</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>

            <label for="phone">Phone</label>
            <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>">

            <label for="role">Role</label>
            <select id="role" name="role" required>
                <option value="client" <?php if($user['role']=='client') echo 'selected'; ?>>Client</option>
                <option value="instructor" <?php if($user['role']=='instructor') echo 'selected'; ?>>Instructor</option>
            </select>

            <button type="submit">Update User</button>
        </form>
        <?php endif; ?>
        <div class="links">
            <a href="admin.php">Back to Admin Dashboard</a>
        </div>
    </div>
</body>
</html>
<?php $conn->close(); ?>

