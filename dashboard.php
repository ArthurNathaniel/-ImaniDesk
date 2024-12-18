<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
</head>
<body>
    <h2>Welcome, <?php echo $_SESSION['admin']; ?>!</h2>
    <p>Dashboard Content Goes Here...</p>
    <a href="logout.php">Logout</a>
</body>
</html>
