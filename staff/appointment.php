<?php
session_start();

include("../shared/config.php");
include("../shared/function.php"); // Include Function

$staff_ID = $_SESSION['staff_ID'];
$facility_ID = $_SESSION['facility_ID'];

$appointments = getAppointmentsForTodayByFacility($facility_ID);
$totalAppointments = getTotalAppointmentsForTodayByFacility($facility_ID);
$confirmedAppointment = getTotalAppointmentsForTodayByStatusAndFacility($facility_ID, 'Confirmed');
$InProgressAppointment = getTotalAppointmentsForTodayByStatusAndFacility($facility_ID, 'In Progress');
$CompletedAppointment = getTotalAppointmentsForTodayByStatusAndFacility($facility_ID, 'Completed');

$confrimedAppointmentDetail = getAppointmentsForTodayByStatusAndFacility($facility_ID, 'Confirmed');
$InProgressAppointmentDetail = getAppointmentsForTodayByStatusAndFacility($facility_ID, 'In Progress');
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
    }

    /* Set a fixed height for the card */
    .fixed-height-card {
        height: 300px;
        /* Adjust the height as needed */
        overflow-y: auto;
    }

    .fixed-height-card-1 {
        height: 600px;
        /* Adjust the height as needed */
        overflow-y: auto;
    }
</style>

<body>
    <?php
    include("nav.php");
    ?>
    <main>
        <div class="container">
            <div class="row mt-4">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-7">
                                    <h4>Today Appointment</h4>
                                    <h3><?php echo $totalAppointments; ?></h3>
                                </div>
                                <div class="col-5 text-end">
                                    <img src="../Icon/today_appointment.png" alt="User" class="icon">
                                </div>
                                <div class="row mt-3">
                                    <div class="col-lg-4">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <h4>Pending Appointment</h4>
                                                        <h3><?php echo $confirmedAppointment; ?></h3>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <h4>Appointment In Progress</h4>
                                                        <h3><?php echo $InProgressAppointment; ?></h3>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <h4>Completed Appointment</h4>
                                                        <h3><?php echo $CompletedAppointment; ?></h3>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-4">
                                    <div class="card fixed-height-card">
                                        <div class="card-body">
                                            <h6 class="card-title">Timeline</h6>
                                            <div id="content">
                                                <ul class="timeline">
                                                    <?php if (empty($appointments)) : ?>
                                                        <p>No appointments available.</p>
                                                    <?php else : ?>
                                                        <ul class="timeline">
                                                            <?php foreach ($appointments as $appointment) : ?>
                                                                <li class="timeline-item">
                                                                    <strong><?php echo $appointment['TimeSlot']; ?></strong>
                                                                    <span class="float-end text-muted text-sm"><?php echo $appointment['user_name']; ?></span>
                                                                    <p><?php echo $appointment['Service']; ?></p>
                                                                </li>
                                                            <?php endforeach; ?>
                                                        </ul>
                                                    <?php endif; ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="card fixed-height-card">
                                        <div class="card-body">
                                            <h3 class="card-title">Appointment Details</h3>
                                            <?php if (empty($appointments)) : ?>
                                                <p>No appointments available.</p>
                                            <?php else : ?>
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th>ID</th>
                                                            <th>IC Number</th>
                                                            <th>Name</th>
                                                            <th>Date</th>
                                                            <th>Time</th>
                                                            <th>Service</th>
                                                            <th>Status</th>
                                                            <!-- Add more columns as needed -->
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        foreach ($appointments as $appointment) {
                                                            echo "<tr>";
                                                            echo "<td>{$appointment['AppointmentID']}</td>";
                                                            echo "<td>{$appointment['user_ICNumber']}</td>";
                                                            echo "<td>{$appointment['user_name']}</td>";
                                                            echo "<td>{$appointment['AppointmentDate']}</td>";
                                                            echo "<td>{$appointment['TimeSlot']}</td>";
                                                            echo "<td>{$appointment['Service']}</td>";
                                                            echo "<td>{$appointment['Status']}</td>";
                                                            // Add more columns as needed
                                                            echo "</tr>";
                                                        }
                                                        ?>
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
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h3>Update Appointment Status</h3>
                                <div class="row mt-3">
                                    <div class="col-lg-6">
                                        <div class="card fixed-height-card-1">
                                            <div class="card-header sticky-top bg-white">
                                                <h3 class="card-title m-0 p-2">Pending Appointment</h3>
                                            </div>
                                            <div class="card-body">
                                                <hr>
                                                <?php
                                                // Display each pending order
                                                foreach ($confrimedAppointmentDetail as $appointment) {
                                                    echo "<p>Appointment ID: {$appointment['AppointmentID']}</p>";
                                                    echo "<p>IC Number: {$appointment['user_ICNumber']}</p>";
                                                    echo "<p>Name: {$appointment['user_name']}</p>";
                                                    echo "<p>Service: {$appointment['Service']}</p>";
                                                    echo "<p>TimeSlot: {$appointment['TimeSlot']}</p>";

                                                    echo '<button class="btn btn-success ms-1" onclick="InProgress(\'' . $appointment['AppointmentID'] . '\')">Completed</button>';
                                                    echo "<hr>";
                                                }
                                                ?>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="card fixed-height-card-1">
                                            <div class="card-header sticky-top bg-white">
                                                <h3 class="card-title m-0 p-2">In Progress Appointment</h3>
                                            </div>
                                            <div class="card-body">
                                                <hr>
                                                <?php
                                                // Display each pending order
                                                foreach ($InProgressAppointmentDetail as $appointment) {
                                                    echo "<p>Appointment ID: {$appointment['AppointmentID']}</p>";
                                                    echo "<p>IC Number: {$appointment['user_ICNumber']}</p>";
                                                    echo "<p>Name: {$appointment['user_name']}</p>";
                                                    echo "<p>Service: {$appointment['Service']}</p>";
                                                    echo "<p>TimeSlot: {$appointment['TimeSlot']}</p>";

                                                    echo '<button class="btn btn-success ms-1" onclick="Completed(\'' . $appointment['AppointmentID'] . '\')">Completed</button>';
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
        </div>
    </main>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function InProgress(orderID) {
        // Make an AJAX request to update the order status
        $.ajax({
            type: "POST",
            url: "InProgress_appointment.php", // Replace with the actual PHP script to update the status
            data: { orderID: orderID },
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

    function clearResultsTable() {
        $('#resultsBody').empty();
    }

    function Completed(orderID) {
        // Make an AJAX request to update the order status
        $.ajax({
            type: "POST",
            url: "completed_appointment.php", // Replace with the actual PHP script to update the status
            data: { orderID: orderID },
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

    function clearResultsTable() {
        $('#resultsBody').empty();
    }
</script>

</html>
