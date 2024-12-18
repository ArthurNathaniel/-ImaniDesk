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

// Handle form submission to book the room
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $client_name = mysqli_real_escape_string($conn, $_POST['client_name']);
    $client_phone = mysqli_real_escape_string($conn, $_POST['client_phone']);
    $room_id = mysqli_real_escape_string($conn, $_POST['room_id']);
    $start_date = mysqli_real_escape_string($conn, $_POST['start_date']);
    $end_date = mysqli_real_escape_string($conn, $_POST['end_date']);
    $payment_mode = mysqli_real_escape_string($conn, $_POST['payment_mode']);
    $receptionist_id = $_SESSION['receptionist_id']; // Assuming the receptionist's ID is stored in session

    // Check if the room has been booked until today and if current time is past 12 PM
    $check_room_end_today = "SELECT * FROM bookings WHERE room_id = '$room_id' AND end_date = CURDATE()";
    $result_end_today = $conn->query($check_room_end_today);

    // Get current time
    $current_time = date('H:i'); // Format as "HH:MM"
    $noon_time = '12:00'; // 12 PM

    if ($result_end_today->num_rows > 0) {
        // Room is booked until today, check if current time is after 12 PM
        if ($current_time >= $noon_time) {
            // The room is available for booking after 12 PM
            echo "<script>
                    var confirmation = confirm('The room is booked until today. Do you want to continue with the booking?');
                    if (confirmation) {
                        document.getElementById('booking-form').submit();
                    } else {
                        alert('Booking canceled.');
                        return false;
                    }
                  </script>";
            exit();
        } else {
            // The room is not yet available for booking (before 12 PM)
            echo "<script>alert('The room is still booked until 12 PM today. You cannot book it yet.');</script>";
            exit();
        }
    }

    // Calculate the number of days for the booking
    $start_timestamp = strtotime($start_date);
    $end_timestamp = strtotime($end_date);
    $days = ceil(($end_timestamp - $start_timestamp) / (60 * 60 * 24));

    // Fetch room price per day
    $room_query = "SELECT price_per_day FROM rooms WHERE id = '$room_id'";
    $room_result = $conn->query($room_query);
    $room = $room_result->fetch_assoc();
    $price_per_day = $room['price_per_day'];

    // Check if the room is already booked for the selected date range
    $check_query = "SELECT * FROM bookings WHERE room_id = '$room_id' AND (
                    (start_date <= '$end_date' AND end_date >= '$start_date')
                    )";
    $check_result = $conn->query($check_query);

    if ($check_result->num_rows > 0) {
        // If a booking exists within the date range, display an error message
        $error_message = "Sorry, the selected room is already booked for these dates.";
    } else {
        // Calculate the total price
        $total_price = $price_per_day * $days;

        // Insert the booking data into the database
        $insert_query = "INSERT INTO bookings (client_name, client_phone, room_id, start_date, end_date, total_price, payment_mode, receptionist_id) 
                         VALUES ('$client_name', '$client_phone', '$room_id', '$start_date', '$end_date', '$total_price', '$payment_mode', '$receptionist_id')";

        if ($conn->query($insert_query) === TRUE) {
            $success_message = "Booking successful!";
        } else {
            $error_message = "Error: " . $conn->error;
        }
    }
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book a Room</title>
    <style>
        /* Your existing styles */
    </style>
</head>
<body>

    <div class="container">
        <h2>Book a Room</h2>
        
        <!-- Display error or success message -->
        <?php
        if (!empty($error_message)) {
            echo "<p class='error'>$error_message</p>";
        }
        if (!empty($success_message)) {
            echo "<p class='success'>$success_message</p>";
        }
        ?>

        <form method="POST" action="" onsubmit="return validateForm()">
            <div class="form-group">
                <label for="client_name">Client Name:</label>
                <input type="text" id="client_name" name="client_name" required>
            </div>
            <div class="form-group">
                <label for="client_phone">Client Phone Number:</label>
                <input type="text" id="client_phone" name="client_phone" required>
            </div>
            <div class="form-group">
                <label for="room_id">Select Room:</label>
                <select name="room_id" id="room_id" required onchange="calculateTotalPrice()">
                    <option value="">Select a room</option>
                    <?php
                    // Populate room options
                    if ($rooms_result->num_rows > 0) {
                        while ($room = $rooms_result->fetch_assoc()) {
                            echo "<option value='" . $room['id'] . "' data-price='" . $room['price_per_day'] . "'>" . $room['room_name'] . " (Room " . $room['room_number'] . ")</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="start_date">Booking Start Date:</label>
                <input type="date" id="start_date" name="start_date" required onchange="calculateTotalPrice()">
            </div>
            <div class="form-group">
                <label for="end_date">Booking End Date:</label>
                <input type="date" id="end_date" name="end_date" required onchange="calculateTotalPrice()">
            </div>
            <div class="form-group">
                <label for="payment_mode">Payment Mode:</label>
                <select name="payment_mode" id="payment_mode" required>
                    <option value="cash">Cash</option>
                    <option value="mobile_money">Mobile Money</option>
                    <option value="bank_transfer">Bank Transfer</option>
                    <option value="cheque">Cheque</option>
                    <option value="other">Other</option>
                </select>
            </div>

            <div class="total-price" id="total-price">Total Price: 0 GHS</div>
            <button type="submit" class="btn">Book Room</button>
        </form>
    </div>

    <script src="./js/book.js"></script>

</body>
</html>
