<?php
session_start();

include"../shared/config.php";


?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Staff</title>
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
        include"nav.php";
     ?>
<main>

  <div class="container">
    <div class="row mt-4">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <h3>Blood Bank Admin Only</h3>
            <div class="row">
            <div class="col-lg-4">
              <a href="blood_manage.php" class="card-link">
              <div class="card">
              <img src="../Icon/user.png" class="card-img icon" alt="...">
              <div class="card-img-overlay ">
                <h5 class="card-title">Blood Management</h5>
              </div>
            </div>
            </a>
          </div>
          <div class="col-lg-4">
            <a href="blood_order.php" class="card-link">
            <div class="card">
            <img src="../Icon/user.png" class="card-img icon" alt="...">
            <div class="card-img-overlay ">
              <h5 class="card-title">Blood Order</h5>
            </div>
          </div>
          </a>
        </div>
      </div>
  </div>
  </div>
  </div>
  </div>
  <div class="row mt-4">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <h3>Doctor Only</h3>
          <div class="row">
            <div class="col-lg-4">
              <a href="medical.php" class="card-link">
              <div class="card">
              <img src="../Icon/user.png" class="card-img icon" alt="...">
              <div class="card-img-overlay ">
                <h5 class="card-title">Medical</h5>
              </div>
            </div>
            </a>
          </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row mt-4">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <div class="row">
          <div class="col-lg-4">
            <a href="appointment.php" class="card-link">
            <div class="card">
            <img src="../Icon/user.png" class="card-img icon" alt="...">
            <div class="card-img-overlay ">
              <h5 class="card-title">Appointment</h5>
            </div>
          </div>
          </a>
        </div>
        <div class="col-lg-4">
          <a href="facility_info.php" class="card-link">
          <div class="card">
          <img src="../Icon/user.png" class="card-img icon" alt="...">
          <div class="card-img-overlay ">
            <h5 class="card-title">Facility Info</h5>
          </div>
        </div>
        </a>
      </div>
      <div class="col-lg-4">
        <a href="blood_order_request.php" class="card-link">
        <div class="card">
        <img src="../Icon/user.png" class="card-img icon" alt="...">
        <div class="card-img-overlay ">
          <h5 class="card-title">Blood Order Request</h5>
        </div>
      </div>
      </a>
    </div>
    <div class="col-lg-4">
      <a href="vaccine.php" class="card-link">
      <div class="card">
      <img src="../Icon/user.png" class="card-img icon" alt="...">
      <div class="card-img-overlay ">
        <h5 class="card-title">List of Vaccine</h5>
      </div>
    </div>
    </a>
  </div>

  <div class="col-lg-4">
    <a href="medical_order.php" class="card-link">
    <div class="card">
    <img src="../Icon/user.png" class="card-img icon" alt="...">
    <div class="card-img-overlay ">
      <h5 class="card-title">Medical Orders</h5>
    </div>
  </div>
  </a>
  </div>
            </div>
        </div>
        </div>
      </div>
    </div>
  </div>


</main>

  </body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</html>
