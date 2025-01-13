<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <title>Sabah Healthcare</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Unbounded:wght@900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:opsz@6..12&family=Unbounded:wght@900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="UserCSS/style.css">
</head>
<style>
    .card:hover {
        background-color: #f8f9fb;
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
    }

    .card {
        height: 350px;
        width: 350px;
        margin-bottom: 20px;
    }

    .card:hover .card-body {
        opacity: 1;
    }

    .card .card-body {
        opacity: 0;
        transition: opacity 1s ease;
    }

    .card:hover .card-img {
        opacity: 0.3;
    }

    .card .card-img {
        opacity: 1;
        transition: opacity 0.5s ease;
    }

    .card:hover .card-body {
        opacity: 1;
    }

    nav {
        background-color: white;
        margin-top: -30px;
    }

</style>
<body>
<?php
  $activePage = 'facility';
include "header.php"; ?>
<main>
    <div class="container">
        <div class="row m-4">
            <?php
            // Assuming you have a database connection established
            include "../shared/config.php";

            // Fetch facility information from the database
            $query = "SELECT * FROM facilities";
            $result = mysqli_query($conn, $query);

            // check if there are rows in the result
            if (mysqli_num_rows($result) > 0) {
                // Iterate through the rows to display cards
                while ($row = mysqli_fetch_assoc($result)) {
                    $facilityName = $row['facility_Name'];
                    $facilityID = $row['facility_ID'];
                    $facilityLogo = $row['facility_logo'];

                    // Display card for each facility
                    echo '<div class="col-md-6 col-lg-4">';
                    echo '<div class="card">';
                    echo '<img src="' . $facilityLogo . '" class="card-img" alt="Facility Logo">';
                    echo '<div class="card-img-overlay card-body">';
                    echo '<h5 class="card-title">' . $facilityName . '</h5>';
                    echo '<p class="card-text">';
                    echo 'This is a wider card with supporting text below as a natural lead-in to additional content.';
                    echo 'This content is a little bit longer.';
                    echo '</p>';
                    echo '<p class="card-text">';
                    echo '<button class="btn btn-primary FacilityInfo" type="button" data-facility-id="' . $facilityID. '">';
                    echo 'Details';
                    echo '</button>';
                    echo '</p>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                // No facilities found in the database
                echo '<div class="alert alert-info mt-3">No facilities available.</div>';
            }

            // Close the database connection
            mysqli_close($conn);
            ?>
        </div>
    </div>
</main>
</body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Use .FacilityInfo as a class selector
    var FacilityInfo = document.querySelectorAll('.FacilityInfo');

    FacilityInfo.forEach(function(button) {
        button.addEventListener('click', function() {
            var facilityID = this.getAttribute('data-facility-id'); // Corrected attribute name
            window.location.href = 'facility_detail.php?facilityID=' + facilityID;
        });
    });
</script>


</script>
</html>
