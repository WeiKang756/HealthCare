<?php
session_start();

include("../shared/config.php");
include("../shared/function.php");

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

$totaldonate = getTotalBloodByUserId($conn, $user_ICNumber)

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
    <link rel="stylesheet" href="UserCSS/style.css">
  </head>
  <body>
    <?php
      $activePage = 'blood';
        include("header.php");
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
    <div class="row d-flex justify-content-center mt-2">
      <div class="col-8">
        <div class="card">
          <div class="card-body">
            <div class="row">
              <div class="col-lg-6">
                <h3>Blood Card</h3>
                <h5>IC Number: <?php echo $user_ICNumber ?></h5>
                <h5>Blood Type: <?php echo $bloodType ?></h5>
              </div>
              <div class="col-lg-6 d-flex justify-content-center align-items-center">
                <h4>Total Donate: <?php echo $totaldonate; ?></h4>
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
