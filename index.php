<!DOCTYPE html>
<html>
<head>
  <title>Gym Portal</title>
  <link rel="stylesheet" href="./css/styles.css?v=<?php echo time(); ?>">
  <style>
    
    
  </style>
</head>
<body>
  <div class="main-container">
    <h1 class="title">Welcome to the Gym Portal</h1>
    <div class="links">
      <a href="login.php"><button>Login</button></a>
      <a href="register.php"><button>Register</button></a>
      <a href="registerInstructor.php"><button>Become an Instructor</button></a>
    </div>
    <h2 style="text-align:center; color:#2d3a4b; margin-bottom:18px;">Meet Our Instructors</h2>
    <div class="instructors-grid">
      <!-- Instructor 1 -->
      <?php

  // SQL to fetch all instructors and their reviews
  include 'db.php';

  // SQL to fetch all instructors and their reviews
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
      i.photo

      FROM reviews r

      -- Join to get instructor info
      JOIN instructors i ON r.instructor_id = i.instructor_id
      JOIN users instructor_user ON i.instructor_id = instructor_user.user_id

      -- Join to get reviewer info
      JOIN users reviewer ON r.user_id = reviewer.user_id
      LIMIT 8
      "
      ;


// Prepare and execute the query
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();

    // Initialize an array to group reviews by instructor
    $instructors = [];

    while($row = $result->fetch_assoc()){
        // Group reviews by instructor
        $instructors[$row['instructor_first_name'] . ' ' . $row['instructor_last_name']][] = $row;
    }

    if(count($instructors) > 0){
        // Loop through each instructor
        foreach($instructors as $instructor_name => $reviews){
            
            // Get the first review for the instructor (to get their photo and bio)
            $instructor = $reviews[0];
            
            // Output the instructor's card with reviews
            echo '<div class="instructor-card">
                    <img class="instructor-photo" src="uploads/'. htmlspecialchars($instructor['photo']) . '" alt="Instructor Photo">
                    <div class="instructor-name">' . htmlspecialchars($instructor_name) . '</div>
                    <div class="instructor-specialty">' . htmlspecialchars($instructor['bio']) . '</div>
                    <div class="reviews-title">Client Reviews</div>';

            // Output each review for this instructor
            if(count($reviews) === 0) {
              echo "Nothing to See.";
            } else {
              $reviewCount = 0;
              foreach($reviews as $review){
                if(!empty($review['review_id'])){
                  echo '
                    <div class="review">
                        <span class="review-client">- ' . htmlspecialchars($review['reviewer_first_name']) . '</span>
                        ' . htmlspecialchars($review['comment']) . '
                    </div>';
                    $reviewCount++;
                    if($reviewCount >= 3) break;
                }
                
              }
            echo '</div>';  // Close the instructor card
            }
        }
    } else {
        echo 'No reviews found.';
    }
 
      ?>
      <!-- Instructor 2 -->
      
      <!-- Instructor 3 -->
      
      <!-- Instructor 4 -->
      
    </div>
  </div>
</body>
</html>
