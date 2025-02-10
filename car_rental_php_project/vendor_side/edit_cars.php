<?php
session_start();
if (!isset($_SESSION['vendor_id']) || $_SESSION['role'] !== 'vendor') {
    header("Location: ../login.php");
    exit;
}
require_once '../db.php';


$product_id = intval($_GET['id']);

// Fetch product details
$query = "SELECT * FROM cars WHERE vehicle_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Product not found.";
    exit;
}

$product = $result->fetch_assoc();




$category_query = "SELECT * FROM car_categories";
$category_result = $conn->query($category_query);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $category_id = $_POST['category_id'];
    $vendor_id = $_SESSION['user_id'];
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
    $vehicle_image = $product['vehicle_image']; // Default to existing image
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = "uploads/";
        $image_name = basename($_FILES['image']['name']);
        $target_file = $upload_dir . $image_name;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $vehicle_image = $target_file;
        } else {
            echo "Error uploading image.";
        }
    }

    // Insert into database
    $update_query = "UPDATE cars 
                     SET cat_id = ?, vehicle_name = ?, vehicle_number = ?, rc_number = ?, 
                         insurance_policy_number = ?, fuel_type = ?, seating_capacity = ?, 
                         ac_status = ?, gear_type = ?, vehicle_image = ?, price = ? 
                     WHERE vehicle_id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param(
      "issssssssssi",
        $category_id,
        $name,
        $vehicle_number,
        $rc_number,
        $insurance_policy_number,
        $fuel_type,
        $seating_capacity,
        $ac_status,
        $gear_type,
        $vehicle_image,
        $price,
        $product_id
    );

    if ($update_stmt->execute()) {
        echo "Car updated successfully.";
        header("Location: view_cars.php");

    } else {
        echo "Error: " . $update_stmt->error;
    }

    $update_stmt->close();
}
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
          <h2>Update Cars</h2>
            <form action="" method="post" enctype="multipart/form-data" >
              <div class="twothirds w-100">
                Car Name:<br/>
                    <input type="text" id="name" name="name" size="40" required value="<?php echo $product['vehicle_name']; ?>"/><br/>
                Fuel (Petrol/Diesel/CNG/Electric):<br/>
                <select id="gender" name="fuel_type" required>
                    <option value="" disabled selected>Select a Fuel Type</option>
                    <option value="Petrol" <?php if ($product['fuel_type'] == 'Petrol') echo 'selected'; ?>>Petrol</option>
                    <option value="Diesel" <?php if ($product['fuel_type'] == 'Diesel') echo 'selected'; ?>>Diesel</option>
                    <option value="CNG" <?php if ($product['fuel_type'] == 'CNG') echo 'selected'; ?>>CNG</option>
                    <option value="Electric" <?php if ($product['fuel_type'] == 'Electric') echo 'selected'; ?>>Electric</option>
                  </select>
                
                <!-- Category Dropdown -->
                <div class="mb-1">
                    <label for="pro_cat" class="form-label">Category Name</label>
                    <select class="form-control" id="pro_cat" name="category_id" required>
                        <option value="" disabled selected>Select a Category</option>
                        <?php
            if ($category_result->num_rows > 0) {
                while ($row = $category_result->fetch_assoc()) {
                    if ($row['id'] == $product['cat_id']) {
                        echo "<option value='" . $row['id'] . "' selected>" . $row['category_name'] . "</option>";
                    }
                    else{
                        echo "<option value='" . $row['id'] . "'>" . $row['category_name'] . "</option>";

                    }
                }
                
                
            } else {
                echo "<option value=''>No categories available</option>";
            }
            ?>
                    </select>
                    
                </div><br>



                Price:<br/>
                    <input type="number" id="price" name="price" size="40" required value="<?php echo $product['price']; ?>" /><br/>
                    Vehicle Number:<br/>
                    <input type="text" id="offer_price" name="vehicle_number" size="40" required value="<?php echo $product['vehicle_number']; ?>" /><br/>
                    RC Number:<br/>
                    <input type="text" id="offer_price" name="rc_number" size="40" required value="<?php echo $product['rc_number']; ?>" /><br/>

                Policy Number:<br/>
                <input type="number" id="stock" name="insurance_policy_number" size="40" required value="<?php echo $product['insurance_policy_number']; ?>" /><br/>
                Seats:<br/>
                <input type="number" id="stock" name="seating_capacity" size="40" required value="<?php echo $product['seating_capacity']; ?>" /><br/>
                AC<input type="radio" id="" name="ac_status" value="1" <?php echo ($product['ac_status'] == "AC") ? 'checked' : ''; ?>/>
                NonAc <input type="radio" id="" name="ac_status" value="2"<?php echo ($product['ac_status'] == "Non-AC") ? 'checked' : ''; ?> /><br/>
                Automatic <input type="radio" id="" name="gear_type" value="2" <?php echo ($product['gear_type'] == "Automatic") ? 'checked' : ''; ?>  />
                Manuel <input type="radio" id="" name="gear_type" value="1" <?php echo ($product['gear_type'] == "Manual") ? 'checked' : ''; ?> /><br/>
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

