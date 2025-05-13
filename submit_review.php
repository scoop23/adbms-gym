<?php
session_start();
include "db.php";
include "util.php";


if (!isset($_SESSION["user_id"])) {
    die("You must be logged in to submit a review.");
}
$review_id = generateNewUserIdReview($conn);
$user_id = $_SESSION["user_id"];
$instructor_id = $_POST["instructor_id"];
$comment = trim($_POST["comment"]);


$rating = isset($_POST["rating"]) ? intval($_POST["rating"]) : NULL;

// Validattion
if (empty($comment) || empty($instructor_id)) {
    die("Missing required data.");
}

// Insert into the reviews table
$sql = "INSERT INTO reviews (review_id , instructor_id, user_id,`rating`, comment) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssis", $review_id, $instructor_id, $user_id, $rating, $comment);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo "success";
    header("Location: client_dashboard.php?review=success");  // redirect back to dashboard
    exit();
} else {
    echo "Failed to submit review.";
}
