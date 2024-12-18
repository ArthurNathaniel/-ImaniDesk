<?php
include('db.php'); // Include database connection

session_start();

// Check if the receptionist is logged in
if (!isset($_SESSION['receptionist_id'])) {
    header("Location: login_receptionist.php"); // Redirect to login if not logged in
    exit();
}

// Fetch rooms from the database
$query = "SELECT * FROM rooms";
$rooms_result = $conn->query($query);

// Handle form submission to check availability
$availability_message = "";
$available_rooms = [];
$booked_rooms = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the start and end dates
    $start_date = mysqli_real_escape_string($conn, $_POST['start_date']);
    $end_date = mysqli_real_escape_string($conn, $_POST['end_date']);
    
    // Check if start and end dates are valid
    if (strtotime($start_date) > strtotime($end_date)) {
        $availability_message = "Start date must be earlier than the end date.";
    } else {
        $availability_message = "Checking availability for the selected dates...";

        // Query to find rooms that are booked during the given date range
        $availability_query = "SELECT * FROM bookings WHERE 
            (start_date <= '$end_date' AND end_date >= '$start_date')";
        $availability_result = $conn->query($availability_query);
        
        // Get the booked rooms
        while ($row = $availability_result->fetch_assoc()) {
            $booked_rooms[] = [
                'room_id' => $row['room_id'],
                'start_date' => $row['start_date'],
                'end_date' => $row['end_date']
            ];
        }

        // Check which rooms are available
        $rooms_result = $conn->query($query); // Re-fetch rooms
        while ($room = $rooms_result->fetch_assoc()) {
            $is_booked = false;
            foreach ($booked_rooms as $booked_room) {
                if ($booked_room['room_id'] == $room['id']) {
                    $is_booked = true;
                    break;
                }
            }

            if (!$is_booked) {
                $available_rooms[] = $room;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Availability Check</title>
    <style>
        /* Add your styles here */
        .container {
            width: 80%;
            margin: 0 auto;
        }
        .form-group {
            margin: 20px 0;
        }
        .btn {
            padding: 10px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #45a049;
        }
        .availability-status {
            font-weight: bold;
        }
        .available {
            color: green;
        }
        .booked {
            color: red;
        }
        .booked-room-details {
            font-size: 0.9em;
            color: gray;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Check Room Availability</h2>
        
        <!-- Display availability check result -->
        <?php
        if (!empty($availability_message)) {
            echo "<p>$availability_message</p>";
        }
        ?>

        <!-- Form to check room availability -->
        <form method="POST" action="">
            <div class="form-group">
                <label for="start_date">Start Date:</label>
                <input type="date" id="start_date" name="start_date" required>
            </div>
            <div class="form-group">
                <label for="end_date">End Date:</label>
                <input type="date" id="end_date" name="end_date" required>
            </div>
            <button type="submit" class="btn">Check Availability</button>
        </form>

        <!-- Display available and booked rooms -->
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            echo "<h3>Room Availability for the selected dates:</h3>";
            
            // Check if rooms are available or booked
            if (count($available_rooms) > 0) {
                echo "<h4>Available Rooms:</h4><ul>";
                foreach ($available_rooms as $room) {
                    echo "<li><strong>Room " . $room['room_number'] . ": " . $room['room_name'] . "</strong> - <span class='availability-status available'>Available</span></li>";
                }
                echo "</ul>";
            } else {
                echo "<p>No rooms available for the selected dates.</p>";
            }

            if (count($booked_rooms) > 0) {
                echo "<h4>Booked Rooms:</h4><ul>";
                // Check all rooms and display those that are booked
                foreach ($booked_rooms as $booked_room) {
                    $room_query = "SELECT * FROM rooms WHERE id = " . $booked_room['room_id'];
                    $room_result = $conn->query($room_query);
                    $room = $room_result->fetch_assoc();

                    echo "<li><strong>Room " . $room['room_number'] . ": " . $room['room_name'] . "</strong> - <span class='availability-status booked'>Booked</span><br>
                        <div class='booked-room-details'>Booked from: " . date("d-m-Y", strtotime($booked_room['start_date'])) . " to " . date("d-m-Y", strtotime($booked_room['end_date'])) . "<br>
                        Available again on: " . date("d-m-Y", strtotime($booked_room['end_date'])) . "</div></li>";
                }
                echo "</ul>";
            }
        }
        ?>

    </div>

</body>
</html>
