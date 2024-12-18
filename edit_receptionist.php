<?php
include('db.php'); // Include database connection

// Start session and check if the admin is logged in
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php"); // Redirect to login if not logged in as admin
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the receptionist data to edit
    $query = "SELECT * FROM receptionists WHERE id='$id'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "Receptionist not found.";
        exit();
    }

    // Handle form submission for updating the receptionist
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $full_name = $_POST['full_name'];
        $email = $_POST['email'];
        $phone_number = $_POST['phone_number'];
        $password = $_POST['password'];
        $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Encrypt password

        $update_query = "UPDATE receptionists SET full_name='$full_name', email='$email', phone_number='$phone_number', password='$hashed_password' WHERE id='$id'";

        if ($conn->query($update_query) === TRUE) {
            echo "<script>alert('Receptionist details updated successfully.');</script>";
            header("Location: view_receptionists.php"); // Redirect back to the list page
            exit();
        } else {
            echo "<script>alert('Error: " . $conn->error . "');</script>";
        }
    }
} else {
    echo "Invalid request.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Receptionist</title>
</head>
<body>
    <h2>Edit Receptionist</h2>
    <form method="POST" action="edit_receptionist.php?id=<?php echo $id; ?>">
        <label for="full_name">Full Name:</label>
        <input type="text" name="full_name" id="full_name" value="<?php echo $row['full_name']; ?>" required><br><br>

        <label for="email">Email Address:</label>
        <input type="email" name="email" id="email" value="<?php echo $row['email']; ?>" required><br><br>

        <label for="phone_number">Phone Number:</label>
        <input type="text" name="phone_number" id="phone_number" value="<?php echo $row['phone_number']; ?>" required><br><br>

        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required><br><br>

        <button type="submit">Update Receptionist</button>
    </form>
</body>
</html>
