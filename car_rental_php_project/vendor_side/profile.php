<?php
session_start();
require_once '../db.php';
if (!isset($_SESSION['vendor_id']) || $_SESSION['role'] !== 'vendor') {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $company_registration_number = $_POST['company_registration_number'];
    $address = $_POST['address'];    
    $sql = "UPDATE vendor SET name = ?, email = ?, phone_number = ?, address = ?, company_registration_number = ? WHERE vendor_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $name, $email, $phone, $address,$company_registration_number, $_SESSION['vendor_id']);
    if ($stmt->execute()) {
        echo "Profile updated successfully!";
    } else {
        echo "Error updating profile: " . $stmt->error;
    }
}



$vendor_id = $_SESSION['vendor_id'];
$query = "SELECT * FROM vendor WHERE vendor_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $vendor_id);
$stmt->execute();
$result = $stmt->get_result();
$vendor = $result->fetch_assoc();

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
            <h1 class="text-center">View And Update Profile</h1>
            <div class="container">
                <div class="row">
                    <div class="col-lg-1"></div>
                    <div class="col-lg-10">
                        <div class="card">
                            <div class="card-body">
                                <form action="" method="POST">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Name</label>
                                        <input type="text" class="form-control" id="name" name="name" value="<?=$vendor['name']?>" required>  
                                    </div>
                                    <div class="mb-3">  
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" value="<?=$vendor['email']?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="phone" class="form-label">Phone</label> 
                                        <input type="text" class="form-control" id="phone" name="phone" value="<?=$vendor['phone_number']?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="company_registration_number" class="form-label">company_registration_number</label> 
                                        <input type="text" class="form-control" id="company_registration_number" name="company_registration_number" value="<?=$vendor['company_registration_number']?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="address" class="form-label">Address</label> 
                                        <input type="text" class="form-control" id="address" name="address" value="<?=$vendor['address']?>" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Update Profile</button>
                                    <a href="delete_account.php" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete your account?')">Delete account</a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>


            </div>

        </div>


      </main>
</body>
</html>

