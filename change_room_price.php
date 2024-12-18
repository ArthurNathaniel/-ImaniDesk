<?php
include('db.php'); // Include database connection

// Start session and check if the admin is logged in
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php"); // Redirect to login if not logged in as admin
    exit();
}

// Initialize variables
$room_id = "";
$new_price = "";
$error_message = "";
$success_message = "";

// Fetch all rooms for the select dropdown
$query = "SELECT id, room_name, room_number FROM rooms";
$rooms_result = $conn->query($query);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $room_id = $_POST['room_id'];
    $new_price = $_POST['new_price'];

    // Sanitize inputs
    $room_id = mysqli_real_escape_string($conn, $room_id);
    $new_price = mysqli_real_escape_string($conn, $new_price);

    // Validation checks
    if (empty($room_id) || empty($new_price)) {
        $error_message = "All fields are required!";
    } elseif (!is_numeric($new_price)) {
        $error_message = "Price must be numeric!";
    } else {
        // Update room price in the database
        $update_query = "UPDATE rooms SET price_per_day='$new_price' WHERE id='$room_id'";
        if ($conn->query($update_query) === TRUE) {
            $success_message = "Room price updated successfully!";
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
    <title>Change Room Price</title>
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
        select, input[type="number"] {
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
        .error, .success {
            color: red;
            font-size: 14px;
            margin-top: 10px;
        }
        .success {
            color: green;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Change Room Price</h2>
        <?php
        // Display error message if there is any
        if (!empty($error_message)) {
            echo "<p class='error'>$error_message</p>";
        }
        // Display success message if the price is updated successfully
        if (!empty($success_message)) {
            echo "<p class='success'>$success_message</p>";
        }
        ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="room_id">Select Room:</label>
                <select id="room_id" name="room_id" required>
                    <option value="">Select a room</option>
                    <?php
                    // Display all rooms in the dropdown with room name and number
                    while($row = $rooms_result->fetch_assoc()) {
                        echo "<option value='" . $row['id'] . "'>" . $row['room_name'] . " (Room No: " . $row['room_number'] . ")</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="new_price">New Price Per Day (GHS):</label>
                <input type="number" id="new_price" name="new_price" value="<?php echo $new_price; ?>" required>
            </div>
            <button type="submit" class="btn">Update Price</button>
        </form>
    </div>

</body>
</html>
