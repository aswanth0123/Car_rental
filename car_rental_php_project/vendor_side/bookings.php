<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'vendor') {
    header("Location: login.php");
    exit;
}
require_once '../db.php';
$vendor_id = $_SESSION['user_id']; // Get the logged-in customer ID
$query = "
    SELECT rent.rent_id, users.name AS customer_name, rent.customer_id, cars.vehicle_name, 
           rent.pickup_timestamp, rent.return_timestamp, rent.total_amount, rent.status 
    FROM rent
    JOIN cars ON rent.vehicle_id = cars.vehicle_id
    JOIN users ON rent.customer_id = users.user_id
    WHERE cars.vendor_id = ?
    ORDER BY rent.pickup_timestamp DESC
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $vendor_id);
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
        
        <div class="container my-4" >
            <h1 class="text-center">View User Bookings</h1>
            <!-- Products Table --> 
            <table class="table table-striped">
        <thead>
            <tr>
                <th>Booking ID</th>
                <th>Customer</th>
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
                    <td><?php echo $row['rent_id']; ?></td>
                    <td><?php echo htmlspecialchars($row['customer_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['vehicle_name']); ?></td>
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


      </main>
</body>
</html>

