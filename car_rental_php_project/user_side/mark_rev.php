<?php

session_start();

require_once '../db.php';   
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $date = date("Y-m-d H:i:s");
    $rev = $_POST['rev'];
    $user_id = $_SESSION['user_id']; // Get the logged-in customer ID

    $query = "insert into Feedback (CustomerID,FeedbackDescription,ReviewDateTime)
    values(?,?,?) ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('iss',$user_id,$rev,$date);
    if ($stmt->execute()) {
        header("Location: view_bookings.php");
    } else {
        echo "Error: " . $conn->error;
    }

}
?>