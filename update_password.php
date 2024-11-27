<?php
include("shared/config.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Process the form submission to update the password
    $token = $_POST["token"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    // Update the password in the database
    $sql = "UPDATE users SET user_password = '$password', reset_token = NULL WHERE reset_token = '$token'";
    $conn->query($sql);

    $_SESSION['update_pass_success'] = true;
    header("Location: index.php");

    // Close the database connection
    $conn->close();
    exit();
}

// If it's not a POST request, display the form
$token = $_GET["token"];

// Check if the token exists in the database
$sql = "SELECT * FROM users WHERE reset_token = '$token'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Display the form to set a new password
    ?>
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
</head>
<style>
    .logo {
        width: 300px;
        height: 300px;
        margin-top: 0px;
        margin-left: auto;
        margin-right: auto;
    }

    h2{
        text-align: center;
    }

    body {
        background-color: #FAFCFB;
        text-align: center;
    }
</style>
<body class="d-flex flex-column align-items-center">
<div class=" align-items-center">
    <img src="Images/logo.png" alt="logo" class="img-fluid logo">
</div>
        <h2>Update Password</h2><br>
        <table>
        <form action="update_password.php" method="post">
        <div class="row g-3 align-items-center">
            <div class="col-auto">
                <label for="password">New Password:</label>
            </div>
            <div class="col-auto">
                <input type="password" class="form-control" name="password" required>
                <input type="hidden" name="token" value="<?php echo $token; ?>">
            </div>
        </div>
        <br>
            <button type="submit" class="btn btn-success">Update Password</button>

        </form>
    </table>
    </body>
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    </html>
    <?php
} else {
    session_start();
    $_SESSION['pass_invalid_token'] = true;
    header("Location: index.php");
}

// Close the database connection
$conn->close();
?>
