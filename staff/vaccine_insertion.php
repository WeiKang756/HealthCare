<?php
session_start();
namespace Staff;

use Shared\Config;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $vaccine_name = $_POST['vaccine_name'];
    $total_dose = $_POST['total_dose'];

    // Validate data if needed
    if (empty($vaccine_name) || empty($total_dose)) {
        // Handle empty fields
        echo "All fields are required.";
        // You might want to redirect back to the form or display an error message
        exit;
    }

    // Perform the insertion query
    $insert_query = "INSERT INTO vaccine (vaccine_name, total_doses) VALUES (?, ?)";
    $insert_stmt = $conn->prepare($insert_query);
    $insert_stmt->bind_param("ss", $vaccine_name, $total_dose);

    if ($insert_stmt->execute()) {
        // The insertion was successful
        header("Location: vaccine.php");
        exit;
    } else {
        // The insertion failed
        echo "Error: " . $insert_stmt->error;
    }

    $insert_stmt->close();
} else {
    // If the request method is not POST, handle it accordingly
    echo "Invalid request.";
}
