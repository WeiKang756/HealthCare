<?php
session_start();
namespace Staff;

use Shared\Config;
use Shared\Navigation;
use Shared\Functions;

$staff_ID = $_SESSION['staff_ID'];
$facility_ID = $_SESSION['facility_ID'];

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
$order = getOrdersByFacility($facility_ID);

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

              <div class="col-lg-3 col-sm-6">
                <div class="card">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-6 m-auto text-center">
                        <H4><?php echo $typeOP; ?></H4>
                      </div>
                      <div class="col-6">
                        <img src="../Icon/O+.png" alt="User" class="icon img-fluid">
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-lg-3 col-sm-6">
                <div class="card">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-6 m-auto text-center">
                        <H4><?php echo $typeAP; ?></H4>
                      </div>
                      <div class="col-6">
                        <img src="../Icon/A+.png" alt="User" class="icon img-fluid">
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-lg-3 col-sm-6">
                <div class="card">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-6 m-auto text-center">
                        <H4><?php echo $typeBP; ?></H4>
                      </div>
                      <div class="col-6">
                        <img src="../Icon/B+.png" alt="User" class="icon img-fluid">
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-lg-3 col-sm-6">
                <div class="card">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-6 m-auto text-center">
                        <H4><?php echo $typeABP; ?></H4>
                      </div>
                      <div class="col-6">
                        <img src="../Icon/AB+.png" alt="User" class="icon img-fluid">
                      </div>
                    </div>
                  </div>
                </div>
              </div>

            </div>
            <div class="row mt-4">

              <div class="col-lg-3 col-sm-6">
                <div class="card">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-6 m-auto text-center">
                        <H4><?php echo $typeON; ?></H4>
                      </div>
                      <div class="col-6">
                        <img src="../Icon/O-.png" alt="User" class="icon img-fluid">
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-lg-3 col-sm-6">
                <div class="card">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-6 m-auto text-center">
                        <H4><?php echo $typeAN; ?></H4>
                      </div>
                      <div class="col-6">
                        <img src="../Icon/A-.png" alt="User" class="icon img-fluid">
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-lg-3 col-sm-6">
                <div class="card">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-6 m-auto text-center">
                        <H4><?php echo $typeBN; ?></H4>
                      </div>
                      <div class="col-6 col-sm-6">
                        <img src="../Icon/B-.png" alt="User" class="icon img-fluid">
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-lg-3 col-sm-6">
                <div class="card">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-6 m-auto text-center">
                        <H4><?php echo $typeABN; ?></H4>
                      </div>
                      <div class="col-6">
                        <img src="../Icon/AB-.png" alt="User" class="icon img-fluid">
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
            <h4>Blood Order</h4>
            <form method="post" action="blood_order_action.php">

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
                <label for="quantity">Quantity:</label>
                <input class="form-control" type="number" name="quantity" id="quantity" required><br><br>
              </div>

              <button type="submit" class="btn btn-primary">Submit</button>
          </form>

        </div>
      </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <p class="card-title">Blood History</p>
                <?php
                // Display the counts as a table
                echo '<table class="table table-hover table-responsive">';
                echo '<tr><th>Order ID</th>
                      <th>Blood Type</th>
                      <th>Quantity</th>
                      <th>Date</th>
                      <th>Status</th></tr>';

                foreach ($order as $orderItem) {
                    echo '<tr>';
                    echo "<td>{$orderItem['order_ID']}</td>";
                    echo "<td>{$orderItem['blood_type']}</td>";
                    echo "<td>{$orderItem['quantity']}</td>";
                    echo "<td>{$orderItem['order_date']}</td>";
                    echo "<td>{$orderItem['status']}</td>";
                    echo '</tr>';
                }

                echo '</table>';
                ?>
            </div>
        </div>
      </div>
    </div>>
  </div>
</main>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>


</script>
</html>
