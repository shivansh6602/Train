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

if (isset($_GET['station_name'])) {
    $station_name = $_GET['station_name'];
    
    // Fetch station details
    $stmt = $con->prepare("SELECT stations.id, stations.station_name, stations.distance_km, stations.train_id, trains.train_name 
                           FROM stations
                           JOIN trains ON stations.train_id = trains.id
                           WHERE stations.station_name = ?");
    $stmt->bind_param("s", $station_name);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $station = $result->fetch_assoc();
    } else {
        echo "Station not found!";
        exit;
    }
    $stmt->close();
}

// Fetch all trains for the dropdown
$trains_result = $con->query("SELECT id, train_name FROM trains");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Station</title>
</head>
<body>
<div class="edit-station-form">
    <h1>Edit Station</h1>
    <form method="POST" action="updateStation2.php">
        <!-- Hidden field for station ID -->
        <input type="hidden" name="station_id" value="<?php echo htmlspecialchars($station['id']); ?>">

        <div class="form-group">
            <label for="train_id">Train</label>
            <select id="train_id" name="train_id" required>
                <option value="">Select Train</option>
                <?php while ($train = $trains_result->fetch_assoc()) { ?>
                    <option value="<?php echo $train['id']; ?>"
                        <?php echo $station['train_id'] == $train['id'] ? 'selected' : ''; ?>>
                        <?php echo $train['train_name']; ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div class="form-group">
            <label for="station_name">Station Name</label>
            <input type="text" id="station_name" name="station_name" value="<?php echo htmlspecialchars($station['station_name']); ?>" required>
        </div>

        <div class="form-group">
            <label for="distance_km">Distance (km)</label>
            <input type="number" id="distance_km" name="distance_km" value="<?php echo htmlspecialchars($station['distance_km']); ?>" required>
        </div>

        <div class="form-group">
            <input type="submit" value="Update Station" class="submit-button">
        </div>
    </form>
</div>
<style>
.edit-station-form {
    background-color: #f9f9f9;
    padding: 30px;
    border-radius: 8px;
box-shadow: 0 2px 23px black;
    width: 50%;
    margin: 0 auto;
    margin-top: 6%;
    background-color: lavender;
    font-family: sans-serif;
}
/* Form Heading */
.edit-station-form h1 {
    text-align: center;
    color: #333;
    margin-bottom: 20px;
    font-size: 24px;
}

/* Form Group */
.form-group {
    margin-bottom: 20px;
}

/* Labels for form inputs */
.form-group label {
    display: block;
    color: #555;
    font-size: 16px;
    margin-bottom: 8px;
}

/* Input fields and select dropdown */
.form-group input,
.form-group select {
    width: 100%;
    padding: 10px;
    font-size: 16px;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box;
}

/* Input focus effect */
.form-group input:focus,
.form-group select:focus {
    outline: none;
    border-color: #007BFF;
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
}

/* Submit Button */
.submit-button {
    width: 100%;
    padding: 12px;
    font-size: 16px;
    background-color: #2c3e50;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    box-shadow: 0 3px 4px black;
}

.submit-button:hover {
    background-color: #2c3e50;
}

/* Optional: Add responsiveness */
@media (max-width: 768px) {
    .edit-station-form {
        width: 80%;
    }
}
body {
    background-color: lavender;
}

    </style>

</body>
</html>
