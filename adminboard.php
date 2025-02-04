
<?php
$show_add_station = isset($_GET['show_add_station']) && $_GET['show_add_station'] === 'true';

$servername = "localhost";
$username = "root";
$password = "";
$database = "trainbooking";

// Connect to the database
$con = new mysqli($servername, $username, $password, $database);

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Check if admin updates the fare
if (isset($_POST['update_fare'])) {
    $new_fare = floatval($_POST['fare_per_km']);
    $con->query("UPDATE settings SET fare_per_km = $new_fare WHERE id = 1");
}
// Get the current fare per km
$result = $con->query("SELECT fare_per_km FROM settings WHERE id = 1");
$fare_per_km = ($result->num_rows > 0) ? $result->fetch_assoc()['fare_per_km'] : 5; // Default ₹5/km

// Handle AJAX Add Train Request
if (isset($_POST['ajax_request']) && $_POST['ajax_request'] == 'add_train') {
    $train_name = $_POST['train_name'];
    $train_unique_id = $_POST['train_unique_id'];
    $source = $_POST['source'];
    $destination = $_POST['destination'];

    // Prepare and execute query without price
    $stmt = $con->prepare("INSERT INTO trains (train_name, train_unique_id, source, destination) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $train_name, $train_unique_id, $source, $destination); // Updated format

    if ($stmt->execute()) {
        echo "Train Added Successfully!";
    } else {
        echo "<p style='color: red;'>Error adding train: " . $stmt->error . "</p>";
    }

    $stmt->close();
    exit();
}

// Fetch all trains for display
$trains_result = $con->query("SELECT * FROM trains");

// Handle Fare Calculation AJAX Request
if (isset($_POST['ajax_request']) && $_POST['ajax_request'] == 'calculate_fare') {
    $distance = floatval($_POST['distance']);
    
    $result = $con->query("SELECT id, train_name FROM trains");
    
    $fares = [];
    while ($train = $result->fetch_assoc()) {
        $train_name = $train['train_name'];
        $total_fare = $distance * $fare_per_km;

        $fares[] = [
            'train_name' => $train_name,
            'total_fare' => number_format($total_fare, 2)
        ];
    }

    echo json_encode($fares);
    exit;
}

// Handle Delete Train AJAX Request
if (isset($_POST['ajax_request']) && $_POST['ajax_request'] == 'delete_train') {
    $trainId = intval($_POST['delete_id']);

    $stmt = $con->prepare("DELETE FROM trains WHERE id = ?");
    $stmt->bind_param("i", $trainId);

    if ($stmt->execute()) {
        echo "Train deleted successfully!";
    } else {
        echo "Error deleting train: " . $stmt->error;
    }

    $stmt->close();
    exit();
}
// Handle station deletion (AJAX)
if (isset($_POST['ajax_request']) && $_POST['ajax_request'] === 'delete_station') {
    $station_name = trim($_POST['station_name']);

    if (empty($station_name)) {
        echo "Error: Invalid station name.";
        exit;
    }

    $stmt = $con->prepare("DELETE FROM stations WHERE station_name = ?");
    $stmt->bind_param("s", $station_name);

    if ($stmt->execute()) {
        echo "Station deleted succeswwwwsfully!";
    } else {
        echo "Error deleting station: " . $stmt->error;
    }

    $stmt->close();
    exit;
}

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
        echo "Station added successssfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Admin Dashboard</title>
</head>
<body>
    <nav class="dashboard-nav">
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="#" id="add" class="active">Add Train</a></li>
            <li><a href="#" id="addstation">Add Station</a></li>
            <li><a href="#" id="book">Booking</a></li>
            <li><a href="#" id="user">Users</a></li>
            <li><a href="logout.php" id="out">Logout</a></li>
        </ul>
    </nav>

    <div class="dashboard-container">
        <div id="content" class="content">
            <div id="addTrainSection">
                <div id="imgs">
                <h2 id="add">Add Train</h2>
                <img src="https://cdn-icons-png.flaticon.com/128/3085/3085363.png">
</div>
                <form id="addTrainForm">
                    <input type="text" name="train_name" placeholder="Train Name" required><br>
                    <input type="text" name="train_unique_id" placeholder="Train Unique ID" required><br>
                    <!-- <input type="number" name="price_per_km" placeholder="Price per km (₹)" step="0.01" required><br> -->
                    <input type="text" name="source" placeholder="Source" required><br>
                    <input type="text" name="destination" placeholder="Destination" required><br>
                    <button type="submit">Add Train</button>
                </form>

                <h2>Train List</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Train Name</th>
                            <th>Train No</th>
                            <!-- <th>Train Price</th> -->
                            <!-- <th>Price per km (₹)</th> -->
                            <th>Source</th>
                            <th>Destination</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="trainList">
                        <?php
                        $result = $con->query("SELECT * FROM trains");
                        while ($train = $result->fetch_assoc()) {
                            echo "<tr>
                                <td>{$train['train_name']}</td>
                                <td>{$train['train_unique_id']}</td>
                               

                                <td>{$train['source']}</td>
                                <td>{$train['destination']}</td>
                                <td>
                                    <a class='edit' href='editTrain.php?id={$train['id']}'>Edit</a> |
                                    <a href='#' onclick=\"deleteTrain({$train['id']})\">Delete</a>
                                </td>
                            </tr>";
                        }
                        ?>
                    </tbody>
                </table>
                <h2>Fare Calculator</h2>

<!-- Fare Per Km Editable Field -->
<form method="post">
    <label for="fare_per_km">Fare per KM (₹):</label>
    <input type="number" id="fare_per_km" name="fare_per_km" step="0.01" value="<?php echo $fare_per_km; ?>" required>
    <button type="submit" name="update_fare">Update Fare</button>
</form>

<!-- Fare Calculator Form -->
<form id="fareCalculatorForm">
    <label for="distance">Enter Distance (km):</label>
    <input type="number" id="distance" name="distance" step="0.01" required><br>
    <button type="submit">Calculate Fare</button>
</form>

<div id="fareResult"></div>

            </div>
        </div>
    </div>

    <script>
  document.getElementById("add").addEventListener("click", function (e) {
    e.preventDefault();
    
    fetch("addTrain.php") // Load the train form
    .then(response => response.text())
    .then(data => {
        document.getElementById("content").innerHTML = data; // Inject form into content div
        attachFormHandler(); // Attach event listener for form submission
    })
    .catch(error => console.error('Error:', error));
});

function attachFormHandler() {
    const form = document.getElementById("addTrainForm");
    if (!form) return; // Ensure form exists

    form.addEventListener("submit", function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        formData.append("ajax_request", "add_train");

        fetch("adminboard.php", {
            method: "POST",
            body: formData,
        })
        .then(response => response.text())
        .then(data => {
            document.getElementById("content").innerHTML = data; // Show success message
        })
        .catch(error => console.error('Error:', error));
    });
}

        // Handle Add Train Form Submission
        document.getElementById("addTrainForm").addEventListener("submit", function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            formData.append("ajax_request", "add_train");

            fetch("adminboard.php", {
                method: "POST",
                body: formData,
            })
            .then(response => response.text())
            .then(data => {
                alert(data);
                location.reload();
            })
            .catch(error => console.error('Error:', error));
        });

        // Handle Fare Calculator Form Submission
        document.getElementById("fareCalculatorForm").addEventListener("submit", function (e) {
    e.preventDefault();
    const formData = new FormData(this);
    formData.append("ajax_request", "calculate_fare");

    fetch("adminboard.php", {
        method: "POST",
        body: formData,
    })
    .then(response => response.json())
    .then(data => {
        let resultHTML = "<h3>Fare Details:</h3><ul>";
        data.forEach(train => {
            resultHTML += `<li>${train.train_name}: ₹${train.total_fare}</li>`;
        });
        resultHTML += "</ul>";
        document.getElementById("fareResult").innerHTML = resultHTML;
    })
    .catch(error => console.error('Error:', error));
});
             // Handle Delete Train
             function deleteTrain(id) {
            if (!confirm("Are you sure you want to delete this train?")) return;

            const formData = new FormData();
            formData.append("ajax_request", "delete_train");
            formData.append("delete_id", id);

            fetch("adminboard.php", {
                method: "POST",
                body: formData,
            })
            .then(response => response.text())
            .then(data => {
                alert(data);
                location.reload();
            })
            .catch(error => console.error('Error:', error));
        }
        //Handle Station Detail
        document.getElementById("addstation").addEventListener("click", function (e) {
    e.preventDefault();
    
    fetch("station.php")
    .then(response => response.text())
    .then(data => {
        document.getElementById("content").innerHTML = data;
    })
    .catch(error => console.error('Error:', error));
});

// Delete station for station.php
function deleteStation(stationName) {
    if (!confirm("Are you sure you want to delete this station?")) return;

    const formData = new FormData();
    formData.append("ajax_request", "delete_station");
    formData.append("station_name", stationName);

    fetch("station.php", {
        method: "POST",
        body: formData,
    })
    .then(response => response.json())  // Expecting JSON response
    .then(data => {
        alert(data.message); // Show success/error message

        if (data.status === "success") {
            loadStations();  // Reload station list after deletion
        }
    })
    .catch(error => console.error("Error:", error));
}


// Add event listener to "Add Station" button
document.getElementById("addstation").addEventListener("click", function (e) {
    e.preventDefault(); // Prevent default action to stop page reload

    fetch("station.php")
        .then(response => response.text())
        .then(data => {
            document.getElementById("content").innerHTML = data;
            bindAddStationForm(); // Bind the form submission event after loading
        })
        .catch(error => console.error('Error:', error));
});

// Bind the event listener for the add station form
function bindAddStationForm() {
    const form = document.getElementById("addStationForm");
    if (form) {
        form.addEventListener("submit", function (e) {
            e.preventDefault(); // Prevent default form submission (no page reload)

            const formData = new FormData(this);
            formData.append("ajax_request", "add_station");

            fetch("station.php", { // Send AJAX request to station.php
                method: "POST",
                body: formData,
            })
            .then(response => response.text())
            .then(data => {
                alert(data); // Show success or error message
                loadStations(); // Reload station list dynamically after success
                form.reset(); // Reset the form fields
            })
            .catch(error => console.error('Error:', error));
        });
    }
}

// Function to dynamically load the station list after adding a station
function loadStations() {
const stationList = document.getElementById("stationList");
    stationList.innerHTML = ""; // Clear existing list to prevent duplication

    fetch("station.php?ajax=1")
        .then(response => response.text())
        .then(data => {
            document.getElementById("stationList").innerHTML = data;
        })
        .catch(error => console.error('Error loading stations:', error));
}

    </script>


</body>
</html>
