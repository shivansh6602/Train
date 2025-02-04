<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "trainbooking";

$con = new mysqli("localhost", "root", "", "trainbooking");

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];

    $stmt = $con->prepare("DELETE FROM stations WHERE id = ?");
    $stmt->bind_param("i", $delete_id);

    if ($stmt->execute()) {
        echo "Station deleted successfully!";
    } else {
        echo "Error deleting station: " . $stmt->error;
    }
    $stmt->close();
}
$con->close();
?>
