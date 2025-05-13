
<?php
session_start();
include "db.php";

// Check if instructor is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'instructor') {
    header("Location: login.php");
    exit();
}

$instructor_id = $_SESSION['user_id'];

// Fetch instructor info
$stmt = $conn->prepare("SELECT u.first_name, u.last_name, u.email, u.phone, i.bio, i.photo, i.certifications, i.specialties 
                        FROM instructors i 
                        JOIN users u ON i.instructor_id = u.user_id 
                        WHERE i.instructor_id = ?");
$stmt->bind_param("s", $instructor_id);
$stmt->execute();
$instructor = $stmt->get_result()->fetch_assoc();

// Fetch instructor's classes and schedules
$sql_classes = "SELECT c.name, c.description, s.schedule_id, s.start_time, s.end_time, s.location
                FROM schedules s
                JOIN classes c ON s.class_id = c.class_id
                WHERE s.instructor_id = ?
                ORDER BY s.start_time DESC";
$stmt_classes = $conn->prepare($sql_classes);
$stmt_classes->bind_param("s", $instructor_id);
$stmt_classes->execute();
$classes = $stmt_classes->get_result();

// Fetch reviews
$sql_reviews = "SELECT r.rating, r.comment, r.review_time, u.first_name, u.last_name
                FROM reviews r
                JOIN users u ON r.user_id = u.user_id
                WHERE r.instructor_id = ?
                ORDER BY r.review_time DESC";
$stmt_reviews = $conn->prepare($sql_reviews);
$stmt_reviews->bind_param("s", $instructor_id);
$stmt_reviews->execute();
$reviews = $stmt_reviews->get_result();
?>
<!DOCTYPE html>
<html>
<head>
  <title>Instructor Dashboard | Gym Portal</title>
  <link rel="stylesheet" href="./css/styles.css">
  <style>
    .dashboard-container {
      max-width: 1000px;
      margin: 40px auto;
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 4px 24px rgba(0,0,0,0.08);
      padding: 40px 30px;
    }
    .dashboard-title {
      text-align: center;
      color: #2d3a4b;
      margin-bottom: 32px;
    }
    .profile-section {
      display: flex;
      align-items: center;
      gap: 24px;
      margin-bottom: 32px;
    }
    .profile-photo {
      width: 90px;
      height: 90px;
      border-radius: 50%;
      object-fit: cover;
      border: 3px solid #4f8cff;
      background: #e5e7eb;
    }
    .profile-info {
      flex: 1;
    }
    .section-title {
      color: #2563eb;
      margin-top: 24px;
      margin-bottom: 10px;
      font-size: 18px;
      font-weight: 600;
    }
    .class-table, .review-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 24px;
    }
    .class-table th, .class-table td, .review-table th, .review-table td {
      border: 1px solid #e5e7eb;
      padding: 10px 8px;
      text-align: center;
    }
    .class-table th, .review-table th {
      background: #f4f6fb;
      color: #2d3a4b;
    }
    .review-rating {
      color: #f59e42;
      font-weight: bold;
    }
  </style>
</head>
<body>
  <div class="dashboard-container">
    <h1 class="dashboard-title">Welcome, <?php echo htmlspecialchars($instructor['first_name'] . ' ' . $instructor['last_name']); ?>!</h1>
    <div class="profile-section">
      <img class="profile-photo" src="<?php echo 'uploads/' . htmlspecialchars($instructor['photo'] ?: 'https://via.placeholder.com/90'); ?>" alt="Profile Photo">
      <div class="profile-info">
        <div><b>Email:</b> <?php echo htmlspecialchars($instructor['email']); ?></div>
        <div><b>Phone:</b> <?php echo htmlspecialchars($instructor['phone']); ?></div>
        <div><b>Specialties:</b> <?php echo htmlspecialchars($instructor['specialties']); ?></div>
        <div><b>Certifications:</b> <?php echo htmlspecialchars($instructor['certifications']); ?></div>
        <div><b>Bio:</b> <?php echo htmlspecialchars($instructor['bio']); ?></div>
      </div>
    </div>

    <div class="section-title">Your Scheduled Classes</div>
    <table class="class-table">
      <tr>
        <th>Class Name</th>
        <th>Description</th>
        <th>Location</th>
        <th>Start Time</th>
        <th>End Time</th>
      </tr>
      <?php if ($classes->num_rows > 0): ?>
        <?php while($row = $classes->fetch_assoc()): ?>
          <tr>
            <td><?php echo htmlspecialchars($row['name']); ?></td>
            <td><?php echo htmlspecialchars($row['description']); ?></td>
            <td><?php echo htmlspecialchars($row['location']); ?></td>
            <td><?php echo htmlspecialchars($row['start_time']); ?></td>
            <td><?php echo htmlspecialchars($row['end_time']); ?></td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr><td colspan="5">No scheduled classes.</td></tr>
      <?php endif; ?>
    </table>

    <div class="section-title">Recent Reviews</div>
    <table class="review-table">
      <tr>
        <th>Client</th>
        <th>Rating</th>
        <th>Comment</th>
        <th>Date</th>
      </tr>
      <?php if ($reviews->num_rows > 0): ?>
        <?php while($row = $reviews->fetch_assoc()): ?>
          <tr>
            <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
            <td class="review-rating"><?php echo str_repeat('⭐', (int)$row['rating']); ?></td>
            <td><?php echo htmlspecialchars($row['comment']); ?></td>
            <td><?php echo htmlspecialchars($row['review_time']); ?></td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr><td colspan="4">No reviews yet.</td></tr>
      <?php endif; ?>
    </table>

    <div class="links" style="margin-top:32px;">
      <a href="logout.php">Logout</a>
    </div>
  </div>
</body>
</html>
<?php $conn->close(); ?>