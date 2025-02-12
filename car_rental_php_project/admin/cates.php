<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit;
}
require_once '../db.php';

$category_query = "SELECT * FROM car_categories";
$category_result = $conn->query($category_query);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['cat_name'];
    $description = $_POST['description'];
    $stmt =$conn->prepare("INSERT INTO car_categories (category_name,description) VALUES (?,?)");
    $stmt->bind_param("ss", $name,$description);
    if ($stmt->execute()) {
        header("Location: cates.php");
    };
    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category</title>
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
          <li class=""><a href="payments.php">payments</a></li>        </ul>
      </nav>
      <main role="main">
    
        
    <section class="panel important">
      <div class="card shadow-lg p-4 my-4 admin-card">
        <h2 class="text-center mb-4">Add New Category</h2>
        <form method="post">

            <!-- Category Name -->
            <div class="mb-3">
                <label for="c_name" class="form-label">Category Name</label>
                <input type="text" class="form-control" id="cat_name" name="cat_name" placeholder="Add Category" required>
            </div>
            <div class="mb-3">
                <label for="c_name" class="form-label">description</label>
                <input type="text" class="form-control" id="cat_name" name="description" placeholder="Add a description" required>
            </div>
            <!-- Submit Button -->
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-dark px-4">Add Category</button>
            </div>
        </form>
    </div>


    <!-- view category  -->

    <div class="main-content mt-0 ms-0 w-100">
        <div class="row w-100">
        <?php
            if ($category_result->num_rows > 0) {
                while ($row = $category_result->fetch_assoc()) {
                    ?>
                    <div class="col-12 mb-3 d-flex w-50">
                <div class="d-flex w-100 align-items-center border p-3 rounded shadow-sm" style="background-color: azure;">
                    <!-- Product Name and Actions -->
                    <div class="ml-3 w-100">
                        <div class="d-flex justify-content-between">
                            <h5 class="mb-0 text-truncate"><?php echo $row['category_name']; ?></h5>
                            <p class="mb-0 text-truncate"><?php echo $row['description']; ?></p>
                            <!-- Delete Button -->
                            <a href="delete_cate.php?id=<?php echo $row['id']; ?>" class="btn btn-outline-dark btn-sm" onclick="return confirm('Are you sure you want to delete this category?');">
                                <i class="fas fa-trash-alt"></i> Delete
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php
                }
            }
            else{
            ?>
            <div class="col-12">
                    <p class="text-center text-muted">No categories available.</p>
            </div>


            <?php
            }
            ?>

                        </div>
                    </div>
                </div>
            </div>
    </section>
  </main>
</body>
</html>

