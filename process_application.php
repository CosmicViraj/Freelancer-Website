<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'employer') {
    header("Location: login.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $application_id = $conn->real_escape_string($_POST['application_id']);
    $action = $conn->real_escape_string($_POST['action']);

    // Validate action
    if (!in_array($action, ['accept', 'reject'])) {
        die("Invalid action.");
    }

    // Update application status
    $status = $action === 'accept' ? 'accepted' : 'rejected';
    $sql = "UPDATE applications SET status = '$status' WHERE id = $application_id";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: dashboard-employer.php");
        exit();
    } else {
        die("Error processing application: " . $conn->error);
    }
}

$conn->close();
header("Location: dashboard-employer.php");
exit();
?>