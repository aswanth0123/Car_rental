<?php
session_start();
require_once '../db.php';
if (!isset($_SESSION['vendor_id']) || $_SESSION['role'] !== 'vendor') {
    header("Location: ../login.php");
    exit;
}
echo " Welcome to the Admin Dashboard!";
$sql = "SELECT COUNT(*) AS total_users FROM users";  
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$total_users = $row['total_users'];
$sql = "SELECT COUNT(*) AS total_cars FROM cars where vendor_id = ".$_SESSION['vendor_id'];  
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$total_cars = $row['total_cars'];
$vendor_id = $_SESSION['vendor_id'];

// Query to count total bookings for this vendor
$sql = "SELECT COUNT(*) AS total_bookings
        FROM rent r
        JOIN cars c ON r.vehicle_id = c.vehicle_id
        WHERE c.vendor_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $vendor_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$total_bookings = $row['total_bookings'];

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
        <h1>Vendor Panel</h1>
        <ul class="utilities">
          <br>
          <li class="users"><a href="profile.php">My Account</a></li>
          <li class="logout warn"><a href="../logout.php">Log Out</a></li>
        </ul>
      </header>
      
      <nav role='navigation'>
        <ul class="main">
          <li class=""><a href="vendor_dashboard.php">Dashboard</a></li>
          <li class=""><a href="add_cars.php">Add Cars</a></li>
          <li class=""><a href="view_cars.php">View Cars</a></li>
          <li class=""><a href="add_cate.php">View Category</a></li>
          <li class=""><a href="bookings.php">Manage Bookings</a></li>
          <li class=""><a href="view_feedback.php">feedbacks</a></li>

        </ul>
      </nav>
      
      <main role="main">

        <div class=" container row">
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
                <h5 class="card-title">Your Cars Bookings</h5>
                <p class="card-text"><?php echo $total_bookings; ?></p>
              </div>
            </div>
          </div>
        </div>
        <!-- <div class=" container row">
          <div class="col-lg-4">
            <div class="card">
              <div class="card-body"> 
                <h5 class="card-title">Total Cars</h5>
                <p class="card-text">10</p>
              </div>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="card">
              <div class="card-body"> 
                <h5 class="card-title">Total Users</h5>
                <p class="card-text">10</p>
              </div>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="card">
              <div class="card-body"> 
                <h5 class="card-title">Your Bookings</h5>
                <p class="card-text">10</p>
              </div>
            </div>
          </div>
        </div> -->

      </main>
</body>
</html>

