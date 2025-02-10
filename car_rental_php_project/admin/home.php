<?php
session_start();
require_once '../db.php';
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit;
}
$sql = "SELECT COUNT(*) AS total_users FROM users";  
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$total_users = $row['total_users'];

$sql = "SELECT COUNT(*) AS total_vendors FROM vendor";  
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$total_vendors = $row['total_vendors'];


$sql = "SELECT COUNT(*) AS total_cars FROM cars";  
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$total_cars = $row['total_cars'];


// Query to count total bookings for this vendor
$sql = "SELECT COUNT(*) AS total_bookings FROM rent";

$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$total_bookings = $row['total_bookings'];


$today = date("Y-m-d");

// Query to count bookings for today
$sql = "SELECT COUNT(*) AS today_bookings FROM rent WHERE date(pickup_timestamp) = ? OR date(return_timestamp) = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $today, $today);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$today_bookings = $row['today_bookings'];

$tomorrow = date("Y-m-d", strtotime("+1 day"));

// Query to count bookings for tomorrow
$sql = "SELECT COUNT(*) AS tomorrow_bookings FROM rent WHERE date(pickup_timestamp) = ? OR date(return_timestamp) = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $tomorrow, $tomorrow);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$tomorrow_bookings = $row['tomorrow_bookings'];


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../css/admin_home.css">
</head>
<body>
    
    <header role="banner">
        <h1>Admin Panel</h1>
        <ul class="utilities">
          <br>
          <li class="logout warn"><a href="../logout.php">Log Out</a></li>
        </ul>
      </header>
      
      <nav role='navigation'>
        <ul class="main">
          <li class=""><a href="home.php">Dashboard</a></li>
          <li class=""><a href="cars.php">Cars</a></li>
          <li class=""><a href="users.php">users</a></li>
          <li class=""><a href="vendors.php">Vendors</a></li>
          <li class=""><a href="cates.php">Categorys</a></li>
          <li class=""><a href="bookings.php">Bookings</a></li>
          <li class=""><a href="feedbacks.php">feedbacks</a></li>
        </ul>
      </nav>    
      <main role="main">
        <div class=" container row ">
          <div class="col-lg-4">
            <div class="card">
              <div class="card-body"> 
                <h5 class="card-title">Total Cars</h5>
                <p class="card-text"><?php echo $total_cars; ?></p>
              </div>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="card">
              <div class="card-body"> 
                <h5 class="card-title">Total Users</h5>
                <p class="card-text"><?php echo $total_users; ?></p>
              </div>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="card">
              <div class="card-body"> 
                <h5 class="card-title">Total Cars Bookings</h5>
                <p class="card-text"><?php echo $total_bookings; ?></p>
              </div>
            </div>
          </div>
        </div>
        <div class=" container row mt-3">
          <div class="col-lg-4">
            <div class="card">
              <div class="card-body"> 
                <h5 class="card-title">Total Vendors</h5>
                <p class="card-text"><?php echo $total_vendors ?></p>
              </div>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="card">
              <div class="card-body"> 
                <h5 class="card-title">Today Bookings</h5>
                <p class="card-text"><?php echo $today_bookings ?></p>
              </div>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="card">
              <div class="card-body"> 
                <h5 class="card-title">Tomorrow Bookings</h5>
                <p class="card-text"><?php echo $tomorrow_bookings ?></p>
              </div>
            </div>
          </div>
        </div>

      </main>
</body>
</html>

