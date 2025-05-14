<?php 
session_start();
include "util.php";
include "db.php";

if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'instructor'){
  header("Location: login.php");
  exit();
}

$instructor_id = $_SESSION['user_id'];
$message = "";

if($_SERVER["REQUEST_METHOD"] === "POST"){
  $class_id = generateNewClassId($conn);
  $name = $_POST['name'];
  $description = $_POST['description'];
  $difficulty_level = $_POST['difficulty_level'];
  $duration = $_POST['duration'];
  $capacity = $_POST['capacity'];


  $stmt = $conn->prepare("INSERT INTO classes (class_id, `name`, `description`, difficulty_level, duration, capacity) VALUES (?, ?, ?, ?, ? ,?)");
  $stmt->bind_param($class_id , $name, $description, $difficulty_level, $duration, $capacity);

  if($stmt->execute()){
    $schedule_id = generateNewScheduleId($conn);
    $start_time = $_POST('start_time');
    $end_time = $_POST('end_time');
    $location = $_POST('location');

    $stmt2 = $conn->prepare("INSERT INTO schedules (schedule_id, start_time, end_time , `location`) VALUES (?, ?, ?, ?)");
    $stmt2->bind_param($schedule_id, $start_time, $end_time, $location);
    if($stmt2->execute()){
      $message = "Class and Schedule Added Successfully.";
    } else {
      $message = "Something went wrong.";
    }
  } else {
    $message = "Failed to add class: ". $conn->error;
  }

}


?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./css/classes.css?v=<?php echo time(); ?>">
  <title>Document</title>
</head>
<body>
  <div class="add-class-container">
        <h1 class="add-class-title">Add a New Class</h1>
        <?php if ($message): ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <form class="add-class-form" method="POST" action="classes.php">
            <label for="name">Class Name</label>
            <input type="text" id="name" name="name" required>

            <label for="description">Description</label>
            <textarea id="description" name="description" required></textarea>

            <label for="difficulty_level">Difficulty Level</label>
            <select id="difficulty_level" name="difficulty_level" required>
                <option value="">Select</option>
                <option value="Beginner">Beginner</option>
                <option value="Intermediate">Intermediate</option>
                <option value="Advanced">Advanced</option>
            </select>

            <label for="duration">Duration (minutes)</label>
            <input type="number" id="duration" name="duration" min="1" required>

            <label for="capacity">Capacity</label>
            <input type="number" id="capacity" name="capacity" min="1" required>

            <label for="start_time">Start Time</label>
            <input type="datetime-local" id="start_time" name="start_time" required>

            <label for="end_time">End Time</label>
            <input type="datetime-local" id="end_time" name="end_time" required>

            <label for="location">Location</label>
            <input type="text" id="location" name="location" required>

            <button type="submit">Add Class</button>
        </form>
        <div class="links">
            <a href="instructor.php">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
<?php $conn->close(); ?>
  
</body>
</html>