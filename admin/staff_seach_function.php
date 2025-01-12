<?php
session_start();

include"../shared/config.php";

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

// Check if the "search" parameter is set in the URL
if (isset($_GET['search'])) {
    $search = $_GET['search'];

    // SQL query to search for staff data
    $search_sql = "SELECT * FROM staff WHERE staff_ID LIKE '%$search%' OR staff_name LIKE '%$search%'";
    $search_result = $conn->query($search_sql);
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <title>Admin Dashboard</title>
    <!-- Add your CSS and other head content here -->
</head>
<body>

<?php include"nav.php"; ?>

<main>
    <div class="container mt-3">
        <div class="row">
            <div class="col-lg">
                <form method="get">
                    <div class="input-group mb-3">
                        <input type="text" name="search" class="form-control" placeholder="Search by Staff ID or Staff Name" value="<?= $search ?>">
                        <button type="submit" class="btn btn-outline-secondary">Search</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Display search results -->
    <?php if ($search_result->num_rows > 0): ?>
        <div class="container mt-3">
            <div class="row">
                <div class="col-lg">
                    <h2>Search Results</h2>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Staff ID</th>
                                <th>Staff Name</th>
                                <th>Staff Position</th>
                                <th>Staff Contact</th>
                                <th>Facility ID</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $search_result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $row['staff_ID'] ?></td>
                                    <td><?= $row['staff_name'] ?></td>
                                    <td><?= $row['staff_position'] ?></td>
                                    <td><?= $row['staff_contact'] ?></td>
                                    <td><?= $row['facility_ID'] ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="container mt-3">
            <div class="row">
                <div class="col-lg">
                    <h2>No staff found.</h2>
                </div>
            </div>
        </div>
    <?php endif; ?>
</main>

</body>
</html>

<?php
    // End of HTML
} else {
    // The "search" parameter is not set, continue displaying the page as before
    $staff_sql = "SELECT * FROM staff";
    $staff_result = $conn->query($staff_sql);
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <title>Admin Dashboard</title>
    <!-- Add your CSS and other head content here -->
</head>
<body>

<?php include"nav.php"; ?>

<main>
    <div class="container">
        <div class="row mt-3 text-center">
            <div class="col-lg">
                <button class="btn btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#InsertStaff">Insert New Staff</button>
            </div>
        </div>
    </div>

    <div class="container mt-3">
        <div class="row">
            <div class="col-lg">
                <form method="get">
                    <div class="input-group mb-3">
                        <input type="text" name="search" class="form-control" placeholder="Search by Staff ID or Staff Name">
                        <button type="submit" class="btn btn-outline-secondary">Search</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Continue displaying staff data as before -->
    <div class="container mt-3">
        <div class="row">
            <div class="col-lg">
                <h2>Staff Data</h2>
                <?php if ($staff_result->num_rows > 0): ?>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Staff ID</th>
                                <th>Staff Name</th>
                                <th>Staff Position</th>
                                <th>Staff Contact</th>
                                <th>Facility ID</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $staff_result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $row['staff_ID'] ?></td>
                                    <td><?= $row['staff_name'] ?></td>
                                    <td><?= $row['staff_position'] ?></td>
                                    <td><?= $row['staff_contact'] ?></td>
                                    <td><?= $row['facility_ID'] ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No staff found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

</body>
</html>

<?php
    // End of HTML
}
?>
