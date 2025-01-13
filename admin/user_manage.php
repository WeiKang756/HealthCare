<?php
session_start();

namespace Admin;

use Shared\Config;
use Shared\Navigation;

define('TD_CLOSE_TAG', '</td>');


$username = $_SESSION['admin'];
$user_role = $_SESSION['role'];

if ($user_role !== 'admin') {
    echo "You are not authorized to access this page.  Please contact the administrator for assistance.";
    exit;
}

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<body>
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
  <style media="screen">

  .custom-table-row {
      height: auto; /* Set the height to auto to match the content */
  }

  </style>
    <?php
        Navigation::render();
     ?>
<main>
  <div class="container-fluid">
    <div class="row">
        <h1>User List</h1>
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th>User IC</th>
                    <th>User Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch all hospitals from the facilities table
                $sql = "SELECT * FROM users";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<tr class="custom-table-row">';
                        echo '<td>' . $row['user_ICNumber'] . TD_CLOSE_TAG;
                        echo '<td>' . $row['user_name'] . TD_CLOSE_TAG;
                        echo '<td>';
                        echo '<button class="btn btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#ViewUser"
                                data-user-ic="' . $row['user_ICNumber'] . '"
                                data-user-name="' . $row['user_name'] . '"
                                data-user-contact="' . $row['user_contact'] . '"
                                data-user-email="' . $row['user_email'] . '"
                                data-user-address="' . $row['user_address'] . '">View</button>';
                        echo '<button class="btn btn-danger btn-sm" data-bs-toggle="modal" style="margin-left: 10px;" data-bs-target="#DeleteUser"
                                data-user-ic="' . $row['user_ICNumber'] . '" data-user-name="' . $row['user_name'] . '">Delete</button>';
                        echo TD_CLOSE_TAG;
                    }
                } else {
                    echo '<tr><td colspan="4">No User found.</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
  </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</main>
</body>
<div class="modal fade" id="ViewUser" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">User Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form id="UserEditForm" action="user_edition.php" method="post">

                <div class="mb-3">
                    <label for="user_ic" class="form-label" hidden>User IC:</label>
                    <input type="text" name="user_ic" id="user_ic" class="form-control" hidden>
                </div>

                <div class="mb-3">
                    <label for="user_name" class="form-label">User Name:</label>
                    <input type="text" name="user_name" id="user_name" class="form-control" disabled>
                </div>

                <div class="mb-3">
                    <label for="user_contact" class="form-label">User Contact:</label>
                    <input type="text" name="user_contact" id="user_contact" class="form-control" disabled>
                </div>

                <div class="mb-3">
                    <label for="user_email" class="form-label">User Email:</label>
                    <input type="text" name="user_email" id="user_email" class="form-control" disabled>
                </div>

                <div class="mb-3">
                    <label for="user_address" class="form-label">User Address:</label>
                    <textarea type="text" name="user_address" id="user_address" class="form-control" disabled></textarea>
                </div>

              </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="cancelButton"  style="display:none" >Cancel Edit</button>
                  <button type="button" class="btn btn-primary" id="editButton">Edit</button>
                <button type="submit" class="btn btn-success" form="UserEditForm" id="submitButton" style="display:none">Submit</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="DeleteUser" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form method="post" action="userDeletion.php" id="DeleteStaffForm">

                <div class="mb-3">
                    <label for="user_ic" class="form-label" hidden>User ID:</label>
                    <input type="text" name="user_ic" class="form-control" id="delete_user_id" required hidden>
                </div>

                <div class="mb-3">
                    <label for="staff_id-2" class="form-label" hidden>User ID:</label>
                    <input type="text" name="user_id-2" class="form-control" id="delete_user_id-2" disabled>
                </div>

                <div class="mb-3">
                    <label for="user_name" class="form-label" hidden>User Name:</label>
                    <input type="text" name="user_name" class="form-control" id="delete_user_name" disabled>
                </div>

                <div class="mb-3">
                  <p>Are you sure to delete this User?</p>
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

</html>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    var ViewUser = document.getElementById('ViewUser');
    ViewUser.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var user_ic = button.getAttribute('data-user-ic');
        var user_name = button.getAttribute('data-user-name');
        var user_contact = button.getAttribute('data-user-contact');
        var user_email = button.getAttribute('data-user-email');
        var user_address = button.getAttribute('data-user-address');

        // Store the original values
        var originalValues = {
            user_name: user_name,
            user_contact: user_contact,
            user_email: user_email,
            user_address: user_address
        };

        // Assign the original values to the fields
        document.getElementById('user_ic').value = user_ic;
        document.getElementById('user_name').value = user_name;
        document.getElementById('user_contact').value = user_contact;
        document.getElementById('user_email').value = user_email;
        document.getElementById('user_address').value = user_address;

        // Attach the original values to the form
        document.getElementById('UserEditForm').originalValues = originalValues;
    });

    const name = document.getElementById('user_name');
    const contact = document.getElementById('user_contact');
    const email = document.getElementById('user_email');
    const address = document.getElementById('user_address');
    const editButton = document.getElementById('editButton');
    const cancelButton = document.getElementById('cancelButton');
    const submitButton = document.getElementById('submitButton');

    // Function to toggle the disabled property of specific input fields
    function toggleInputDisabled(input) {
        input.disabled = !input.disabled;
    }

    // Function to check if there are changes in the form
    function hasChanges(form, originalValues) {
        return (
            form.user_name.value !== originalValues.user_name ||
            form.user_contact.value !== originalValues.user_contact ||
            form.user_email.value !== originalValues.user_email ||
            form.user_address.value !== originalValues.user_address
        );
    }

    // Add a click event listener to the Edit button
    editButton.addEventListener('click', () => {
        toggleInputDisabled(name);
        toggleInputDisabled(contact);
        toggleInputDisabled(email);
        toggleInputDisabled(address);
        editButton.style.display = "none";
        cancelButton.style.display = "inline";
        submitButton.style.display = "inline";
    });

    // Add a click event listener to the Cancel button
    cancelButton.addEventListener('click', () => {
        toggleInputDisabled(name);
        toggleInputDisabled(contact);
        toggleInputDisabled(email);
        toggleInputDisabled(address);
        cancelButton.style.display = "none";
        submitButton.style.display = "none";
        editButton.style.display = "inline";
    });

    // Add a click event listener to the Submit button to trigger form submission
    submitButton.addEventListener('click', () => {
        const form = document.getElementById('UserEditForm');
        const originalValues = form.originalValues;

        // Check if there are changes
        if (!hasChanges(form, originalValues)) {
            // No changes, prevent form submission
            alert("No changes have been made.");
            return false;
        }

        // Allow form submission
        form.submit();
    });

    // Prevent the form from submitting (for demonstration purposes)
    document.querySelector('form').addEventListener('submit', (e) => {
        e.preventDefault();
        console.log('Form submitted');
    });

    var DeleteUser = document.getElementById('DeleteUser');
    DeleteUser.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var user_ic = button.getAttribute('data-user-ic');
        var user_name = button.getAttribute('data-user-name');

        document.getElementById('delete_user_id').value = user_ic;
        document.getElementById('delete_user_id-2').value = user_ic;
        document.getElementById('delete_user_name').value = user_name;
    });

</script>
