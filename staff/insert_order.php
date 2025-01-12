<?php
// search_orders.php
error_reporting(E_ALL);
ini_set('display_errors', 1);
// insert_order.php
include"../shared/config.php";
include"../shared/function.php";
session_start();

$staff_ID = $_SESSION['staff_ID'];
$facility_ID = $_SESSION['facility_ID'];
$orderID = generateUniqueOrderID();

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $user_ICNumber = $_POST["user_ICNumber"];
    $prescription = $_POST["prescription"];
    $instructions = $_POST["instructions"];
    $diagnostic = $_POST["diagnostic"];

    // Insert data into the orders table
    $sql = "INSERT INTO MedicalOrders (order_id, user_ICNumber, prescription, instructions, Status, staff_ID, facility_ID, diagnostic)
            VALUES (?, ?, ?, ?, 'Pending', ?, ?, ?)"; // Assuming 'Pending' as the default status

    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("sssssss", $orderID, $user_ICNumber, $prescription, $instructions, $staff_ID, $facility_ID, $diagnostic);

        if ($stmt->execute()) {
            $stmt->close();
            $conn->close();
            echo "Order successfully submitted!";
        } else {
            echo "Error executing SQL statement: " . $stmt->error;
        }
    } else {
        echo "Error in preparing SQL statement: " . $conn->error;
    }
} else {
    echo "Invalid request method.";
}
?>
