<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}
require_once '../db.php';

$user_id = $_SESSION['user_id'];

$sql_rent = "DELETE FROM rent WHERE customer_id = ?";
$stmt_rent = $conn->prepare($sql_rent);
$stmt_rent->bind_param("i", $user_id);
$stmt_rent->execute();

// Delete related feedback records
$sql_feedback = "DELETE FROM feedback WHERE customerid = ?";
$stmt_feedback = $conn->prepare($sql_feedback);
$stmt_feedback->bind_param("i", $user_id);
$stmt_feedback->execute();

// Now, delete the user from the users table
$sql_user = "DELETE FROM users WHERE user_id = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("i", $user_id);

if ($stmt_user->execute()) {
    // Logout and redirect
    session_destroy();
    header("Location: ../index.php?message=Account Deleted Successfully");
    exit;
} else {
    echo "Error deleting account!";
}
?>