<?php
include('db.php'); // Include the database connection

session_start();

// Check if the receptionist is logged in
if (!isset($_SESSION['receptionist_id'])) {
    header("Location: login_receptionist.php"); // Redirect to login page
    exit();
}

$total_revenue = 0;
$revenue_by_payment_mode = [];
$booking_list = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the selected booking date
    $booking_date = mysqli_real_escape_string($conn, $_POST['booking_date']);

    // Query to get booking details and calculate total revenue for the selected booking date
    $revenue_query = "
        SELECT b.id, b.client_name, r.room_name, r.room_number, b.start_date, b.end_date, b.total_price, DATE(b.booking_date) as booking_date, b.payment_mode
        FROM bookings b 
        JOIN rooms r ON b.room_id = r.id 
        WHERE DATE(b.booking_date) = '$booking_date'
    ";

    $result = $conn->query($revenue_query);

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $booking_list[] = $row;
            $total_revenue += $row['total_price'];

            // Calculate total revenue by payment mode
            $payment_mode = $row['payment_mode'];
            if (!isset($revenue_by_payment_mode[$payment_mode])) {
                $revenue_by_payment_mode[$payment_mode] = 0;
            }
            $revenue_by_payment_mode[$payment_mode] += $row['total_price'];
        }
    } else {
        $error_message = "Error calculating revenue: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Revenue Report</title>
    <style>
        .container {
            width: 80%;
            margin: 0 auto;
            font-family: Arial, sans-serif;
        }
        h2 {
            text-align: center;
            margin-top: 20px;
        }
        .form-group {
            margin: 20px 0;
        }
        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .form-group input[type="date"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
        }
        .btn {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            text-align: center;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }
        .btn:hover {
            background-color: #45a049;
        }
        .revenue-message {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: center;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Revenue Report</h2>

    <!-- Display error message -->
    <?php
    if (!empty($error_message)) {
        echo "<p class='revenue-message error'>$error_message</p>";
    }
    ?>

    <!-- Form to select date for revenue report -->
    <form method="POST" action="">
        <div class="form-group">
            <label for="booking_date">Booking Date:</label>
            <input type="date" id="booking_date" name="booking_date" required>
        </div>
        <button type="submit" class="btn">Generate Report</button>
    </form>

    <!-- Booking List Table -->
    <?php if (!empty($booking_list)) { ?>
    <table>
        <tr>
            <th>#</th>
            <th>Client Name</th>
            <th>Room Name</th>
            <th>Room Number</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Booking Date</th>
            <th>Payment Mode</th>
            <th>Total Price (GHS)</th>
        </tr>
        <?php foreach ($booking_list as $index => $booking) { ?>
            <tr>
                <td><?php echo $index + 1; ?></td>
                <td><?php echo $booking['client_name']; ?></td>
                <td><?php echo $booking['room_name']; ?></td>
                <td><?php echo $booking['room_number']; ?></td>
                <td><?php echo date('F j, Y', strtotime($booking['start_date'])); ?></td>
                <td><?php echo date('F j, Y', strtotime($booking['end_date'])); ?></td>
                <td><?php echo date('F j, Y', strtotime($booking['booking_date'])); ?></td>
                <td><?php echo ucfirst($booking['payment_mode']); ?></td>
                <td><?php echo number_format($booking['total_price'], 2); ?> GHS</td>
            </tr>
        <?php } ?>
    </table>

    <!-- Total Revenue Display (below the table) -->
    <p class='revenue-message'>Total Revenue for <?php echo date('F j, Y', strtotime($booking_date)); ?>: <?php echo number_format($total_revenue, 2); ?> GHS</p>

    <!-- Revenue by Payment Mode Display -->
    <h3>Revenue Breakdown by Payment Mode</h3>
    <ul>
        <?php foreach ($revenue_by_payment_mode as $mode => $amount) { ?>
            <li><?php echo ucfirst($mode); ?>: <?php echo number_format($amount, 2); ?> GHS</li>
        <?php } ?>
    </ul>

    <?php } else if (!empty($booking_date)) { ?>
        <p class='revenue-message'>No bookings found for <?php echo date('F j, Y', strtotime($booking_date)); ?>.</p>
    <?php } ?>
</div>

</body>
</html>
