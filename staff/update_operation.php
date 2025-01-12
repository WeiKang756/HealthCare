<?php
session_start();

if (!isset($_SESSION['staff_ID'])) {
    header("Location: index.php");
    exit;
}

include"../shared/config.php";
include"../shared/function.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $facility_ID = $_SESSION['facility_ID'];

    // Loop through the arrays using the dynamic indices
    foreach ($_POST['DayOfWeek'] as $rowIndex => $dayOfWeek) {
        $openingTime = $_POST['OpeningTime'][$rowIndex];
        $closingTime = $_POST['ClosingTime'][$rowIndex];
        $status = $_POST['Status'][$rowIndex];

        // Your SQL update statements go here
        // Example: Update the operation hours in FacilityOperation table
        if ($status == "Open") {
            $update_sql = "UPDATE FacilityOperation SET
                            OpeningTime = ?,
                            ClosingTime = ?,
                            Status = ?
                            WHERE DayOfWeek = ? AND
                            facility_ID = ?";

            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("sssss", $openingTime, $closingTime, $status, $dayOfWeek, $facility_ID);
        } else {
            $update_sql = "UPDATE FacilityOperation SET
                            OpeningTime = NULL,
                            ClosingTime = NULL,
                            Status = ?
                            WHERE DayOfWeek = ?
                            AND facility_ID = ?";

            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("sss", $status, $dayOfWeek, $facility_ID);
        }

        // Execute the prepared statement
        if ($update_stmt->execute()) {
            // The update was successful
            echo json_encode(["status" => "Success"]);
        } else {
            // The update failed
            echo json_encode(["status" => "Error", "message" => $update_stmt->error]);
        }
    }
} else {
    // If the request method is not POST, handle it accordingly
    echo json_encode(["status" => "InvalidRequest"]);
}
?>
