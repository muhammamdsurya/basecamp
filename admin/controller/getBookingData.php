<?php

require '../../functions.php'; // Ensure this file sets up your database connection
// Get the start and end dates from the AJAX request
$startDate = isset($_GET['startDate']) ? $_GET['startDate'] : null;
$endDate = isset($_GET['endDate']) ? $_GET['endDate'] : null;

// Initialize response array
$response = array();

if ($startDate && $endDate) {
    // Prepare SQL query to fetch sales data within the specified date range
    $query = "SELECT DATE(booking_date) AS tanggal, COUNT(id) AS jml_sewa
              FROM bookings
              WHERE booking_date BETWEEN ? AND ?
              GROUP BY tanggal
              ORDER BY tanggal";

    // Prepare the statement
    if ($stmt = $conn->prepare($query)) {
        // Bind parameters
        $stmt->bind_param("ss", $startDate, $endDate);

        // Execute the statement
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        // Check if there are results
        if ($result->num_rows > 0) {
            // Fetch data and store it in an array
            while ($row = $result->fetch_assoc()) {
                $response[] = $row;
            }
            // Return success response with data
            echo json_encode(array('success' => true, 'data' => $response));
        } else {
            // Return success response with no data found
            echo json_encode(array('success' => true, 'data' => []));
        }

        // Close the statement
        $stmt->close();
    } else {
        // Return error response if query preparation fails
        echo json_encode(array('success' => false, 'message' => 'Query preparation failed.'));
    }
} else {
    // Return error response if dates are not provided
    echo json_encode(array('success' => false, 'message' => 'Invalid date range.'));
}

// Close the database connection
$conn->close();
?>