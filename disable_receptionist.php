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

    // Update the receptionist status to 'disabled'
    $disable_query = "UPDATE receptionists SET status='disabled' WHERE id='$id'";

    if ($conn->query($disable_query) === TRUE) {
        echo "<script>alert('Receptionist account disabled successfully.');</script>";
        header("Location: view_receptionists.php"); // Redirect back to the list page
        exit();
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
} else {
    echo "Invalid request.";
    exit();
}
?>
