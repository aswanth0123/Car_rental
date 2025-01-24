<?php
$servername = "localhost";
$username = "car_rental";
$password = "car_rental";
$dbname = "car_rental";

$conn = mysqli_connect($servername, $username,$password,$dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

