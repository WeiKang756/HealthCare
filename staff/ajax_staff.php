<?php
session_start();
namespace Staff;

use Shared\Config;


// Simulate fetching facilities based on the selected service
if (isset($_GET['action']) && $_GET['action'] === 'checkUser') {
    global $conn;
    $user_ICNumber = $_GET['ICNum'];

    // Perform the query to check if the user exists
    $query = "SELECT * FROM users WHERE user_ICNumber = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $user_ICNumber);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // User exists
        echo json_encode(["exists" => true]);
    } else {
        // User does not exist
        echo json_encode(["exists" => false]);
    }

    $stmt->close();
} else {
    // If the request method is not POST, handle it accordingly
    echo "Invalid request.";
}

// Simulate fetching user vaccine records based on the IC Number
if (isset($_GET['action']) && $_GET['action'] === 'fetchUserVaccineRecords') {
    global $conn;
    $user_ICNumber = $_GET['ICNum'];

    // Perform the query to get user vaccine records
    $query = "SELECT * FROM user_vaccine WHERE user_ICNumber = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $user_ICNumber);
    $stmt->execute();
    $result = $stmt->get_result();

    $userVaccineRecords = array();

    if ($result->num_rows > 0) {
        // Fetch and store user vaccine records
        while ($row = $result->fetch_assoc()) {
            $userVaccineRecords[] = $row;
        }
    }

    echo json_encode($userVaccineRecords);
    $stmt->close();
} else {
    // If the request method is not POST, handle it accordingly
    echo json_encode(["error" => "Invalid request"]);
}
