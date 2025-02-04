
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="style1.css">
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
</head>
<body>
    <div class="dashboard-container">
        <aside class="sidebar"> 
            <img src="https://cdn-icons-png.flaticon.com/128/1042/1042307.png">
            <h2>User Dashboard</h2>
            <ul>
                <li>
                <a href="bookTrain.php">Book a Train</a>
                <img src="https://cdn-icons-png.flaticon.com/128/2539/2539552.png" id="images"></li>
                <li><a href="booking.php">My Bookings</a>
                <img src="https://cdn-icons-png.flaticon.com/128/9588/9588518.png" id="images1"></li>
                <li><a href="confirmed.php">Tickets Confirmed</a>
                <img src="https://cdn-icons-png.flaticon.com/128/16115/16115554.png" id="images2"></li>
                <li><a href="profile.php">Profile</a>
                <img src="https://cdn-icons-png.flaticon.com/128/4795/4795897.png" id="images3"></li>
                </li>
                <li><a href="#">Logout</a>
                <img src="https://cdn-icons-png.flaticon.com/128/5509/5509486.png" id="images4"></li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header>
                <h1>Welcome, [User Name]!</h1>
                <p>Here's your dashboard overview:</p>
            </header>

            <!-- Summary Section -->
            <section class="summary">
    <div class="card">
        <h3>Total Bookings</h3>
        <p id="total-bookings">0</p>
    </div>
    <div class="card">
        <h3>Upcoming Trips</h3>
        <p id="upcoming-trips">0</p>
    </div>
    <div class="card">
        <h3>Email</h3>
        <p id="user-email">user@example.com</p>
    </div>
</section>

<section class="booking-history">
    <h3>My Bookings</h3>
    <table id="booking-history-table">
        <thead>
            <tr>
                <th>Train Name</th>
                <th>Source</th>
                <th>Destination</th>
                <th>Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            </tbody>
    </table>
</section>
        </main>
    </div>
   <?php 
?>
</body>
</html>
