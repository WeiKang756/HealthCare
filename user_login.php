<?php
session_start();
use Shared\Config;

$ic_num = $_POST["ic_num"];
$login_password = $_POST["login_password"];

// Check user credentials in the database (replace with your database query)
$login_sql = "SELECT * FROM users WHERE user_ICNumber = ?";
$login_stmt = mysqli_prepare($conn, $login_sql);
mysqli_stmt_bind_param($login_stmt, "s", $ic_num);
mysqli_stmt_execute($login_stmt);
$login_result = mysqli_stmt_get_result($login_stmt);
$login_row = mysqli_fetch_assoc($login_result);

if ($login_row && password_verify($login_password, $login_row['user_password'])) {
    // Successful login, set session variables, e.g., $_SESSION['user_id'] = $row['user_id']
    $_SESSION['user_id'] = $login_row['user_ICNumber'];
    $_SESSION['user_name'] = $login_row['user_name'];
    $_SESSION['user_contact'] = $login_row['user_contact'];
    $_SESSION['user_email'] = $login_row['user_email'];
    $_SESSION['user_address'] = $login_row['user_address'];


    header("Location: user/home.php"); // Redirect to the dashboard page or any other desired page
    exit();
} else {
  $_SESSION['login_fail'] = true;
  header("Location: index.php"); // Redirect to the dashboard page or any other desired page
  exit();
}

mysqli_stmt_close($login_stmt);
 
