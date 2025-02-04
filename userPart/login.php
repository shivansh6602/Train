<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <style>
        /* General Styling */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            height: 100vh;
            display: flex;
            padding: 5%; /* Maintain consistent padding */
            background-color: #1f1a16;
            box-sizing: border-box;
        }

        .container {
            display: flex;
            width: 91%;
            height: calc(100% - 10%); /* Adjust height to account for padding */
        }

        /* Left Section - Background Image */
        .left-section {
    flex: 1;
    background-image: url(./images/trainlayout.jpg);
    background-size: cover;
    background-position: center;
    height: 75vh;
    border-top-left-radius: 10px;
    border-bottom-left-radius: 10px;
    margin-left: 17%;
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
            margin-top: 27%;
            font-size: 29px;
        }

        form {
    display: flex;
    flex-direction: column;
    width: 143%;
    padding: 20px;
    height: 73vh;
    background-color: black;
    border-top-right-radius: 10px;
    border-bottom-right-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    margin-left: -42%;
    margin-top: 9%;
    padding-top: 0%;
}

        label {
            margin-bottom: 7px;
            color: white;
        }

        input, select {
            border-radius: 5px;
            border: 1px solid orange;
            margin-bottom: 7px;
        }

        input[type="text"], input[type="password"], input[type="submit"] {
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
            margin-top: 5%;
            font-size: 16px;
        }
        p {
    color: darkgray;
}
a {
    color: #ff7300;
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
                <form action="userboard.php" method="post">
                    <h3>Login Form</h3>
                    <label for="mail">Email:</label>
                    <input type="text" name="mail" placeholder="Enter Your Mail" required>
                    <label for="pass">Password:</label>
                    <input type="password" name="pass" placeholder="Enter Your Passsword" required>
                    <input type="submit" value="Login" name="btn">
                    <p>Don't have an account? <a href="register.php">Register here</a></p>
                </form>
            </div>
        </div>
    </div>
</body>
</html>