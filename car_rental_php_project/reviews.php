<?php
// Include database connection
require_once 'db.php';
session_start();
$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $date = date("Y-m-d H:i:s");
    $rev = $_POST['rev'];
    $query = "insert into Feedback (CustomerID,FeedbackDescription,ReviewDateTime)
    values(?,?,?) ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('iss',$user_id,$rev,$date);
    if ($stmt->execute()) {
        echo "";
    } else {
        echo "Error: " . $conn->error;
    }

}

$sql = "SELECT f.FeedbackID, f.FeedbackDescription, f.ReviewDateTime, c.name AS customer_name 
        FROM feedback f 
        JOIN users c ON f.CustomerID = c.user_id 
        ORDER BY f.ReviewDateTime DESC";

$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

?>




<!doctype html>
<html lang="en">

  <head>
    <title>RIDE NOW RENTALS</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700;900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="fonts/icomoon/style.css">

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap-datepicker.css">
    <link rel="stylesheet" href="css/jquery.fancybox.min.css">
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">
    <link rel="stylesheet" href="fonts/flaticon/font/flaticon.css">
    <link rel="stylesheet" href="css/aos.css">

    <!-- MAIN CSS -->
    <link rel="stylesheet" href="css/style.css">

  </head>

  <body>

    
    <div class="site-wrap" id="home-section">

      <div class="site-mobile-menu site-navbar-target">
        <div class="site-mobile-menu-header">
          <div class="site-mobile-menu-close mt-3">
            <span class="icon-close2 js-menu-toggle"></span>
          </div>
        </div>
        <div class="site-mobile-menu-body"></div>
      </div>



      <header class="site-navbar site-navbar-target" role="banner">

        <div class="container">
          <div class="row align-items-center position-relative">

            <div class="col-3">
              <div class="site-logo">
                <a href="index.html"><strong>RIDE NOW RENTALS</strong></a>
              </div>
            </div>

            <div class="col-9  text-right">
              
              <span class="d-inline-block d-lg-none"><a href="#" class=" site-menu-toggle js-menu-toggle py-5 "><span class="icon-menu h3 text-black"></span></a></span>

              <nav class="site-navigation text-right ml-auto d-none d-lg-block" role="navigation">
                <ul class="site-menu main-menu js-clone-nav ml-auto ">
                  <li class="active"><a href="index.php" class="nav-link">Home</a></li>
                  <li><a href="reviews.php" class="nav-link">Reviews</a></li>
                  <li><a href="about.php" class="nav-link">About</a></li>
                  <?php
               
                  if (isset($_SESSION['user_id'])) {
                    echo '<li><a href="user_side/view_bookings.php" class="nav-link">Bookings</a></li>';
                    echo '<li><a href="user_side/profile.php" class="nav-link">profile</a></li>';
                    echo '<li><a href="logout.php" class="nav-link">logout</a></li>';
                  } else {
                      echo '<li><a href="login.php" class="nav-link">login</a></li>';
                  }
                  ?>
                </ul>
              </nav>
            </div>

            
          </div>
        </div>

      </header>
      
      <div class="hero inner-page" style="background-image: url('images/hero_1_a.jpg');">
        
        <div class="container">
          <div class="row align-items-end ">
            <div class="col-lg-5">

              <div class="intro">
                <h1><strong>Reviews</strong></h1>
                <div class="custom-breadcrumbs"><a href="index.php">Home</a> <span class="mx-2">/</span> <strong>Reviews</strong></div>
              </div>

            </div>
          </div>
        </div>
      </div>



    <div class="site-section bg-light">
      <div class="container">
        <div class="row">
          <div class="col-lg-7">
            <h2 class="section-heading"><strong>Reviews</strong></h2>
            <p>Some customers have reviewed us.</p>    
            <?php
              if (isset($_SESSION['user_id'])) {
                ?>
                <form action="" method="POST">
                <div class="form-group row">
                <div class="col-md-6 mb-4 mb-lg-0">
                  <input type="text" class="form-control" placeholder="Your review" name="rev" required>
                </div>
                <div class="col-md-6">
                  <input type="submit" class="btn btn-info mt-2" >
                </div>
              </div>
          </form>
          <?php

              }

            ?>
                    </div>

        </div>
        <div class="row">
          <?php
          foreach($result as $rev){
            ?>
          <div class="col-lg-4 mb-4">
          <div class="testimonial-2">
            <blockquote class="mb-4" style="width:100%">
              <p>"<?php echo htmlspecialchars($rev['FeedbackDescription']); ?>"</p>
            </blockquote>
            <div class="d-flex v-card align-items-center">
              <img src="images/person_1.jpg" alt="Image" class="img-fluid mr-3">
              <div class="author-name">
                <span class="d-block"><?php echo htmlspecialchars($rev['customer_name']); ?></span>
                <span>User</span>
              </div>
            </div>
          </div>
        </div>
        <?php
          }
          ?>
          

        </div>
      </div>
    </div>

    <div class="site-section bg-primary py-5">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-lg-7 mb-4 mb-md-0">
            <h2 class="mb-0 text-white">What are you waiting for?</h2>
            <p class="mb-0 opa-7"> Book your ideal car now and enjoy a seamless rental experience tailored just for you!</p>
          </div>
          <div class="col-lg-5 text-md-right">
            <a href=".listing" class="btn btn-primary btn-white">Rent a car now</a>
          </div>
        </div>
      </div>
    </div>

      
      <footer class="site-footer">
        <div class="container">
          <div class="row">
            <div class="col-lg-3">
              <h2 class="footer-heading mb-4">About Us</h2>
              <p>Welcome to our Car Rental System, a convenient and user-friendly platform designed to simplify the process of renting a vehicle. Whether you need a car for a business trip, vacation, or daily commute, our system provides a seamless booking experience with a wide range of vehicles to choose from.</p>
              <ul class="list-unstyled social">
                <li><a href="#"><span class="icon-facebook"></span></a></li>
                <li><a href="#"><span class="icon-instagram"></span></a></li>
                <li><a href="#"><span class="icon-twitter"></span></a></li>
                <li><a href="#"><span class="icon-linkedin"></span></a></li>
              </ul>
            </div>
            <div class="col-lg-8 ml-auto">
              <div class="row">
                <div class="col-lg-3">
                  <h2 class="footer-heading mb-4">Quick Links</h2>
                  <ul class="list-unstyled">
                    <li><a href="#">About Us</a></li>
                    <li><a href="reviews.php">Reviews</a></li>
                    <li><a href="#">Terms of Service</a></li>
                    <li><a href="#">Privacy</a></li>
                    <li><a href="contact.php">Contact Us</a></li>
                  </ul>
                </div>
                <div class="col-lg-3">
                  <h2 class="footer-heading mb-4">Resources</h2>
                  <ul class="list-unstyled">
                    <li><a href="#">About Us</a></li>
                    <li><a href="reviews.php">Reviews</a></li>
                    <li><a href="#">Terms of Service</a></li>
                    <li><a href="#">Privacy</a></li>
                    <li><a href="contact.php">Contact Us</a></li>
                  </ul>
                </div>
                <div class="col-lg-3">
                  <h2 class="footer-heading mb-4">Support</h2>
                  <ul class="list-unstyled">
                    <li><a href="#">About Us</a></li>
                    <li><a href="reviews.php">Reviews</a></li>
                    <li><a href="#">Terms of Service</a></li>
                    <li><a href="#">Privacy</a></li>
                    <li><a href="contact.php">Contact Us</a></li>
                  </ul>
                </div>
                <div class="col-lg-3">
                  <h2 class="footer-heading mb-4">Company</h2>
                  <ul class="list-unstyled">
                    <li><a href="#">About Us</a></li>
                    <li><a href="reviews.php">Reviews</a></li>
                    <li><a href="#">Terms of Service</a></li>
                    <li><a href="#">Privacy</a></li>
                    <li><a href="contact.php">Contact Us</a></li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
          <div class="row pt-5 mt-5 text-center">
            <div class="col-md-12">
              <div class="border-top pt-5">
                <p>
              <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
              Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved | This template is made with RIDE NOW RENTALS</a>
              <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
              </p>
              </div>
            </div>

          </div>
        </div>
      </footer>

    </div>

    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/jquery.sticky.js"></script>
    <script src="js/jquery.waypoints.min.js"></script>
    <script src="js/jquery.animateNumber.min.js"></script>
    <script src="js/jquery.fancybox.min.js"></script>
    <script src="js/jquery.easing.1.3.js"></script>
    <script src="js/bootstrap-datepicker.min.js"></script>
    <script src="js/aos.js"></script>

    <script src="js/main.js"></script>

  </body>

</html>

