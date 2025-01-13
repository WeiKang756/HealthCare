<?php

namespace User;

use Shared\Config;

function generateTimeSlots($startTime, $endTime, $duration) {
    $allSlots = [];
    $currentTime = DateTime::createFromFormat('H:i', $startTime);

    while ($currentTime < DateTime::createFromFormat('H:i', $endTime)) {
        $allSlots[] = $currentTime->format('H:i');
        $currentTime->modify("+$duration minutes");
    }

    return $allSlots;
}

function getOperationHours($facilityID, $dayOfWeek) {
    global $conn;

    $query = "SELECT * FROM FacilityOperation WHERE facility_ID = ? AND DayOfWeek = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ss", $facilityID, $dayOfWeek);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $operationHours = mysqli_fetch_assoc($result);

    // Format opening and closing times
    $operationHours['OpeningTime'] = date('H:i', strtotime($operationHours['OpeningTime']));
    $operationHours['ClosingTime'] = date('H:i', strtotime($operationHours['ClosingTime']));

    mysqli_stmt_close($stmt);

    return $operationHours;
}

function queryDatabaseForBookedSlots($date, $facilityID) {
    global $conn;

    // SQL query to fetch booked time slots for a specific date and facility
    $sql = "SELECT TimeSlot FROM Appointments WHERE AppointmentDate = ? AND facility_ID = ?";
    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bind_param("ss", $date, $facilityID); // "si" denotes that $date is a string and $facilityID is an integer

    // Execute the query
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    $bookedSlots = [];
    while ($row = $result->fetch_assoc()) {
        // Format the time to 'HH:MM'
        $formattedTime = date('H:i', strtotime($row['TimeSlot']));
        $bookedSlots[] = $formattedTime;
    }

    // Close statement (no need to close the connection if it's reused)
    $stmt->close();

    return $bookedSlots;
}

function getDayOfWeek($dateString) {
    $timestamp = strtotime($dateString);
    return date("l", $timestamp);
}

// Simulate fetching facilities based on the selected service
if (isset($_GET['action']) && $_GET['action'] === 'loadFacilities') {
    // Here, you should query your database to fetch facilities data
    // Modify the SQL query to match your database schema and retrieve relevant facility data
    global $conn;

    // Example SQL query (modify as needed):
    $sql = "SELECT facility_ID, facility_Name FROM facilities";

    // Execute the query and fetch the facilities data
    // Replace '$conn' with your database connection variable
    $result = $conn->query($sql);

    // Check if there are results
    if ($result->num_rows > 0) {
        $facilities = array(); // Create an array to store facility data

        // Fetch facility data and add it to the array
        while ($row = $result->fetch_assoc()) {
            $facilities[] = $row;
        }

        // Return the array as a JSON response
        echo json_encode($facilities);
    } else {
        // No facilities found, return an empty array as JSON
        echo json_encode(array());
    }
}

// Simulate fetching available time slots for the selected date and facility
if (isset($_GET['action']) && $_GET['action'] === 'loadDates') {
    $selectedFacility = $_GET['facility'];

    global $conn;
    $sql = "SELECT * FROM FacilityOperation WHERE facility_ID = ? ORDER BY FIELD(DayOfWeek, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')";
    $stmt = $conn->prepare($sql);
    // Bind parameters
    $stmt->bind_param("s", $selectedFacility);
    // Execute the query
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if there are results
    if ($result->num_rows > 0) {
        $operations = array(); // Change variable name to $operations

        // Fetch operation data and add it to the array
        while ($row = $result->fetch_assoc()) {
            $operations[] = $row; // Change variable name to $operations
        }

        // Close the statement
        $stmt->close();
        // Close the connection
        $conn->close();

        // Return the array as a JSON response
        echo json_encode($operations);
    } else {
        // No operations found, return an empty array as JSON
        echo json_encode(array());
    }
}



// Simulate fetching available time slots for the selected date and facility
if (isset($_GET['action']) && $_GET['action'] === 'loadTimeSlots') {
    $selectedDate = $_GET['date'];
    $selectedFacility = $_GET['facility'];

    $day = getDayOfWeek($selectedDate);
    $operationTime = getOperationHours($selectedFacility, $day);
    $openTime = $operationTime['OpeningTime'];
    $closeTime = $operationTime['ClosingTime'];

    // Replace this with actual database queries to fetch available time slots for the selected date and facility
    // Return JSON response with time slot data
    $availableTimeSlots = generateTimeSlots($openTime, $closeTime, 30);
    $bookedTimeSlots = queryDatabaseForBookedSlots($selectedDate, $selectedFacility);

    $response = [
        'availableTimeSlots' => $availableTimeSlots,
        'bookedTimeSlots' => $bookedTimeSlots,
    ];

    echo json_encode($response);
}


?>
