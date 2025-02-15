<?php
session_start();
require_once '../db.php';
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit;
}

$query = "SELECT cars.vehicle_id, cars.vehicle_name, cars.vehicle_number, cars.rc_number, 
                 cars.insurance_policy_number, cars.fuel_type, cars.seating_capacity, 
                 cars.ac_status, cars.gear_type, cars.vehicle_image,cars.price,
                 car_categories.category_name 
          FROM cars 
          JOIN car_categories ON cars.cat_id = car_categories.id";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();

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
          <li class=""><a href="payments.php">payments</a></li> 
          <li class=""><a href="reports.php">Report</a></li>
         </ul>
      </nav>    
      <main role="main">
        
        <!-- Manage Products Section -->
        <div class="section" id="products">
          <div class="bt" style="margin-left: 30px; margin-top: 15px; color: red;">
            <h3>Cars</h3><br>
          </div>
            <div class="container mt-3">
                <div class="row">
                <?php
                foreach ($result as $pro) {
                    ?>                    <div class="col-lg-3">
                <div class="card" style="width:100%;height:95%;">
                  <img class="card-img-top" src="<?php echo htmlspecialchars($pro['vehicle_image']); ?>" alt="Card image" style="width:100%; height: 35vh; object-fit: cover;">
                  <div class="card-body">
                    <p class="card-text">category: <?php echo htmlspecialchars($pro['category_name']); ?></p>
                    <h4 class="card-title"><?php echo htmlspecialchars($pro['vehicle_name']); ?></h4>
                    <p class="text-muted">

                      <span>vehicle_number: <?php echo htmlspecialchars($pro['vehicle_number']); ?></span><br>
                      <span style="">Price : ₹<?php echo htmlspecialchars($pro['price']); ?></span>
                    </p>
               
                  </div>
                </div><br>
                </div>
                <?php
                    }
                ?>
            </div>
            </div>
        </div>
        
      </main>
</body>
</html>

