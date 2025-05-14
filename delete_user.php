<?php
include "db.php";
session_start();

// Optional: Only allow admins
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];
    $del_sql = "DELETE FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($del_sql);
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
}

header("Location: admin.php");
exit();
?>