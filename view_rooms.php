<?php
include('db.php'); // Include database connection

// Start session and check if the admin is logged in
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php"); // Redirect to login if not logged in as admin
    exit();
}

// Fetch all rooms from the database
$query = "SELECT * FROM rooms";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Rooms</title>
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
    </style>
</head>
<body>
    <h2>Registered Rooms</h2>
    <table>
        <tr>
            <th>Room ID</th>
            <th>Room Name</th>
            <th>Room Number</th>
            <th>Price Per Day</th>
            <th>Actions</th> <!-- Add Actions Column -->
        </tr>

        <?php
        // Check if there are any rooms in the database
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $row['room_name'] . "</td>";
                echo "<td>" . $row['room_number'] . "</td>";
                echo "<td>" . $row['price_per_day'] . " GHS</td>";
                echo "<td><a href='edit_room.php?id=" . $row['id'] . "'>Edit</a></td>"; // Add Edit link
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No rooms registered.</td></tr>";
        }
        ?>
    </table>
</body>
</html>
