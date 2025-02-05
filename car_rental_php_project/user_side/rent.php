<?php
require_once '../db.php';
session_start();

// Check if vehicle_id is provided
if (!isset($_GET['car_id'])) {
    die("Vehicle ID not provided.");
}
$user_id = $_SESSION['user_id'];

$vehicle_id = intval($_GET['car_id']);

// Fetch vehicle details
$query = "SELECT c.*, cat.category_name FROM cars c 
          JOIN car_categories cat ON c.CAT_ID = cat.ID 
          WHERE c.vehicle_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $vehicle_id);
$stmt->execute();
$result = $stmt->get_result();
$car = $result->fetch_assoc();
$price_per_day = $car['price'];

if (!$car) {
    die("Car not found.");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_id = 1; // Replace with the logged-in user's ID
    $pickup = $_POST['pickup'];
    $dropoff = $_POST['dropoff'];
    $loc = $pickup . '-' . $dropoff;
    $query1 = "INSERT INTO pickup_dropoff_location (LOC_NAME) VALUES (?)";
    $stmt1 = $conn->prepare($query1);
    $stmt1->bind_param("s",$loc);
    $stmt1->execute();
    $location_id = $conn->insert_id;
    $rent_date = date("Y-m-d H:i:s");



    $pickup_date = $_POST['pickup_date'];
    $return_date = $_POST['dropoff_date'];
    $total_rent = $_POST['total'];

    // Insert into rent table
    $query = "INSERT INTO rent (customer_id, vehicle_id,RENT_TIMESTAMP, PICKUP_TIMESTAMP, RETURN_TIMESTAMP, LOCATION_ID, TOTAL_AMOUNT)
              VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iisssid", $user_id, $vehicle_id, $rent_date, $pickup_date, $return_date, $location_id, $total_rent);
    
    if ($stmt->execute()) {
        echo "<script>alert('Car rented successfully!'); window.location='../index.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>




<!doctype html>
<html lang="en">

  <head>
    <title>CarRental &mdash; Free Website Template by Colorlib</title>
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
                <a href="index.html"><strong>CarRental</strong></a>
              </div>
            </div>

            <div class="col-9  text-right">
              
              <span class="d-inline-block d-lg-none"><a href="#" class=" site-menu-toggle js-menu-toggle py-5 "><span class="icon-menu h3 text-black"></span></a></span>

              <nav class="site-navigation text-right ml-auto d-none d-lg-block" role="navigation">
                <ul class="site-menu main-menu js-clone-nav ml-auto ">
                  <li><a href="../index.php" class="nav-link">Home</a></li>
                  <li><a href="listing.html" class="nav-link">Listing</a></li>
                  <li><a href="testimonials.html" class="nav-link">Testimonials</a></li>
                  <li><a href="blog.html" class="nav-link">Blog</a></li>
                  <li><a href="about.html" class="nav-link">About</a></li>
                  <li class=""><a href="contact.html" class="nav-link">Contact</a></li>
                  <?php
               
               if (isset($_SESSION['user_id'])) {
                 echo '<li><a href="logout.php" class="nav-link">Bookings</a></li>';
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

      
      <div class="hero inner-page" style="background-image: url('../images/hero_1_a.jpg');">
        
        <div class="container">
          <div class="row align-items-end ">
            <div class="col-lg-5">

              <div class="intro">
                <h1><strong>About</strong></h1>
                <div class="custom-breadcrumbs"><a href="index.html">Home</a> <span class="mx-2">/</span> <strong>About</strong></div>
              </div>

            </div>
          </div>
        </div>
      </div>

    

    <div class="site-section bg-light" id="contact-section">
      <div class="container">
        <div class="row justify-content-center text-center">
        <div class="col-7 text-center mb-5">
          <h2>Contact Us Or Use This Form To Rent A Car</h2>
          <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nemo assumenda, dolorum necessitatibus eius earum voluptates sed!</p>
        </div>
      </div>
        <div class="row">
          <div class="col-lg-8 mb-5" >
            <form action="" method="post">
              <div class="form-group row">
                <div class="col-md-6 mb-4 mb-lg-0">
                  <input type="text" class="form-control" placeholder="pickup location" name="pickup">
                </div>
                <div class="col-md-6">
                  <input type="text" class="form-control" placeholder="dropoff location" name="dropoff">
                </div>
              </div>
              <div class="form-group row">
  
              <div class="col-md-6">
                <label for="">Pickup Date & Time</label>
                  <input type="datetime-local" class="form-control" id="pickupDate" name="pickup_date" placeholder="Pickup Date & Time">
              </div>
              <div class="col-md-6">
              <label for="">Dropoff Date & Time</label>

                  <input type="datetime-local" class="form-control" id="dropoffDate" name="dropoff_date" placeholder="Dropoff Date & Time">
              </div>
            </div>
            <div class="form-group row">

                <div class="col-md-6">
                    <Label>Amount to Pay</Label>
                    <input type="text" class="form-control" id="total" name="total" placeholder="0" readonly>
                </div>
            </div>
              <div class="form-group row">
                <div class="col-md-6 mr-auto">
                  <input type="submit" class="btn btn-block btn-primary text-white py-3 px-5" value="Rent Now">
                </div>
                
              </div>
            </form>
          </div>
          <div class="col-lg-4 ml-auto">
            <div class="bg-white p-3 p-md-5">
              <h3 class="text-black mb-4">Contact Info</h3>
              <ul class="list-unstyled footer-link">
                <li class="d-block mb-3">
                  <span class="d-block text-black">Address:</span>
                  <span>34 Street Name, City Name Here, United States</span></li>
                <li class="d-block mb-3"><span class="d-block text-black">Phone:</span><span>+1 242 4942 290</span></li>
                <li class="d-block mb-3"><span class="d-block text-black">Email:</span><span>info@yourdomain.com</span></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>


      
      <footer class="site-footer">
        <div class="container">
          <div class="row">
            <div class="col-lg-3">
              <h2 class="footer-heading mb-4">About Us</h2>
              <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. </p>
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
                    <li><a href="#">Testimonials</a></li>
                    <li><a href="#">Terms of Service</a></li>
                    <li><a href="#">Privacy</a></li>
                    <li><a href="#">Contact Us</a></li>

                  </ul>
                </div>
                <div class="col-lg-3">
                  <h2 class="footer-heading mb-4">Resources</h2>
                  <ul class="list-unstyled">
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">Testimonials</a></li>
                    <li><a href="#">Terms of Service</a></li>
                    <li><a href="#">Privacy</a></li>
                    <li><a href="#">Contact Us</a></li>
                  </ul>
                </div>
                <div class="col-lg-3">
                  <h2 class="footer-heading mb-4">Support</h2>
                  <ul class="list-unstyled">
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">Testimonials</a></li>
                    <li><a href="#">Terms of Service</a></li>
                    <li><a href="#">Privacy</a></li>
                    <li><a href="#">Contact Us</a></li>
                  </ul>
                </div>
                <div class="col-lg-3">
                  <h2 class="footer-heading mb-4">Company</h2>
                  <ul class="list-unstyled">
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">Testimonials</a></li>
                    <li><a href="#">Terms of Service</a></li>
                    <li><a href="#">Privacy</a></li>
                    <li><a href="#">Contact Us</a></li>
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
              Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved | This template is made with <i class="icon-heart text-danger" aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank" >Colorlib</a>
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
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const pickupInput = document.getElementById("pickupDate");
            const dropoffInput = document.getElementById("dropoffDate");

            if (pickupInput && dropoffInput) {
                // Set minimum pickup date (tomorrow)
                const now = new Date();
                now.setDate(now.getDate() + 1);
                pickupInput.min = now.toISOString().slice(0, 16);

                // Update dropoff date when pickup date is selected
                pickupInput.addEventListener("change", function () {
                    if (pickupInput.value) {
                        let pickupDate = new Date(pickupInput.value);
                        pickupDate.setDate(pickupDate.getDate() + 1); // Add 1 day for dropoff

                        dropoffInput.min = pickupDate.toISOString().slice(0, 16);
                        dropoffInput.value = dropoffInput.min; // Set default dropoff date
                        let pricePerDay = <?php echo $price_per_day; ?>;
                        let totalAmountField = document.getElementById("total");
                        totalAmountField.value = pricePerDay;
                    }
                });
                dropoffInput.addEventListener("change", function () {
                    let pricePerDay = <?php echo $price_per_day; ?>;
                    let totalAmountField = document.getElementById("total");
                    let pickup = new Date(pickupDate.value);
                let returnD = new Date(dropoffDate.value);
                let timeDiff = returnD - pickup;
                let days = timeDiff / (1000 * 3600 * 24);
                    console.log(days);
                    if (days<1) {
                        totalAmountField.value = pricePerDay;

                    }else{
                        let totalAmount = days * pricePerDay;
                        totalAmountField.value = totalAmount.toFixed(2);
                    }
                    
                

                    
                
                })
            }
        });
    </script> 
  </body>

</html>

