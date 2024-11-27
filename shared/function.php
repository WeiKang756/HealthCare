<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

function queryDatabaseForBookedSlots($date, $facilityID) {
    global $conn; // Ensure that $conn is accessible if it's defined outside this function

    // SQL query to fetch booked time slots for a specific date and facility
    $sql = "SELECT TimeSlot FROM appointments WHERE AppointmentDate = ? AND facility_ID = ?";
    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bind_param("ss", $date, $facilityID); // "si" denotes that $date is a string and $facilityID is an integer

    // Execute the query
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    $bookedSlots = [];
    while ($row = $result->fetch_assoc()) {
        $bookedSlots[] = (new DateTime($row['TimeSlot']))->format('H:i');
    }

    // Close statement (no need to close the connection if it's reused)
    $stmt->close();

    return $bookedSlots;
}

function getTotalBloodByUserId($conn, $user_ICNumber) {
    // Ensure the user_ICNumber is safe to use in a query (avoid SQL injection)
    $user_ICNumber = mysqli_real_escape_string($conn, $user_ICNumber);

    // Query to get the total blood for the given user ID
    $query = "SELECT COUNT(*) AS totalBlood FROM Blood WHERE user_ICNumber = '$user_ICNumber'";

    // Execute the query
    $result = mysqli_query($conn, $query);

    if ($result) {
        // Fetch the result as an associative array
        $row = mysqli_fetch_assoc($result);

        // Return the total blood amount (or 0 if no records found)
        return $row ? (int)$row['totalBlood'] : 0;
    } else {
        // Handle query error
        echo "Error: " . mysqli_error($conn);
        return false;
    }
}

function generateUniqueOrderID() {
    // Get the current timestamp (Unix timestamp)
    $timestamp = time();

    // Generate a random component (you can customize the length)
    $random_component = mt_rand(1000, 9999);

    // Combine timestamp and random component to create the order ID
    $order_id = $timestamp . $random_component;

    return $order_id;
}

function getOrdersByStatus($conn, $status) {
    $sql = "SELECT * FROM FacilityBloodOrders WHERE Status = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        // Handle error here
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("s", $status);

    if (!$stmt->execute()) {
        // Handle error here
        die("Error executing statement: " . $stmt->error);
    }

    $result = $stmt->get_result();
    $orders = [];

    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }

    $stmt->close();
    return $orders;
}

function updateBloodUnitsStatus($order_id, $status, $conn) {
    // SQL query to update the status of blood units related to the specified order
    $update_sql = "UPDATE Blood SET Status = ? WHERE order_id = ?";

    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ss", $status, $order_id);

    if ($stmt->execute()) {
        return true; // Successfully updated blood units status
    } else {
        return false; // Error updating blood units status
    }
}

function updateMediacalOrderStatus($order_id, $status, $conn) {
    // SQL query to update the status of blood units related to the specified order
    $update_sql = "UPDATE MedicalOrders SET Status = ? WHERE order_id = ?";

    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ss", $status, $order_id);

    if ($stmt->execute()) {
        return true; // Successfully updated blood units status
    } else {
        return false; // Error updating blood units status
    }
}

function updateAppointmentStatus($order_id, $status, $conn) {
    // SQL query to update the status of blood units related to the specified order
    $update_sql = "UPDATE Appointments SET Status = ? WHERE AppointmentID = ?";

    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ss", $status, $order_id);

    if ($stmt->execute()) {
        return true; // Successfully updated blood units status
    } else {
        return false; // Error updating blood units status
    }
}

function listAllOrders($conn) {
    // SQL query to select all orders
    $sql = "SELECT * FROM FacilityBloodOrders";

    // Execute the query
    $result = $conn->query($sql);

    // Initialize an array to store the orders
    $order = array();

    // Check if there are any orders
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Append each order to the $orders array
            $order[] = $row;
        }
    }

    return $order;
}

function getFacilityOperationHours($conn, $facility_ID) {
    $query_operation = "SELECT * FROM FacilityOperation WHERE facility_ID = ? ORDER BY FIELD(DayOfWeek, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')";
    $stmt = mysqli_prepare($conn, $query_operation);
    mysqli_stmt_bind_param($stmt, "s", $facility_ID);
    mysqli_stmt_execute($stmt);
    $result_operation = mysqli_stmt_get_result($stmt);

    return $result_operation;
}

function getOperationHours($conn, $facilityID, $dayOfWeek) {
    // Prepare the SQL query
    $query = "SELECT OpeningTime, ClosingTime FROM FacilityOperation WHERE facility_ID = ? AND DayOfWeek = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ss", $facilityID, $dayOfWeek);
    mysqli_stmt_execute($stmt);

    // Get the result
    $result = mysqli_stmt_get_result($stmt);

    // Fetch operation hours
    $operationHours = mysqli_fetch_assoc($result);

    // Close the statement
    mysqli_stmt_close($stmt);

    return $operationHours;
}

// Function to get the total number of blood units for a specific status and blood type
function getTotalBloodCount($bloodTypeCondition, $statusCondition) {
    global $conn;

    // Prepare the SQL query with conditions
    $sql = "SELECT COUNT(*) AS TotalCount FROM Blood WHERE BloodType = ? AND Status = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $bloodTypeCondition, $statusCondition);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch the total count
        $row = $result->fetch_assoc();
        $totalCount = $row['TotalCount'];

        $stmt->close();

        return $totalCount;
    } else {
        // If no rows are returned, return 0
        $stmt->close();
        return 0;
    }
}

function getMedicalOrdersByStatusAndFacility($status, $facilityID) {
   global $conn;
    // Sanitize inputs to prevent SQL injection
    $status = mysqli_real_escape_string($conn, $status);
    $facilityID = mysqli_real_escape_string($conn, $facilityID);

    // Query to retrieve medical orders based on Status and Facility ID
    $sql = "SELECT * FROM MedicalOrders
            WHERE Status = '$status' AND facility_id = '$facilityID'";

    $result = $conn->query($sql);

    $medicalOrders = array();

    if ($result->num_rows > 0) {
        // Fetch the results into an array
        while ($row = $result->fetch_assoc()) {
            $medicalOrders[] = $row;
        }
    }

    return $medicalOrders;
}

function getMedicalOrdersByICNumber($conn, $userICNumber) {
    // SQL query
    $sql = "SELECT MedicalOrders.*, facilities.facility_Name
            FROM MedicalOrders
            JOIN facilities ON MedicalOrders.facility_ID = facilities.facility_ID
            WHERE MedicalOrders.user_ICNumber = '$userICNumber'";

    // Execute the query
    $result = $conn->query($sql);

    // Check if the query was successful
    if ($result === false) {
        die("Error executing query: " . $conn->error);
    }

    // Array to store the results
    $medicalOrders = array();

    // Check if there are rows in the result set
    if ($result->num_rows > 0) {
        // Loop through each row in the result set
        while ($row = $result->fetch_assoc()) {
            // Add the row to the array
            $medicalOrders[] = $row;
        }
    }

    return $medicalOrders;
}

function getTotalMedicalOrdersByStatusAndFacility($status, $facilityID) {
    global $conn; // Access the global $conn variable

    // Sanitize inputs to prevent SQL injection
    $status = mysqli_real_escape_string($conn, $status);
    $facilityID = mysqli_real_escape_string($conn, $facilityID);

    // Query to retrieve the total count of medical orders based on Status and Facility ID
    $sql = "SELECT COUNT(*) AS TotalOrders FROM MedicalOrders
            WHERE Status = '$status' AND facility_id = '$facilityID'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Fetch the result
        $row = $result->fetch_assoc();
        return $row['TotalOrders'];
    } else {
        return 0; // Return 0 if no matching records found
    }
}

function getTotalAppointmentsForTodayByStatusAndFacility($facilityID, $status) {
    global $conn;
    // Assuming your appointment table has columns like 'appointmentDate', 'facility_ID', and 'status'
    $sql = "SELECT COUNT(*) AS totalAppointments FROM Appointments
            WHERE DATE(appointmentDate) = CURDATE() AND facility_ID = '$facilityID' AND status = '$status'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $totalAppointmentsToday = $row['totalAppointments'];
    } else {
        $totalAppointmentsToday = 0;
    }

    return $totalAppointmentsToday;
}

function getTotalAppointmentsForTodayByFacility($facilityID) {
    global $conn;
    // Assuming your appointment table has columns like 'appointmentDate' and 'facility_ID'
    $sql = "SELECT COUNT(*) AS totalAppointments FROM Appointments
            WHERE DATE(appointmentDate) = CURDATE() AND facility_ID = '$facilityID'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $totalAppointmentsToday = $row['totalAppointments'];
    } else {
        $totalAppointmentsToday = 0;
    }

    return $totalAppointmentsToday;
}

function getAppointmentsForTodayByFacility($facilityID) {
    global $conn;
    // Assuming your appointment table has columns like 'appointmentDate' and 'facility_ID'
    $sql = "SELECT Appointments.*, users.user_name
            FROM Appointments
            JOIN users ON Appointments.user_ICNumber = users.user_ICNumber
            WHERE DATE(Appointments.appointmentDate) = CURDATE() AND Appointments.facility_ID = '$facilityID'";

    $result = $conn->query($sql);

    $appointments = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $appointments[] = $row;
        }
    }

    return $appointments;
}

function getAppointmentsForTodayByStatusAndFacility($facilityID, $status) {
    global $conn;
    // Assuming your appointment table has columns like 'appointmentDate', 'facility_ID', 'status',
    // and 'user_ICNumber'. Also, assuming the user table has columns like 'user_name', 'user_contact', etc.
    $sql = "SELECT Appointments.*, users.user_name
            FROM Appointments
            JOIN users ON Appointments.user_ICNumber = users.user_ICNumber
            WHERE DATE(Appointments.appointmentDate) = CURDATE()
                  AND Appointments.facility_ID = '$facilityID'
                  AND Appointments.status = '$status'";

    $result = $conn->query($sql);

    $appointments = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $appointments[] = $row;
        }
    }

    return $appointments;
}

function getOrdersByFacility($facilityID) {
    global $conn;
    $facilityID = mysqli_real_escape_string($conn, $facilityID);

    $sql = "SELECT * FROM FacilityBloodOrders
            WHERE facility_id = '$facilityID'";

    $result = $conn->query($sql);

    $orders = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }
    }

    return $orders;
}

function getAppointmentsWithFacility($userID) {
    global $conn;

    $sql = "SELECT Appointments.*, facilities.facility_Name
            FROM Appointments
            JOIN facilities ON Appointments.facility_ID = Facilities.facility_ID
            WHERE Appointments.appointmentDate >= NOW() AND Appointments.user_ICNumber = '$userID'
            ORDER BY Appointments.appointmentDate ASC";

    $result = $conn->query($sql);

    $appointments = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $appointments[] = $row;
        }
    }

    return $appointments;
}

function getPastAppointmentsWithFacility($userID) {
    global $conn;

    $sql = "SELECT Appointments.*, facilities.facility_Name
            FROM Appointments
            JOIN facilities ON Appointments.facility_ID = Facilities.facility_ID
            WHERE Appointments.appointmentDate < NOW() AND Appointments.user_ICNumber = '$userID'
            ORDER BY Appointments.appointmentDate ASC";

    $result = $conn->query($sql);

    $appointments = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $appointments[] = $row;
        }
    }

    return $appointments;
}

function getBloodByStatus($conn, $status) {
    // Sanitize the status to prevent SQL injection
    $status = $conn->real_escape_string($status);

    // SQL query
    $sql = "SELECT * FROM `Blood` WHERE `Status` = '$status'";

    // Execute the query
    $result = $conn->query($sql);

    // Check if the query was successful
    if ($result === false) {
        die("Error executing query: " . $conn->error);
    }

    // Array to store the results
    $bloodRecords = array();

    // Check if there are rows in the result set
    if ($result->num_rows > 0) {
        // Loop through each row in the result set
        while ($row = $result->fetch_assoc()) {
            // Add the row to the array
            $bloodRecords[] = $row;
        }
    }

    return $bloodRecords;
}

function getBloodOrdersByStatusWithFacility($conn, $status)
{
    // Sanitize the input to prevent SQL injection
    $status = mysqli_real_escape_string($conn, $status);

    // Construct the SQL query with a JOIN clause
    $query = "
        SELECT o.*, f.facility_name
        FROM FacilityBloodOrders o
        JOIN facilities f ON o.facility_id = f.facility_ID
        WHERE o.status = '$status'
    ";

    // Execute the query
    $result = mysqli_query($conn, $query);

    // Check if the query was successful
    if ($result) {
        // Fetch all rows as an associative array
        $orders = mysqli_fetch_all($result, MYSQLI_ASSOC);

        // Free the result set
        mysqli_free_result($result);

        return $orders;
    } else {
        // If the query fails, you might want to handle the error appropriately
        echo "Error retrieving blood orders: " . mysqli_error($conn);
        return array(); // Return an empty array if there's an error
    }
}



?>
