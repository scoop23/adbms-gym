<?php 
include "db.php";
include "util.php";

function insertInstructor($conn, $new_user_id, $bio, $photo, $rating, $certifications, $specialties){
  $statement = "INSERT INTO instructors (instructor_id, bio, photo, rating, certifications, specialties) VALUES (?,?,?,?,?,?)";
  $stmt = $conn->prepare($statement);
  $stmt->bind_param("ssssss", $new_user_id, $bio, $photo, $rating, $certifications, $specialties);


  if (!$stmt->execute()) {
    die("Insert instructor failed: " . $stmt->error);
  }
  echo "Instructor inserted successfully!";

}

$uploadDir = 'uploads/'; 

if($_SERVER['REQUEST_METHOD'] === "POST"){
  $firstname = $_POST['firstname'];
  $lastname = $_POST['lastname'];
  $username = $_POST['username'];
  $email = $_POST['email'];
  $phone = $_POST['phone'];
  $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
  $role = $_POST["role"];
  $bio = $_POST["bio"];
  // $photo = $_POST["photo"];
  $rating = 1;
  $certifications = $_POST["certifications"];
  $specialties = $_POST["specialties"];

  $new_user_id = generateNewUserId($conn);
  $photoFilename = null;

  if(isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK){
    $tmp_name = $_FILES['photo']['tmp_name'];
    $originalName = basename($_FILES['photo']['name']);
    $extension = pathinfo($originalName, PATHINFO_EXTENSION);
    $photoFilename = uniqid('photo_', true) . '.' . $extension;
    $destination = $uploadDir . $photoFilename;

    if(!move_uploaded_file($tmp_name, $destination)){
      die("failed to upload photo.");
    }
  }



  insertUser($conn, $new_user_id, $firstname, $lastname, $email, $username, $password, $phone, $role);

  if($role === 'instructor') {
    echo "Role is instructor<br>";
    insertInstructor($conn,$new_user_id, $bio, $photoFilename, $rating, $certifications, $specialties);
  }
  $conn->close();
  

}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Register as Instructor | Gym Portal</title>
  <link href="./css/register.css" rel="stylesheet">
</head>
<body>
  <div class="main-container">
    <h1 class="title">Become an Instructor</h1>
    <form class="register-form" method="post" action="registerInstructor.php" enctype="multipart/form-data">
      <label for="firstname">First Name</label>
      <input type="text" id="firstname" name="firstname" required>

      <label for="lastname">Last Name</label>
      <input type="text" id="lastname" name="lastname" required>

      <label for="email">Email</label>
      <input type="email" id="email" name="email" required>

      <label for="username">Username</label>
      <input type="text" id="username" name="username" required>

      <label for="password">Password</label>
      <input type="password" id="password" name="password" required>

      <label for="phone">Phone</label>
      <input type="text" id="phone" name="phone" required>

      <label for="bio">Bio</label>
      <textarea id="bio" name="bio" required></textarea>

      <label for="photo">Photo</label>
      <input type="file" id="photo" name="photo" accept="image/*" required>

      <label for="certifications">Certifications</label>
      <input type="text" id="certifications" name="certifications" required>

      <label for="specialties">Specialties</label>
      <input type="text" id="specialties" name="specialties" required>

      <input type="hidden" name="role" value="instructor">

      <button type="submit">Register as Instructor</button>
    </form>
    <div class="links">
      <a href="login.php">Already have an account? Login</a>
      <a href="index.php">Back to home</a>
    </div>
  </div>
</body>
</html>