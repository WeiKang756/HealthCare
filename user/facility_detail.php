<?php

session_start();
namespace User;

use Shared\Config;
use Shared\Functions;

define('TD_CLOSE_TAG', '</td>');

// Get the facilityID value from the URL
$facilityID = $_GET['facilityID'];
$query = "SELECT * FROM facilities WHERE facility_ID = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $facilityID);
mysqli_stmt_execute($stmt);

// Get the result
$result = mysqli_stmt_get_result($stmt);

// Fetch facility information
$facilityInfo = mysqli_fetch_assoc($result);
$facility_Name = $facilityInfo['facility_Name'];
$facility_address = $facilityInfo['facility_address'];
$facility_contact = $facilityInfo['facility_contact'];
$facility_logo = $facilityInfo['facility_logo'];
 ?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <title>Sabah Healthcare</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Unbounded:wght@900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:opsz@6..12&family=Unbounded:wght@900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="UserCSS/style.css">
</head>
<body>
<?php
  $activePage = 'facility';
use User\Header; 
?>
<main>
  <div class="container">
    <div class="rows">
      <div class="col h-100">
      <div class="card mt-5 h-100">
        <div class="card-body">
          <div class="row">
            <div class="col-lg-6">
              <div class="col-lg-10">
                  <h4 class="card-title">Facility Information</h4>
                  <table class="table table-hover">
                      <tr>
                          <td>Facility Name:</td>
                          <td><?php echo $facility_Name; ?></td>
                      </tr>
                      <tr>
                          <td>Facility ID:</td>
                          <td><?php echo $facilityID; ?></td>
                      </tr>
                      <tr>
                          <td>Facility Contact:</td>
                          <td><?php echo $facility_contact; ?></td>
                      </tr>
                      <tr>
                          <td>Facility Address:</td>
                          <td><?php echo $facility_address; ?></td>
                      </tr>
                  </table>
              </div>
            </div>
            <div class="col-lg-6 text-center">
              <p><img src="<?php echo $facility_logo ?>" alt="logo" style="width: 300px; height: 300px;" class="img-fluid"></p>
            </div>
          <div class="row text-center mt-3">
            <hr>
            <h4>Operation Hour</h4>
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th>Day</th>
                    <th>Opening Time</th>
                    <th>Closing Time</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $result_operation = getFacilityOperationHours($conn, $facilityID);

                  while ($row = mysqli_fetch_assoc($result_operation)) {
                      echo '<tr>';
                      echo '<td>' . $row['DayOfWeek'] . TD_CLOSE_TAG;
                      echo '<td>' . $row['OpeningTime'] . TD_CLOSE_TAG;
                      echo '<td>' . $row['ClosingTime'] . TD_CLOSE_TAG;
                      echo '<td>' . $row['Status'] . TD_CLOSE_TAG;
                      echo '</tr>';
                  }
                  ?>
                </tbody>
              </table>
          </div>
        </div>
        </div>
      </div>
    </div>
  </div>
</main>
</body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Use .FacilityInfo as a class selector
    var FacilityInfo = document.querySelectorAll('.FacilityInfo');

    FacilityInfo.forEach(function(button) {
        button.addEventListener('click', function() {
            var facilityID = this.getAttribute('data-facility-id'); // Corrected attribute name
            window.location.href = 'activities_edit.php?facilityID=' + facilityID;
        });
    });
</script>


</script>
</html>
