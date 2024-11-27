<?php
session_start();

include("../shared/config.php");
include("../shared/function.php");

$staff_ID = $_SESSION['staff_ID'];
$facility_ID = $_SESSION['facility_ID'];

$totalPending = getTotalMedicalOrdersByStatusAndFacility('Pending', $facility_ID);
$pendingOrders = getMedicalOrdersByStatusAndFacility('Pending', $facility_ID);
$totalInProgress = getTotalMedicalOrdersByStatusAndFacility('In Progress', $facility_ID);
$InProgressOrders = getMedicalOrdersByStatusAndFacility('In Progress', $facility_ID);


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
</head>
<style media="screen">

</style>
<body>
  <?php
    include("nav.php");
  ?>

  <main>
    <div class="container">
      <div class="row mt-3">
        <div class="col-lg-6">
          <div class="card h-100">
            <div class="card-body">
              <div class="row">
                <div class="col-6">
                  <h5>Pending Order</h5>
                  <h4><?php echo $totalPending; ?></h4>
                </div>
                <div class="col-6">
                  <img src="../Icon/pending_order.png" alt="User" class="icon">
                </div>
              </div>
              <hr>
              <div class="row">
                <div class="col-12">
                  <div class="card">
                    <div class="card-body">
                      <?php
                        // Display each pending order
                        foreach ($pendingOrders as $order) {
                          echo "<p>Order ID: {$order['order_id']}</p>";
                          echo "<p>User IC Number: {$order['user_ICNumber']}</p>";

                          // Button to open the modal
                          echo "<button type='button' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#orderModal{$order['order_id']}'>View Details</button>";
                          echo '<button class="btn btn-warning ms-1" onclick="InProgress(\'' . $order['order_id'] . '\')">In Progress</button>';
                          echo "<hr>";
                        }
                      ?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="card h-100">
            <div class="card-body">
              <div class="row">
                <div class="col-6">
                  <h5>Pending Order</h5>
                  <h4><?php echo $totalInProgress; ?></h4>
                </div>
                <div class="col-6">
                  <img src="../Icon/complete.png" alt="User" class="icon">
                </div>
              </div>
              <hr>
              <div class="row">
                <div class="col-12">
                  <div class="card">
                    <div class="card-body">
                      <?php
                        // Display each pending order
                        foreach ($InProgressOrders as $order) {
                          echo "<p>Order ID: {$order['order_id']}</p>";
                          echo "<p>User IC Number: {$order['user_ICNumber']}</p>";

                          // Button to open the modal
                          echo "<button type='button' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#orderModal{$order['order_id']}'>View Details</button>";
                          echo '<button class="btn btn-success ms-1" onclick="complete(\'' . $order['order_id'] . '\')">Completed</button>';
                          echo "<hr>";
                        }
                      ?>
                    </div>
                  </div>
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
    foreach ($pendingOrders as $order) {
      echo "<div class='modal fade' id='orderModal{$order['order_id']}' tabindex='-1'>";
      echo "<div class='modal-dialog'>";
      echo "<div class='modal-content'>";
      echo "<div class='modal-header'>";
      echo "<h5 class='modal-title'>Order Details</h5>";
      echo "<button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>";
      echo "</div>";
      echo "<div class='modal-body'>";
      echo "<p>Prescription: {$order['prescription']}</p>";
      echo "<p>Instructions: {$order['instructions']}</p>";
      echo "</div>";
      echo "</div>";
      echo "</div>";
      echo "</div>";
    }

    // Dynamically generate modals for each order
    foreach ($InProgressOrders as $order) {
      echo "<div class='modal fade' id='orderModal{$order['order_id']}' tabindex='-1'>";
      echo "<div class='modal-dialog'>";
      echo "<div class='modal-content'>";
      echo "<div class='modal-header'>";
      echo "<h5 class='modal-title'>Order Details</h5>";
      echo "<button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>";
      echo "</div>";
      echo "<div class='modal-body'>";
      echo "<p>Prescription: {$order['prescription']}</p>";
      echo "<p>Instructions: {$order['instructions']}</p>";
      echo "</div>";
      echo "</div>";
      echo "</div>";
      echo "</div>";
    }
  ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Your scripts here

    function InProgress(orderId) {
        // Make an AJAX request to update the order status
        $.ajax({
            type: "POST",
            url: "update_medicalOrder_status.php", // Replace with the actual PHP script to update the status
            data: { orderID: orderId }, // Corrected the variable name to match the function parameter
            success: function(response) {
                location.reload();
            },
            error: function() {
                alert("Error confirming the order.");
            }
        });
    }

    function complete(orderId) {
        // Make an AJAX request to update the order status
        $.ajax({
            type: "POST",
            url: "complete_medicalOrder_status.php", // Replace with the actual PHP script to update the status
            data: { orderID: orderId }, // Corrected the variable name to match the function parameter
            success: function(response) {
                location.reload();
            },
            error: function() {
                alert("Error confirming the order.");
            }
        });
    }
  </script>
</body>
</html>
