<?php
session_start();
if (!isset($_SESSION['vendor_id']) || $_SESSION['role'] !== 'vendor') {
    header("Location: login.php");
    exit;
}
require_once '../db.php';

$category_query = "SELECT * FROM car_categories";
$category_result = $conn->query($category_query);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $category_id = $_POST['category_id'];
    $vendor_id = $_SESSION['vendor_id'];
    $name = $_POST['name'];
    $vehicle_number = $_POST['vehicle_number'];
    $rc_number = $_POST['rc_number'];
    $price = $_POST['price'];
    $insurance_policy_number = $_POST['insurance_policy_number'];
    $fuel_type = $_POST['fuel_type'];
    $seating_capacity = $_POST['seating_capacity'];
    $ac_status = $_POST['ac_status'];
    $gear_type = $_POST['gear_type'];

    // Handle file upload
    $target_dir = "../uploads/";
    $vehicle_image = $target_dir . basename($_FILES["image"]["name"]);
    move_uploaded_file($_FILES["image"]["tmp_name"], $vehicle_image);

    // Insert into database
    $stmt = $conn->prepare("
        INSERT INTO cars (cat_id, vendor_id, vehicle_name, vehicle_number, rc_number, insurance_policy_number, fuel_type, seating_capacity, ac_status, gear_type, vehicle_image,price)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?)
    ");
    $stmt->bind_param(
        "iissssssisss",
        $category_id,
        $vendor_id,
        $name,
        $vehicle_number,
        $rc_number,
        $insurance_policy_number,
        $fuel_type,
        $seating_capacity,
        $ac_status,
        $gear_type,
        $vehicle_image,
        $price
    );

    if ($stmt->execute()) {
        echo "Car added successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
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
        <h1>Admin Panel</h1>
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
    
        
        <section class="panel important">
          <h2>Add New Cars</h2>
            <form action="" method="post" enctype="multipart/form-data" >
              <div class="twothirds w-100">
                Car Name:<br/>
                    <input type="text" id="name" name="name" size="40" required /><br/>
                Fuel (Petrol/Diesel/CNG/Electric):<br/>
                <select id="gender" name="fuel_type" required>
                    <option value="" disabled selected>Select a Fuel Type</option>
                    <option value="Petrol">Petrol</option>
                    <option value="Diesel">Diesel</option>
                    <option value="CNG">CNG</option>
                    <option value="Electric">Electric</option>
                  </select>
                
                <!-- Category Dropdown -->
                <div class="mb-1">
                    <label for="pro_cat" class="form-label">Category Name</label>
                    <select class="form-control" id="pro_cat" name="category_id" required>
                        <option value="" disabled selected>Select a Category</option>
                        <?php
            if ($category_result->num_rows > 0) {
                while ($row = $category_result->fetch_assoc()) {
                    echo "<option value='" . $row['id'] . "'>" . $row['category_name'] . "</option>";
                }
            } else {
                echo "<option value=''>No categories available</option>";
            }
            ?>
                    </select>
                </div><br>



                Price:<br/>
                    <input type="number" id="price" name="price" size="40" required /><br/>
                    Vehicle Number:<br/>
                    <input type="text" id="offer_price" name="vehicle_number" size="40" required /><br/>
                    RC Number:<br/>
                    <input type="text" id="offer_price" name="rc_number" size="40" required /><br/>

                Policy Number:<br/>
                <input type="number" id="stock" name="insurance_policy_number" size="40" required /><br/>
                Seats:<br/>
                <input type="number" id="stock" name="seating_capacity" size="40" required /><br/>
                AC<input type="radio" id="" name="ac_status" value="1"  />
                NonAc <input type="radio" id="" name="ac_status" value="2"  /><br/>
                Automatic <input type="radio" id="" name="gear_type" value="2"  />
                Manuel <input type="radio" id="" name="gear_type" value="1" /><br/>
                Car Image:
                    <input type="file" id="img" name="image" accept="image/*">
                    <div class="btn">
                      <input type="submit" name="submit" value="Save" />
                    </div>
                </div>
              </div>
            </div>
            </form>
        </section>
      </main>
      
    </body>
</html>

