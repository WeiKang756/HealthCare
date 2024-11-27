<?php
session_start();

include("../shared/config.php");
include("../shared/function.php");

// Check if the user is logged in
if (!isset($_SESSION['position'])) {
    // Redirect to the login page or show an error message
    header("Location: login.php");
    exit();
}

// Check the user's position
$userPosition = $_SESSION['position'];

// Check if the user has the required position to access this page
if ($userPosition != 'Doctor') {
    // Redirect to a different page or show an error message
    header("Location: unauthorized.php");
    exit();
}


 ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <title>Blood Order</title>
  <!-- Include necessary stylesheets and scripts -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <link href="https://fonts.googleapis.com/css2?family=Unbounded:wght@900&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:opsz@6..12&family=Unbounded:wght@900&display=swap" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <link rel="stylesheet" href="css/style.css">
  <!-- Your inline styles here -->
  <style media="screen">
    .icon {
      height: 80px;
      width: 80px;
      margin-left: 160px;
      margin-top: 56px;
    }

    .search {
      position: relative;
    }

    .search input {
      height: 60px;
      text-indent: 25px;
      border: 2px solid #d6d4d4;
    }

    .search input:focus {
      box-shadow: none;
      border: 2px solid blue;
    }

    .search .fa-search {
      position: absolute;
      top: 20px;
      left: 16px;
    }

    .search button {
      position: absolute;
      top: 5px;
      right: 15px;
      height: 50px;
      width: 110px;
      background: blue;
    }

    /* Additional styles for the table */
    #resultsTable {
      margin-top: 20px;
    }

    #resultsTable th, #resultsTable td {
      text-align: center;
      padding: 8px;
    }

    .orderForm{
      display: none;
    }

    .resultsTable{
      display: none;
    }

  </style>
</head>
<body>
  <?php
    include("nav.php");
  ?>
  <main>
    <div class="container">
      <div class="row mt-4">
        <div class="search">
          <i class="fa fa-search"></i>
          <input type="text" class="form-control" id="searchInput" placeholder="Enter Patient IC Number">
          <button class="btn btn-primary" onclick="searchOrders()">Enter</button>
        </div>
      </div>
      <div class="row">
          <!-- Display search results in a table -->
          <table class="table resultsTable" id="resultsTable">
            <thead>
              <tr>
                <th>Diagnostic</th>
                <th>Facility Name</th>
              </tr>
            </thead>
            <tbody id="resultsBody">
              <!-- Results will be dynamically added here -->
            </tbody>
          </table>
      </div>
      <div class="row">
          <form id="orderForm" method="post" class="orderForm" >
            <h2>Diagnostic Form</h2>
            <div class="mb-3">
              <label for="user_ICNumber" class="form-label">User IC Number:</label>
              <input type="text" class="form-control" id="user_ICNumber" name="user_ICNumber" required>
            </div>

            <div class="mb-3">
              <label for="diagnostic" class="form-label">Diagnostic:</label>
              <textarea class="form-control" id="diagnostic" name="diagnostic" rows="4" required></textarea>
            </div>

            <div class="mb-3">
              <label for="prescription" class="form-label">Prescription:</label>
              <textarea class="form-control" id="prescription" name="prescription" rows="4" required></textarea>
            </div>
            <div class="mb-3">
              <label for="instructions" class="form-label">Instructions:</label>
              <textarea class="form-control" id="instructions" name="instructions" rows="4" required></textarea>
            </div>
            <button type="button" class="btn btn-primary" id="submitBtn">Submit</button>
          </form>
        </div>
      </div>
  </main>
  <!-- Offcanvas to display detailed information -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="orderDetailsOffcanvas" aria-labelledby="orderDetailsOffcanvasLabel">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="orderDetailsOffcanvasLabel">Order Details</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body" id="orderDetailsBody">
    <!-- Content will be dynamically added here -->
  </div>
</div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>



    $(document).ready(function () {
      // Handle form submission using jQuery
      $('#submitBtn').on('click', function () {
        $.ajax({
          url: 'insert_order.php', // Replace with your server-side script
          method: 'POST',
          data: $('#orderForm').serialize(),
          success: function (response) {
            // Handle the success response if needed
            console.log(response);

            // Clear the search results table
            clearResultsTable();
            $('#orderForm, #resultsTable').hide();
          },
          error: function () {
            alert('Error submitting the form.');
          }
        });

        // Clear the form after submission attempt
        $('#orderForm')[0].reset();
      });
    });

    function searchOrders() {
      var user_ICNumber = $('#searchInput').val();

      $.ajax({
        url: 'search_orders.php',
        method: 'GET',
        data: { user_ICNumber: user_ICNumber },
        dataType: 'json',
        success: function (data) {
          // Pre-fill the form with the User IC Number from the search result
          if (data.length > 0) {
            $('#user_ICNumber').val(data[0].user_ICNumber);
          }
          $('#orderForm, #resultsTable').show();

          // Display the search results
          displayResults(data);
        },
        error: function (error) {
          console.log('Error:', error.responseText);
        }
      });
    }

    function displayResults(orders) {
      var resultsBody = $('#resultsBody');
      resultsBody.empty();

      if (orders.length > 0) {
        $.each(orders, function (index, order) {
          var row = '<tr><td>' + order.diagnostic + '</td><td>' + order.facility_Name + '</td><td>' + order.staff_id + '</td>';
          row += '<td><button class="btn btn-info" onclick="showOrderDetails(' + order.order_id + ')">Details</button></td></tr>';
          resultsBody.append(row);
        });
      } else {
        resultsBody.append('<tr><td colspan="3">No results found.</td></tr>');
      }
    }

    function showOrderDetails(orderId) {
      // Fetch detailed information for the specified order (prescription, instructions)
      $.ajax({
        url: 'get_order_details.php', // Replace with your server-side script to get order details
        method: 'GET',
        data: { order_id: orderId },
        dataType: 'json',
        success: function (data) {
          // Populate the Offcanvas with order details
          var orderDetailsBody = $('#orderDetailsBody');
          orderDetailsBody.empty();

          if (data) {
            orderDetailsBody.append('<p><strong>Prescription:</strong></p><p>' + data.prescription + '</p>');
            orderDetailsBody.append('<p><strong>Instructions:</strong></p><p> ' + data.instructions + '</p>');
          } else {
            orderDetailsBody.append('<p>No details available.</p>');
          }

          // Show the Offcanvas
          new bootstrap.Offcanvas(document.getElementById('orderDetailsOffcanvas')).show();
        },
        error: function (error) {
          console.log('Error:', error.responseText);
        }
      });
    }

    function clearResultsTable() {
  $('#resultsBody').empty();
}

  </script>
</body>
</html>
