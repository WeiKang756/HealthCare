<?php
namespace Staff;

use Shared\Config;

// Check if order_id is set in the URL
if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];

    // Fetch order details from the database
    $sql = "SELECT * FROM MedicalOrders WHERE order_id = $order_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Convert result to associative array
        $order_details = $result->fetch_assoc();

        // Output order details as JSON
        header('Content-Type: application/json');
        echo json_encode($order_details);
    } else {
        // No results found
        echo json_encode(['error' => 'Order not found']);
    }
} else {
    // Missing order_id parameter
    echo json_encode(['error' => 'Missing order_id parameter']);
}
?>
