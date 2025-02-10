<?php
require_once '../db.php';

if (isset($_GET['id'])) {
    $category_id = $_GET['id'];

    $query = "DELETE FROM car_categories WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $category_id);
    $stmt->execute();
    header("Location: cates.php");
    exit;
}
$stmt->close();
$conn->close();
?>