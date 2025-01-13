<?php
session_start();
namespace Admin;

use Shared\Config;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $facility_id = $_POST['facility_id'];
    $facility_name = $_POST['facility_name'];

    // Check if the Facility ID already exists
    $check_sql = "SELECT COUNT(*) FROM facilities WHERE facility_ID = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $facility_id);
    $check_stmt->execute();
    $check_stmt->bind_result($count);
    $check_stmt->fetch();
    $check_stmt->close();

    if ($count > 0) {
        // Facility ID already exists, display an alert
        echo '<script>';
        echo 'alert("Facility ID already exists. Please choose a different Facility ID.");';
        echo 'window.location.href = "facility_manage.php";'; // Redirect back to the form page
        echo '</script>';
        exit();
    } else {
        // Facility ID is unique, proceed with insertion
        $sql = "INSERT INTO facilities (facility_ID, facility_Name, facility_logo) VALUES (?, ?, '../Images/logo.png')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $facility_id, $facility_name);

        if ($stmt->execute()) {
            echo '<script>';
            echo 'alert("Insert Facility Successful");';
            echo 'window.location.href = "facility_manage.php";';
            echo '</script>';
            exit();
        } else {
            // Error handling
            echo "Error: " . $stmt->error;
        }
    }
}
?>
