<?php
session_start();
require '../db_connect.php';


if (!isset($_SESSION['user_id'])) {
    echo "Unauthorized";
    exit();
}

$user_id = $_SESSION['user_id'];
$job_id = $_POST['job_id'];
$note = $_POST['note'];

// Basic input validation (optional)
if (empty($note) || empty($job_id)) {
    echo "Note and Job ID are required.";
    exit();
}

// Save note to database
$stmt = $conn->prepare("INSERT INTO notes (user_id, job_id, note) VALUES (?, ?, ?)");
$stmt->bind_param("iis", $user_id, $job_id, $note);

if ($stmt->execute()) {
    echo "Note saved!";
} else {
    echo "Failed to save note: " . $conn->error;
}

$stmt->close();
$conn->close();
?>
