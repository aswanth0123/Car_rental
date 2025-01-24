<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'vendor') {
    header("Location: login.php");
    exit;
}
echo " Welcome to the Admin Dashboard!";
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
          <li class=""><a href="perfume_home">Dashboard</a></li>
          <li class=""><a href="add_cars.php">Add Cars</a></li>
          <li class=""><a href="manage_products">View Cars</a></li>
          <li class=""><a href="add_category">View Category</a></li>
          <li class=""><a href="view_bookings">Manage Bookings</a></li>
          <li class=""><a href="view_feedbacks">feedbacks</a></li>

        </ul>
      </nav>
      
</body>
</html>

