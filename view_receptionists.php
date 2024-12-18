<?php
include('db.php'); // Include database connection

// Start session and check if the admin is logged in
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php"); // Redirect to login if not logged in as admin
    exit();
}

// Fetch all receptionists from the database
$query = "SELECT * FROM receptionists";
$result = $conn->query($query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Receptionists</title>
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
    <h2>Registered Receptionists</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Phone Number</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>

        <?php
        // Check if there are any receptionists in the database
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $row['full_name'] . "</td>";
                echo "<td>" . $row['email'] . "</td>";
                echo "<td>" . $row['phone_number'] . "</td>";
                echo "<td>" . ucfirst($row['status']) . "</td>";
                // Toggle between Disable and Activate action
                if ($row['status'] == 'active') {
                    echo "<td><a href='toggle_receptionist.php?id=" . $row['id'] . "&action=disable'>Disable</a> | <a href='edit_receptionist.php?id=" . $row['id'] . "'>Edit</a></td>";
                } else {
                    echo "<td><a href='toggle_receptionist.php?id=" . $row['id'] . "&action=activate'>Activate</a> | <a href='edit_receptionist.php?id=" . $row['id'] . "'>Edit</a></td>";
                }
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No receptionists registered.</td></tr>";
        }
        ?>
    </table>
</body>
</html>
