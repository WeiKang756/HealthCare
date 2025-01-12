<?php
session_start();

include"../shared/config.php";
include"../shared/function.php";// Include Function

$facility_ID = $_SESSION['facility_ID']; // Replace with the actual facility ID

$query = "SELECT * FROM Facilities WHERE facility_ID = '$facility_ID'";
$result = mysqli_query($conn, $query);

// Fetch the data
while ($row = mysqli_fetch_assoc($result)) {
    $facility_Name = $row['facility_Name'];
    $facility_address = $row['facility_address'];
    $facility_contact = $row['facility_contact'];
    $facility_logo = $row['facility_logo'];
}

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <title>Facility Information</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Unbounded:wght@900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:opsz@6..12&family=Unbounded:wght@900&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="css/style.css">
</head>
<style>
    body {
        background: #F4F4F4;
    }

    .card {
        width: 100%;
        overflow: auto;
        box-sizing: border-box; /* Optional: includes padding and border in the width calculation */
    }
</style>
<body>
<?php
include"nav.php";
?>
<main>
    <div class="container">
        <div class="rows">
            <div class="col h-100">
                <div class="card mt-5 h-100">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <h4 class="card-title">Facility Information</h4>
                                <p class="card-text">Facility Name: <?php echo $facility_Name; ?></p>
                                <p class="card-text">Facility ID: <?php echo $facility_ID; ?></p>
                                <p class="card-text">Facility Contact: <?php echo $facility_contact; ?> </p>
                                <p class="card-text">Facility Address: <?php echo $facility_address; ?></p>
                            </div>
                            <div class="col-lg-6">
                                <p><img src="<?php echo $facility_logo ?>" alt="logo" style="width: 400px; height: 400px;"
                                        class="img-fluid"></p>
                            </div>
                        </div>
                        <div class="row">
                            <p>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#changeLogo">Change Logo
                                </button>
                                <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#EditFacility">Edit
                                </button>
                            </p>
                        </div>
                        <div class="row text-center mt-3">
                            <hr>
                            <div class="col-lg-6">
                                <h4>Operation Hour</h4>
                                <form id="editOperationForm" action="update_operation.php" method="post">
                                    <table class="table table-hover">
                                        <thead>
                                        <tr>
                                            <th>Day</th>
                                            <th>Opening Time</th>
                                            <th>Closing Time</th>
                                            <th>Status</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $query_operation = "SELECT * FROM FacilityOperation WHERE facility_ID = '$facility_ID' ORDER BY FIELD(DayOfWeek, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')";
                                        $result_operation = mysqli_query($conn, $query_operation);

                                        $rowIndex = 0; // Add this variable to track row index

                                        while ($row = mysqli_fetch_assoc($result_operation)) {
                                            echo '<tr>';
                                            echo '<td><input type="text" name="DayOfWeek[' . $rowIndex . ']" value="' . $row['DayOfWeek'] . '" ></td>';
                                            echo '<td><input type="time" name="OpeningTime[' . $rowIndex . ']" value="' . $row['OpeningTime'] . '"></td>';
                                            echo '<td><input type="time" name="ClosingTime[' . $rowIndex . ']" value="' . $row['ClosingTime'] . '"></td>';
                                            echo '<td><select name="Status[' . $rowIndex . ']">';
                                            echo '<option value="Open" ' . ($row['Status'] == 'Open' ? 'selected' : '') . '>Open</option>';
                                            echo '<option value="Close" ' . ($row['Status'] == 'Close' ? 'selected' : '') . '>Close</option>';
                                            echo '</select></td>';
                                            echo '</tr>';

                                            $rowIndex++;
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                    <p>
                                        <button type="button" class="btn btn-info" onclick="enableEdit(this)">Edit
                                        </button>
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                    </p>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<div class="modal fade" id="EditFacility" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Facility Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="EditFacilityForm" action="facility_edition.php" method="post">

                    <div class="mb-3">
                        <label for="facility_name" class="form-label">Facility Name:</label>
                        <input type="text" name="facility_name" class="form-control"
                               value="<?php echo $facility_Name; ?>">
                    </div>

                    <div class="mb-3">
                        <label for="facility_contact" class="form-label">Facility Contact:</label>
                        <input type="text" name="facility_contact" class="form-control"
                               value="<?php echo $facility_contact; ?>">
                    </div>

                    <div class="mb-3">
                        <label for="facility_address" class="form-label">Facility Address:</label>
                        <textarea class="form-control" name="facility_address"
                                  rows="8"><?php echo $facility_address; ?></textarea>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" form="EditFacilityForm">Save Changes</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="changeLogo" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Change Facility Logo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="changeLogoForm" action="logo_edition.php" method="post" enctype="multipart/form-data">

                    <label for="inputGroupFile04" class="form-label">Profile Picture</label>
                    <input type="file" class="form-control" id="inputGroupFile04" aria-describedby="inputGroupFileAddon04"
                           aria-label="Upload" name="profilePicture" required>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" form="changeLogoForm">Change Logo</button>
            </div>
        </div>
    </div>
</div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function enableEdit(button) {
        const row = button.closest('tr');
        row.querySelectorAll('input, select').forEach(input => input.disabled = !input.disabled);
        const saveButton = document.querySelector('#editOperationForm button[type="submit"]');
        saveButton.disabled = !saveButton.disabled;
    }
</script>
</html>
