<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit;
}
require_once '../db.php';


// Fetch cars for the current vendor
$sql = "SELECT f.FeedbackID, f.FeedbackDescription, f.ReviewDateTime, c.name AS customer_name 
        FROM feedback f 
        JOIN users c ON f.CustomerID = c.user_id 
        ORDER BY f.ReviewDateTime DESC";

$stmt = $conn->prepare($sql);
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
        <div class="container my-4" >
            <h1 class="text-center">View User Reviews</h1>
            <!-- Products Table --> 
            <table class="table table-striped" style="width:92%">
                <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Review</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                <?php
          foreach($result as $rev){
            ?>                        
                    <tr>
                            <td><?php echo htmlspecialchars($rev['customer_name']); ?></td>
                            <td><?php echo htmlspecialchars($rev['FeedbackDescription']); ?></td>
                            <td><?php echo date('d M Y', strtotime($rev['ReviewDateTime'])); ?></td>
                            
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>


      </main>
</body>
</html>

