<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit;
}
echo "Welcome to the User Dashboard!";
?>
<a href="logout.php">Logout</a>
