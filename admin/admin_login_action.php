<?php
session_start();

include "../shared/config.php";

if($_SERVER["REQUEST_METHOD"] == "POST"){
  $admin_username = $_POST['admin_username'];
  $admin_password = $_POST['admin_password'];

  $sql = "SELECT * FROM admin WHERE username = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $admin_username);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result && $result->num_rows == 1) {
      $admin = $result->fetch_assoc();
      $password_hash = $admin['password'];

      if (password_verify($admin_password, $password_hash)) {
          $_SESSION['admin'] = $admin['username'];
          $_SESSION['role'] = 'admin';
          echo '<script>alert("Login Successful");</script>';
          header("Location: admin_dashboard.php");
          exit;
      } else {
          echo '<script>alert("Incorrect Password"); window.location.href = "login_admin.php";</script>';
      }
  } else {
      echo '<script>alert("User not found"); window.location.href = "login_admin.php";</script>';
  }
}
?>
