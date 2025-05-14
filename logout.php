<?php 



if($_SERVER["REQUEST_METHOD"] === "POST"){
  
  session_start(); 
  session_unset();  
  session_destroy();
  header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
  header("Pragma: no-cache"); 
  header("Expires: 0");
  header("Location: index.php");

  exit(); 
}


?>