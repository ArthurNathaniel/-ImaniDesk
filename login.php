<?php
include('db.php'); // Include database connection

session_start(); // Start a session to store login state

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Retrieve the stored password hash for the given username
    $query = "SELECT * FROM admins WHERE username='$username'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) { // Check password
            $_SESSION['admin'] = $row['username']; // Set session variable
            header("Location: dashboard.php"); // Redirect to admin dashboard
            exit();
        } else {
            echo "<script>alert('Incorrect password.');</script>";
        }
    } else {
        echo "<script>alert('Username not found.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
</head>
<body>
    <h2>Admin Login</h2>
    <form method="POST" action="login.php">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" required><br><br>
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required><br><br>
        <button type="submit">Login</button>
    </form>
</body>
</html>
