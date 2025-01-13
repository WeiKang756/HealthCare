<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

use Shared\Config;
use Shared\Functions;
// update_blood_type.php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $user_ICNumber = $_POST['user_ICNumber'];
    $bloodType = $_POST['bloodType'];

    // Perform the update query
    // Replace the following with your actual database update logic
    $sql = "UPDATE users SET bloodType = '$bloodType' WHERE user_ICNumber = '$user_ICNumber'";
    // Execute the query
     $result = mysqli_query($conn, $sql);

    // Check for success and handle accordingly
    if ($result) {
        // Update successful
        // You might want to redirect or show a success message
        header("Location: home.php");
        exit;
    } else {
        // Update failed
        // Handle the error, show a message, or redirect as needed
        echo "Error updating blood type: " . mysqli_error($conn);
    }
}
