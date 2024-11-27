<?php
session_start();
include("../shared/config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST"){
$facility_ID = $_SESSION['facility_ID'];
$facility_Name = $_POST['facility_name'];
$facility_contact = $_POST['facility_contact'];
$facility_address = $_POST['facility_address'];

$query = "UPDATE Facilities
          SET facility_Name = ?,
              facility_contact = ?,
              facility_address = ?
          WHERE facility_ID = ?";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "ssss", $facility_Name, $facility_contact, $facility_address, $facility_ID);
  if (mysqli_stmt_execute($stmt)) {
      echo '<script>';
      echo 'alert("Update Staff Successful");';
      echo 'window.location.href = "facility_info.php";';
      echo '</script>';
  } else {
      echo "Error: " . $stmt->error;
  }

  $stmt->close();
  } else {
  echo '<script>';
  echo 'alert("Staff ID not found. Please provide a valid Staff ID.");';
  echo 'window.location.href = "facility_info.php";';
  echo '</script>';
  }

mysqli_stmt_close($stmt);
?>
