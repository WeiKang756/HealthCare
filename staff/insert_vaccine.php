<?php
// Include your database connection configuration
include("../shared/config.php");

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sanitize and get form data
    $user_ICNumber = mysqli_real_escape_string($conn, $_POST['user_ICNumber']);
    $vaccine_ID = mysqli_real_escape_string($conn, $_POST['vaccine_ID']);
    $dose_number = mysqli_real_escape_string($conn, $_POST['dose_number']);
    $vaccination_date = mysqli_real_escape_string($conn, $_POST['vaccination_date']);

    // Check for empty values
    if (empty($user_ICNumber) || empty($vaccine_ID) || empty($dose_number) || empty($vaccination_date)) {
        echo json_encode(["status" => "error", "message" => "All fields are required"]);
        exit;
    }

    // Your SQL query to insert data into the 'user_vaccine' table using prepared statements
    $sql = "INSERT INTO user_vaccine (user_ICNumber, vaccine_ID, dose_number, vaccination_date) VALUES (?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("siss", $user_ICNumber, $vaccine_ID, $dose_number, $vaccination_date);

    // Check if the query is executed successfully
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Vaccine record inserted successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error inserting vaccine record"]);
    }

    // Close the prepared statement
    $stmt->close();
} else {
    // Return an error message for invalid request
    echo json_encode(["status" => "error", "message" => "Invalid request"]);
}

// Close the database connection
$conn->close();
?>
