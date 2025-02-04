<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "trainbooking";

$con = new mysqli($servername, $username, $password, $database);

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

$successMessage = null;  // Initialize success message variable
$errorMessage = null; 

// Handle Add Station via AJAX
if (isset($_POST['ajax_request']) && $_POST['ajax_request'] === 'add_station') {
    $train_id = $_POST['train_id'];
    $station_name = trim($_POST['station_name']);
    $distance_km = isset($_POST['distance_km']) && !empty($_POST['distance_km']) ? $_POST['distance_km'] : null;

    // Check for duplicate station
    $checkStmt = $con->prepare("SELECT COUNT(*) FROM stations WHERE station_name = ?");
    $checkStmt->bind_param("s", $station_name);
    $checkStmt->execute();
    $checkStmt->bind_result($count);
    $checkStmt->fetch();
    $checkStmt->close();

    if ($count > 0) {
        echo "Error: Station already exists!";
        exit;
    }

    // Insert new station
    if ($distance_km !== null) {
        $stmt = $con->prepare("INSERT INTO stations (train_id, station_name, distance_km) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $train_id, $station_name, $distance_km);
    } else {
        $stmt = $con->prepare("INSERT INTO stations (train_id, station_name) VALUES (?, ?)");
        $stmt->bind_param("is", $train_id, $station_name);
    }

    if ($stmt->execute()) {
        echo "Station added successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    exit;
}

// Handle station deletion (AJAX)
if (isset($_POST['ajax_request']) && $_POST['ajax_request'] === 'delete_station') {
    $station_name = trim($_POST['station_name']);

    if (empty($station_name)) {
        echo json_encode(["status" => "error", "message" => "Error: Invalid station name."]);
        exit;
    }

    $stmt = $con->prepare("DELETE FROM stations WHERE station_name = ?");
    $stmt->bind_param("s", $station_name);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Station deleted successfully!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error deleting station: " . $stmt->error]);
    }

    $stmt->close();
    exit;
}


// Fetch stations dynamically for AJAX
if (isset($_GET['ajax']) && $_GET['ajax'] == '1') {
    $stations_result = $con->query("SELECT stations.station_name, stations.distance_km, trains.train_name 
                                    FROM stations 
                                    JOIN trains ON stations.train_id = trains.id");

    // Return the *entire* table structure
    echo "<table>"; // Start the table
    echo "<tbody>"; // Start the table body

    while ($station = $stations_result->fetch_assoc()) {
        echo "<tr id='station-{$station['station_name']}'>
                <td>{$station['train_name']}</td>
                <td>{$station['station_name']}</td>
                <td>{$station['distance_km']}</td>
                <td>
 |
                    <a href='#' onclick=\"deleteStation('{$station['station_name']}')\">Delete</a>
                </td>
            </tr>";
    }
    

    echo "</tbody>"; // Close the table body
    echo "</table>"; // Close the table
    exit;
}

// Fetch all stations for the first page load
$stations_result = $con->query("SELECT stations.station_name, stations.distance_km, trains.train_name 
                                FROM stations 
                                JOIN trains ON stations.train_id = trains.id");

// Fetch trains for dropdown
$trains_result = $con->query("SELECT id, train_name FROM trains");

?>


<div class="add-station-form">
    <div id="imgs">
<h1 id="station"> Add Station</h1>
<img src="https://cdn-icons-png.flaticon.com/128/6113/6113244.png" alt="Add Icon" class="icon"> 

</div>
    <form id="addStationForm" method="POST" action="station.php">
        <label for="train_id">Train:</label>
        <select id="train_id" name="train_id" required>
            <option value="">Select Train</option>
            <?php while ($train = $trains_result->fetch_assoc()) { ?>
                <option value="<?php echo $train['id']; ?>">
                    <?php echo $train['train_name']; ?>
                </option>
            <?php } ?>  
        </select>

        <label for="station_name">Station Name</label>
        <input type="text" id="station_name" name="station_name" placeholder="Station Name" required>

        <label for="distance_km">Distance (km)</label>
        <input type="number" id="distance_km" name="distance_km" placeholder="Distance (km)" step="0.01" required>

        <button type="submit">Add Station</button>
    </form>
</div>

</div>

<h2>Station List</h2>
<table>
    <thead>
        <tr>
            <th>Train Name</th>
            <th>Station Name</th>
            <th>Distance (km)</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody id="stationList">
    <?php
    if ($stations_result && $stations_result->num_rows > 0) {
        while ($station = $stations_result->fetch_assoc()) {
            echo "<tr id='station-{$station['station_name']}'>
                    <td>{$station['train_name']}</td>
                    <td>{$station['station_name']}</td>
                    <td>{$station['distance_km']}</td>
                    <td>
                        <a href='editStation2.php?station_name=" . urlencode($station['station_name']) . "'>Edit</a> |
                        <a href='#' onclick=\"deleteStation('{$station['station_name']}')\">Delete</a>
                    </td>
                </tr>";
        }
        } else {
            echo "<tr><td colspan='4' class='no-stations'>No stations found.</td></tr>";
        }
    ?>
    </tbody>
</table>

<script>
  
</script>
<style>
       select#train_id {
    width: 100%;
    border: none;
    height: 6vh;
    border-radius: 5px;
    font-size: 17px;
}
       label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
        }
        img.icon {
    width: 6%;
}/* Form Heading with Icon */
.edit-station-form h1 {
    text-align: center;
    color: #333;
    margin-bottom: 20px;
    font-size: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.edit-station-form h1 .icon {
    width: 30px; /* Fixed width for the icon */
    height: auto; /* Maintain aspect ratio */
    margin-right: 10px; /* Space between the icon and text */
}
div#imgs {
    display: flex
;
}
    </style>