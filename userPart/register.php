<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <style>
        /* General Styling */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            height: 100vh;
            padding: 5%; /* Maintain consistent padding */
            background-color: #1f1a16;
            box-sizing: border-box;
        }

        .container {
            display: flex;
            width: 100%;
            height: calc(100% - 10%); /* Adjust height to account for padding */
        }

        /* Left Section - Background Image */
        .left-section {
            flex: 1; /* Take up 50% of the width */
            background-image: url('./images/trainlayout.jpg');
            background-size: cover; /* Ensure the image covers the container */
            background-position: center; /* Center the image */
            height: 89vh; /* Match the height of the container */
            border-top-left-radius: 10px; 
            border-bottom-left-radius: 10px;
            margin-left: 13%;
            margin-top: -36px;
        }

        /* Right Section - Form */
        .right-section {
            flex: 1; /* Take up 50% of the width */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%; /* Match the height of the container */
        }

        h3 {
            text-align: center;
            color: white;
            font-weight: bold;
        }

        form {
            display: flex;
            flex-direction: column;
            width: 143%;
            padding: 20px;
            height: 100%;
            background-color: black;
            border-top-right-radius: 10px;
            border-bottom-right-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-left: -59%;
            margin-top: 23%;
            padding-top: 0%;
        }

        label {
            margin-bottom: 2px;
            color: white;
        }

        input, select {
            border-radius: 5px;
            border: 1px solid orange;
            margin-bottom: 7px;
        }

        input[type="text"], input[type="number"], input[type="date"], select, input[type="submit"], input[type="email"] {
            height: 40px;
            padding: 0 10px;
            background-color: black;
            color: darkgray;
        }

        input#pass {
            height: 40px;
            padding: 0 10px;
            background-color: black;
            color: darkgray;
        }
        input#number {
            height: 40px;
            padding: 0 10px;
            background-color: black;
            color: darkgray;
        }
        input[type="submit"] {
            background-color: #ff7300;
            color: #fff;
            border: none;
            cursor: pointer;
            border: 1px solid #ff7300;
        }

        .gender-options {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }

        .gender-options label {
            margin-right: 10px;
        }
        .error-message {
            text-align: center;
            margin-top: 6%;
            margin-left: 44%;
            color: red;
            font-weight: 800;
        }
        .done {
            text-align: center;
            margin-top: 6%;
            margin-left: 44%;
            color: green;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Left Section -->
        <div class="left-section"></div>

        <!-- Right Section -->
        <div class="right-section">
            <div>
                <form method="POST">
                    <h3>Indian Railway Registration</h3>
                    <label for="text">Full Name:</label>
                    <input type="text" placeholder="Your Name" name="fname" required>
                    <label for="mail">Email:</label>
                    <input type="email" placeholder="Your Email" name="mail" required>
                    <label for="number">Phone Number:</label>
                    <input type="tel" placeholder="Number" name="number" id="number" required>
                    <label for="pan">PAN No.:</label>
                    <input type="text" placeholder="AAAAA0000A" name="pan"  title="Please enter a valid PAN number (e.g., AAAAA0000A)" required>
                    <label for="dob">DOB:</label>
                    <input type="date" name="dob" required>
                   <label for="pass">Password:</label>
                   <input type="password" name="pass" id="pass">
                    <label for="gender">Gender:</label>
                    <div class="gender-options">
                        <label for="gender-male">
                            <input type="radio" name="gender" value="Male" id="gender-male" required> Male
                        </label>
                        <label for="gender-female">
                            <input type="radio" name="gender" value="Female" id="gender-female"> Female
                        </label>
                        <label for="gender-other">
                            <input type="radio" name="gender" value="Other" id="gender-other"> Other
                        </label>
                    </div>
                    <label for="address">Address:</label>
                    <input type="text" placeholder="Your Address" name="address" required>
                    <input type="submit" value="Register" name="btn">
                </form>
            </div>
        </div>
    </div>
<?php
$servername ="localhost";
$username = "root";
$password = "";
$database = "trainbooking";
$con = mysqli_connect($servername, $username, $password, $database);
if(!$con) {
    die("Error Deleting Record:" .mysqli_connect_error());
}
if(isset($_POST['btn'])){
    $fname = $_POST['fname'];
    $mail = $_POST['mail'];
    $number = $_POST['number'];
    $pan = $_POST['pan'];
    $dob = $_POST['dob'];
    $pass = $_POST['pass'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];

    $check_mail_sql = "SELECT * FROM `registration` WHERE `Email` = '$mail'";
    $check_number_sql = "SELECT * FROM `registration` WHERE `Phone Number` = '$number'";
    $check_pan_sql = "SELECT * FROM `registration` WHERE `PAN No` = '$pan'";

    $check_mail_result = mysqli_query($con, $check_mail_sql);
    $check_number_result = mysqli_query($con, $check_number_sql);
    $check_pan_result = mysqli_query($con, $check_pan_sql);

    if(mysqli_num_rows($check_mail_result) > 0) {
        echo "<div class='error-message'>Email Already Exist</div>";
    } elseif(mysqli_num_rows($check_number_result) > 0) {
        echo "<div class='error-message'>Number Exist</div>";
    } elseif(mysqli_num_rows($check_pan_result) > 0) {
        echo "<div class='error-message'>Pan Exist</div>";
    }
    else {
        $sql = "INSERT INTO `registration`(`Full Name`, `Email`, `Phone Number`, `PAN No`, `DOB`, `Password`, `Gender`, `Address`)
         VALUES ('$fname','$mail','$number','$pan','$dob','$pass','$gender','$address')";

        if (mysqli_query($con,$sql)) {
            echo "<div class='done'>Registration Done</div>";
        } else {
            echo "Error" .$sql . "<br>" . mysqli_error($con);
        }
        mysqli_close($con);
    }
}
?>
</body>
</html>