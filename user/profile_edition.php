<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

include "../shared/config.php";
include "../shared/function.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve user details from the form
    $user_ICNumber = $_SESSION['user_id'];
    $user_name = $_POST['user_name'];
    $user_contact = $_POST['user_contact'];
    $user_email = $_POST['user_email'];
    $user_address = $_POST['user_address'];

    // Validate or sanitize the inputs if necessary

    // Check if the updated email and contact exist for other users
    $check_sql = "SELECT user_ICNumber FROM users WHERE (user_email = ? OR user_contact = ?) AND user_ICNumber != ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("sss", $user_email, $user_contact, $user_ICNumber);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        // Email or contact already exists for other users
        $_SESSION['edit_profile_error'] = true;
        header("Location: home.php"); // Redirect back to the profile page with an error message
        exit;
    }

    // Update user information in the database
    $update_sql = "UPDATE users SET
                    user_name = ?,
                    user_contact = ?,
                    user_email = ?,
                    user_address = ?
                    WHERE user_ICNumber = ?";

    $update_stmt = $conn->prepare($update_sql);

    // Bind parameters
    $update_stmt->bind_param("sssss", $user_name, $user_contact, $user_email, $user_address, $user_ICNumber);

    // Execute the prepared statement
    if ($update_stmt->execute()) {
        // The update was successful
        $_SESSION['edit_profile_success'] = true;
        header("Location: home.php"); // Redirect to the profile page or any other appropriate page
        exit;
    } else {
        // The update failed
        echo "Error updating user information: " . $update_stmt->error;
    }
} else {
    // If the request method is not POST, handle it accordingly
    echo "Invalid request method";
}
?>
