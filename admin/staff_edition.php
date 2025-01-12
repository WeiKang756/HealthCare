<?php
session_start();
include"../shared/config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $staff_id = $_POST['staff_id'];
    $staff_name = $_POST['staff_name'];
    $staff_position = $_POST['staff_position'];
    $staff_contact = $_POST['staff_contact'];
    $facility_id = $_POST['facility_id'];

    // Check if the Staff ID exists
    $check_sql = "SELECT COUNT(*) FROM staff WHERE staff_ID = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $staff_id);
    $check_stmt->execute();
    $check_stmt->bind_result($count);
    $check_stmt->fetch();
    $check_stmt->close();

    if ($count > 0) {
        // Staff ID exists, update the staff information
        $update_sql = "UPDATE staff SET staff_name = ?, staff_position = ?, staff_contact = ?, facility_ID = ? WHERE staff_ID = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("sssss", $staff_name, $staff_position, $staff_contact, $facility_id, $staff_id);

        if ($update_stmt->execute()) {
            echo '<script>';
            echo 'alert("Update Staff Successful");';
            echo 'window.location.href = "staff_manage.php";';
            echo '</script>';
        } else {
            echo "Error: " . $update_stmt->error;
        }

        $update_stmt->close();
    } else {
        // Staff ID doesn't exist, display an alert
        echo '<script>';
        echo 'alert("Staff ID not found. Please provide a valid Staff ID.");';
        echo 'window.location.href = "staff_manage.php";';
        echo '</script>';
    }

    $conn->close();
}
?>
