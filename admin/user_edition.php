<?php
session_start();
namespace Admin;

use Shared\Config;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the updated form data
    $user_ICNumber = $_POST["user_ic"];
    $user_name = $_POST["user_name"];
    $user_contact = $_POST["user_contact"];
    $user_email = $_POST["user_email"];
    $user_address = $_POST["user_address"];

    // Input Validation
    if (empty($user_ICNumber) || empty($user_name) || empty($user_contact) || empty($user_email) || empty($user_address)) {
        // Handle empty fields
        echo '<script>';
        echo 'alert("Please fill in all fields.");';
        echo 'window.location.href = "user_manage.php";';
        echo '</script>';
    } elseif (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
        // Handle invalid email format
        echo '<script>';
        echo 'alert("Invalid email format. Please enter a valid email address.");';
        echo 'window.location.href = "user_manage.php";';
        echo '</script>';
    } elseif (!preg_match("/^[0-9]+$/", $user_ICNumber)) {
        // Handle invalid IC Number format (assuming it should be numeric)
        echo '<script>';
        echo 'alert("Invalid IC Number format. Please enter a numeric IC Number.");';
        echo 'window.location.href = "user_manage.php";';
        echo '</script>';
    } else {
      // Check if Contact Number and Email Address already exist in the database, excluding the current user
      $check_sql = "SELECT user_ICNumber, user_contact, user_email FROM users WHERE (user_contact = ? OR user_email = ?) AND user_ICNumber != ?";
      $check_stmt = mysqli_prepare($conn, $check_sql);
      mysqli_stmt_bind_param($check_stmt, "sss", $user_contact, $user_email, $user_ICNumber);
      mysqli_stmt_execute($check_stmt);
      mysqli_stmt_store_result($check_stmt);

        if (mysqli_stmt_num_rows($check_stmt) > 0) {
            // Contact Number or Email Address already exist for another user
            echo '<script>';
            echo 'alert("Contact Number or Email Address already exists for another user. Please use different values.");';
            echo 'window.location.href = "user_manage.php";';
            echo '</script>';
        } else {
            // Update the user's information in the database
            $update_sql = "UPDATE users SET user_name = ?, user_contact = ?, user_email = ?, user_address = ? WHERE user_ICNumber = ?";

            // Use prepared statements to prevent SQL injection
            $update_stmt = mysqli_prepare($conn, $update_sql);
            mysqli_stmt_bind_param($update_stmt, "sssss", $user_name, $user_contact, $user_email, $user_address, $user_ICNumber);

            // Execute the update statement
            if (mysqli_stmt_execute($update_stmt)) {
                echo '<script>';
                echo 'alert("Update Information Successful.");';
                echo 'window.location.href = "user_manage.php";';
                echo '</script>';
            } else {
                $error_message = "Error: " . mysqli_error($conn);
                echo '<script>';
                echo 'alert("Update Information Failed. ' . $error_message . '");';
                echo 'window.location.href = "user_manage.php";';
                echo '</script>';
            }

            // Close the update statement
            mysqli_stmt_close($update_stmt);
        }

        // Close the check statement
        mysqli_stmt_close($check_stmt);
    }
}
else {
  echo "error very error";
}
?>
