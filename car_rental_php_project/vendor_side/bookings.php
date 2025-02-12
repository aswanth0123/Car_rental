<?php
session_start();
if (!isset($_SESSION['vendor_id']) || $_SESSION['role'] !== 'vendor') {
    header("Location: login.php");
    exit;
}
require_once '../db.php';
$vendor_id = $_SESSION['vendor_id']; // Get the logged-in customer ID
$query = "
    SELECT rent.rent_id, 
           users.user_id, users.name AS customer_name, users.email, users.phone_number, users.address, 
           cars.vehicle_id, cars.vehicle_name, 
           rent.pickup_timestamp, rent.return_timestamp, rent.total_amount, rent.status, 
           pickup_dropoff_location.id, pickup_dropoff_location.loc_name ,
           payment.payment_id, payment.advance_amount, payment.payment_status, payment.payment_date

    FROM rent
    JOIN cars ON rent.vehicle_id = cars.vehicle_id
    JOIN users ON rent.customer_id = users.user_id
    JOIN pickup_dropoff_location ON rent.location_id = pickup_dropoff_location.id
    JOIN payment ON rent.rent_id = payment.rent_id
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
        
        <div class="container my-4" >
            <h1 class="text-center">View User Bookings</h1>
            <!-- Products Table --> 
            <table class="table table-striped" style="width:92%">
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
                            <td>  
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="<?php echo "#myModal" . $row['rent_id']; ?>">
                                    More Info
                                </button>
                            </td>
                        </tr>
                        <div class="modal fade" id="<?php echo "myModal" . $row['rent_id']; ?>">
                            <div class="modal-dialog">
                                <div class="modal-content">

                                <!-- Modal Header -->
                                <div class="modal-header">
                                    <h4 class="modal-title">More info</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>

                                <!-- Modal body -->
                                <div class="modal-body">
                                    <p>Customer Name: <?php echo $row['customer_name']; ?></p>
                                    <p>Customer Email: <?php echo $row['email']; ?></p>
                                    <p>Customer Phone Number: <?php echo $row['phone_number']; ?></p>
                                    <p>Customer Address: <?php echo $row['address']; ?></p>
                                    <p>Car Name: <?php echo $row['vehicle_name']; ?></p>
                                    <p>Pickup Location: <?php echo $row['loc_name']; ?></p>
                                </div>

                                <!-- Modal footer -->
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                </div>

                                </div>
                            </div>
                            </div>









                    <?php } ?>
                </tbody>
            </table>
        </div>


      </main>
</body>
</html>

