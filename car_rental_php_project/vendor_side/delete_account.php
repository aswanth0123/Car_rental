<?php

session_start();
require_once '../db.php';

$vendor_id = $_SESSION['vendor_id'];

$sql_cars = "DELETE FROM cars WHERE vendor_id = ?";
$stmt_cars = $conn->prepare($sql_cars);
$stmt_cars->bind_param("i", $vendor_id);
$stmt_cars->execute();


// Step 2: Delete vendor's account

$sql_rent = "DELETE FROM rent WHERE vehicle_id IN (SELECT vehicle_id FROM cars WHERE vendor_id = ?)";
$stmt_rent = $conn->prepare($sql_rent);
$stmt_rent->bind_param("i", $vendor_id);
$stmt_rent->execute();





$sql_vendor = "DELETE FROM vendor WHERE vendor_id = ?";
$stmt_vendor = $conn->prepare($sql_vendor);
$stmt_vendor->bind_param("i", $vendor_id);
$stmt_vendor->execute();


// Logout and redirect after deletion
session_destroy();
header("Location: ../index.php?message=Account Deleted Successfully");
exit;


?>