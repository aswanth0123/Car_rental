<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'vendor') {
    header("Location: login.php");
    exit;
}
require_once '../db.php';

$vendor_id = $_SESSION['user_id'];

// Fetch cars for the current vendor
$query = "SELECT cars.vehicle_id, cars.vehicle_name, cars.vehicle_number, cars.rc_number, 
                 cars.insurance_policy_number, cars.fuel_type, cars.seating_capacity, 
                 cars.ac_status, cars.gear_type, cars.vehicle_image,cars.price,
                 car_categories.category_name 
          FROM cars 
          JOIN car_categories ON cars.cat_id = car_categories.id
          WHERE cars.vendor_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $vendor_id);
$stmt->execute();
$result = $stmt->get_result();

?>

<a href="../logout.php">Logout</a>
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
          <li class="users"><a href="#">My Account</a></li>
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
          <li class=""><a href="view_feedbacks">feedbacks</a></li>

        </ul>
      </nav>
      <main role="main">
        
        <!-- Manage Products Section -->
        <div class="section" id="products">
          <div class="bt" style="margin-left: 30px; margin-top: 15px; color: red;">
            <h3>Manage Products</h3><br>
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
                    <a href="edit_cars.php?id=<?php echo htmlspecialchars($pro['vehicle_id']); ?>" class="btn btn-primary">Edit</a>
                    <a href="delete_car.php?id=<?php echo htmlspecialchars($pro['vehicle_id']); ?>" class="btn btn-primary">Delete</a>
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

