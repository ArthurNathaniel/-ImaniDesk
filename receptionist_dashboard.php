<?php
session_start();

// Check if the receptionist is logged in
if (!isset($_SESSION['receptionist_id'])) {
    header("Location: login_receptionist.php"); // Redirect to login if not logged in
    exit();
}

// Get receptionist name from session
$receptionist_name = $_SESSION['receptionist_name'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receptionist Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 50px;
            background-color: #f4f4f4;
        }
        .dashboard-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 30px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
        }
        .welcome {
            text-align: center;
            margin-bottom: 30px;
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
    </style>
</head>
<body>

    <div class="dashboard-container">
        <h2>Receptionist Dashboard</h2>
        <div class="welcome">
            <p>Welcome, <?php echo $receptionist_name; ?>!</p>
            <p>You are now logged in to the system.</p>
        </div>
        <a href="logout.php" class="btn">Logout</a>
    </div>

</body>
</html>
