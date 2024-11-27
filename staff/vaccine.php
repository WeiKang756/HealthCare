<?php
session_start();

include("../shared/config.php");

echo $_SESSION['facility_ID'];
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Staff</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Unbounded:wght@900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:opsz@6..12&family=Unbounded:wght@900&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="css/style.css">
  </head>
  <style media="screen">

.icon{
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
      <div class="col text-center">
        <button class="btn btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#InsertVaccine">New Vaccine</button>
      </div>
    </div>

    <div class="row">
      <div class="col">
        <table class="table">
          <h2>Vaccine Records</h2>
            <thead>
                <tr>
                    <th>Vaccine ID</th>
                    <th>Vaccine Name</th>
                    <th>Total Doses</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $select_query = "SELECT * FROM Vaccine";
                $result = $conn->query($select_query);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["vaccine_ID"] . "</td>";
                        echo "<td>" . $row["vaccine_name"] . "</td>";
                        echo "<td>" . $row["total_doses"] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>No results</td></tr>";
                }
                ?>
            </tbody>
        </table>
      </div>
     </div>

     <div class="row mt-5">
       <div class="col-12">

         <div id="ICNumForm">
             <div class="mb-3">
                 <label for="ICNum">Enter User IC Number:</label>
                 <input type="text" name="ICNum" id="ICNum" value="" class="form-control">
                  <button type="button" class="btn btn-primary btn-button" onclick="searchRecord()">Next</button>
             </div>
         </div>
       </div>
       <div class="col-lg-7">
         <div class="row">
             <!-- Display search results in a table -->
             <table class="table resultsTable" id="resultsTable">
               <thead>
                 <tr>
                   <th>Vaccine ID</th>
                   <th>User Name</th>
                   <th>Dose Number</th>
                   <th>Vaccine Name</th>
                   <th>Vaccine Date</th>
                 </tr>
               </thead>
               <tbody id="resultsBody">
                 <!-- Results will be dynamically added here -->
               </tbody>
             </table>
         </div>
     </div>
     <div class="col-lg-5">
       <h2 class="mb-4">User Vaccine Record Insertion Form</h2>

         <form id="vaccineForm"  method="post">
             <div class="mb-3">
                 <label for="user_ICNumber" class="form-label">User IC Number:</label>
                 <input type="text" class="form-control" name="user_ICNumber" id="user_ICNumber"required>
             </div>

             <div class="mb-3">
                 <label for="vaccine_ID" class="form-label">Vaccine:</label>
                 <select class="form-select" name="vaccine_ID" required>
                     <?php
                         // Fetch vaccine IDs and Names from the database and populate the dropdown
                         $vaccineQuery = "SELECT vaccine_ID, vaccine_Name FROM Vaccine";
                         $vaccineResult = $conn->query($vaccineQuery);

                         if ($vaccineResult->num_rows > 0) {
                             while ($vaccineRow = $vaccineResult->fetch_assoc()) {
                                 echo "<option value='{$vaccineRow['vaccine_ID']}'>{$vaccineRow['vaccine_Name']}</option>";
                             }
                         } else {
                             echo "<option value='' disabled>No vaccines available</option>";
                         }
                     ?>
                 </select>
             </div>

             <div class="mb-3">
                 <label for="dose_number" class="form-label">Dose Number:</label>
                 <input type="number" class="form-control" name="dose_number" required>
             </div>

             <div class="mb-3">
                 <label for="vaccination_date" class="form-label">Vaccination Date:</label>
                 <input type="date" class="form-control" name="vaccination_date" required>
             </div>

             <button type="submit" class="btn btn-primary" id="submitBtn">Submit</button>
         </form>

     </div>
  </div>
</main>

<div class="modal fade" id="InsertVaccine" tabindex="-1">.
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Insert New Facility</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form id="InsertVaccineForm" action="vaccine_insertion.php" method="post">

                <div class="mb-3">
                    <label for="vaccine_name" class="form-label">Vaccine Name:</label>
                    <input type="text" name="vaccine_name" class="form-control">
                </div>

                <div class="mb-3">
                    <label for="total_dose" class="form-label">Total Dose:</label>
                    <input type="text" name="total_dose" class="form-control">
                </div>

              </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" form="InsertVaccineForm">Insert Vaccine</button>
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
            url: 'insert_vaccine.php', // Replace with your server-side script
            method: 'POST',
            data: $('#vaccineForm').serialize(),
            success: function (response) {
                // Handle the success response if needed
                console.log(response);

                // Clear the form after submission attempt
                clearResultsTable();
                $('#vaccineForm')[0].reset();
            },
            error: function () {
                alert('Error submitting the form.');
            }
        });
    });
});

function searchRecord() {
  var user_ICNumber = $('#ICNum').val();

  $.ajax({
    url: 'search_vaccine.php',
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
      var userVaccineID = order.user_vaccine_ID || 'N/A';
      var userName = order.user_name || 'N/A';
      var doseNumber = order.dose_number || 'N/A';
      var vaccinationDate = order.vaccination_date || 'N/A';
      var vaccinationName = order.vaccine_name || 'N/A';


      var row = '<tr><td>' + userVaccineID + '</td><td>' + userName + '</td><td>' + doseNumber + '</td><td>' + vaccinationName + '</td><td>' + vaccinationDate + '</td>';
      resultsBody.append(row);
    });
  } else {
    resultsBody.append('<tr><td colspan="4">No results found.</td></tr>');
  }
}

function clearResultsTable() {
$('#resultsBody').empty();
}

</script>
</html>
