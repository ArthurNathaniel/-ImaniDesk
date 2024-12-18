<?php
include('db.php'); // Include the database connection
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php"); // Redirect to login if not logged in as admin
    exit();
}

// Handle receptionist registration form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $password = $_POST['password'];
    $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Encrypt password

    // Check if the email or phone number already exists
    $check_query = "SELECT * FROM receptionists WHERE email='$email' OR phone_number='$phone_number'";
    $result = $conn->query($check_query);

    if ($result->num_rows > 0) {
        echo "<script>alert('Email or Phone Number already exists.');</script>";
    } else {
        // Insert the new receptionist into the database
        $query = "INSERT INTO receptionists (full_name, email, phone_number, password) 
                  VALUES ('$full_name', '$email', '$phone_number', '$hashed_password')";
        
        if ($conn->query($query) === TRUE) {
            echo "<script>alert('Receptionist registered successfully.');</script>";
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
    <title>Register Receptionist</title>
</head>
<body>
    <h2>Register Receptionist</h2>
    <form method="POST" action="register_receptionist.php">
        <label for="full_name">Full Name:</label>
        <input type="text" name="full_name" id="full_name" required><br><br>

        <label for="email">Email Address:</label>
        <input type="email" name="email" id="email" required><br><br>

        <label for="phone_number">Phone Number:</label>
        <input type="text" name="phone_number" id="phone_number" required><br><br>

        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required><br><br>

        <button type="submit">Register Receptionist</button>
    </form>
</body>
</html>
