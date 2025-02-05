<?php
// Include database connection
require_once '../db.php';

// Check if the car ID is provided in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "Invalid car ID.";
    exit;
}

$car_id = intval($_GET['id']);

// Fetch the car record to delete the image (optional, if images are stored in the filesystem)
$query = "SELECT vehicle_image FROM cars WHERE vehicle_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $car_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Car not found.";
    exit;
}

$car = $result->fetch_assoc();

// Delete the car image from the filesystem (optional)
if (!empty($car['vehicle_image']) && file_exists($car['vehicle_image'])) {
    unlink($car['vehicle_image']);
}

// Delete the car from the database
$delete_query = "DELETE FROM cars WHERE vehicle_id = ?";
$delete_stmt = $conn->prepare($delete_query);
$delete_stmt->bind_param("i", $car_id);

if ($delete_stmt->execute()) {
    echo "Car deleted successfully.";
    header("Location: view_cars.php"); // Redirect to the cars list page
    exit;
} else {
    echo "Error deleting car: " . $conn->error;
}

$stmt->close();
$conn->close();
?>
