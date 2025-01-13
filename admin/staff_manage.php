<?php
session_start();

use Shared\Config;;

define('TD_CLOSE_TAG', '</td>');

$username = $_SESSION['admin'];
$user_role = $_SESSION['role'];

if ($user_role !== 'admin') {
    echo "You are not authorized to access this page.  Please contact the administrator for assistance.";
    exit;
}

$facilities = array();
$sql = "SELECT facility_ID, facility_Name FROM facilities";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $facilities[$row['facility_ID']] = $row['facility_Name'];
    }
}

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
    <link rel="stylesheet" href="css/style.css">
  </head>
<body>
    <?php
        include"nav.php";
     ?>
<main>
  <div class="container">
    <div class="row mt-3 text-center">
      <div class="col-lg">
        <button class="btn btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#InsertStaff">Insert New Staff</button>
      </div>
    </div>
  </div>

  <!-- Display Staff Data Section -->
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg">
        <h2>Staff Data</h2>
        <?php
        // SQL query to fetch staff data
        $staff_sql = "SELECT * FROM staff ORDER BY facility_ID";
        $staff_result = $conn->query($staff_sql);

        if ($staff_result->num_rows > 0) {
            echo '<table class="table table-bordered">';
            echo '<thead>';
            echo '<tr>';
            echo '<th>Staff ID</th>';
            echo '<th>Staff Name</th>';
            echo '<th>Staff Position</th>';
            echo '<th>Staff Contact</th>';
            echo '<th>Facility ID</th>';
            echo '<th>Action</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';

            while ($row = $staff_result->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . $row['staff_ID'] . TD_CLOSE_TAG;
                echo '<td>' . $row['staff_name'] . TD_CLOSE_TAG;
                echo '<td>' . $row['staff_position'] . TD_CLOSE_TAG;
                echo '<td>' . $row['staff_contact'] . TD_CLOSE_TAG;
                echo '<td>' . $row['facility_ID'] . TD_CLOSE_TAG;
                echo '<td>';
                echo '<button class="btn btn-primary btn-sm mr-5" data-bs-toggle="modal" data-bs-target="#EditStaff"
                        data-staff-id="' . $row['staff_ID'] . '"
                        data-staff-name="' . $row['staff_name'] . '"
                        data-staff-position="' . $row['staff_position'] . '"
                        data-staff-contact="' . $row['staff_contact'] . '"
                        data-facility-ID="' . $row['facility_ID'] . '">Edit</button>';
                echo '<button class="btn btn-danger btn-sm" style="margin-left: 8px;" data-bs-toggle="modal" data-bs-target="#DeleteStaff"
                        data-staff-id="' . $row['staff_ID'] . '" data-staff-name="' . $row['staff_name'] . '">Delete</button>';
                        echo TD_CLOSE_TAG;
                echo '</tr>';
            }

            echo '</tbody>';
            echo '</table>';
        } else {
            echo 'No staff found.';
        }
        ?>
      </div>
    </div>
  </div>

</main>
</body>
<div class="modal fade" id="InsertStaff" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Insert New Staff</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form method="post" action="staff_insertion.php" id="InsertStaffForm">

                <div class="mb-3">
                    <label for="staff_id" class="form-label">Staff ID:</label>
                    <input type="text" name="staff_id" class="form-control" required>
                    <p class="text-muted">Note: Facility ID is not available to change once inserted.</p>
                </div>

                  <div class="mb-3">
                      <label for="staff_name" class="form-label">Staff Name:</label>
                      <input type="text" name="staff_name" class="form-control" required>
                  </div>

                    <div class="mb-3">
                        <label for="staff_position" class="form-label">Staff Position:</label>
                        <select name="staff_position" class="form-select" required>
                            <option value="Doctor">Doctor</option>
                            <option value="Nurse">Nurse</option>
                            <option value="BloodBankAdmin">Blood Bank Admin</option>
                        </select>
                    </div>


                  <div class="mb-3">
                      <label for="staff_password" class="form-label">Password*:</label>
                      <input type="password" name="staff_password" class="form-control" required>
                  </div>

                  <div class="mb-3">
                      <label for="staff_contact" class="form-label">Staff Contact:</label>
                      <input type="text" name="staff_contact" class="form-control" required>
                  </div>

                  <div class="mb-3">
                      <label for="facility_id" class="form-label">Select Facility:</label>
                      <select name="facility_id" class="form-select" required>
                          <option value="" disabled selected>Select a Facility</option>
                          <?php
                          // Dynamically populate the dropdown options
                          foreach ($facilities as $id => $name) {
                              echo '<option value="' . $id . '">' . $name . '</option>';
                          }
                          ?>
                      </select>
                  </div>
              </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" form="InsertStaffForm">Insert Staff</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="EditStaff" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Form Staff</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form method="post" action="staff_edition.php" id="EditStaffForm">

                <div class="mb-3">
                    <label for="staff_id" class="form-label">Staff ID:</label>
                    <input type="text" name="staff_id" class="form-control" id="staff_id" required>
                    <p class="text-muted">Note: Facility ID is not available to change once inserted.</p>
                </div>

                  <div class="mb-3">
                      <label for="staff_name" class="form-label">Staff Name:</label>
                      <input type="text" name="staff_name" class="form-control" id="staff_name" required>
                  </div>

                  <div class="mb-3">
                    <label for="staff_position" class="form-label">Staff Position:</label>
                    <select name="staff_position" class="form-select" id="staff_position" required>
                        <option value="" selected disabled>Select Staff Position</option>
                        <option value="Doctor">Doctor</option>
                        <option value="Nurse">Nurse</option>
                        <option value="BloodBankAdmin">Blood Bank Admin</option>
                    </select>
                </div>


                  <div class="mb-3">
                      <label for="staff_contact" class="form-label">Staff Contact:</label>
                      <input type="text" name="staff_contact" class="form-control" id="staff_contact" required>
                  </div>

                  <div class="mb-3">
                      <label for="facility_id" class="form-label">Select Facility:</label>
                      <select name="facility_id" class="form-select" id="facility_id"  required>
                          <option value="" disabled selected>Select a Facility</option>
                          <?php
                          // Dynamically populate the dropdown options
                          foreach ($facilities as $id => $name) {
                              echo '<option value="' . $id . '">' . $name . '</option>';
                          }
                          ?>
                      </select>
                  </div>
              </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" form="EditStaffForm">Edit Staff</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="DeleteStaff" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Staff</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form method="post" action="staffDeletion.php" id="DeleteStaffForm">

                <div class="mb-3">
                    <label for="staff_id" class="form-label" hidden>Staff ID:</label>
                    <input type="text" name="staff_id" class="form-control" id="delete_staff_id" required hidden>
                </div>

                <div class="mb-3">
                    <label for="staff_id-2" class="form-label" hidden>Staff ID:</label>
                    <input type="text" name="staff_id-2" class="form-control" id="delete_staff_id-2" disabled>
                </div>

                <div class="mb-3">
                    <label for="staff_name" class="form-label" hidden>Staff ID:</label>
                    <input type="text" name="staff_name" class="form-control" id="delete_staff_name" disabled>
                </div>

                <div class="mb-3">
                  <p>Are you sure to delete this Staff?</p>
                </div>

              </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-danger" form="DeleteStaffForm">Delete Staff</button>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
var EditStaff = document.getElementById('EditStaff');
EditStaff.addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget;
    var staff_id = button.getAttribute('data-staff-id'); // Corrected attribute name
    var staff_name = button.getAttribute('data-staff-name'); // Corrected attribute name
    var staff_position = button.getAttribute('data-staff-position'); // Corrected attribute name
    var staff_contact = button.getAttribute('data-staff-contact'); // Corrected attribute name
    var facility_id = button.getAttribute('data-facility-ID'); // Corrected attribute name

    document.getElementById('staff_id').value = staff_id;
    document.getElementById('staff_name').value = staff_name;
    document.getElementById('staff_position').value = staff_position;
    document.getElementById('staff_contact').value = staff_contact;
    document.getElementById('facility_id').value = facility_id;
});

var DeleteStaff = document.getElementById('DeleteStaff');
DeleteStaff.addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget;
    var staff_id = button.getAttribute('data-staff-id');
    var staff_name = button.getAttribute('data-staff-name');

    document.getElementById('delete_staff_id').value = staff_id;
    document.getElementById('delete_staff_id-2').value = staff_id;
    document.getElementById('delete_staff_name').value = staff_name;
});

</script>
</html>
