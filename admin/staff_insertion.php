
<?php
session_start();
include("../shared/config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $staff_id = $_POST['staff_id'];
    $staff_name = $_POST['staff_name'];
    $staff_position = $_POST['staff_position'];
    $staff_contact = $_POST['staff_contact'];
    $facility_id = $_POST['facility_id'];
    $staff_password = $_POST['staff_password'];
    $hashed_password = password_hash($staff_password, PASSWORD_DEFAULT);

    // Check if the Facility ID already exists
    $check_sql = "SELECT COUNT(*) FROM staff WHERE staff_ID = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $staff_id);
    $check_stmt->execute();
    $check_stmt->bind_result($count);
    $check_stmt->fetch();
    $check_stmt->close();

    if ($count > 0) {
        // Facility ID already exists, display an alert
        echo '<script>';
        echo 'alert("Staff ID already exists. Please choose a different Staff ID.");';
        echo 'window.location.href = "staff_manage.php";'; // Redirect back to the form page
        echo '</script>';
        exit();
    } else {

      // Insert the hashed password into the database
      $insert_sql = "INSERT INTO staff (staff_ID, staff_name, staff_position, staff_password, staff_contact, facility_ID) VALUES (?, ?, ?, ?, ?, ?)";
      $stmt = $conn->prepare($insert_sql);
      $stmt->bind_param("ssssss", $staff_id, $staff_name, $staff_position, $hashed_password, $staff_contact, $facility_id);

      // Execute the statement
      if ($stmt->execute()) {
          echo '<script>';
          echo 'alert("Insert Staff Successful");';
          echo 'window.location.href = "staff_manage.php";';
          echo '</script>';
      } else {
          echo "Error: " . $stmt->error;
      }

      // Close the connection
      $stmt->close();
      $conn->close();

    }
  }
?>
