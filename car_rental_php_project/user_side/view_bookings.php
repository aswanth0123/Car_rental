<?php
session_start();

require_once '../db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    die("Please log in to view your bookings.");
}

$customer_id = $_SESSION['user_id']; // Get the logged-in customer ID
    
if($_SERVER['REQUEST_METHOD'] === 'POST') { 
    $rent_id = $_POST['rent_id'];
    $query1 = "update rent set status = 'cancelled' where rent_id = ?";
    $stmt1 = $conn->prepare($query1);
    $stmt1->bind_param("i", $rent_id);
    $stmt1->execute();  
}

// Fetch bookings for the current customer with location details
$query = "SELECT r.rent_id, r.vehicle_id, r.pickup_timestamp, r.return_timestamp, 
                 r.total_amount,r.status, l.LOC_NAME AS location, c.vehicle_name
          FROM rent r
          JOIN cars c ON r.vehicle_id = c.vehicle_id
          JOIN pickup_dropoff_location l ON r.location_id = l.ID
          WHERE r.customer_id = ?
          ORDER BY r.pickup_timestamp DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();
?>







<!doctype html>
<html lang="en">

  <head>
    <title>RIDE NOW RENTALS &mdash; Free Website Template by Colorlib</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700;900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../fonts/icomoon/style.css">

    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/bootstrap-datepicker.css">
    <link rel="stylesheet" href="../css/jquery.fancybox.min.css">
    <link rel="stylesheet" href="../css/owl.carousel.min.css">
    <link rel="stylesheet" href="../css/owl.theme.default.min.css">
    <link rel="stylesheet" href="../fonts/flaticon/font/flaticon.css">
    <link rel="stylesheet" href="../css/aos.css">

    <!-- MAIN CSS -->
    <link rel="stylesheet" href="../css/style.css">

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
                <a href="../index.php"><strong>RIDE NOW RENTALS</strong></a>
              </div>
            </div>

            <div class="col-9  text-right">
              
              <span class="d-inline-block d-lg-none"><a href="#" class=" site-menu-toggle js-menu-toggle py-5 "><span class="icon-menu h3 text-black"></span></a></span>

              <nav class="site-navigation text-right ml-auto d-none d-lg-block" role="navigation">
                <ul class="site-menu main-menu js-clone-nav ml-auto ">
                  <li class="active"><a href="../index.php" class="nav-link">Home</a></li>
                  <li><a href="../reviews.php" class="nav-link">Reviews</a></li>
                  <li><a href="../about.php" class="nav-link">About</a></li>
                  <?php
               
                  if (isset($_SESSION['user_id'])) {
                    echo '<li><a href="view_bookings.php" class="nav-link">Bookings</a></li>';
                    echo '<li><a href="profile.php" class="nav-link">profile</a></li>';
                    echo '<li><a href="../logout.php" class="nav-link">logout</a></li>';
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
                <h1><strong>Bookings</strong></h1>
                <div class="custom-breadcrumbs"><a href="../index.php">Home</a> <span class="mx-2">/</span> <strong>Bookings</strong></div>
              </div>

            </div>
          </div>
        </div>
      </div>
  



      

    <div class="site-section bg-light">
      <div class="container">
        

        <div class="row">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Rent ID</th>
                    <th>Car</th>
                    <th>Location</th>
                    <th>Pickup Date</th>
                    <th>Return Date</th>
                    <th>Total Amount</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['rent_id']; ?></td>
                        <td><?php echo htmlspecialchars($row['vehicle_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['location']); ?></td>
                        <td><?php echo date('d M Y', strtotime($row['pickup_timestamp'])); ?></td>
                        <td><?php echo date('d M Y', strtotime($row['return_timestamp'])); ?></td>
                        <td>₹<?php echo number_format($row['total_amount'], 2); ?></td>
                        <td>
                            <span class="badge bg-<?php 
                                echo ($row['status'] == 'confirmed') ? 'success' : 
                                    (($row['status'] == 'cancelled') ? 'danger' : 'warning'); ?>">
                                <?php echo ucfirst($row['status']); ?>
                            </span>
                        </td>
                        <td>
                        <?php 
                         $pickup_date = date('Y-m-d', strtotime($row['pickup_timestamp']));
                         $current_date = date('Y-m-d');
                        if ($row['status'] == 'confirmed'  && $pickup_date > $current_date) { ?>
                            <form method="POST" action="" onsubmit="return confirm('Are you sure you want to cancel this booking?');">
                                <input type="hidden" name="rent_id" value="<?php echo $row['rent_id']; ?>">
                                <button type="submit" class="btn btn-danger">Cancel</button>
                            </form>
                        <?php }
                        
                        else { ?>
                            <!-- <button class="btn btn-secondary" disabled>mark feedback</button> -->
                            <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#myModal">
                            mark feedback
  </button>
                        <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        </div>
    
      </div>
    </div>

    <div class="modal fade" id="myModal">
    <div class="modal-dialog">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Write your feedback</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
        <form action="mark_rev.php" method="POST">
            <div class="form-group">
                <label for="feedback">Feedback:</label>
                <input type="text" class="form-control" id="feedback" name="rev" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
        
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
              <p>Welcome to our Car Rental System, a convenient and user-friendly platform designed to simplify the process of renting a vehicle. Whether you need a car for a business trip, vacation, or daily commute, our system provides a seamless booking experience with a wide range of vehicles to choose from. </p>
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

    <script src="../js/jquery-3.3.1.min.js"></script>
    <script src="../js/popper.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/owl.carousel.min.js"></script>
    <script src="../js/jquery.sticky.js"></script>
    <script src="../js/jquery.waypoints.min.js"></script>
    <script src="../js/jquery.animateNumber.min.js"></script>
    <script src="../js/jquery.fancybox.min.js"></script>
    <script src="../js/jquery.easing.1.3.js"></script>
    <script src="../js/bootstrap-datepicker.min.js"></script>
    <script src="../js/aos.js"></script>

    <script src="../js/main.js"></script>

  </body>

</html>

