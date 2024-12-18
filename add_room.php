<?php
include('db.php'); // Include database connection

// Start session and check if the admin is logged in
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php"); // Redirect to login if not logged in as admin
    exit();
}

// Initialize variables
$room_name = "";
$room_number = "";
$price_per_day = "";
$error_message = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $room_name = $_POST['room_name'];
    $room_number = $_POST['room_number'];
    $price_per_day = $_POST['price_per_day'];

    // Sanitize inputs
    $room_name = mysqli_real_escape_string($conn, $room_name);
    $room_number = mysqli_real_escape_string($conn, $room_number);
    $price_per_day = mysqli_real_escape_string($conn, $price_per_day);

    // Validation checks
    if (empty($room_name) || empty($room_number) || empty($price_per_day)) {
        $error_message = "All fields are required!";
    } elseif (!is_numeric($room_number) || !is_numeric($price_per_day)) {
        $error_message = "Room number and price per day must be numeric!";
    } else {
        // Check if the room number already exists
        $check_query = "SELECT * FROM rooms WHERE room_number='$room_number'";
        $check_result = $conn->query($check_query);
        if ($check_result->num_rows > 0) {
            $error_message = "Room number already exists!";
        } else {
            // Insert room into the database
            $insert_query = "INSERT INTO rooms (room_name, room_number, price_per_day) VALUES ('$room_name', '$room_number', '$price_per_day')";
            if ($conn->query($insert_query) === TRUE) {
                header("Location: view_rooms.php"); // Redirect to the rooms list page
                exit();
            } else {
                $error_message = "Error: " . $conn->error;
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
    <title>Register Room</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 50px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        input[type="text"], input[type="number"], input[type="text"], input[type="decimal"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }
        .btn {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #45a049;
        }
        .error {
            color: red;
            font-size: 14px;
            margin-top: 10px;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Register Room</h2>
        <?php
        // Display error message if there is any
        if (!empty($error_message)) {
            echo "<p class='error'>$error_message</p>";
        }
        ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="room_name">Room Name:</label>
                <input type="text" id="room_name" name="room_name" value="<?php echo $room_name; ?>" required>
            </div>
            <div class="form-group">
                <label for="room_number">Room Number:</label>
                <input type="number" id="room_number" name="room_number" value="<?php echo $room_number; ?>" required>
            </div>
            <div class="form-group">
                <label for="price_per_day">Price Per Day (GHS):</label>
                <input type="number" id="price_per_day" name="price_per_day" value="<?php echo $price_per_day; ?>" required>
            </div>
            <button type="submit" class="btn">Register Room</button>
        </form>
    </div>

</body>
</html>
