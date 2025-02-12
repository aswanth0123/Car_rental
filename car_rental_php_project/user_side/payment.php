<?php
require_once '../db.php';
session_start();

// Check if vehicle_id is provided
if (!isset($_GET['rent_id'])) {
    die("Vehicle ID not provided.");
}
$user_id = $_SESSION['user_id'];

$rent_id = intval($_GET['rent_id']);

$query = "SELECT r.TOTAL_AMOUNT, u.name
          FROM rent r
          JOIN users u ON r.customer_id = u.user_id
          WHERE r.rent_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $rent_id);
$stmt->execute();
$result = $stmt->get_result();
$rent_details = $result->fetch_assoc();
$adv_pr = $rent_details['TOTAL_AMOUNT']*0.20;
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
                  <li ><a href="../index.php" class="nav-link">Home</a></li>
                  <li><a href="../reviews.php" class="nav-link">Reviews</a></li>
                  <li><a href="../about.php" class="nav-link">About</a></li>
                  <?php
               
                  if (isset($_SESSION['user_id'])) {
                    echo '<li class="active"><a href="user_side/view_bookings.php" class="nav-link">Bookings</a></li>';
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

      
      <div class="hero inner-page" style="background-image: url('../images/hero_1_a.jpg');">
        
        <div class="container">
          <div class="row align-items-end ">
            <div class="col-lg-5">

              <div class="intro">
                <h1><strong>Rent</strong></h1>
                <div class="custom-breadcrumbs"><a href="index.html">Home</a> <span class="mx-2">/</span> <strong>Rent</strong></div>
              </div>

            </div>
          </div>
        </div>
      </div>

    

    <div class="site-section bg-light" id="contact-section">
      <div class="container">
        <div class="row justify-content-center text-center">
        <div class="col-7 text-center mb-5">
          <h2>Book Your Dream Car</h2>
          <p></p>
        </div>
      </div>
        <div class="row">
          <div class="col-lg-8 mb-5" >
            <form>
          
            <div class="form-group row">

                <div class="col-md-6">
                    <Label>Amount to Pay</Label>
                    <input type="text" class="form-control" id="total" value="<?php echo $rent_details['TOTAL_AMOUNT']; ?>" readonly>
                </div>
             
                <div class="col-md-6">
                    <Label>Enter Advance Amount to Pay(min 20%)</Label>
                    <input type="number" class="form-control" id="amount" value="<?php echo ceil($adv_pr); ?>" max="<?php echo $rent_details['TOTAL_AMOUNT']; ?>" min="<?php echo ceil($rent_details['TOTAL_AMOUNT'] * 0.2) ?>" required>
                </div>
            </div>
              <div class="form-group row">
                <div class="col-md-6 mr-auto">
                <input type="text" class="form-control" id="rent_id" value="<?php echo $rent_id; ?>" hidden>

                  <button type="button" class="btn btn-block btn-primary text-white py-3 px-5" onclick="sample()">pay now </button>
                </div>
                
              </div>
            </form>
          </div>
        
        </div>
      </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script src=sample.js></script>
    <script>
        function pay_now(){
            var rent_id= jQuery('#rent_id').val();
            var amount= jQuery('#amount').val();
            var total = jQuery('#total').val();
            console.log('rent',rent_id);
            console.log('amount',amount);
            console.log('total',total);
            
            jQuery.ajax({
                type:'POST',
                url:'payment_process.php',
                data:"amount=" + amount + '&rent_id='+rent_id+'&total='+total,
                success:function(result){
                    var options = {
                        "key": "rzp_test_jOgzwF2bu1fdVA", // Enter the Key ID generated from the Dashboard
                        "amount": amount*100, // Amount is in currency subunits. Default currency is INR. Hence, 50000 refers to 50000 paise
                        "currency": "INR",
                        "name": "Acme Corp",
                        "description": "Test Transaction",
                        "image": "https://example.com/your_logo",
                        "handler": function (response){
                            jQuery.ajax({
                                type:'POST',
                                url:'payment_process.php',
                                data:'payment_id='+response.razorpay_payment_id,
                                success:function(result){
                                    window.location.href='../index.php'
                                }
                            });
                        }
                };
                var rzp1 = new Razorpay(options);
                rzp1.open();
            }
        })
        }
    </script>
      
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
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        function pay_now(){
            var rent_id= jQuery('#rent_id').val();
            var amount= jQuery('#amount').val();
            var total = jQuery('#total').val();
            console.log(rent_id,amount);
            
            jQuery.ajax({
                type:'POST',
                url:'payment_process.php',
                data:"amount=" + amount + '&rent_id='+rent_id+'&total='+total,
                success:function(result){
                    var options = {
                        "key": "rzp_test_jOgzwF2bu1fdVA", // Enter the Key ID generated from the Dashboard
                        "amount": amount*100, // Amount is in currency subunits. Default currency is INR. Hence, 50000 refers to 50000 paise
                        "currency": "INR",
                        "name": "Acme Corp",
                        "description": "Test Transaction",
                        "image": "https://example.com/your_logo",
                        "handler": function (response){
                            jQuery.ajax({
                                type:'POST',
                                url:'payment_process.php',
                                data:'payment_id='+response.razorpay_payment_id,
                                success:function(result){
                                    window.location.href='../index.php'
                                }
                            });
                        }
                };
                var rzp1 = new Razorpay(options);
                rzp1.open();
            }
        })
        }
    </script>
  </body>

</html>

