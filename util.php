<?php 
function generateNewUserId($conn) {
  $sql = "SELECT MAX(CAST(SUBSTRING(user_id, 2) AS UNSIGNED)) AS last_user_id FROM users";
  $result = $conn->query($sql);
  $row = $result->fetch_assoc();
  $last_user_id = $row['last_user_id'] ?? 0;
  return 'u' . ($last_user_id + 1);
  // --->
}

function generateNewUserIdReview($conn) {
  $sql = "SELECT MAX(CAST(SUBSTRING(review_id, 2) AS UNSIGNED)) AS last_review_id FROM reviews";
  $result = $conn->query($sql);
  $row = $result->fetch_assoc();
  $last_review_id = $row['last_review_id'] ?? 0;
  return 'r' . ($last_review_id + 1);
}

function generateNewBookingId($conn) {
  $sql = "SELECT MAX(CAST(SUBSTRING(booking_id, 2) AS UNSIGNED)) AS last_book_id FROM bookings";
  $result = $conn->query($sql);
  $row = $result->fetch_assoc();
  $last_book_id = $row['last_book_id'] ?? 0;
  return 'b' . ($last_book_id + 1);
}


?>