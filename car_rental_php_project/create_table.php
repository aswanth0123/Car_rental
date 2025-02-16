<?php
require_once 'db.php'; // Ensure this file connects to your database

// SQL Queries to Create Tables

$queries = [

    // Users Table (for both vendors & customers)
    "CREATE TABLE IF NOT EXISTS users (
        user_id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) UNIQUE NOT NULL,
        phone_number VARCHAR(20) UNIQUE NOT NULL,
        driving_license_number VARCHAR(50) NULL,
        address TEXT NOT NULL,
        password VARCHAR(255) NOT NULL
    )",
    "CREATE TABLE IF NOT EXISTS vendor (
            vendor_id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            email VARCHAR(255) UNIQUE NOT NULL,
            phone_number VARCHAR(20) UNIQUE NOT NULL,
            company_registration_number VARCHAR(50) NULL,
            address TEXT NOT NULL,
            password VARCHAR(255) NOT NULL
    )",

    // Categories Table
    "CREATE TABLE IF NOT EXISTS car_categories (
        id INT AUTO_INCREMENT PRIMARY KEY,
        category_name VARCHAR(255) NOT NULL,
        description TEXT
    )",

    // Cars Table
    "CREATE TABLE IF NOT EXISTS cars (
        vehicle_id INT AUTO_INCREMENT PRIMARY KEY,
        cat_id INT NOT NULL,
        vendor_id INT NOT NULL,
        vehicle_name VARCHAR(255) NOT NULL,
        vehicle_number VARCHAR(50) UNIQUE NOT NULL,
        rc_number VARCHAR(50) UNIQUE NOT NULL,
        insurance_policy_number VARCHAR(50) NOT NULL,
        fuel_type ENUM('Petrol', 'Diesel', 'Electric', 'Hybrid') NOT NULL,
        seating_capacity INT NOT NULL,
        ac_status ENUM('AC', 'Non-AC') NOT NULL,
        gear_type ENUM('Manual', 'Automatic') NOT NULL,
        vehicle_image VARCHAR(255) NOT NULL,
        price DECIMAL(10,2) NOT NULL,
        FOREIGN KEY (cat_id) REFERENCES car_categories(id) ON DELETE CASCADE,
        FOREIGN KEY (vendor_id) REFERENCES vendor(vendor_id) ON DELETE CASCADE
    )",

    // Locations Table
    "CREATE TABLE IF NOT EXISTS pickup_dropoff_location (
        id INT AUTO_INCREMENT PRIMARY KEY,
        loc_name VARCHAR(255) NOT NULL
    )",

    // Rent Table
    "CREATE TABLE IF NOT EXISTS rent (
        rent_id INT AUTO_INCREMENT PRIMARY KEY,
        customer_id INT NOT NULL,
        vehicle_id INT NOT NULL,
        rent_timestamp DATETIME NOT NULL,
        pickup_timestamp DATETIME NOT NULL,
        return_timestamp DATETIME NOT NULL,
        location_id INT NOT NULL,
        total_amount DECIMAL(10,2) NOT NULL,
        status ENUM('confirmed', 'cancelled', 'completed') DEFAULT 'confirmed',
        FOREIGN KEY (customer_id) REFERENCES users(user_id) ON DELETE CASCADE,
        FOREIGN KEY (vehicle_id) REFERENCES cars(vehicle_id) ON DELETE CASCADE,
        FOREIGN KEY (location_id) REFERENCES pickup_dropoff_location(id) ON DELETE CASCADE
    )",

    // Feedback Table
    "CREATE TABLE IF NOT EXISTS Feedback (
        FeedbackID INT AUTO_INCREMENT PRIMARY KEY, 
        CustomerID INT NOT NULL,                   
        FeedbackDescription TEXT NOT NULL,         
        ReviewDateTime DATETIME NOT NULL,          
        FOREIGN KEY (CustomerID) REFERENCES users(user_id) 
    )",

    //admin
    "create table IF NOT EXISTS admin(
    admin_id int auto_increment primary key,
    email varchar(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
    )",
    "CREATE TABLE payment (
    id INT AUTO_INCREMENT PRIMARY KEY,
    rent_id INT NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    payment_mode ENUM('UPI', 'Card') NOT NULL,
    payment_status ENUM('success', 'failed', 'pending') NOT NULL,
    payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    payment_id VARCHAR(255) NULL,
    advance_amount DECIMAL(10,2) DEFAULT 0.00 ,
    remaining_amount DECIMAL(10,2) DEFAULT 0.00,
    misc_charges DECIMAL(10,2) DEFAULT 0.00,
    FOREIGN KEY (rent_id) REFERENCES rent(rent_id) ON DELETE CASCADE
)"
];

// Execute each query
foreach ($queries as $query) {
    if ($conn->query($query) === TRUE) {
        echo "Table created successfully.<br>";
    } else {
        echo "Error creating table: " . $conn->error . "<br>";
    }
}

// Close the connection
$conn->close();
?>
