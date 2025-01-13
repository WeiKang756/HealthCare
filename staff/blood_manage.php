<?php
session_start();
namespace Staff;

use Shared\Config;
use Shared\Navigation;
use Shared\Functions;

define('TR_CLOSE_TAG', '</tr>')

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

$_SESSION['staff_ID'];

// Function to get the total number of blood units for each status and blood type
function getTotalBloodCounts() {
    global $conn;

    $bloodCounts = array();

    // Query to get the total count for each blood type and status
    $sql = "SELECT BloodType, Status, COUNT(*) AS TotalCount FROM Blood GROUP BY BloodType, Status";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $bloodType = $row['BloodType'];
            $status = $row['Status'];
            $totalCount = $row['TotalCount'];

            // Create an associative array to store the counts
            if (!isset($bloodCounts[$bloodType])) {
                $bloodCounts[$bloodType] = array();
            }

            // Store the count for each status under the blood type
            $bloodCounts[$bloodType][$status] = $totalCount;
        }
    }

    return $bloodCounts;
}

// Function to get all blood records
function getAllBloodRecords() {
    global $conn;

    $bloodRecords = array();

    // Query to get all blood records
    $sql = "SELECT * FROM Blood";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $bloodRecords[] = $row;
        }
    }

    return $bloodRecords;
}

$typeOP = getTotalBloodCount('O+', 'Available');
$typeON = getTotalBloodCount('O-', 'Available');
$typeAP = getTotalBloodCount('A+', 'Available');
$typeAN = getTotalBloodCount('A-', 'Available');
$typeBP = getTotalBloodCount('B+', 'Available');
$typeBN = getTotalBloodCount('B-', 'Available');
$typeABP = getTotalBloodCount('AB+', 'Available');
$typeABN = getTotalBloodCount('AB-', 'Available');

$pendingBlood = getBloodByStatus($conn, 'Pending');

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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="css/style.css">
  </head>
  <body>
    <?php
        Navigation::render();
     ?>
<style>
  .icon{
    height: 100px;
    width: 100px;
  }

</style>
<main>
  <div class="container">
    <div class="row mt-2">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <h4>Total Available Blood</h4>
            <div class="row mt-3">

              <div class="col-lg-3">
                <div class="card">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-6 m-auto text-center">
                        <H4><?php echo $typeOP; ?></H4>
                      </div>
                      <div class="col-6">
                        <img src="../Icon/O+.png" alt="User" class="icon">
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-lg-3">
                <div class="card">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-6 m-auto text-center">
                        <H4><?php echo $typeAP; ?></H4>
                      </div>
                      <div class="col-6">
                        <img src="../Icon/A+.png" alt="User" class="icon">
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-lg-3">
                <div class="card">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-6 m-auto text-center">
                        <H4><?php echo $typeBP; ?></H4>
                      </div>
                      <div class="col-6">
                        <img src="../Icon/B+.png" alt="User" class="icon">
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-lg-3">
                <div class="card">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-6 m-auto text-center">
                        <H4><?php echo $typeABP; ?></H4>
                      </div>
                      <div class="col-6">
                        <img src="../Icon/AB+.png" alt="User" class="icon">
                      </div>
                    </div>
                  </div>
                </div>
              </div>

            </div>
            <div class="row mt-4">

              <div class="col-lg-3">
                <div class="card">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-6 m-auto text-center">
                        <H4><?php echo $typeON; ?></H4>
                      </div>
                      <div class="col-6">
                        <img src="../Icon/O-.png" alt="User" class="icon">
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-lg-3">
                <div class="card">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-6 m-auto text-center">
                        <H4><?php echo $typeAN; ?></H4>
                      </div>
                      <div class="col-6">
                        <img src="../Icon/A-.png" alt="User" class="icon">
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-lg-3">
                <div class="card">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-6 m-auto text-center">
                        <H4><?php echo $typeBN; ?></H4>
                      </div>
                      <div class="col-6">
                        <img src="../Icon/B-.png" alt="User" class="icon">
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-lg-3">
                <div class="card">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-6 m-auto text-center">
                        <H4><?php echo $typeABN; ?></H4>
                      </div>
                      <div class="col-6">
                        <img src="../Icon/AB-.png" alt="User" class="icon">
                      </div>
                    </div>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>


    <div class="row mt-4">
      <div class="col-lg-6 d-flex">
        <div class="card flex-fill">
          <div class="card-body">
            <h4>Insert Blood</h4>
            <form id="bloodForm" method="post" action="blood_insertion.php">
               <div class="form-group">
                 <label for="user_ICNumber">User IC Number:</label>
                 <input type="text" class="form-control" id="user_ICNumber" name="user_ICNumber" required>
               </div>

               <div class="form-group">
                 <label for="BloodType">Blood Type:</label>
                 <select class="form-control" id="BloodType" name="BloodType" required>
                   <?php
                   $sql = "SELECT DISTINCT BloodType FROM BloodTypeInventory";
                   $result = $conn->query($sql);

                   if ($result->num_rows > 0) {
                     while ($row = $result->fetch_assoc()) {
                       $bloodType = $row["BloodType"];
                       echo "<option value='$bloodType'>$bloodType</option>";
                     }
                   }
                   ?>
                 </select>
               </div>

               <div class="form-group">
                 <label for="status">Status:</label>
                 <select class="form-control" id="status" name="status" required>
                   <option value="Available">Available</option>
                   <option value="Allocated">Allocated</option>
                   <option value="Pending">Pending</option>
                   <option value="Infected">Expired</option>
                 </select>
               </div>

               <button type="button" class="btn btn-primary mt-2" id="submitBtn">Submit</button>
             </form>

        </div>
      </div>
    </div>
      <div class="col-lg-6">
        <div class="card">
          <div class="card-body">
            <p class"card-title">Blood Type Status</p>
            <?php // Example usage:
                $totalBloodCounts = getTotalBloodCounts();

                // Display the counts as a table
                echo '<table class="table table-hover">';
                echo '<tr><th>Blood Type</th><th>Status</th><th>Count</th></tr>';

                foreach ($totalBloodCounts as $bloodType => $statusCounts) {
                    foreach ($statusCounts as $status => $count) {
                        echo '<tr>';
                        echo "<td>$bloodType</td>";
                        echo "<td>$status</td>";
                        echo "<td>$count</td>";
                        echo TR_CLOSE_TAG;
                    }
                }

                echo '</table>';
                ?>
          </div>
        </div>
      </div>
    </div>
    <div class="row mt-3">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <h4>Pending Blood</h4>
                <?php
                // Display the counts as a table
                echo '<table class="table table-hover table-responsive text-center">';
                echo '<tr><th>ID</th>
                      <th>IC Number</th> <!-- Fixed the column name -->
                      <th>Blood Type</th> <!-- Adjusted the column names -->
                      <th>Date</th>
                      <th>Update Status</th>
                      </tr>';

                if (empty($pendingBlood)) {
                    echo '<tr>';
                    echo "<td colspan='5'>No Pending Blood</td>";
                    echo TR_CLOSE_TAG;
                } else {
                    foreach ($pendingBlood as $pending) {
                        echo '<tr>';
                        echo "<td>{$pending['blood_ID']}</td>"; // Corrected the variable name
                        echo "<td>{$pending['user_ICNumber']}</td>";
                        echo "<td>{$pending['BloodType']}</td>";
                        echo "<td>{$pending['CreatedAt']}</td>";
                        echo '<td><button class="btn btn-success ms-1" onclick="available(\'' . $pending['blood_ID'] . '\')">Available</button>
                        <button class="btn btn-danger ms-1" onclick="reject(\'' . $pending['blood_ID'] . '\')">Reject</button>
                        </td>';
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

</main>

<div class="modal fade" id="ViewBlood" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Blood List</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <?php
              // Display the blood records as a table
              echo '<table class="table">';
              echo '<tr><th>Blood ID</th><th>User IC Number</th><th>Blood Type</th><th>Created At</th><th>Status</th><th>Facility ID</th><th>Order ID</th></tr>';

              foreach ($allBloodRecords as $record) {
                  echo '<tr>';
                  echo "<td>{$record['blood_ID']}</td>";
                  echo "<td>{$record['user_ICNumber']}</td>";
                  echo "<td>{$record['BloodType']}</td>";
                  echo "<td>{$record['CreatedAt']}</td>";
                  echo "<td>{$record['Status']}</td>";
                  echo "<td>{$record['facility_id']}</td>";
                  echo "<td>{$record['order_id']}</td>";
                  echo '</tr>';
              }

              echo '</table>';
              ?>
        </div>
    </div>
</div>
</div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>

$(document).ready(function () {
    // Handle form submission using jQuery
    $('#submitBtn').on('click', function () {
      $.ajax({
        url: 'blood_insertion.php', // Replace with your server-side script
        method: 'POST',
        data: $('#bloodForm').serialize(),
        success: function (response) {
          // Handle the success response if needed
          location.reload();
          console.log(response);
          // Clear the form after successful submission
          $('#bloodForm')[0].reset();
        },
        error: function () {
          alert('Error submitting the form.');
        }
      });
    });
});

function available(orderId) {
    // Make an AJAX request to update the order status
    $.ajax({
        type: "POST",
        url: "available_status.php", // Replace with the actual PHP script to update the status
        data: { orderID: orderId }, // Corrected the variable name to match the function parameter
        success: function(response) {
            location.reload();
        },
        error: function() {
            alert("Error confirming the order.");
        }
    });
}

function reject(orderId) {
    // Make an AJAX request to update the order status
    $.ajax({
        type: "POST",
        url: "reject_status.php", // Replace with the actual PHP script to update the status
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
</html>
