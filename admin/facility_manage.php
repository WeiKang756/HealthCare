<?php
session_start();

include("../shared/config.php");

$username = $_SESSION['admin'];
$user_role = $_SESSION['role'];

if ($user_role !== 'admin') {
    echo "You are not authorized to access this page.  Please contact the administrator for assistance.";
    exit;
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
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="css/style.css">
  </head>
    <?php
        include("nav.php");
     ?>
<main>
  <div class="container">
    <div class="row mt-3 text-center">
      <div class="col-lg">
        <button class="btn btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#InsertFacility">Insert New Facility</button>
      </div>
    </div>
    <div class="row">
        <h1>Hospital List</h1>
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th>Facility ID</th>
                    <th>Facility Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch all hospitals from the facilities table
                $sql = "SELECT facility_id, facility_name, facility_address, facility_contact FROM facilities";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                      echo '<tr>';
                      echo '<td>' . $row['facility_id'] . '</td>';
                      echo '<td>' . $row['facility_name'] . '</td>';
                      // Add the Delete button
                      echo '<td><button class="btn btn-danger" onclick="deleteFacility(\'' . $row['facility_id'] . '\')">Delete</button>';
                      echo '<button class="btn btn-info" onclick="viewFacilityDetails(\'' . $row['facility_id'] . '\')">View Details</button></td>';
                      echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="4">No hospitals found.</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
  </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


    </div>
  </div>


</main>
</body>
<div class="modal fade" id="InsertFacility" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Insert New Facility</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form id="InsertFacilityForm" action="facility_insertion.php" method="post">

                <div class="mb-3">
                    <label for="facility_id" class="form-label">Facility ID:</label>
                    <input type="text" name="facility_id" class="form-control">
                    <p class="text-muted">Note: Facility ID is not available to change once inserted.</p>
                </div>

                <div class="mb-3">
                    <label for="facility_name" class="form-label">Facility Name:</label>
                    <input type="text" name="facility_name" class="form-control">
                </div>

              </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" form="InsertFacilityForm">Insert Facility</button>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<div class="modal fade" id="deleteFacilityModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this facility?</p>
                <!-- Hidden input to store the facility ID for reference -->
                <input type="hidden" id="facilityIdToDelete">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="confirmDeleteFacility()">Delete</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Facility Details -->
<div class="modal fade" id="facilityDetailsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Facility Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Facility ID:</strong> <span id="facilityId"></span></p>
                <p><strong>Facility Name:</strong> <span id="facilityName"></span></p>
                <p><strong>Facility Address:</strong> <span id="facilityAddress"></span></p>
                <p><strong>Facility Contact:</strong> <span id="facilityContact"></span></p>
                <p><strong>Facility Description:</strong> <span id="facilityDescription"></span></p>
                <p><strong>Facility Logo:</strong> <img id="facilityLogo" alt="Facility Logo"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>

function deleteFacility(facilityId) {
    // Open the confirmation modal
    $('#deleteFacilityModal').modal('show');

    // Set the facility ID in the modal for reference when confirming the deletion
    $('#facilityIdToDelete').val(facilityId);
}

function confirmDeleteFacility() {
    // Get the facility ID from the modal
    var facilityId = $('#facilityIdToDelete').val();

    // Make an AJAX request to delete the facility
    $.ajax({
        type: 'POST',
        url: 'delete_facility.php', // Replace with the actual PHP script to delete the facility
        data: { facilityId: facilityId },
        success: function(response) {
            // Handle the success response if needed
            console.log(response);

            // Reload the page to reflect the updated facility list
            location.reload();
        },
        error: function() {
            alert('Error deleting the facility.');
        }
    });
}

function viewFacilityDetails(facilityId) {
    // Make an AJAX request to fetch facility details
    $.ajax({
        type: 'POST',
        url: 'fetch_facility_details.php', // Replace with the actual PHP script to fetch details
        data: { facilityId: facilityId },
        dataType: 'json',
        success: function(response) {
            // Populate the modal with fetched details
            $('#facilityDetailsModal #facilityId').text(response.facility_ID);
            $('#facilityDetailsModal #facilityName').text(response.facility_Name);
            $('#facilityDetailsModal #facilityAddress').text(response.facility_address);
            $('#facilityDetailsModal #facilityContact').text(response.facility_contact);
            $('#facilityDetailsModal #facilityDescription').text(response.facility_description);
            $('#facilityDetailsModal #facilityLogo').attr('src', response.facility_logo);

            // Open the modal
            $('#facilityDetailsModal').modal('show');
        },
        error: function() {
            alert('Error fetching facility details.');
        }
    });
}

</script>
</html>
