<?php
session_start();

namespace Staff;

use Shared\Config;

if($_SERVER["REQUEST_METHOD"] == "POST"){
  $staff_ID = $_POST['staff_ID'];
  $staff_password = $_POST['staff_password'];

  $sql = "SELECT * FROM staff WHERE staff_ID = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $staff_ID);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result && $result->num_rows == 1) {
      $staff = $result->fetch_assoc();
      $password_hash = $staff['staff_password'];
      $facility_ID = $staff['facility_ID'];

      if (password_verify($staff_password, $password_hash)) {
          $_SESSION['staff_ID'] = $staff['staff_ID'];
          $_SESSION['facility_ID'] = $staff['facility_ID'];
          $_SESSION['position'] = $staff['staff_position'];
          echo '<script>alert("Login Successful");</script>';
          header("Location: staff_home.php");
          exit;
      } else {
          echo '<script>alert("Incorrect Password"); window.location.href = "staff_login.php";</script>';
      }
  } else {
      echo '<script>alert("User not found"); window.location.href = "staff_login.php";</script>';
  }
}
