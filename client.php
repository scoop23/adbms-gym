<?php 
session_start();

// Check if the session variable is set
if(isset($_SESSION["username"])) {
    $username = $_SESSION["username"];
} else {
    // If the session variable is not set, assign a default value or handle the error
    $username = 'Guest';  // Default or error message, can be handled differently if needed
}

?>
<!DOCTYPE html>
<html>
<head>
  <title>Client Dashboard | Gym Portal</title>
  <link rel="stylesheet" href="./css/client.css?v=<?php echo time(); ?>">
  <style>
    
  </style>
  <script>
    function toggleReviewForm(id) {
      let form = document.getElementById('review-form-' + id);
      if (form.style.display === 'flex') {
        form.style.display = 'none';
      } else {
        form.style.display = 'flex';
      }
    }
  </script>
</head>
<body>
  <div class="client-dashboard">
    <?php
      echo '<h1 class="client-title">Welcome, ' . htmlspecialchars($username) . '!</  h1>';
    ?>
    <div>
      <button>Logout</button>
    </div>
    <h2 style="text-align:center; color:#2d3a4b; margin-bottom:18px;">Our Instructors</h2>
    <div class="instructors-grid">
    <?php
    include "db.php";
    $sql = "SELECT 
      r.review_id,
      r.rating,
      r.comment,

      -- Reviewer's name
      reviewer.first_name AS reviewer_first_name,
      reviewer.last_name AS reviewer_last_name,

      -- Instructor's name
      instructor_user.first_name AS instructor_first_name,
      instructor_user.last_name AS instructor_last_name,

      i.bio,
      i.photo,
      i.specialties,
      i.instructor_id

      FROM instructors i

      -- Join to get instructor info
      LEFT JOIN reviews r ON r.instructor_id = i.instructor_id
      LEFT JOIN users instructor_user ON i.instructor_id = instructor_user.user_id

      -- Join to get reviewer info
      LEFT JOIN users reviewer ON r.user_id = reviewer.user_id
      LIMIT 8
      "
      ;

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $results = $stmt->get_result();
    
    $instructors = [];

    while($row = $results->fetch_assoc()) {
      $instructors[$row['instructor_first_name'] . ' ' . $row['instructor_last_name']][] = $row;
    }
    
    foreach($instructors as $instructor_name => $reviews) {
      $instructor = $reviews[0];
      $random_number = rand();
      echo '
        <div class="instructor-card">
          <img class="instructor-photo" src="uploads/'.htmlspecialchars($instructor['photo']).'" alt="Instructor '.  htmlspecialchars($instructor['instructor_first_name']) .'">
          <div class="instructor-name">'. htmlspecialchars($instructor_name) .'</div>
          <div class="instructor-specialty">'. htmlspecialchars($instructor['specialties']) .'</div>
          <div class="reviews-title">Client Reviews</div>
        ';
      
      if(empty($reviews[0]['review_id'])) {
        echo '
        <div class="no-review">
          No Reviews Yet.
         </div>
        <button class="book-btn">Book Session</button>
        <button class="review-btn" onclick="toggleReviewForm('.$random_number.')">Leave Review</button>
        <form method="POST" action="submit_review.php" class="review-form" id="review-form-'.$random_number.'">
        <input type="hidden" name="instructor_id" value="'.htmlspecialchars($instructor['instructor_id']).'">
          <textarea name="comment" placeholder="Write your review..."></textarea>
          <button type="submit">Submit</button>
        </form>
        </div>
        
        ';
        
      } else {
        $reviewCount = 0;
        foreach($reviews as $review){
          if(!empty($review['review_id'])){
          echo '
            <div class="review">
            <span class="review-client">- '.htmlspecialchars($review['reviewer_first_name']).'</span>
            "'. htmlspecialchars($review['comment']).'"
            </div>
          ';
          $reviewCount++;
          if($reviewCount >= 2) break;
          }
          
        }

        echo '
        <form method="POST" action="booking.php">
          <button type="submit" class="book-btn">Book Session</button>

        </form>
        <button class="review-btn" onclick="toggleReviewForm('.$random_number.')">Leave Review</button>
          <form method="POST" action="submit_review.php" class="review-form" id="review-form-'.htmlspecialchars($random_number).'">
            <input type="hidden" name="instructor_id" value="'. htmlspecialchars($instructor['instructor_id']).'">
            <textarea name="comment" placeholder="Write your review..." required></textarea>
            <button type="submit">Submit</button>
          </form>

        </div>
        ';
      }
        
    }
    ?>
    
      
      <!-- <div class="instructor-card">
        <img class="instructor-photo" src="https://randomuser.me/api/portraits/men/32.jpg" alt="Instructor John">
        <div class="instructor-name">John Carter</div>
        <div class="instructor-specialty">Strength & Conditioning</div>
        <div class="reviews-title">Client Reviews</div>
        <div class="review">
          <span class="review-client">- Mike</span>
          "John's workouts are intense but effective. Highly recommend!"
        </div>
        <div class="review">
          <span class="review-client">- Sarah</span>
          "Very motivating and knowledgeable trainer."
        </div>
        <button class="book-btn">Book Session</button>
        <button class="review-btn" onclick="toggleReviewForm(1)">Leave Review</button>
        <form class="review-form" id="review-form-1">
          <textarea placeholder="Write your review..."></textarea>
          <button type="submit">Submit</button>
        </form>
      </div> -->
      
      <!-- Instructor 2 -->
      
      <!-- Instructor 3 -->
      
      <!-- Instructor 4 -->
      
    </div>
  </div>
</body>
</html>