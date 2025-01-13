<?php
session_start(); // Start or resume the session

// Check if the user is logged in (you may customize this part)
if (!isset($_SESSION['staff_ID'])) {
    // Redirect to the login page if the user is not logged in
    header("Location: index.php"); // Change "login.php" to your actual login page
    exit;
}
namespace Staff;

use Shared\Config;
include"../shared/function.php";// Include Function

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $facility_id = $_SESSION['facility_ID'];
    $blood_type = $_POST['BloodType'];
    $quantity = $_POST['quantity'];
    $order_id = generateUniqueOrderID();

    // Check if there are enough available blood units of the specified type
    $check_sql = "SELECT COUNT(*) AS available_count FROM Blood WHERE BloodType = ? AND Status = 'Available'";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $blood_type);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result && $check_row = $check_result->fetch_assoc()) {
        $available_count = $check_row['available_count'];

        if ($available_count >= $quantity) {

          $insert_sql = "INSERT INTO FacilityBloodOrders (order_ID, facility_id, blood_type, quantity, order_date, status) VALUES (?, ?, ?, ?, CURRENT_TIMESTAMP(), 'Reserved')";
          $insert_stmt = $conn->prepare($insert_sql);
          $insert_stmt->bind_param("sssi",$order_id, $facility_id, $blood_type, $quantity);

            if ($insert_stmt->execute()) {

              $conn->autocommit(FALSE); // Start a transaction
              $update_sql = "UPDATE Blood SET Status = 'Reserved', order_id = ?, facility_id = ? WHERE BloodType = ? AND Status = 'Available' ORDER BY CreatedAt ASC LIMIT ?";
              $update_stmt = $conn->prepare($update_sql);
              $update_stmt->bind_param("sssi", $order_id, $facility_id, $blood_type, $quantity);

                if ($update_stmt->execute()) {
                    $conn->commit(); // Commit the transaction
                    echo '<script>alert("Order placed successfully, and blood status updated to Reserved"); window.location.href = "blood_order_request.php";</script>';
                    exit;
                } else {
                    $conn->rollback(); // Rollback the transaction if insertion fails
                    echo '<script>alert("Error inserting order data"); window.location.href = "blood_order_request.php";</script>';
                    exit;
                }
            } else {
                $conn->rollback(); // Rollback the transaction if status update fails
                echo '<script>alert("Error updating blood status"); window.location.href = "blood_order_request.php";</script>';
                exit;
            }
        } else {
            // Insufficient available blood units, insert the order as "Rejected"
            $insert_sql = "INSERT INTO FacilityBloodOrders (order_ID,facility_id, blood_type, quantity, order_date, status) VALUES (?, ?, ?, ?, CURRENT_TIMESTAMP(), 'Rejected')";
            $insert_stmt = $conn->prepare($insert_sql);
            $insert_stmt->bind_param("sssi", $order_id, $facility_id, $blood_type, $quantity);

            if ($insert_stmt->execute()) {
                echo '<script>alert("Insufficient available blood units, order marked as Rejected"); window.location.href = "blood_order_request.php";</script>';
                exit;
            } else {
                echo '<script>alert("Error inserting rejected order data"); window.location.href = "blood_order_request.php";</script>';
                exit;
            }
        }
    } else {
        echo '<script>alert("Error checking available blood count"); window.location.href = "blood_order_request.php";</script>';
        exit;
    }

    $conn->autocommit(TRUE); // Restore autocommit mode
}
else {
  echo "error";
}
?>
