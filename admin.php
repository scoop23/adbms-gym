<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
} // admin lang pede

//user
$users = $conn->query("SELECT user_id, first_name, last_name, email, username, phone, role FROM users ORDER BY role, last_name");

// instructors
$instructors = $conn->query("SELECT u.user_id, u.first_name, u.last_name, u.email, i.bio, i.rating, i.specialties 
    FROM instructors i JOIN users u ON i.instructor_id = u.user_id");

// classes
$classes = $conn->query("SELECT c.class_id, c.name, c.difficulty_level, c.capacity, u.first_name, u.last_name 
    FROM classes c 
    LEFT JOIN schedules s ON c.class_id = s.class_id 
    LEFT JOIN instructors i ON s.instructor_id = i.instructor_id 
    LEFT JOIN users u ON i.instructor_id = u.user_id
    GROUP BY c.class_id");

//  bookingsdf
$bookings = $conn->query("SELECT b.booking_id, b.status, b.booking_time, u.first_name AS client_first, u.last_name AS client_last, 
    cl.name AS class_name, s.start_time, s.location, ins.first_name AS instructor_first, ins.last_name AS instructor_last
    FROM bookings b
    JOIN users u ON b.user_id = u.user_id
    JOIN schedules s ON b.schedule_id = s.schedule_id
    JOIN classes cl ON s.class_id = cl.class_id
    JOIN instructors i ON s.instructor_id = i.instructor_id
    JOIN users ins ON i.instructor_id = ins.user_id
    ORDER BY b.booking_time DESC
");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard | Gym Portal</title>
    <link rel="stylesheet" href="./css/admin.css?v=<?php echo time(); ?>">
</head>
<body>
    <div class="admin-container">
        <h1 class="admin-title">Admin Dashboard</h1>

        <div class="section-title">All Users</div>
        <table>
            <tr>
                <th>User ID</th><th>Name</th><th>Email</th><th>Username</th><th>Phone</th><th>Role</th><th>Actions</th>
            </tr>
            <?php while($row = $users->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['user_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                    <td><?php echo htmlspecialchars($row['phone']); ?></td>
                    <td><?php echo htmlspecialchars($row['role']); ?></td>
                    <td>
                        <a href="edit_user.php?user_id=<?php echo urlencode($row['user_id']); ?>">Edit</a> |
                        <a href="delete_user.php?user_id=<?php echo urlencode($row['user_id']); ?>" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>

        <div class="section-title">Instructors</div>
        <table>
            <tr>
                <th>Name</th><th>Email</th><th>Specialties</th><th>Bio</th><th>Rating</th><th>Actions</th>
            </tr>
            <?php while($row = $instructors->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo htmlspecialchars($row['specialties']); ?></td>
                    <td><?php echo htmlspecialchars($row['bio']); ?></td>
                    <td><?php echo htmlspecialchars($row['rating']); ?></td>
                    <td>
                        <a href="edit_instructor.php?user_id=<?php echo urlencode($row['user_id']); ?>">Edit</a> |
                        <a href="delete_user.php?user_id=<?php echo urlencode($row['user_id']); ?>" onclick="return confirm('Are you sure you want to delete this instructor?');">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>

        <div class="section-title">Classes</div>
        <table>
            <tr>
                <th>Class Name</th><th>Difficulty</th><th>Capacity</th><th>Instructor</th><th>Actions</th>
            </tr>
            <?php while($row = $classes->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['difficulty_level']); ?></td>
                    <td><?php echo htmlspecialchars($row['capacity']); ?></td>
                    <td><?php echo htmlspecialchars($row['first_name'] ? $row['first_name'] . ' ' . $row['last_name'] : 'Unassigned'); ?></td>
                    <td>
                        <a href="edit_class.php?class_id=<?php echo urlencode($row['class_id']); ?>">Edit</a> |
                        <a href="delete_class.php?class_id=<?php echo urlencode($row['class_id']); ?>" onclick="return confirm('Are you sure you want to delete this class?');">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>

        <div class="section-title">Recent Bookings</div>
        <table>
            <tr>
                <th>Booking ID</th><th>Client</th><th>Class</th><th>Instructor</th><th>Status</th><th>Start Time</th><th>Location</th><th>Booked At</th><th>Actions</th>
            </tr>
            <?php while($row = $bookings->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['booking_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['client_first'] . ' ' . $row['client_last']); ?></td>
                    <td><?php echo htmlspecialchars($row['class_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['instructor_first'] . ' ' . $row['instructor_last']); ?></td>
                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                    <td><?php echo htmlspecialchars($row['start_time']); ?></td>
                    <td><?php echo htmlspecialchars($row['location']); ?></td>
                    <td><?php echo htmlspecialchars($row['booking_time']); ?></td>
                    <td>
                        <a href="edit_booking.php?booking_id=<?php echo urlencode($row['booking_id']); ?>">Edit</a> |
                        <a href="delete_booking.php?booking_id=<?php echo urlencode($row['booking_id']); ?>" onclick="return confirm('Are you sure you want to delete this booking?');">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>

        <form method="POST"action="logout.php">
          <div class="links">
              <button type="submit">Logout</button>
          </div>
        </form>
        
    </div>
</body>
</html>
<?php $conn->close(); ?>