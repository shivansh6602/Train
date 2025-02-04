<?php
// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$database = "trainbooking";

$con = new mysqli($servername, $username, $password, $database);

// Check the database connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// If the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data
    $station_id = $_POST['station_id'];  // Hidden field for station ID
    $train_id = $_POST['train_id'];  // Train ID from the form
    $station_name = $_POST['station_name'];  // Station name from the form
    $distance_km = $_POST['distance_km'];  // Distance (km) from the form

    // Check if all required fields are filled
    if (!empty($station_id) && !empty($train_id) && !empty($station_name) && !empty($distance_km)) {
        // Prepare the SQL statement to update the station record
        $stmt = $con->prepare("UPDATE stations SET train_id = ?, station_name = ?, distance_km = ? WHERE id = ?");
        $stmt->bind_param("isdi", $train_id, $station_name, $distance_km, $station_id);  // Bind the parameters

        // Execute the query
        if ($stmt->execute()) {
            // If successful, redirect to adminboard.php and include the query parameter to show the station section
            header("Location: adminboard.php?show_add_station=true");  // Adjust this if necessary
            exit;
        } else {
            // If an error occurs, display the error message
            echo "Error updating record: " . $stmt->error;
        }

        $stmt->close();  // Close the statement
    } else {
        echo "All fields are required!";  // Display a message if any field is empty
    }
}

// Close the database connection
$con->close();
?>
