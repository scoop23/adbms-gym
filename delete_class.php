<?php
session_start();
include "db.php";

if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin'){
  header("Location: login.php");
  exit();
}

if(isset($_GET['class_id'])){
  $class_id = $_GET['class_id'];
  $delete_class_sql = 'DELETE FROM classes WHERE class_id = ?';
  $stmt = $conn->prepare($delete_class_sql);
  $stmt->bind_param("s" , $class_id);
  $stmt->execute();
}

header("Location: admin.php");
exit();
?>