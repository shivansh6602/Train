<?php 
$servername = "localhost";
$username = "root";
$password = "";
$database = "trainbooking";
include ('../header.html');

// Establish database connection
$con = mysqli_connect($servername, $username, $password, $database);
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle add train
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_train'])) {
    $name = $_POST['train_name'];
    $unique_id = $_POST['train_unique_id'];
   
    $source = $_POST['source'];
    $destination = $_POST['destination'];

    // Prepare and execute query
    $stmt = $con->prepare("INSERT INTO trains (train_name, train_unique_id, source, destination) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdds", $name, $unique_id, $source, $destination); // Fixed the bind_param format

    if ($stmt->execute()) {
        echo "<p>Train added successfully!</p>";
    } else {
        echo "<p>Error adding train: " . $stmt->error . "</p>";
    }

    $stmt->close();
}


// Handle delete train
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // Prepare and execute query
    $stmt = $con->prepare("DELETE FROM trains WHERE id = ?");
    $stmt->bind_param("i", $delete_id); // Bind parameter (i = integer)
    $stmt->execute();
    $stmt->close();

    echo "<p>Train deleted successfully!</p>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Train Management</title>
</head>
<body>
<h1>Add Train</h1>
<form id="addTrainForm" method="POST">
    <input type="text" name="train_name" placeholder="Train Name" required><br>
    <input type="text" name="train_unique_id" placeholder="Train Unique ID" required><br>
    <input type="text" name="source" placeholder="Source Station" required><br>
    <input type="text" name="destination" placeholder="Destination Station" required><br>
    <button type="submit" name="add_train">Add Train</button> <!-- Fix: Added name="add_train" -->
</form>




    <h2>Train List</h2>
    <table border="1">
        <thead>
            <tr>
                
                <th>Train Name</th>
                <th>Unique ID</th>
               
                <th>Source</th>
                <th>Destination</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            // Fetch all trains
            $result = $con->query("SELECT * FROM trains");

            if ($result->num_rows > 0) {
                while ($train = $result->fetch_assoc()) {
                    echo "<tr>
                    
                        <td>{$train['train_name']}</td>
                        <td>{$train['train_unique_id']}</td>
                  
                        <td>{$train['source']}</td>
                        <td>{$train['destination']}</td>
                        <td>
                            <a href='editTrain.php?id={$train['id']}'>Edit</a> |
                            <a href='addTrain.php?delete_id={$train['id']}' onclick=\"return confirm('Are you sure?')\">Delete</a>
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No trains found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>
