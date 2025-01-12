<?php
// search_orders.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

use Shared\Config;
use Shared\Functions;
session_start();

if ($_SERVER["REQUEST_METHOD"] === "GET") {
  $user_ICNumber = isset($_GET["user_ICNumber"]) ? $_GET["user_ICNumber"] : '';

  // Construct the SELECT query with JOIN

  $sql = "SELECT users.*, user_vaccine.*, vaccine.*
          FROM users
          LEFT JOIN user_vaccine ON users.user_ICNumber = user_vaccine.user_ICNumber
          LEFT JOIN vaccine ON user_vaccine.vaccine_ID = vaccine.vaccine_ID
          WHERE users.user_ICNumber = ?";

  $stmt = $conn->prepare($sql);

  if ($stmt) {
      $stmt->bind_param("s", $user_ICNumber);
      $stmt->execute();
      $result = $stmt->get_result();
      $stmt->close();

      $orders = [];

      // Fetch results and store in an array
      while ($row = $result->fetch_assoc()) {
          $orders[] = $row;
      }

      $conn->close();

      // Output JSON for jQuery to handle on the client side
      echo json_encode($orders);
  } else {
      // Debugging: Output the error message
      echo json_encode(["error" => "Error in preparing SQL statement: " . $conn->error]);
  }
} else {
    // Debugging: Output the error message
    echo json_encode(["error" => "Invalid request method."]);
}
