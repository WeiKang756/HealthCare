<?php
include("shared/config.php");

     // Retrieve user_ic and user_email from the form
     $user_ic = $_POST["ic_num"];
     $user_email = $_POST["email"];

     // Check if the user_ic and user_email combination exists in the database
     $sql = "SELECT * FROM users WHERE user_ICNumber = '$user_ic' AND user_email = '$user_email'";
     $result = $conn->query($sql);

     if ($result->num_rows > 0) {
         // Generate a unique token (you can use a library like random_bytes for a secure token)
         $token = bin2hex(random_bytes(32));

         // Store the token in the database
         $sql = "UPDATE users SET reset_token = '$token' WHERE user_ICNumber = '$user_ic' AND user_email = '$user_email'";
         $conn->query($sql);

         // Redirect to the password update page with the token as a parameter
         header("Location: update_password.php?token=$token");
         exit();
     } else {
         echo "Invalid user_ic and user_email combination.";
     }

     // Close the database connection
     $conn->close();

 ?>
