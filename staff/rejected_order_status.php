<?php
session_start(); // Start or resume the session

// Check if the user is logged in (you may customize this part)
if (!isset($_SESSION['staff_ID'])) {
    // Redirect to the login page if the user is not logged in
    header("Location: index.php"); // Change "index.php" to your actual login page
    exit;
}

use Shared\Config;
use Shared\Functions;

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the order ID from the POST request
    $orderID = $_POST['orderID'];

    // Update the order status in the database (replace 'orders' with your actual table name)
    $update_sql = "UPDATE FacilityBloodOrders SET status = 'Rejected' WHERE order_ID = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("s", $orderID);

    if ($update_stmt->execute()) {
        // The update was successful

        $update_blood = updateBloodUnitsStatus($orderID, "Available", $conn);
        if ($update_blood) {
            echo "Success";
            exit;
        } else {
            echo "Error"; // You can customize the response message
            exit;
        }
    } else {
        // The update failed
        echo "Error"; // You can customize the response message
        exit;
    }
} else {
    // If the request method is not POST, handle it accordingly
    echo "Invalid request method"; // You can customize the response message
    exit;
}
?>
