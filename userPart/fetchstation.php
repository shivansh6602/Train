<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json'); // Ensure JSON output

$servername = "localhost";
$username = "root";
$password = "";
$database = "trainbooking";

$con = new mysqli($servername, $username, $password, $database);

if ($con->connect_error) {
    echo json_encode(["error" => "Database connection failed: " . $con->connect_error]);
    exit;
}

// Check if train_id is set
if (!isset($_GET['train_id']) || empty($_GET['train_id'])) {
    echo json_encode(["error" => "Missing train ID"]);
    exit;
}

$train_id = intval($_GET['train_id']);

$query = "SELECT station_name FROM stations WHERE train_id = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $train_id);
$stmt->execute();
$result = $stmt->get_result();

$stations = [];
while ($row = $result->fetch_assoc()) {
    $stations[] = $row;
}

if (empty($stations)) {
    echo json_encode(["error" => "No stations found for this train"]);
} else {
    echo json_encode($stations);
}

$stmt->close();
$con->close();
?>
