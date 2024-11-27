<?php
session_start();

include("../shared/config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_ICNumber = $_POST['user_ICNumber'];
    $BloodType = $_POST['BloodType'];
    $status = $_POST['status'];
        // For blood units with statuses other than "Available," insert them without updating the quantity
        $sql = "INSERT INTO Blood (user_ICNumber, BloodType, Status) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $user_ICNumber, $BloodType, $status);

        if ($stmt->execute()) {
            echo '<script>alert("Data inserted successfully"); window.location.href = "blood_manage.php";</script>';
            exit;
        } else {
            echo '<script>alert("Error inserting data"); window.location.href = "blood_manage.php";</script>';
            exit;
    }
}
?>
