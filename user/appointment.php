<?php
session_start();
namespace User;

use Shared\Config;
use Shared\Navigation;
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
    <title>Appointment</title>
  </head>
  <style media="screen">
    .card{
      display: none;
      overflow: auto;
      height: 600px;
    }
  </style>
  <body>
  <?php
  $activePage = 'appointment';
  Navigation::render(); ?>
    <main>

      <div class="container mt-5">
        <div class="row">
          <div class="col-6">
            <h1 class="mb-4">Appointment Booking</h1>
            <form id="appointmentForm "class="" action="appointmentInsertion.php" method="post">

            <!-- Service Selection Form -->
            <div id="serviceForm">
                <div class="mb-3">
                    <label for="serviceSelect" class="form-label">Choose a service:</label>
                    <select  class="form-select" id="serviceSelect" name="service">
                        <option value="Outpatient Treatment">Outpatient Treatment</option>
                        <option value="National Health Screening Initiative">National Health Screening Initiative</option>
                        <option value="Peka B40 Health Screening">Peka B40 Health Screening</option>
                        <option value="Quit Smoking Service">Quit Smoking Service</option>
                        <option value="Pre-Employment/Education Mediacal Check-Up">Pre-Employment/Education Mediacal Check-Up</option>
                    </select>
                </div>
                <button id="serviceButton" type="button" class="btn btn-primary" onclick="loadFacilities()">Next</button>
            </div>

            <!-- Facility Selection Form -->
            <div id="facilityForm" class="mt-4" style="display: none;">
                <div class="mb-3">
                    <label for="facilitySelect" class="form-label">Choose a facility:</label>
                    <select class="form-select" id="facilitySelect" name="facility">
                        <!-- Options will be populated dynamically -->
                    </select>
                </div>
                <button type="button" class="btn btn-primary" onclick="loadDates()">Next</button>
            </div>

            <!-- Date Selection Form -->
            <div id="dateForm" class="mt-4" style="display: none;">
                <div class="mb-3">
                    <label for="appointmentDate" class="form-label">Choose a date:</label>
                    <input type="date" class="form-control" id="appointmentDate" name="appointmentDate">
                </div>
                <button type="button" class="btn btn-primary" onclick="loadTimeSlots()">Next</button>
            </div>

            <!-- Time Slot Selection Form -->
            <div id="timeSlotForm" class="mt-4" style="display: none;">
                <div class="mb-3">
                    <label for="timeSlotSelect" class="form-label">Choose a time slot:</label>
                    <select class="form-select" id="timeSlotSelect" name="timeSlot">
                        <!-- Options will be populated dynamically -->
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Book Appointment</button>
            </div>
          </form>
          </div>
          <div class="col-6">
          <div class="card">
            <div class="card-body p-0">
              <h5 class="card-title sticky-top bg-primary text-white py-3 text-center">  Time Slot Availability</h5>
                <table id="timeTable" class="table table-hover text-center">
                  <!-- Table will be populated dynamically using JavaScript -->
              </table>
              <table id="operationDay" class="table table-hover text-center">
                <!-- Table will be populated dynamically using JavaScript -->
            </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
  </body>

  <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
      <div id="errorToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
          <div class="toast-header">
              <strong class="me-auto">Error</strong>
              <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
          </div>
          <div class="toast-body">
              The selected time slot is not available. Please choose another one.
          </div>
      </div>

    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <div id="AppointmentSuccessfulToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto">Appointment Successful</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
              Appointment booked successfully.
            </div>
        </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>

  function showErrorToast() {
      var toastEl = document.getElementById('errorToast');
      var toast = new bootstrap.Toast(toastEl);
      toast.show();
  }

  function showAppointmentSuccessfulToast() {
      var toastEl = document.getElementById('AppointmentSuccessfulToast');
      var toast = new bootstrap.Toast(toastEl);
      toast.show();
  }


  <?php
  if (isset($_SESSION['error']) && $_SESSION['error']) {
      $_SESSION['error'] = false; // Reset the variable
      echo "document.addEventListener('DOMContentLoaded', showErrorToast);";
  }
  ?>

  <?php
  if (isset($_SESSION['success']) && $_SESSION['success']) {
      $_SESSION['success'] = false; // Reset the variable
      echo "document.addEventListener('DOMContentLoaded', showAppointmentSuccessfulToast);";
  }
  ?>

  // Function to load facilities based on selected service
  function loadFacilities() {
      // Make an AJAX request to fetch facilities based on the selected service
      // Replace 'ajax.php' with the actual URL of your server-side script
      var button1 = document.getElementById('serviceButton');
      button1.style.display = 'none';

      fetch(`ajax.php?action=loadFacilities`)
          .then(response => response.json())
          .then(data => {
              const facilitySelect = document.getElementById('facilitySelect');
              facilitySelect.innerHTML = ""; // Clear existing options
              data.forEach(facility => {
                  const option = document.createElement('option');
                  option.value = facility.facility_ID;
                  option.textContent = facility.facility_Name;
                  facilitySelect.appendChild(option);
              });
              const facilityForm = document.getElementById('facilityForm');
              facilityForm.style.display = 'block';
          });
  }

      // Function to load available dates
      function loadDates() {
          const facilitySelect = document.getElementById('facilitySelect').value;
          fetch(`ajax.php?action=loadDates&facility=${facilitySelect}`)
              .then(response => {
                  if (!response.ok) {
                      throw new Error(`HTTP error! Status: ${response.status}`);
                  }
                  return response.json();
              })
              .then(data => {
                  const operationDay = document.getElementById("operationDay");
                  operationDay.innerHTML = ""; // Clear existing table content

                  // Create table header
                  const headerRow = operationDay.insertRow(0);
                  const dayHeader = headerRow.insertCell(0);
                  const statusHeader = headerRow.insertCell(1);
                  dayHeader.innerHTML = "Day";
                  statusHeader.innerHTML = "Status";

                  data.forEach(operation => {
                      const row = operationDay.insertRow(operationDay.rows.length);
                      const dayCell = row.insertCell(0);
                      const statusCell = row.insertCell(1);

                      dayCell.innerHTML = operation.DayOfWeek;
                      statusCell.innerHTML = operation.Status;
                  })
              });

          const card = document.querySelector('.card');
          card.style.display = "block";

          const dateForm = document.getElementById('dateForm');
          dateForm.style.display = 'block';
      }

      function loadTimeSlots() {
          const appointmentDate = document.getElementById('appointmentDate').value;
          const facilitySelect = document.getElementById('facilitySelect').value;

          // Make an AJAX request to fetch available and booked time slots for the selected date and facility
          // Replace 'ajax.php' with the actual URL of your server-side script
          fetch(`ajax.php?action=loadTimeSlots&date=${appointmentDate}&facility=${facilitySelect}`)
              .then(response => {
                  if (!response.ok) {
                      throw new Error(`HTTP error! Status: ${response.status}`);
                  }
                  return response.json();
              })
              .then(data => {
                  const timeTable = document.getElementById("timeTable");
                  timeTable.innerHTML = ""; // Clear existing table content

                  // Check if the selected date is Sunday
                  const selectedDate = new Date(appointmentDate);
                  if (selectedDate.getDay() === 0) {
                      // Display a message indicating that the facility is closed on Sundays
                      timeTable.innerHTML = "<tr><td colspan='2'>Facility is closed on Sundays</td></tr>";
                      return;
                  }

                  // Create table header
                  const headerRow = timeTable.insertRow(0);
                  const timeSlotHeader = headerRow.insertCell(0);
                  const statusHeader = headerRow.insertCell(1);
                  timeSlotHeader.innerHTML = "Time Slot";
                  statusHeader.innerHTML = "Status";

                  // Check if data is an object with the expected properties
                  if (data.hasOwnProperty('availableTimeSlots') && data.hasOwnProperty('bookedTimeSlots')) {
                      // Loop through all time slots and create table rows
                      data.availableTimeSlots.forEach(slot => {
                          const row = timeTable.insertRow(timeTable.rows.length);
                          const timeSlotCell = row.insertCell(0);
                          const statusCell = row.insertCell(1);

                          timeSlotCell.innerHTML = slot;

                          if (data.bookedTimeSlots.includes(slot)) {
                              statusCell.innerHTML = "Unavailable";
                              statusCell.style.color = "red";
                          } else {
                              statusCell.innerHTML = "Available";
                              statusCell.style.color = "green";
                          }
                      });

                      const timeSlotSelect = document.getElementById('timeSlotSelect');
                      timeSlotSelect.innerHTML = ""; // Clear existing options

                      // Populate dropdown with available time slots
                      data.availableTimeSlots.forEach(slot => {
                          const option = document.createElement('option');
                          option.value = slot;
                          option.textContent = slot;
                          timeSlotSelect.appendChild(option);
                      });

                      const timeSlotForm = document.getElementById('timeSlotForm');
                      timeSlotForm.style.display = 'block';

                      const operationDay = document.getElementById('operationDay');
                      operationDay.style.display = 'none';

                  } else {
                      console.error('Invalid data format:', data);
                  }
              })
              .catch(error => {
                  console.error('Fetch error:', error);
              });
      }


  </script>
</html>
