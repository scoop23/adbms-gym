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
  <link href="./css/client.css" rel="stylesheet">
  <style>
    
  </style>
  <script>
    function toggleReviewForm(id) {
      var form = document.getElementById('review-form-' + id);
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
    
    <h2 style="text-align:center; color:#2d3a4b; margin-bottom:18px;">Our Instructors</h2>
    <div class="instructors-grid">
      <!-- Instructor 1 -->
      <div class="instructor-card">
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
      </div>
      <!-- Instructor 2 -->
      <div class="instructor-card">
        <img class="instructor-photo" src="https://randomuser.me/api/portraits/women/44.jpg" alt="Instructor Lisa">
        <div class="instructor-name">Lisa Smith</div>
        <div class="instructor-specialty">Yoga & Flexibility</div>
        <div class="reviews-title">Client Reviews</div>
        <div class="review">
          <span class="review-client">- Emma</span>
          "Lisa's yoga classes helped me improve my flexibility a lot!"
        </div>
        <div class="review">
          <span class="review-client">- Alex</span>
          "Very calming and professional approach."
        </div>
        <button class="book-btn">Book Session</button>
        <button class="review-btn" onclick="toggleReviewForm(2)">Leave Review</button>
        <form class="review-form" id="review-form-2">
          <textarea placeholder="Write your review..."></textarea>
          <button type="submit">Submit</button>
        </form>
      </div>
      <!-- Instructor 3 -->
      <div class="instructor-card">
        <img class="instructor-photo" src="https://randomuser.me/api/portraits/men/65.jpg" alt="Instructor Mark">
        <div class="instructor-name">Mark Lee</div>
        <div class="instructor-specialty">Cardio & HIIT</div>
        <div class="reviews-title">Client Reviews</div>
        <div class="review">
          <span class="review-client">- Olivia</span>
          "Mark's HIIT sessions are challenging but fun!"
        </div>
        <div class="review">
          <span class="review-client">- Daniel</span>
          "Great energy and always pushes you to do your best."
        </div>
        <button class="book-btn">Book Session</button>
        <button class="review-btn" onclick="toggleReviewForm(3)">Leave Review</button>
        <form class="review-form" id="review-form-3">
          <textarea placeholder="Write your review..."></textarea>
          <button type="submit">Submit</button>
        </form>
      </div>
      <!-- Instructor 4 -->
      <div class="instructor-card">
        <img class="instructor-photo" src="https://randomuser.me/api/portraits/women/68.jpg" alt="Instructor Anna">
        <div class="instructor-name">Anna Brown</div>
        <div class="instructor-specialty">Personal Training</div>
        <div class="reviews-title">Client Reviews</div>
        <div class="review">
          <span class="review-client">- Chris</span>
          "Anna created a personalized plan that really works for me."
        </div>
        <div class="review">
          <span class="review-client">- Mia</span>
          "Very supportive and attentive to my goals."
        </div>
        <button class="book-btn">Book Session</button>
        <button class="review-btn" onclick="toggleReviewForm(4)">Leave Review</button>
        <form class="review-form" id="review-form-4">
          <textarea placeholder="Write your review..."></textarea>
          <button type="submit">Submit</button>
        </form>
      </div>
    </div>
  </div>
</body>
</html>