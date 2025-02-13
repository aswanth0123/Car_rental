<?php
// Include database connection
require_once 'db.php';
session_start();

// Fetch all cars
$query = "SELECT c.*, cat.category_name FROM cars c 
          JOIN car_categories cat ON c.CAT_ID = cat.ID";
$result = $conn->query($query);


$sql = "SELECT f.FeedbackID, f.FeedbackDescription, f.ReviewDateTime, c.name AS customer_name 
        FROM feedback f 
        JOIN users c ON f.CustomerID = c.user_id 
        ORDER BY f.ReviewDateTime DESC limit 3";

$stmt = $conn->prepare($sql);
$stmt->execute();
$result1 = $stmt->get_result();

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

      
      <div class="hero" style="background-image: url('images/hero_1_a.jpg');">
        
        <div class="container">
          <div class="row align-items-center justify-content-center">
            <div class="col-lg-10">

              <div class="row mb-5">
                <div class="col-lg-7 intro">
                  <h1><strong>Rent a car</strong> is within your finger tips.</h1>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
  


      <div class="site-section">
        <div class="container">
          <h2 class="section-heading"><strong>How it works?</strong></h2>
          <p class="mb-5">Easy steps to get you started</p>    

          <div class="row mb-5">
            <div class="col-lg-4 mb-4 mb-lg-0">
              <div class="step">
                <span>1</span>
                <div class="step-inner">
                  <span class="number text-primary">01.</span>
                  <h3>Select a car</h3>
                  <p>Browse through a variety of available vehicles and choose the one that best suits your needs and preferences.</p>
                </div>
              </div>
            </div>
            <div class="col-lg-4 mb-4 mb-lg-0">
              <div class="step">
                <span>2</span>
                <div class="step-inner">
                  <span class="number text-primary">02.</span>
                  <h3>Fill up form</h3>
                  <p>Provide your personal details, rental dates, and any additional requirements in the booking form to proceed with the reservation.</p>
                </div>
              </div>
            </div>
            <div class="col-lg-4 mb-4 mb-lg-0">
              <div class="step">
                <span>3</span>
                <div class="step-inner">
                  <span class="number text-primary">03.</span>
                  <h3>Payment</h3>
                  <p>Securely complete the transaction using your preferred payment method to confirm your car rental booking.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      


      

    <div class="site-section bg-light">
      <div class="container">
        <div class="row">
          <div class="col-lg-7">
            <h2 class="section-heading"><strong>Car Listings</strong></h2>
            <p class="mb-5">Explore a wide range of vehicles with detailed descriptions, pricing, and availability to find the perfect car for your rental needs.</p>    
          </div>
        </div>
        

        <div class="row">
        <?php while ($car = $result->fetch_assoc()): ?>

          <div class="col-md-6 col-lg-4 mb-4">

            <div class="listing d-block  align-items-stretch">
              <div class="listing-img h-100 mr-4">
                <img src="uploads/<?php echo htmlspecialchars($car['vehicle_image']); ?>" alt="Image" class="img-fluid">
              </div>
              <div class="listing-contents h-100">
                <h3><?php echo htmlspecialchars($car['vehicle_name']); ?></h3>
                <div class="rent-price">
                  <strong>₹<?php echo htmlspecialchars($car['price']); ?></strong><span class="mx-1">/</span>day
                </div>
                <div class="d-block d-md-flex mb-3 border-bottom pb-3">
                  <div class="listing-feature pr-4">
                    <span class="caption">seatings:</span>
                    <span class="number"><?php echo htmlspecialchars($car['seating_capacity']); ?></span>
                  </div>
                  <div class="listing-feature pr-4">
                    <span class="caption">fuel_type:</span>
                    <span class="number"><?php echo htmlspecialchars($car['fuel_type']); ?></span>
                  </div>
                  <div class="listing-feature pr-4">
                    <span class="caption">gear_type:</span>
                    <span class="number"><?php echo htmlspecialchars($car['gear_type']); ?></span>
                  </div>
                </div>
                <div>
                <div class="d-block d-md-flex mb-3 border-bottom pb-3">
                  <div class="listing-feature pr-4">
                    <span class="caption">vehicle_number:</span>
                    <span class="number"><?php echo htmlspecialchars($car['vehicle_number']); ?></span>
                  </div>
                  <div class="listing-feature pr-4">
                    <span class="caption">rc_number:</span>
                    <span class="number"><?php echo htmlspecialchars($car['rc_number']); ?></span>
                  </div>
                  <div class="listing-feature pr-4">
                    <span class="caption">ac_status:</span>
                    <span class="number"><?php echo htmlspecialchars($car['ac_status']); ?></span>
                  </div>
                </div>
                  <p><?php echo htmlspecialchars($car['vehicle_name']); ?> – A premium <?php echo htmlspecialchars($car['category_name']); ?> 
                  with <?php echo htmlspecialchars($car['seating_capacity']); ?>-seater capacity,
                  <?php echo htmlspecialchars($car['gear_type']); ?> transmission, and <?php echo htmlspecialchars($car['ac_status']); ?>, perfect for comfortable trips.</p>
                  <?php
               
               if (isset($_SESSION['user_id'])) {
                 echo '<p><a href="user_side/rent.php?car_id=' . $car['vehicle_id'] . '" class="btn btn-primary btn-sm">Rent Now</a></p>';
               } else {
                   echo '<p><a href="login.php" class="btn btn-primary btn-sm">Rent Now</a></p>';
               }
               ?>
                  
                  
                </div>
              </div>

            </div>
          </div>
        <?php endwhile; ?>

       
          

          

          

          
          

          

        </div>
      </div>
    </div>



    <div class="site-section bg-light">
      <div class="container">
        <div class="row">
          <div class="col-lg-7">
            <h2 class="section-heading"><strong>Feedback</strong></h2>
            <p class="mb-5">Some Customers Feedbacks</p>    
          </div>
        </div>
        <div class="row">
        <?php
          foreach($result1 as $rev){
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

