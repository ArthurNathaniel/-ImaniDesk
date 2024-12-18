<?php
include('db.php'); // Include database connection

// Start session
session_start();

// Check if the receptionist is already logged in, redirect to dashboard
if (isset($_SESSION['receptionist_id'])) {
    header("Location: receptionist_dashboard.php"); // Redirect to the dashboard
    exit();
}

// Handle login when the form is submitted
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Sanitize input
    $email = mysqli_real_escape_string($conn, $email);
    $password = mysqli_real_escape_string($conn, $password);

    // Query to fetch receptionist data by email
    $query = "SELECT * FROM receptionists WHERE email='$email' AND status='active'";
    $result = $conn->query($query);

    // Check if the email exists and if the password matches
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Verify password
        if (password_verify($password, $row['password'])) {
            // Set session variables for the receptionist
            $_SESSION['receptionist_id'] = $row['id'];
            $_SESSION['receptionist_name'] = $row['full_name'];

            // Redirect to the receptionist dashboard
            header("Location: receptionist_dashboard.php");
            exit();
        } else {
            $error_message = "Incorrect password!";
        }
    } else {
        $error_message = "No active account found with this email.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receptionist Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 50px;
        }
        .login-container {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
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
        .error {
            color: red;
            font-size: 14px;
            margin-top: 10px;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <h2>Receptionist Login</h2>
        <?php
        // Display error message if there is any
        if (isset($error_message)) {
            echo "<p class='error'>$error_message</p>";
        }
        ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="text" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" name="login" class="btn">Login</button>
        </form>
    </div>

</body>
</html>
