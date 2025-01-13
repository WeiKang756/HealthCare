<?php
session_start();

include "../shared/config.php"; // Include your database connection configuration

// Assuming you receive the appointment ID through a POST request
if (isset($_POST['appointmentID'])) {
    $appointmentID = $_POST['appointmentID'];

    // Perform the database operation to delete the appointment
    $deleteAppointmentQuery = "DELETE FROM Appointments WHERE AppointmentID = ?";
    $deleteAppointmentStmt = $conn->prepare($deleteAppointmentQuery);
    $deleteAppointmentStmt->bind_param("i", $appointmentID);

    if ($deleteAppointmentStmt->execute()) {
        // Appointment deleted successfully
        echo json_encode(["status" => "success", "message" => "Appointment deleted successfully"]);
    } else {
        // Error deleting the appointment
        echo json_encode(["status" => "error", "message" => "Error deleting the appointment"]);
    }

    $deleteAppointmentStmt->close();
} else {
    // Invalid request, appointment ID not provided
    echo json_encode(["status" => "error", "message" => "Invalid request"]);
}

$conn->close();
?>
