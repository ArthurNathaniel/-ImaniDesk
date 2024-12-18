<?php
include('db.php'); // Include database connection

// Handle signup form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Encrypt password
    
    // Check if username already exists
    $check_query = "SELECT * FROM admins WHERE username='$username'";
    $result = $conn->query($check_query);
    
    if ($result->num_rows > 0) {
        echo "<script>alert('Username already exists.');</script>";
    } else {
        // Insert new admin into the database
        $query = "INSERT INTO admins (username, password) VALUES ('$username', '$hashed_password')";
        
        if ($conn->query($query) === TRUE) {
            echo "<script>alert('Signup successful. You can now log in.');</script>";
            header("Location: login.php");
        } else {
            echo "<script>alert('Error: " . $conn->error . "');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Signup</title>
</head>
<body>
    <h2>Admin Signup</h2>
    <form method="POST" action="signup.php">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" required><br><br>
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required><br><br>
        <button type="submit">Sign Up</button>
    </form>
</body>
</html>
