<?php
session_start();
namespace Admin;

use Shared\Config;
use Shared\Navigation;

Navigation::render();

session_start();

$username = $_SESSION['admin'];
$user_role = $_SESSION['role'];

if ($user_role !== 'admin') {
    echo "You are not authorized to access this page.  Please contact the administrator for assistance.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Unbounded:wght@900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:opsz@6..12&family=Unbounded:wght@900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
  </head>
  <style media="screen">

.icon{
  height: 80px;
  width: 80px;
  margin-left: 160px;
  margin-top: 56px;
}

  </style>
  <body>
    <?php
    Navigation::render();
     ?>
<main>

  <div class="container">
    <div class="row mt-4">
      <div class="col-lg-4">
        <a href="user_manage.php" class="card-link">
        <div class="card">
        <img src="../Icon/user.png" class="card-img icon" alt="...">
        <div class="card-img-overlay ">
          <h5 class="card-title">User Management</h5>
        </div>
      </div>
      </a>
    </div>
      <div class="col-lg-4">
        <a href="facility_manage.php" class="card-link">
        <div class="card">
        <img src="../Icon/hospital.png" class="card-img icon" alt="...">
        <div class="card-img-overlay ">
          <h5 class="card-title">Facility Management</h5>
        </div>
      </div>
      </a>
    </div>
    <div class="col-lg-4">
      <a href="staff_manage.php" class="card-link">
      <div class="card">
      <img src="../Icon/employees.png" class="card-img icon" alt="...">
      <div class="card-img-overlay ">
        <h5 class="card-title">Staff Management</h5>
      </div>
    </div>
    </a>
  </div>
  </div>
</div>

</main>

  </body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</html>
