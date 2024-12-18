<?php
include('db.php'); // Include database connection

// Start session and check if the admin is logged in
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php"); // Redirect to login if not logged in as admin
    exit();
}

// Check if 'id' and 'action' are passed in the URL
if (isset($_GET['id']) && isset($_GET['action'])) {
    $id = $_GET['id'];
    $action = $_GET['action'];

    // Validate action
    if ($action != 'activate' && $action != 'disable') {
        echo "<script>alert('Invalid action!');</script>";
        exit();
    }

    // Set the new status based on the action
    $new_status = ($action == 'activate') ? 'active' : 'disabled';

    // Update the receptionist status in the database
    $update_query = "UPDATE receptionists SET status='$new_status' WHERE id='$id'";

    if ($conn->query($update_query) === TRUE) {
        echo "<script>alert('Receptionist status updated successfully.');</script>";
        header("Location: view_receptionists.php"); // Redirect back to the list page
        exit();
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
} else {
    echo "<script>alert('Invalid request.');</script>";
    exit();
}
?>
