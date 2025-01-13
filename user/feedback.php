<?php
session_start();
include "../shared/config.php";

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
  <body>
  <?php include "header.php"; ?>
    <main>

      <table id="timeTable">
        <!-- Table will be populated dynamically using JavaScript -->
    </table>


    </main>
  </body>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>

  // Assuming you have arrays for booked and all time slots
  var bookedTimeSlots = ["09:00", "11:00"];
  var allTimeSlots = generateTimeSlots("9:00", "17:00", 30);

  // Get a reference to the HTML table
  var table = document.getElementById("timeTable");

  // Loop through all time slots and create table rows
  for (var i = 0; i < allTimeSlots.length; i++) {
      var row = table.insertRow(i);
      var timeSlotCell = row.insertCell(0);
      var statusCell = row.insertCell(1);

      timeSlotCell.innerHTML = allTimeSlots[i];

      if (bookedTimeSlots.includes(allTimeSlots[i])) {
          statusCell.innerHTML = "Unavailable";
      } else {
          statusCell.innerHTML = "Available";
      }
  }

  // Function to generate time slots
  function generateTimeSlots(startTime, endTime, duration) {
      var allSlots = [];
      var currentTime = new Date("2024-01-24 " + startTime);

      while (currentTime < new Date("2024-01-24 " + endTime)) {
          allSlots.push(currentTime.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }));
          currentTime.setMinutes(currentTime.getMinutes() + duration);
      }

      return allSlots;
  }

  </script>
</html>
