<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "trainbooking";

$con = new mysqli($servername, $username, $password, $database);

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Check AJAX request and station name (improved)
if (isset($_POST['ajax_request']) && $_POST['ajax_request'] === 'delete_station') {
    if (!isset($_POST['station_name']) || empty(trim($_POST['station_name']))) {
        echo "Error: Invalid station name.";
        exit;
    }

    $station_name = trim($_POST['station_name']);

    $stmt = $con->prepare("DELETE FROM stations WHERE station_name = ?");
    $stmt->bind_param("s", $station_name);

    if ($stmt->execute()) {
        echo "Station deleted successfully!";
    } else {
        echo "Error deleting station: " . $stmt->error;
    }

    $stmt->close();
    $con->close(); // Close the connection here
    exit; // Essential: Stop further execution
} else {
    echo "Invalid request."; // Handle non-AJAX requests
    exit;
}


?>
