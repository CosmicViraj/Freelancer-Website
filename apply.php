<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'freelancer') {
    header("Location: login.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $job_id = $conn->real_escape_string($_POST['job_id']);
    $proposal = $conn->real_escape_string($_POST['proposal']);
    $freelancer_id = $_SESSION['user_id'];

    // Check if already applied
    $check_sql = "SELECT id FROM applications WHERE job_id = $job_id AND freelancer_id = $freelancer_id";
    $result = $conn->query($check_sql);
    
    if ($result->num_rows > 0) {
        die("You've already applied to this job.");
    }

    // Insert application
    $status = 'pending';
    $sql = "INSERT INTO applications (job_id, freelancer_id, proposal, status) 
            VALUES ($job_id, $freelancer_id, '$proposal', '$status')";

    if ($conn->query($sql) === TRUE) {
        header("Location: dashboard-freelancer.php");
        exit();
    } else {
        die("Error submitting application: " . $conn->error);
    }
}

$conn->close();
header("Location: dashboard-freelancer.php");
exit();
?>
