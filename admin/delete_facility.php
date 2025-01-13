<?php
namespace Admin;

use Shared\Config;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $facilityId = $_POST['facilityId'];

    // Perform the deletion (replace 'your_facility_table_name' with your actual table name)
    $deleteQuery = "DELETE FROM facilities WHERE facility_ID = '$facilityId'";
    $result = $conn->query($deleteQuery);

    if ($result) {
      echo '<script>';
      echo 'alert("Delete Facility Successful");';
      echo 'window.location.href = "facility_manage.php";';
      echo '</script>';
    } else {
        echo 'Error deleting facility: ' . $conn->error;
    }
} else {
    echo 'Invalid request method.';
}
?>
