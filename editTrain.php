<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "trainbooking";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if 'id' is provided
if (isset($_GET['id'])) {
    $train_id = intval($_GET['id']);

    $stmt = $conn->prepare("SELECT * FROM trains WHERE id = ?");
    $stmt->bind_param("i", $train_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        ?>

<div class="edit-train-form">
    <h1>Edit Train</h1>
    <form method="POST" action="">  <input type="hidden" name="train_id" value="<?php echo htmlspecialchars($row['id']); ?>">
        <div class="form-group">
            <label for="train_name">Train Name</label>
            <input type="text" id="train_name" name="train_name" value="<?php echo htmlspecialchars($row['train_name']); ?>" required>
        </div>
        <div class="form-group">
            <label for="train_unique_id">Train Unique ID</label>
            <input type="text" id="train_unique_id" name="train_unique_id" value="<?php echo htmlspecialchars($row['train_unique_id']); ?>" required>
        </div>
       
       
        <div class="form-group">
            <label for="source">Source</label>
            <input type="text" id="source" name="source" value="<?php echo htmlspecialchars($row['source']); ?>" required>
        </div>
        <div class="form-group">
            <label for="destination">Destination</label>
            <input type="text" id="destination" name="destination" value="<?php echo htmlspecialchars($row['destination']); ?>" required>
        </div>
        <input type="submit" name="update_train" value="Update Train">
    </form>
</div>

        <?php
    } else {
        echo "Train not found.";
    }
    $stmt->close();
} else {
    echo "Invalid train ID.";
}

// Handle form submission for updating train (Corrected)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_train'])) {
    $trainId = intval($_POST['train_id']);
    $trainName = $_POST['train_name'];
    $trainUniqueId = $_POST['train_unique_id'];

    $source = ($_POST['source']);
    $destination = $_POST['destination'];
   
 // Check for duplicate train_unique_id (Improved)
 $checkStmt = $conn->prepare("SELECT COUNT(*) FROM trains WHERE train_unique_id = ? AND id != ?");
 $checkStmt->bind_param("si", $trainUniqueId, $trainId);
 $checkStmt->execute();
 $checkStmt->bind_result($count);
 $checkStmt->fetch();
 $checkStmt->close();

 if ($count > 0) {
     echo "<div class='error-message'>Error: Train Unique ID already exists!</div>"; // Display error message
 } else {
     // Update train data securely
     $updateStmt = $conn->prepare("UPDATE trains SET train_name = ?, train_unique_id = ?, source = ?, destination = ? WHERE id = ?");
     $updateStmt->bind_param("ssssi", $trainName, $trainUniqueId, $source, $destination, $trainId); // Corrected bind_param
     
     if ($updateStmt->execute()) {
         // Success (Redirect with message)
         header("Location: adminboard.php?message=Train updated succesxzczzsfully!");
         exit();
     } else {
         echo "<div class='error-message'>Error updating train: " . $updateStmt->error . "</div>"; // More specific error message
     }
     $updateStmt->close();
 }
}

$conn->close();
?>
<style>
     
    /* General styling for the form container */
    .edit-train-form {
        max-width: 500px;
        margin: 50px auto;
        padding: 20px;
        background-color: levendar;
        border-radius: 8px;
        box-shadow: 0 5px 36px;;
        font-family: Arial, sans-serif;
    }

    .edit-train-form h1 {
        text-align: center;
        color: #333;
        margin-bottom: 20px;
    }

    /* Styling for labels and inputs */
    .edit-train-form label {
        display: block;
        font-size: 14px;
        margin-bottom: 5px;
        color: #555;
    }

    .edit-train-form input[type="text"],
    .edit-train-form input[type="number"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ddd;
        border-radius: 5px;
        box-sizing: border-box;
        font-size: 14px;
        transition: all 0.3s ease-in-out;
    }

    .edit-train-form input[type="text"]:focus,
    .edit-train-form input[type="number"]:focus {
        border-color: #3498db;
        box-shadow: 0 0 8px rgba(52, 152, 219, 0.2);
    }

    /* Styling the submit button */
    .edit-train-form input[type="submit"] {
        width: 100%;
        padding: 12px;
        background-color: #2c3e50;
        color: white;
        font-size: 16px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
        box-shadow: 0 2px 5px black;
    }

    .edit-train-form input[type="submit"]:hover {
        background-color: #1f78b4;
    }

    /* Form group for better spacing */
    .edit-train-form .form-group {
        margin-bottom: 15px;
    }
    body {
    background-color: lavender;
}
</style>

