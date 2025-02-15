<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
require_once '../db.php';
// Fetch all bookings with user, vendor, car, and location details
$query = "
    SELECT 
        users.name AS customer_name, 
        rent.rent_id, 
        rent.total_amount, 
        payment.payment_status, 
        payment.payment_id, 
        payment.advance_amount AS advance_amount, 
        payment.payment_date AS payment_date, 
        payment.remaining_amount AS remaining_amount
    FROM payment
    JOIN rent ON payment.rent_id = rent.rent_id
    JOIN users ON rent.customer_id = users.user_id
    ORDER BY payment.payment_date DESC
";

$result = $conn->query($query);
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
            <h1 class="text-center">View Payments</h1>
            <!-- Products Table --> 
            <table class="table table-striped" style="width:92%">
                <thead>
                    <tr>
                        <th>Rent Id</th>
                        <th>Customer</th>
                        <th>payment status</th>
                        <th>payment Id</th>
                        <th>Total Amount</th>
                        <th>Advance Amount</th>
                        <th>Remaining Amount</th>
                        <th>Payment Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['rent_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['customer_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['payment_status']); ?></td>
                            <td><?php echo htmlspecialchars($row['payment_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['total_amount']); ?></td>
                            <td><?php echo htmlspecialchars($row['advance_amount']); ?></td>
                            <td><?php echo htmlspecialchars($row['remaining_amount']); ?></td>
                            <td><?php echo htmlspecialchars($row['payment_date']); ?></td>

                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>


      </main>
</body>
</html>

