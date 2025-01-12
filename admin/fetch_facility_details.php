<?php
include"../shared/config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $facilityId = $_POST['facilityId'];

    // Fetch facility details
    $selectQuery = "SELECT * FROM facilities WHERE facility_ID = '$facilityId'";
    $result = $conn->query($selectQuery);

    if ($result->num_rows > 0) {
        $facilityDetails = $result->fetch_assoc();
        echo json_encode($facilityDetails);
    } else {
        echo json_encode(['error' => 'Facility details not found.']);
    }
} else {
    echo json_encode(['error' => 'Invalid request method.']);
}
?>
