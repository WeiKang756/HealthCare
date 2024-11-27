<?php
session_start();

include("../shared/config.php");

if($_SERVER["REQUEST_METHOD"] == "POST"){
  $admin_username = $_POST['admin_username'];
  $admin_password = $_POST['admin_password'];

  // 使用预处理语句来执行 SQL 查询
  $sql = "SELECT * FROM admin WHERE username = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $admin_username);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result && $result->num_rows == 1) {
      $admin = $result->fetch_assoc();
      $password_hash = $admin['password'];

      // 验证密码哈希
      if (password_verify($admin_password, $password_hash)) {
          // 密码正确；登录管理员
          $_SESSION['admin'] = $admin['username']; // 存储管理员用户名到会话
          $_SESSION['role'] = 'admin'; // 存储角色到会话
          echo '<script>alert("Login Successful");</script>';
          header("Location: admin_dashboard.php");
          exit;
      } else {
          // 密码错误
          echo '<script>alert("Incorrect Password"); window.location.href = "login_admin.php";</script>';
      }
  } else {
      // 用户不存在
      echo '<script>alert("User not found"); window.location.href = "login_admin.php";</script>';
  }
}
?>
