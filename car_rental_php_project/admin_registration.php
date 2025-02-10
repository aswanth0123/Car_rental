<?php

require_once 'db.php';
$email = "admin@gmail.com";
$password = password_hash('admin',PASSWORD_DEFAULT);
$sql = "insert into admin (email,password) values(?,?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss",
$email,
$password);
if ($stmt->execute()) {
    echo "Registration successful!";
} else {
    echo "Error: " . $stmt->error;
}

?>