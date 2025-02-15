<?php
include '../db.php'; // Include database connection
session_start();

// Fetch total bookings
$query = "SELECT COUNT(*) AS total_bookings FROM rent";
$result = $conn->query($query);
$bookings = $result->fetch_assoc();

// Fetch total earnings
$query = "SELECT SUM(total_amount) AS total_earnings FROM rent";
$result = $conn->query($query);
$earnings = $result->fetch_assoc();

// Fetch pending payments
$query = "SELECT COUNT(*) AS pending_payments FROM payment WHERE payment_status = 'pending'";
$result = $conn->query($query);
$pending_payments = $result->fetch_assoc();

// Fetch total users
$query = "SELECT COUNT(*) AS total_users FROM users";
$result = $conn->query($query);
$users = $result->fetch_assoc();

// Fetch total vendors
$query = "SELECT COUNT(*) AS total_vendors FROM vendor";
$result = $conn->query($query);
$vendors = $result->fetch_assoc();


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
        
        <div class="container" >
            <h1 class="text-center">View Reports</h1>
            <!-- Products Table --> 
            <table class="table table-striped" style="width:92%">
                <thead>
                    <tr>
                        <th>Report Type</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Total Bookings</td>
                        <td><?php echo $bookings['total_bookings']; ?></td>
                    </tr>
                    <tr>
                        <td>Total Earnings</td>
                        <td>₹<?php echo number_format($earnings['total_earnings'], 2); ?></td>
                    </tr>
                    <tr>
                        <td>Pending Payments</td>
                        <td><?php echo $pending_payments['pending_payments']; ?></td>
                    </tr>
                    <tr>
                        <td>Total Users</td>
                        <td><?php echo  $users['total_users'] ?></td>
                    </tr>
                    <tr>
                        <td>Total Vendors</td>
                        <td><?php echo $vendors['total_vendors']; ?></td>
                    </tr>
                </tbody>
            </table>
            <canvas id="bookingsChart" ></canvas>
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                var ctx = document.getElementById('bookingsChart').getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['Total Bookings', 'Total Earnings', 'Pending Payments'],
                        datasets: [{
                            label: 'Report Data',
                            data: [<?php echo $bookings['total_bookings']; ?>, <?php echo $earnings['total_earnings']; ?>, <?php echo $pending_payments['pending_payments']; ?>],
                            backgroundColor: ['blue', 'green', 'red']
                        }]
                    }
                });
            </script>
        </div>
        

      </main> 

</body>
</html>

