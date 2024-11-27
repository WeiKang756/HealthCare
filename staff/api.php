<?php

// Include necessary database connection and query logic
include("../shared/config.php");

if ($_GET['action'] === 'getPatientInfo') {
    $icNumber = $_GET['icNumber'];

    // Assuming you have a function to fetch patient information based on IC Number
    $patientInfo = getPatientInfoFromDatabase($icNumber);

    // Return the result as JSON
    header('Content-Type: application/json');
    echo json_encode($patientInfo);
    exit;
}

// Other API actions can be handled similarly

function getPatientInfoFromDatabase($icNumber) {
    global $conn; // Assuming you have a database connection

    // Use prepared statements to prevent SQL injection
    $sql = "SELECT * FROM users WHERE user_ICNumber = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        // Log the SQL error
        error_log("SQL Error: " . $conn->error);
        return ['error' => 'Internal Server Error'];
    }

    $stmt->bind_param("s", $icNumber);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch the patient information
        $row = $result->fetch_assoc();

        // You can customize the returned data structure based on your needs
        $patientInfo = [
            'name' => $row['name'],
            'age' => $row['age'],
            // Add more properties as needed
        ];

        $stmt->close();

        return $patientInfo;
    } else {
        // If no matching record is found
        $stmt->close();
        return ['error' => 'Patient not found'];
    }

?>
