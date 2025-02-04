<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "trainbooking";

// Database connection
$con = new mysqli($servername, $username, $password, $database);
if ($con->connect_error) {
    die("Connection Failed: " . $con->connect_error);
}

// Fetch train details
$train_query = "SELECT id, train_name FROM trains";
$train_result = $con->query($train_query);
?>

<form method="POST" action="process_booking.php">
    <label for="train">Select Train:</label>
    <select id="train" name="train" required onchange="fetchStations(this.value)">
        <option value="">Select Train</option>
        <?php while ($train = $train_result->fetch_assoc()) { ?>
            <option value="<?php echo $train['id']; ?>"><?php echo $train['train_name']; ?></option>
        <?php } ?>
    </select>

    <div id="stationSelection" style="display:none;">
        <label for="from_station">From Station:</label>
        <select id="from_station" name="from_station" required onchange="updateToStations()">
            <option value="">Select Departure</option>
        </select>

        <label for="to_station">To Station:</label>
        <select id="to_station" name="to_station" required>
            <option value="">Select Destination</option>
        </select>
    </div>

    <input type="submit" value="Book Train">
</form>

<script>
function fetchStations(trainId) {
    if (!trainId) {
        console.error("Train ID is missing.");
        return;
    }

    fetch('fetchstation.php?train_id=' + trainId)
        .then(response => response.json())
        .then(data => {
            console.log("Received station data:", data);  // Debugging line

            let fromSelect = document.getElementById('from_station');
            let toSelect = document.getElementById('to_station');

            // Clear existing options
            fromSelect.innerHTML = '<option value="">Select Departure</option>';
            toSelect.innerHTML = '<option value="">Select Destination</option>';

            if (data.error) {
                alert(data.error);
                return;
            }

            if (!Array.isArray(data) || data.length === 0) {
                console.error("Unexpected response format or empty stations list:", data);
                return;
            }

            // Populate both dropdowns with station names
            data.forEach(station => {
                let option = document.createElement('option');
                option.value = station.station_name;
                option.textContent = station.station_name;
                fromSelect.appendChild(option);
            });

            // Make sure the section is visible
            document.getElementById('stationSelection').style.display = 'block';
        })
        .catch(error => console.error('Error fetching stations:', error));
}

// Prevent selecting the same station for "To Station"
function updateToStations() {
    let fromSelect = document.getElementById('from_station');
    let toSelect = document.getElementById('to_station');
    let selectedFrom = fromSelect.value;

    // Get all options from "From Station"
    let allOptions = Array.from(fromSelect.options);

    // Clear "To Station" options except the default one
    toSelect.innerHTML = '<option value="">Select Destination</option>';

    // Add all options except the selected "From Station"
    allOptions.forEach(option => {
        if (option.value !== selectedFrom && option.value !== "") {
            let newOption = document.createElement('option');
            newOption.value = option.value;
            newOption.textContent = option.textContent;
            toSelect.appendChild(newOption);
        }
    });
}
</script>
