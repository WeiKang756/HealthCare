<?php
session_start();
namespace Admin;

use Shared\Config;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $staff_id = $_POST['staff_id'];

    // Check if the Staff ID exists
    $check_sql = "SELECT COUNT(*) FROM staff WHERE staff_ID = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $staff_id);
    $check_stmt->execute();
    $check_stmt->bind_result($count);
    $check_stmt->fetch();
    $check_stmt->close();

    if ($count > 0) {
        // Staff ID exists, delete the staff record
        $delete_sql = "DELETE FROM staff WHERE staff_ID = ?";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bind_param("s", $staff_id);

        if ($delete_stmt->execute()) {
            echo '<script>';
            echo 'alert("Delete Staff Successful");';
            echo 'window.location.href = "staff_manage.php";';
            echo '</script>';
        } else {
            echo "Error: " . $delete_stmt->error;
        }

        $delete_stmt->close();
    } else {
        // Staff ID doesn't exist, display an alert
        echo '<script>';
        echo 'alert("Staff ID not found. Please provide a valid Staff ID.");';
        echo 'window.location.href = "staff_manage.php";';
        echo '</script>';
    }

    $conn->close();
}
