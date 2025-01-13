<?php
// search_orders.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

namespace Staff;

use Shared\Config;
use Shared\Functions;

session_start();

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    try {
        // Get the user IC Number from GET parameters
        $user_ICNumber = isset($_GET["user_ICNumber"]) ? $_GET["user_ICNumber"] : '';

        // Validate input
        if (empty($user_ICNumber)) {
            jsonResponse(["error" => "User IC Number is required"], false);
            exit;
        }

        // Include database connection
        require_once 'db_connection.php'; // Assume this sets $conn

        // Fetch user data
        $orders = fetchUserData($conn, $user_ICNumber);

        // Return JSON response
        jsonResponse($orders);
    } catch (Exception $e) {
        // Handle any exceptions and send error response
        jsonResponse(["error" => $e->getMessage()], false);
    }
} else {
    jsonResponse(["error" => "Invalid request method."], false);
}

/**
 * Fetch user data based on IC Number.
 *
 * @param mysqli $conn
 * @param string $user_ICNumber
 * @return array
 * @throws Exception
 */
function fetchUserData($conn, $user_ICNumber) {
    $sql = "SELECT users.*, user_vaccine.*, vaccine.*
            FROM users
            LEFT JOIN user_vaccine ON users.user_ICNumber = user_vaccine.user_ICNumber
            LEFT JOIN vaccine ON user_vaccine.vaccine_ID = vaccine.vaccine_ID
            WHERE users.user_ICNumber = ?";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("SQL error: " . $conn->error);
    }

    $stmt->bind_param("s", $user_ICNumber);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    return $result->fetch_all(MYSQLI_ASSOC);
}

/**
 * Send a JSON response.
 *
 * @param array $data
 * @param bool $success
 * @return void
 */
function jsonResponse($data, $success = true) {
    header('Content-Type: application/json');
    echo json_encode([
        "success" => $success,
        "data" => $data
    ]);
    exit;
}
?>
