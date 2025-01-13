<?php
session_start();
use Shared\Config;
use Shared\Functions;

define('TR_CLOSE_TAG', '</tr>');
$user_ICNumber = $_SESSION['user_id'];


$appointments = getAppointmentsWithFacility($user_ICNumber);
$pastAppointment =  getPastAppointmentsWithFacility($user_ICNumber);
$MedicalOrders = getMedicalOrdersByICNumber($conn, $user_ICNumber)

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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="UserCSS/style.css">
    <title>My Study KPI</title>
  </head>
  <style>

  .card-custom-1{
    overflow: auto;
    height: 400px;
  }

  </style>
  <body>
  <?php
  $activePage = 'record';
  include "header.php"; ?>
    <main>
      <div class="container">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-body">
                <h4>Appointment</h4>
                <div class="row">
                  <div class="col-12">
                    <div class="card card-custom-1">
                      <div class="card-header sticky-top bg-white">
                        <h5>Upcoming Appointment</h5>
                      </div>
                      <div class="card-body">
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
                          echo '<tr>';
                          echo "<td>No Upcoming Appointment</td>";
                          echo TR_CLOSE_TAG;
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
                              echo '<td><button class="btn btn-danger btn-sm" style="margin-left: 8px;" data-bs-toggle="modal" data-bs-target="#Cancel"
                                    data-cancel-button="' . $appointment['AppointmentID'] . '">Delete</button></td>';
                              echo TR_CLOSE_TAG;
                          }
                        }
                        echo '</table>';
                        ?>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row mt-4">
                  <div class="col-12">
                    <div class="card card-custom-1">
                      <div class="card-header sticky-top bg-white">
                        <h5>Appointment History</h5>
                      </div>
                      <div class="card-body">
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
                          echo '<tr>';
                          echo "<td>No Appointment</td>";
                          echo TR_CLOSE_TAG;
                        }
                        else {
                          foreach ($pastAppointment as $appointment) {
                              echo '<tr>';
                              echo "<td>{$appointment['AppointmentID']}</td>";
                              echo "<td>{$appointment['facility_Name']}</td>";
                              echo "<td>{$appointment['AppointmentDate']}</td>";
                              echo "<td>{$appointment['TimeSlot']}</td>";
                              echo "<td>{$appointment['Service']}</td>";
                              echo "<td>{$appointment['Status']}</td>";
                              echo TR_CLOSE_TAG;

                          }
                        }
                        echo '</table>';
                        ?>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header sticky-top bg-white">
                            <h5>Medical History</h5>
                        </div>
                        <div class="card-body">
                            <?php
                            // Display the counts as a table
                            echo '<table class="table table-hover table-responsive text-center">';
                            echo '<tr><th>ID</th>
                                  <th>Diagnostic</th> <!-- Fixed the column name -->
                                  <th>Order Date</th> <!-- Adjusted the column names -->
                                  <th>Facility Name</th>
                                  </tr>';

                            if (empty($MedicalOrders)) {
                                echo '<tr>';
                                echo "<td>No Medical History</td>";
                                echo TR_CLOSE_TAG;
                            } else {
                                foreach ($MedicalOrders as $medicalItem) {
                                    echo '<tr>';
                                    echo "<td>{$medicalItem['order_id']}</td>"; // Corrected the variable name
                                    echo "<td>{$medicalItem['diagnostic']}</td>";
                                    echo "<td>{$medicalItem['order_date']}</td>";
                                    echo "<td>{$medicalItem['facility_Name']}</td>";
                                    echo "<td><button type='button' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#MedicalModal{$medicalItem['order_id']}'>View Details</button><td>";
                                    echo TR_CLOSE_TAG;
                                }
                            }
                            echo '</table>';
                            ?>
                        </div>
                    </div>
                </div>
            </div>

          </div>

        </div>
      </div>
    </main>

    <?php

    // Dynamically generate modals for each order
    foreach ($MedicalOrders as $medicalItem) {
      echo "<div class='modal fade' id='MedicalModal{$medicalItem['order_id']}' tabindex='-1'>";
      echo "<div class='modal-dialog'>";
      echo "<div class='modal-content'>";
      echo "<div class='modal-header'>";
      echo "<h5 class='modal-title'>Order Details</h5>";
      echo "<button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>";
      echo "</div>";
      echo "<div class='modal-body'>";
      echo "<p>Prescription: {$medicalItem['prescription']}</p>";
      echo "<p>Instructions: {$medicalItem['instructions']}</p>";
      echo "</div>";
      echo "</div>";
      echo "</div>";
      echo "</div>";
    }

     ?>
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

<!-- Add this modal dialog at the end of your HTML body -->
<div class="modal fade" id="Cancel" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cancel Appointment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to cancel this appointment?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger" id="confirmCancel">Cancel Appointment</button>
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

  // Store the appointment ID when the delete button is clicked
  $(document).on('click', 'button[data-bs-target="#Cancel"]', function () {
      var appointmentID = $(this).data('cancel-button');
      $('#confirmCancel').data('cancel-button', appointmentID);
  });

  // Handle the confirmation and trigger the cancellation
  $('#confirmCancel').on('click', function () {
      var appointmentID = $(this).data('cancel-button');

      // Make an AJAX request to cancel the appointment
      $.ajax({
          type: 'POST',
          url: 'cancel_appointment.php', // Replace with the actual URL of your cancel appointment script
          data: { appointmentID: appointmentID },
          success: function (response) {
                console.log(response);
              // Handle the response (e.g., show a success message)
              // You may also refresh the table to reflect the updated status
              location.reload();
          },
          error: function () {
              alert('Error canceling the appointment.');
          }
      });

      // Close the modal dialog after the confirmation
      $('#Cancel').modal('hide');
  });
  </script>
</html>
