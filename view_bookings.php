<?php
include('db.php'); // Include database connection


session_start();

// Check if the receptionist is logged in
if (!isset($_SESSION['receptionist_id'])) {
    header("Location: login_receptionist.php"); // Redirect to login if not logged in
    exit();
}
// Fetch all bookings from the database
$query = "SELECT b.id, b.client_name, b.client_phone, b.start_date, b.end_date, b.total_price, b.payment_mode, r.room_name, r.room_number, r.price_per_day, b.booking_date, b.receptionist_id, s.full_name AS receptionist_name 
          FROM bookings b 
          JOIN rooms r ON b.room_id = r.id
          JOIN receptionists s ON b.receptionist_id = s.id";
$result = $conn->query($query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Bookings</title>
    <style>
        table {
            width: 80%;
            border-collapse: collapse;
            margin: 20px auto;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        .status {
            padding: 5px;
            background-color: #4CAF50;
            color: white;
            border-radius: 5px;
        }
        .payment-mode {
            padding: 5px;
            background-color: #2196F3;
            color: white;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <h2>Booking Details</h2>
    <table>
        <tr>
            <th>Booking ID</th>
            <th>Client Name</th>
            <th>Client Phone</th>
            <th>Room Name</th>
            <th>Room Number</th>
            <th>Booking Start Date</th>
            <th>Booking End Date</th>
            <th>Total Price</th>
            <th>Payment Mode</th>
            <th>Booking Date</th>
            <th>Receptionist</th>
        </tr>

        <?php
        // Check if there are any bookings in the database
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $row['client_name'] . "</td>";
                echo "<td>" . $row['client_phone'] . "</td>";
                echo "<td>" . $row['room_name'] . "</td>";
                echo "<td>" . $row['room_number'] . "</td>";
                echo "<td>" . $row['start_date'] . "</td>";
                echo "<td>" . $row['end_date'] . "</td>";
                echo "<td>" . number_format($row['total_price'], 2) . " GHS</td>";
                echo "<td><span class='payment-mode'>" . ucfirst($row['payment_mode']) . "</span></td>";
                echo "<td>" . $row['booking_date'] . "</td>";
                echo "<td>" . $row['receptionist_name'] . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='11'>No bookings found.</td></tr>";
        }
        ?>
    </table>
</body>
</html>
