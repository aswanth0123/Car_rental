<?php
require_once '../db.php';
session_start();

// Check if vehicle_id is provided




$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone_number'];
    $address = $_POST['address']; 
    $driving_license_number = $_POST['driving_license_number'];
    $password = $_POST['password'];   

    if ($user['password']!=$password) {
        $password = password_hash($password, PASSWORD_DEFAULT);
        
    }
    $sql = "UPDATE users SET name = ?, email = ?, phone_number = ?, address = ?,driving_license_number = ?,password = ? WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $name, $email, $phone, $address,$driving_license_number,$password, $_SESSION['user_id']);
    if ($stmt->execute()) {
        echo "Profile updated successfully!";
    } else {
        echo "Error updating profile: " . $stmt->error;
    }
}
// Fetch vehicle details

?>




<!doctype html>
<html lang="en">

  <head>
    <title>RIDE NOW RENTALS</title>
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

      
      <div class="hero inner-page" style="background-image: url('../images/hero_1_a.jpg');">
        
        <div class="container">
          <div class="row align-items-end ">
            <div class="col-lg-5">

              <div class="intro">
                <h1><strong>Profile</strong></h1>
                <div class="custom-breadcrumbs"><a href="../index.php">Home</a> <span class="mx-2">/</span> <strong>Profile</strong></div>
              </div>

            </div>
          </div>
        </div>
      </div>

    

    <div class="site-section bg-light" id="contact-section">
      <div class="container">
      
        <div class="row">
          <div class="col mb-5" >
            <form action="" method="post">
              <div class="form-group row">
                <div class="col-md-6 ">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Name" value="<?php echo $user['name']; ?>" reequired>
                </div>
                <div class="col-md-6">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="<?php echo $user['email']; ?>" readonly>
                </div>
              </div>
              <div class="form-group row">
  
              <div class="col-md-6">
                <label for="phone_number">phone number</label>
                <input type="text" name="phone_number" class="form-control" value="<?php echo $user['phone_number']; ?>" id="phone_number">
              </div>
              <div class="col-md-6">
              <label for="">driving License Number</label>
               <input type="text" name="driving_license_number" class="form-control" value="<?php echo $user['driving_license_number']; ?>" id="">
              </div>
            </div>
            <div class="form-group row">

                <div class="col-md-6">
                  <label for="address">Address</label>
                  <input type="text" name="address" class="form-control" value="<?php echo $user['address']; ?>" id="address">
                </div>
                <div class="col-md-6">
                  <label for="password">password</label>  
                  <input type="password" name="password" class="form-control" value="<?php echo $user['password']; ?>" id="">
                  </div>
            </div>
              <div class="form-group row">
                <div class="col-md-6 ">
                  <input type="submit" class="btn btn-block btn-primary text-white py-3 px-5" value="Update">
                </div>
                <div class="col-md-6">
                  <a href="delete.php" class="btn btn-block btn-primary text-white py-3 px-5">Delete account</a>
                </div>
              </div>
            </form>
          </div>

        </div>
      </div>
    </div>


      
   

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

