<?php
session_start();
include "db.php";
include "util.php";

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle booking form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['schedule_id'])) {
    $schedule_id = $_POST['schedule_id'];
    $status = 'pending';
    $booking_id = generateNewBookingId($conn);

    $stmt = $conn->prepare("INSERT INTO bookings (booking_id, schedule_id, user_id, status) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $booking_id, $schedule_id, $user_id, $status);

    if ($stmt->execute()) {
        $message = "Booking successful!";
    } else {
        $message = "Booking failed: " . $stmt->error;
    }
}

// Fetch available schedules with class and instructor info
$sql = "SELECT 
            s.schedule_id, s.start_time, s.end_time, s.location,
            c.name AS class_name, c.difficulty_level, c.duration,
            u.first_name AS instructor_first_name, u.last_name AS instructor_last_name
        FROM schedules s
        JOIN classes c ON s.class_id = c.class_id
        JOIN instructors i ON s.instructor_id = i.instructor_id
        JOIN users u ON i.instructor_id = u.user_id
        WHERE s.start_time > NOW()
        ORDER BY s.start_time ASC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Book a Session | Gym Portal</title>
    <link rel="stylesheet" href="./css/booking.css?v=<?php echo time(); ?>">
    
</head>
<body>
    <div class="booking-container">
        <h1 class="booking-title">Book a Gym Session</h1>
        <?php if (!empty($message)): ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <table class="schedule-table">
            <tr>
                <th>Class</th>
                <th>Instructor</th>
                <th>Difficulty</th>
                <th>Duration (min)</th>
                <th>Location</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Book</th>
            </tr>
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['class_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['instructor_first_name'] . ' ' . $row['instructor_last_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['difficulty_level']); ?></td>
                        <td><?php echo htmlspecialchars($row['duration']); ?></td>
                        <td><?php echo htmlspecialchars($row['location']); ?></td>
                        <td><?php echo htmlspecialchars($row['start_time']); ?></td>
                        <td><?php echo htmlspecialchars($row['end_time']); ?></td>
                        <td>
                            <form method="POST" action="booking.php">
                                <input type="hidden" name="schedule_id" value="<?php echo htmlspecialchars($row['schedule_id']); ?>">
                                <button type="submit" class="book-btn">Book</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8">No available sessions at the moment.</td>
                </tr>
            <?php endif; ?>
        </table>
        <div class="links">
            <a href="client.php">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>