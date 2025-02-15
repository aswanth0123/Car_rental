<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
require_once '../db.php';
// Fetch all bookings with user, vendor, car, and location details
$sql = "SELECT 
            rent.rent_id, rent.pickup_timestamp, rent.return_timestamp, rent.total_amount, rent.status,
            users.name AS customer_name,
            vendor.name AS vendor_name,
            cars.vehicle_name AS car_name,
            pickup_dropoff_location.loc_name AS pickup_location
        FROM rent
        JOIN users ON rent.customer_id = users.user_id
        JOIN cars ON rent.vehicle_id = cars.vehicle_id
        JOIN vendor ON cars.vendor_id = vendor.vendor_id
        JOIN pickup_dropoff_location ON rent.location_id = pickup_dropoff_location.id
        ORDER BY rent.pickup_timestamp DESC";

$result = $conn->query($sql);
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
        
        <div class="container my-4" >
            <h1 class="text-center">View User Bookings</h1>
            <!-- Products Table --> 
            <table class="table table-striped" style="width:92%">
                <thead>
                    <tr>
                        <th>Customer</th>
                        <th>vendor</th>
                        <th>Car</th>
                        <th>Pickup Date</th>
                        <th>Return Date</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['customer_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['vendor_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['car_name']); ?></td>
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
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>


      </main>
</body>
</html>

