<?php
session_start();
namespace User;

use Shared\Config;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $service = $_POST['service']; // Check if the form has been submitted
    $user_ICNumber = $_SESSION['user_id']; // Replace with your form field names
    $facility_ID = $_POST['facility'];
    $appointmentDate = $_POST['appointmentDate'];
    $timeSlot = $_POST['timeSlot'];

    // Check if the selected time slot is available
    if (!isTimeSlotAvailable($appointmentDate, $facility_ID, $timeSlot)) {
        $_SESSION['error'] = true;
        header("Location: appointment.php"); // Redirect to the dashboard page or any other desired page
        exit();
    } else {
        // Prepare SQL Insert statement
        $sql = "INSERT INTO Appointments (user_ICNumber, facility_ID, AppointmentDate, TimeSlot, Service, Status) VALUES (?, ?, ?, ?, ?, 'Confirmed')";
        $stmt = $conn->prepare($sql);

        // Bind parameters to the statement
        $stmt->bind_param("sssss", $user_ICNumber, $facility_ID, $appointmentDate, $timeSlot, $service);

        // Execute the statement
        if ($stmt->execute()) {
          $_SESSION['success'] = true;
          header("Location: appointment.php"); // Redirect to the dashboard page or any other desired page
          exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close statement and connection
        $stmt->close();
    }

    $conn->close();
}

function isTimeSlotAvailable($date, $facilityID, $timeSlot) {
    global $conn; // Ensure that $conn is accessible if it's defined outside this function

    // SQL query to check if the selected time slot is available
    $sql = "SELECT COUNT(*) AS count FROM Appointments WHERE AppointmentDate = ? AND facility_ID = ? AND TimeSlot = ?";
    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bind_param("sss", $date, $facilityID, $timeSlot);

    // Execute the query
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    // Close statement
    $stmt->close();

    // If count is 0, the time slot is available; otherwise, it's already booked
    return ($row['count'] == 0);
}

 ?>
