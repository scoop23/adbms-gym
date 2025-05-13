
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

      <label for="rating">Rating (1-5)</label>
      <input type="number" id="rating" name="rating" min="1" max="5" required>

      <label for="certifications">Certifications</label>
      <input type="text" id="certifications" name="certifications" required>

      <label for="specialties">Specialties</label>
      <input type="text" id="specialties" name="specialties" required>

      <input type="hidden" name="role" value="instructor">

      <button type="submit">Register as Instructor</button>
    </form>
    <div class="links">
      <a href="login.php">Already have an account? Login</a>
    </div>
  </div>
</body>
</html>