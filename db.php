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
