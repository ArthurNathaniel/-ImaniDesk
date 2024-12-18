<?php
include('db.php'); // Include database connection

session_start();

// Check if the receptionist is logged in
if (!isset($_SESSION['receptionist_id'])) {
    header("Location: login_receptionist.php"); // Redirect to login if not logged in
    exit();
}

$total_revenue = 0; // Default total revenue

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // Validate input
    if (!empty($start_date) && !empty($end_date)) {
        // Query to calculate total revenue within the date range
        $query = "SELECT SUM(total_price) AS total_revenue 
                  FROM bookings 
                  WHERE booking_date BETWEEN ? AND ?";
        
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $start_date, $end_date);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            $total_revenue = $row['total_revenue'] ? $row['total_revenue'] : 0;
        }

        $stmt->close();
    } else {
        echo "<p style='color: red; text-align: center;'>Please select both start and end dates.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Query Revenue by Date</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            padding: 20px;
        }
        .container {
            width: 50%;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 20px;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        label {
            margin-bottom: 8px;
            font-weight: bold;
        }
        input[type="date"] {
            padding: 10px;
            margin-bottom: 20px;
            width: 80%;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #45a049;
        }
        .total-revenue {
            text-align: center;
            margin-top: 20px;
            font-size: 20px;
            font-weight: bold;
            color: #4CAF50;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Query Revenue by Date</h2>

    <!-- Query Form for Start Date and End Date -->
    <form method="POST" action="">
        <label for="start_date">Start Date</label>
        <input type="date" name="start_date" id="start_date" value="<?php echo isset($start_date) ? $start_date : ''; ?>" required>

        <label for="end_date">End Date</label>
        <input type="date" name="end_date" id="end_date" value="<?php echo isset($end_date) ? $end_date : ''; ?>" required>

        <button type="submit">Check Revenue</button>
    </form>

    <?php if ($_SERVER["REQUEST_METHOD"] == "POST") : ?>
        <div class="total-revenue">
            <p>Total Revenue from <strong><?php echo $start_date; ?></strong> to <strong><?php echo $end_date; ?></strong> is:</p>
            <p style="font-size: 24px;">GHS <?php echo number_format($total_revenue, 2); ?></p>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
