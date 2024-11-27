<?php
session_start();

include("../shared/config.php");
include("../shared/function.php"); // Include Function

// Check if the user is logged in
if (!isset($_SESSION['position'])) {
    // Redirect to the login page or show an error message
    header("Location: login.php");
    exit();
}

// Check the user's position
$userPosition = $_SESSION['position'];

// Check if the user has the required position to access this page
if ($userPosition != 'BloodBankAdmin') {
    // Redirect to a different page or show an error message
    header("Location: unauthorized.php");
    exit();
}

$order_id = generateUniqueOrderID();

$order = getOrdersByStatus($conn, "Reserved");
$confirmed = getBloodOrdersByStatusWithFacility($conn, "Confirmed");
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <title>Blood Order</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Unbounded:wght@900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:opsz@6..12&family=Unbounded:wght@900&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="css/style.css">
</head>
<style media="screen">
    .icon {
        height: 80px;
        width: 80px;
        margin-left: 160px;
        margin-top: 56px;
    }
</style>

<body>
    <?php
    include("nav.php");
    ?>
    <main>
        <div class="container">
            <div class="row">
                <div class="col">
                    <h2 class="mb-4">Blood Orders Confirmation</h2>
                    <?php if (empty($order)) : ?>
                        <p>No records found.</p>
                    <?php else : ?>
                        <table class="table table-striped table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Order ID</th>
                                    <th>Facility ID</th>
                                    <th>Blood Type</th>
                                    <th>Quantity</th>
                                    <th>Order Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($order as $order) : ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($order['order_ID']); ?></td>
                                        <td><?php echo htmlspecialchars($order['facility_id']); ?></td>
                                        <td><?php echo htmlspecialchars($order['blood_type']); ?></td>
                                        <td><?php echo htmlspecialchars($order['quantity']); ?></td>
                                        <td><?php echo htmlspecialchars($order['order_date']); ?></td>
                                        <td><?php echo htmlspecialchars($order['status']); ?></td>
                                        <td><button class="btn btn-success m-1" onclick="confirmOrder(<?php echo htmlspecialchars($order['order_ID']); ?>)">Confirm</button>
                                            <button class="btn btn-danger m-1" onclick="rejectedOrder(<?php echo htmlspecialchars($order['order_ID']); ?>)">Rejected</button></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header sticky-top bg-white">
                                <h5>Comfirmed Order</h5>
                            </div>
                            <div class="card-body">
                                  <h2 class="mb-4">Blood Orders Confirmation</h2>
                                  <?php if (empty($order)) : ?>
                                      <p>No records found.</p>
                                  <?php else : ?>
                                      <table class="table table-striped table-bordered">
                                          <thead class="thead-dark">
                                              <tr>
                                                  <th>Order ID</th>
                                                  <th>Facility ID</th>
                                                  <th>Blood Type</th>
                                                  <th>Quantity</th>
                                                  <th>Order Date</th>
                                                  <th>Status</th>
                                              </tr>
                                          </thead>
                                          <tbody>
                                              <?php foreach ($confirmed as $order) : ?>
                                                  <tr>
                                                      <td><?php echo htmlspecialchars($order['order_ID']); ?></td>
                                                      <td><?php echo htmlspecialchars($order['facility_id']); ?></td>
                                                      <td><?php echo htmlspecialchars($order['blood_type']); ?></td>
                                                      <td><?php echo htmlspecialchars($order['quantity']); ?></td>
                                                      <td><?php echo htmlspecialchars($order['order_date']); ?></td>
                                                      <td><?php echo htmlspecialchars($order['status']); ?></td>
                                                  </tr>
                                              <?php endforeach; ?>
                                          </tbody>
                                      </table>
                                  <?php endif; ?>
                            </div>
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
<script>
    function confirmOrder(orderID) {
        // Make an AJAX request to update the order status
        $.ajax({
            type: "POST",
            url: "update_order_status.php", // Replace with the actual PHP script to update the status
            data: {
                orderID: orderID
            },
            success: function(response) {
                // Handle the response (e.g., show a success message)
                // You may also refresh the table to reflect the updated status
                location.reload();
            },
            error: function() {
                alert("Error confirming the order.");
            }
        });
    }

    function rejectedOrder(orderID) {
        // Make an AJAX request to update the order status
        $.ajax({
            type: "POST",
            url: "rejected_order_status.php", // Replace with the actual PHP script to update the status
            data: {
                orderID: orderID
            },
            success: function(response) {
                // Handle the response (e.g., show a success message)
                // You may also refresh the table to reflect the updated status
                location.reload();
            },
            error: function() {
                alert("Error confirming the order.");
            }
        });
    }
</script>

</html>
