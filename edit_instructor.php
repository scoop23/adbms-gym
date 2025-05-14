<?php
// filepath: d:\BASTITE\xampp2\htdocs\GymWeb\edit_instructor.php
session_start();
include "db.php";

// Only allow admins
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['user_id'])) {
    header("Location: admin.php");
    exit();
}

$user_id = $_GET['user_id'];
$message = "";

// Fetch instructor and user data
$stmt = $conn->prepare("SELECT u.first_name, u.last_name, u.email, u.phone, i.bio, i.photo, i.rating, i.certifications, i.specialties 
                        FROM instructors i 
                        JOIN users u ON i.instructor_id = u.user_id 
                        WHERE i.instructor_id = ?");
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$instructor = $result->fetch_assoc();

if (!$instructor) {
    $message = "Instructor not found.";
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $bio = $_POST['bio'];
    $rating = $_POST['rating'];
    $certifications= $_POST['certifications'];
    $specialties = $_POST['specialties'];

    // Update users table
    $update_user = $conn->prepare("UPDATE users SET first_name=?, last_name=?, email=?, phone=? WHERE user_id=?");
    $update_user->bind_param("sssss", $first_name, $last_name, $email, $phone, $user_id);
    $update_user->execute();

    // Update instructors table
    $update_inst = $conn->prepare("UPDATE instructors SET bio=?, rating=?, certifications=?, specialties=? WHERE instructor_id=?");
    $update_inst->bind_param("sisss", $bio, $rating, $certifications, $specialties, $user_id);
    if ($update_inst->execute()) {
        $message = "Instructor updated successfully!";
        $instructor = [
          'first_name' => $first_name,
          'last_name' => $last_name,
          'email' => $email,
          'phone' => $phone,
          'bio' => $bio,
          'rating' => $rating,
          'certifications' => $certifications,
          'specialties' => $specialties
        ];
    } else {
        $message = "Update failed: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Instructor | Admin</title>
    <link rel="stylesheet" href="./css/edit_instructor.css?v=<?php echo time(); ?>">
</head>
<body>
    <div class="edit-instructor-container">
        <h1 class="edit-instructor-title">Edit Instructor</h1>
        <?php if($message): ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <?php if($instructor): ?>
        <form class="edit-instructor-form" method="POST">
            <label for="first_name">First Name</label>
            <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($instructor['first_name']); ?>" required>

            <label for="last_name">Last Name</label>
            <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($instructor['last_name']); ?>" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($instructor['email']); ?>" required>

            <label for="phone">Phone</label>
            <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($instructor['phone']); ?>">

            <label for="bio">Bio</label>
            <textarea id="bio" name="bio" required><?php echo htmlspecialchars($instructor['bio']); ?></textarea>

            <label for="rating">Rating (1-5)</label>
            <input type="number" id="rating" name="rating" min="1" max="5" value="<?php echo htmlspecialchars($instructor['rating']); ?>" required>

            <label for="certifications">Certifications</label>
            <input type="text" id="certifications" name="certifications" value="<?php echo htmlspecialchars($instructor['certifications']); ?>">

            <label for="specialties">Specialties</label>
            <input type="text" id="specialties" name="specialties" value="<?php echo htmlspecialchars($instructor['specialties']); ?>">

            <button type="submit">Update Instructor</button>
        </form>
        <?php endif; ?>
        <div class="links">
            <a href="admin.php">Back to Admin Dashboard</a>
        </div>
    </div>
</body>
</html>
<?php $conn->close(); ?>