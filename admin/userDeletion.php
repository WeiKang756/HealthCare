<?php
session_start();
namespace Admin;

use Shared\Config;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_ic = $_POST['user_ic']; // Get the IC number of the user to be deleted

    // Check if the User IC exists
    $check_sql = "SELECT COUNT(*) FROM users WHERE user_ICNumber = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $user_ic);
    $check_stmt->execute();
    $check_stmt->bind_result($count);
    $check_stmt->fetch();
    $check_stmt->close();

    if ($count > 0) {
        // User IC exists, delete the user record
        $delete_sql = "DELETE FROM users WHERE user_ICNumber = ?";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bind_param("s", $user_ic);

        if ($delete_stmt->execute()) {
            echo '<script>';
            echo 'alert("Delete User Successful");';
            echo 'window.location.href = "user_manage.php";';
            echo '</script>';
        } else {
            echo "Error: " . $delete_stmt->error;
        }

        $delete_stmt->close();
    } else {
        // User IC doesn't exist, display an alert
        echo '<script>';
        echo 'alert("User with the provided IC Number not found. Please provide a valid IC Number.");';
        echo 'window.location.href = "user_manage.php";';
        echo '</script>';
    }

    $conn->close();
}
