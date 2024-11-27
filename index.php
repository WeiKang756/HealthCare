<?php
session_start();
include("shared/config.php");

error_reporting(E_ALL);
ini_set('display_errors', 1);

$user_ICNumber = $user_name = $user_contact = $user_email = $user_address = $user_password = $confirm_password = "";
$success_message = $error_message = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $user_ICNumber = $_POST["user_ICNumber"];
    $user_name = $_POST["user_name"];
    $user_contact = $_POST["user_contact"];
    $user_email = $_POST["user_email"];
    $user_address = $_POST["user_address"];
    $user_password = $_POST["user_password"];
    $confirm_password = $_POST["confirm_password"];

    // Check if IC Number already exists in the database
    $check_sql = "SELECT user_ICNumber FROM users WHERE user_ICNumber = ?";
    $check_stmt = mysqli_prepare($conn, $check_sql);
    mysqli_stmt_bind_param($check_stmt, "s", $user_ICNumber);
    mysqli_stmt_execute($check_stmt);
    mysqli_stmt_store_result($check_stmt);

    // Check if Contact Number already exists in the database
    $check_contact_sql = "SELECT user_contact FROM users WHERE user_contact = ?";
    $check_contact_stmt = mysqli_prepare($conn, $check_contact_sql);
    mysqli_stmt_bind_param($check_contact_stmt, "s", $user_contact);
    mysqli_stmt_execute($check_contact_stmt);
    mysqli_stmt_store_result($check_contact_stmt);

    // Check if Email Address already exists in the database
    $check_email_sql = "SELECT user_email FROM users WHERE user_email = ?";
    $check_email_stmt = mysqli_prepare($conn, $check_email_sql);
    mysqli_stmt_bind_param($check_email_stmt, "s", $user_email);
    mysqli_stmt_execute($check_email_stmt);
    mysqli_stmt_store_result($check_email_stmt);

    if (mysqli_stmt_num_rows($check_stmt) > 0 && mysqli_stmt_num_rows($check_contact_stmt) > 0 && mysqli_stmt_num_rows($check_email_stmt) > 0) {
        // IC Number, Contact Number, and Email Address all already exist
        $error_message = "IC Number, Contact Number, and Email Address all already exist in the database. Please use different values for all three.";
    } elseif (mysqli_stmt_num_rows($check_stmt) > 0) {
        // IC Number already exists
        $error_message = "IC Number already exists in the database. Please use a different IC Number.";
    } elseif (mysqli_stmt_num_rows($check_contact_stmt) > 0) {
        // Contact Number already exists
        $error_message = "Contact Number already exists in the database. Please use a different Contact Number.";
    } elseif (mysqli_stmt_num_rows($check_email_stmt) > 0) {
        // Email Address already exists
        $error_message = "Email Address already exists in the database. Please use a different Email Address.";
    } else {
        // IC Number, Contact Number, and Email Address are all unique, proceed with registration

        // Perform SQL insertion (replace with proper password hashing)
        $hashed_password = password_hash($user_password, PASSWORD_DEFAULT); // Hash the password

        $sql = "INSERT INTO users (user_ICNumber, user_name, user_contact, user_email, user_address, user_password) VALUES (?, ?, ?, ?, ?, ?)";

        // Use prepared statements to prevent SQL injection
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssssss", $user_ICNumber, $user_name, $user_contact, $user_email, $user_address, $hashed_password);

        // Execute the statement
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['registration_success'] = true; // Set the session variable on successful registration
        } else {
            $error_message = "Error: " . mysqli_error($conn);
        }

        // Close the statement
        mysqli_stmt_close($stmt);
    }

    mysqli_stmt_close($check_stmt);
    mysqli_stmt_close($check_contact_stmt);
    mysqli_stmt_close($check_email_stmt);

}


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
        width: 180px;
        height: 180px;
        margin-top: -20px;
    }

    .logo2 {
        width: 50px;
        height: 50px;
    }

    body {
        background-color: #FAFCFB;
    }
</style>
<body>
<header class="container-fluid">
    <div class="row d-flex align-items-center">
        <div class="col">
            <img src="Images/logo.png" alt="logo" class="img-fluid logo">
        </div>
        <div class="col d-flex justify-content-end mb-3">
            <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                <button type="button" class="btn btn-primary">Staff/Admin</button>
                <div class="btn-group" role="group">
                    <button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                        <a class="dropdown-item" href="admin/admin_login.php">System Admin</a>
                        <a class="dropdown-item" href="staff/staff_login.php">Hospital Staff</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<main>
<div class="container mt-5">
    <div class="row mt-4">
        <div class="col-lg-6">
            <h1>Welcome to Sabah Healthcare</h1>
            <h4> – your partner in wellness. </h4>
            <p>Your path to wellness starts with Sabah Healthcare.</p>
            <button type="button" name="button" class="btn btn-success" data-bs-toggle="modal"
                    data-bs-target="#UserLogin">Log In</button>
            <button type="button" name="button" class="btn btn-outline-success" data-bs-toggle="modal"
                    data-bs-target="#UserRegister">Register
            </button>
        </div>
        <div class="col-lg-6">
            <img src="Images/banner.png" alt="logo" class="img-fluid">
        </div>
    </div>
</div>

<div class="modal fade" id="UserRegister" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Register Form</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="InsertUserForm" method="post">

                    <div class="form-group">
                        <label for="user_ICNumber">IC Number:</label>
                        <input type="text" class="form-control" id="user_ICNumber" name="user_ICNumber"
                             required>
                    </div>

                    <div class="form-group">
                        <label for="user_name">Name:</label>
                        <input type="text" class="form-control" id="user_name" name="user_name"
                               required>
                    </div>

                    <div class="form-group">
                        <label for="user_contact">Contact Number:</label>
                        <input type="text" class="form-control" id="user_contact" name="user_contact"
                                required>
                    </div>

                    <div class="form-group">
                        <label for="user_email">Email Address:</label>
                        <input type="email" class="form-control" id="user_email" name="user_email"
                               required>
                    </div>

                    <div class="form-group">
                        <label for="user_address">Address:</label>
                        <textarea class="form-control" id="user_address" name="user_address" rows="4"
                                  maxlength="100" required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="user_password">Password:</label>
                        <input type="password" class="form-control" id="user_password" name="user_password"
                               maxlength="255" required>
                    </div>

                    <div class="form-group">
                        <label for="confirm_password">Confirm Password:</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password"
                               maxlength="255" required>
                    </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success">Register</button>
            </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="UserLogin" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Login</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="LoginUserForm" method="post" action="user_login.php">

                    <div class="form-group">
                        <label for="ic_num">IC Number:</label>
                        <input type="text" class="form-control" id="ic_num" name="ic_num"
                               maxlength="15" required>
                    </div>

                    <div class="form-group">
                        <label for="login_password">Password:</label>
                        <input type="password" class="form-control" id="login_password" name="login_password"
                               maxlength="255" required>
                    </div>

                    <div>
                         <a class="link-opacity-75-hover" data-bs-toggle="modal" data-bs-target="#ForgotPass" >Forgot password?</a>
                    </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success">Login</button>
            </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="ForgotPass" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Forgot Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Please enter your IC Number and Email to verify your identity.</p>
                <form id="ForgotPassForm" method="post" action="reset_password.php">

                    <div class="form-group">
                        <label for="ic_num">IC Number:</label>
                        <input type="text" class="form-control" id="ic_num" name="ic_num"
                               maxlength="15" required>
                    </div>

                    <div class="form-group">
                        <label for="login_password">Email:</label>
                        <input type="email" class="form-control" id="email" name="email"
                               maxlength="255" required>
                    </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success">Submit</button>
            </div>
            </form>
        </div>
    </div>
</div>

<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div id="successToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <strong class="me-auto">Registration Successful</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            Congratulations, your registration was successful!
        </div>
    </div>

    <?php if (!empty($error_message)): ?>
    <div id="icNumberExistToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <strong class="me-auto">Error</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            <?php echo $error_message; ?>
        </div>
    </div>
    <?php endif; ?>

    <div id="login_fail" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <strong class="me-auto">Password Incorrect</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            Login Fail. Password Incorrect.
        </div>
    </div>

    <div id="update_success" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <strong class="me-auto">Successful</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            Password Reset Successfully !
        </div>
    </div>

    <div id="invalid_token" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <strong class="me-auto">Failed</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            Password Reset Failed! Invalid or expired token.
        </div>
    </div>
</div>




</main>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Function to show the success toast
    function showSuccessToast() {
        var toastEl = document.getElementById('successToast');
        var toast = new bootstrap.Toast(toastEl);
        toast.show();
    }

    function showLoginFail() {
        var toastEl = document.getElementById('login_fail');
        var toast = new bootstrap.Toast(toastEl);
        toast.show();
    }

    function showSuceessUpdateToast(){
        var toastEl = document.getElementById('update_success');
        var toast = new bootstrap.Toast(toastEl);
        toast.show();
    }

    function showInvalidTokenToast(){
        var toastEl = document.getElementById('invalid_token');
        var toast = new bootstrap.Toast(toastEl);
        toast.show();
    }

    // Function to show the IC Number exist toast
    function showICNumberExistToast() {
        var toastEl = document.getElementById('icNumberExistToast');
        var toast = new bootstrap.Toast(toastEl);
        toast.show();
    }

    <?php
    if (isset($_SESSION['registration_success']) && $_SESSION['registration_success']) {
        $_SESSION['registration_success'] = false; // Reset the variable
        echo "document.addEventListener('DOMContentLoaded', showSuccessToast);";
    }
    ?>

    <?php
    if (isset($_SESSION['update_pass_success']) && $_SESSION['update_pass_success']) {
        $_SESSION['update_pass_success'] = false; // Reset the variable
        echo "document.addEventListener('DOMContentLoaded', showSuceessUpdateToast);";
    }
    ?>

    <?php
    if (isset($_SESSION['pass_invalid_token']) && $_SESSION['pass_invalid_token']) {
        $_SESSION['pass_invalid_token'] = false; // Reset the variable
        echo "document.addEventListener('DOMContentLoaded', showInvalidTokenToast);";
    }
    ?>

    <?php
    if (isset($_SESSION['login_fail']) && $_SESSION['login_fail']) {
        $_SESSION['login_fail'] = false; // Reset the variable
        echo "document.addEventListener('DOMContentLoaded', showLoginFail);";
    }
    ?>


    <?php if (!empty($error_message)): ?>
    document.addEventListener('DOMContentLoaded', showICNumberExistToast);
    <?php endif; ?>
</script>

<!-- Bootstrap form validation for password matching -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var passwordInput = document.getElementById('user_password');
        var confirmPasswordInput = document.getElementById('confirm_password');

        passwordInput.addEventListener('input', function () {
            if (passwordInput.value !== confirmPasswordInput.value) {
                confirmPasswordInput.setCustomValidity("Passwords do not match");
            } else {
                confirmPasswordInput.setCustomValidity('');
            }
        });

        confirmPasswordInput.addEventListener('input', function () {
            if (passwordInput.value !== confirmPasswordInput.value) {
                confirmPasswordInput.setCustomValidity("Passwords do not match");
            } else {
                confirmPasswordInput.setCustomValidity('');
            }
        });
    });
</script>
</html>
