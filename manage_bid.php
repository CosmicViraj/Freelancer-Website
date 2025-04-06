<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'employer') {
    die("Unauthorized access.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bid_id = $_POST['bid_id'];
    $action = $_POST['action'];

    if ($action === 'accept') {
        $status = 'accepted';
    } elseif ($action === 'delete') {
        // Remove the bid completely
        $delete_sql = "DELETE FROM applications WHERE id = $bid_id";
        if ($conn->query($delete_sql) === TRUE) {
            header("Location: dashboard-employer.php");
        } else {
            echo "Error deleting bid: " . $conn->error;
        }
        exit();
    } else {
        die("Invalid action.");
    }

    // Update bid status
    $update_sql = "UPDATE applications SET status = '$status' WHERE id = $bid_id";
    if ($conn->query($update_sql) === TRUE) {
        header("Location: dashboard-employer.php");
    } else {
        echo "Error updating bid: " . $conn->error;
    }
}

$conn->close();
?>
