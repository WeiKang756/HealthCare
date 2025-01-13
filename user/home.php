<?php
session_start();
use Shared\Config;
use Shared\Functions;


$user_ICNumber = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE user_ICNumber = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_ICNumber);
$stmt->execute();
$result = $stmt->get_result();
if($row = $result->fetch_assoc()){
  $user_name = $row['user_name'];
  $user_contact = $row['user_contact'];
  $user_email = $row['user_email'];
  $user_address = $row['user_address'];
  $bloodType = $row['bloodType'];
  $userBloodType = $row['bloodType'];
}

$appointments = getAppointmentsWithFacility($user_ICNumber);

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
    <title>My Study KPI</title>
  </head>
  <style>

  .card{
    margin: 10px;
    box-shadow: 2px 2px 2px 0 rgba(0, 0, 0, 0.2);
  }

  .card-user{
    background: #F5FFE0;
    border: #F5FFE0;
  }

  .card-blood{
    background: #FCD7D3;
    border: #FCD7D3;
  }

  .card-profile{
    background: #FCFCFC;
    border: #FCFCFC;
  }

  .card-vaccine{
    background: #FFFFFF;
    border: #FFFFFF;
  }

  .icon{
    height: 120px;
    width: 120px;
    margin-top: -40px;
    margin-right: 20px;
  }

  .icon-blood{
    height: 100px;
    width: 100px;
    margin-top: -40px;
    margin-right: 20px;
  }

  .icon-profile{
    height: 100px;
    width: 100px;
    margin: auto;
    margin-top: -10px;
  }

  </style>
  <body>
  <?php
  $activePage = 'home';
   use User\Header; ?>
    <main>
      <div class="container">
        <div class="row m-0">
          <div class="col-lg-7">
            <div class="row">
            <div class="col-lg-7">
            <div class="card card-user">
              <div class="card-body">
                <h5>Welcome Back <span class="h6"> <?php echo $user_name; ?></span></h5>
                <p>You are a User</p>
                <div class="d-flex flex-row justify-content-end">
                  <img src="../Icon/user_icon.png" alt="User" class="icon">
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-5">
            <div class="card card-blood">
              <div class="dropdown ms-auto">
                    <a href="#" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false" class="end">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal align-middle"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
                    </a>
                    <div class="dropdown-menu">
                      <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#updateBloodTypeModal" href="#">Edit Blood Type</a>
                    </div>
                  </div>
              <div class="card-body">
                <h4>Blood Type</h4>
                <h5><?php echo $bloodType; ?></h5>
                <div class="d-flex flex-row justify-content-end">
                  <img src="../Icon/blood_icon.png" alt="User" class="icon-blood">
                </div>
              </div>
            </div>
          </div>
          </div>
          <div class="row">
            <div class="col-12 d-flex">
                      <?php

                        $sql = "SELECT uv.*, v.*
                              FROM user_vaccine uv
                              JOIN vaccine v ON uv.vaccine_ID = v.vaccine_ID
                              WHERE uv.user_ICNumber = '$user_ICNumber'
                              ORDER BY uv.dose_number DESC
                              LIMIT 1";

                        $result = $conn->query($sql);

                        echo '<div class="card card-vaccine flex-fill">';
                        echo '<div class="card-body">';

                        if ($result->num_rows > 0) {
                          $row = $result->fetch_assoc();
                          $doseStatus = ($row['dose_number'] >= $row['total_doses']) ? 'Fully Vaccinated' : 'Partially Vaccinated';
                          $doseStatus .= ($row['dose_number'] > $row['total_doses']) ? ' (Booster Dose)' : '';


                          echo '<h3>Vaccination</h3>';
                          echo '<h5>Status: ' . $doseStatus . '</h5>';
                          echo '<div class="row">';
                          echo '<div class="col-4">';
                          echo '<h6>Dose ' . $row['dose_number'] . ':</h6>';
                          echo '<p>Completed</p>';
                          echo '</div>';
                          // You can add more columns based on the number of doses or customize the display as needed
                          echo '</div>';
                        } else {
                          echo '<p>No vaccination records found for the user.</p>';
                        }
                        echo '</div>';
                        echo '</div>';
                        ?>
            </div>
          </div>
          </div>
          <div class="col-lg-5">
            <div class="card card-profile">
              <div class="card-header card-profile ms-auto">
                <div class="dropdown">
											<a href="#" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false" class="end">
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal align-middle"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
                      </a>
											<div class="dropdown-menu">
												<a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#EditProfile" href="#">Edit Profile</a>
											</div>
										</div>

              </div>
              <div class="card-body" style="margin-top:-20px">
                <table class="table table-hover">
                  <tr>
                    <td>Name</td>
                    <td><?php echo $user_name; ?></td>
                  </tr>
                  <tr>
                    <td>IC Number</td>
                    <td><?php echo $user_ICNumber; ?></td>
                  </tr>
                  <tr>
                    <td>User Contact</td>
                    <td><?php echo $user_contact; ?></td>
                  </tr>
                  <tr>
                    <td>User Address</td>
                    <td><?php echo $user_address; ?></td>
                  </tr>
                  <tr>
                    <td>User Email</td>
                    <td><?php echo $user_email; ?></td>
                  </tr>
                </table>
                <p class="d-flex flex-column"><img src="../Icon/profile.png" alt="User" class="icon-profile" ></p>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col">
            <div class="card">
              <div class="card-body">
                <h4>Upcoming Appointment</h4>
                <?php
                // Display the counts as a table
                echo '<table class="table table-hover table-responsive text-center">';
                echo '<tr><th>ID</th>
                      <th>Facility Name</th>
                      <th>Date</th>
                      <th>Time</th>
                      <th>Service</th>
                      <th>Status</th>
                      </tr>';

                if (empty($appointments)) {
                  echo "No Upcoming Appointment";
                }
                else {
                  foreach ($appointments as $appointment) {
                      echo '<tr>';
                      echo "<td>{$appointment['AppointmentID']}</td>";
                      echo "<td>{$appointment['facility_Name']}</td>";
                      echo "<td>{$appointment['AppointmentDate']}</td>";
                      echo "<td>{$appointment['TimeSlot']}</td>";
                      echo "<td>{$appointment['Service']}</td>";
                      echo "<td>{$appointment['Status']}</td>";
                      echo '</tr>';
                  }
                }

                echo '</table>';
                ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>

    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <div id="successEditToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto">Edit Successful</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                Congratulations, your Edit was successful!
            </div>
        </div>

  <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
      <div id="errorEditToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
          <div class="toast-header">
              <strong class="me-auto">Edit Successful</strong>
              <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
          </div>
          <div class="toast-body">
              Contact Number or Email Address already exist for another user.
          </div>
      </div>
  </div>
</div>
</body>

<!-- Add this modal at the end of your HTML body -->
<div class="modal fade" id="updateBloodTypeModal" tabindex="-1" role="dialog" aria-labelledby="updateBloodTypeModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateBloodTypeModalLabel">Update Blood Type</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Form to update blood type -->
                <form id="updateBloodTypeForm" action="update_blood_type.php" method="post">
                    <div class="mb-3">
                        <label for="bloodType" class="form-label">Blood Type:</label>
                        <input type="text" name="bloodType" class="form-control" value="<?php echo $userBloodType; ?>">
                    </div>
                    <input type="hidden" name="user_ICNumber" value="<?php echo $user_ICNumber; ?>">
                    <!-- Move the submit button inside the form -->
                    <button type="submit" class="btn btn-primary">Update Blood Type</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>



  <div class="modal fade" id="EditProfile" tabindex="-1">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title">User Information</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <form id="UserEditForm" action="profile_edition.php" method="post">

                  <div class="mb-3">
                      <label for="user_ic" class="form-label" hidden>User IC:</label>
                      <input type="text" name="user_ic" id="user_ic" class="form-control" value="<?php echo $user_ICNumber; ?>" hidden>
                  </div>

                  <div class="mb-3">
                      <label for="user_name" class="form-label">User Name:</label>
                      <input type="text" name="user_name" id="user_name" class="form-control"  value="<?php echo $user_name; ?>">
                  </div>

                  <div class="mb-3">
                      <label for="user_contact" class="form-label">User Contact:</label>
                      <input type="text" name="user_contact" id="user_contact" class="form-control" value="<?php echo $user_contact; ?>">
                  </div>

                  <div class="mb-3">
                      <label for="user_email" class="form-label">User Email:</label>
                      <input type="text" name="user_email" id="user_email" class="form-control" value="<?php echo $user_email; ?>">
                  </div>

                  <div class="mb-3">
                      <label for="user_address" class="form-label">User Address:</label>
                      <textarea type="text" name="user_address" id="user_address" class="form-control"><?php echo $user_address; ?></textarea>
                  </div>

                </form>
              </div>
              <div class="modal-footer">
                  <button type="submit" class="btn btn-success" form="UserEditForm" id="submitButton">Submit</button>
              </div>
          </div>
      </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>

  function showEditSuccessToast() {
      var toastEl = document.getElementById('successEditToast');
      var toast = new bootstrap.Toast(toastEl);
      toast.show();
  }

  function showEditErrorToast() {
      var toastEl = document.getElementById('errorEditToast');
      var toast = new bootstrap.Toast(toastEl);
      toast.show();
  }

  <?php
  if (isset($_SESSION['edit_profile_success']) && $_SESSION['edit_profile_success']) {
      $_SESSION['edit_profile_success'] = false; // Reset the variable
      echo "document.addEventListener('DOMContentLoaded', showEditSuccessToast);";
  }
  ?>

  <?php
  if (isset($_SESSION['edit_profile_error']) && $_SESSION['edit_profile_error']) {
      $_SESSION['edit_profile_error'] = false;// Reset the variable
      echo "document.addEventListener('DOMContentLoaded', showEditErrorToast);";
  }
  ?>

  </script>
</html>
