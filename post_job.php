<?php
session_start();
require 'db_connect.php';

// Check if employer is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'employer') {
    header("Location: login.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employer_id = $_SESSION['user_id'];
    $title = $conn->real_escape_string($_POST['title']);
    $description = $conn->real_escape_string($_POST['description']);
    $budget = floatval($_POST['budget']);
    $category = $conn->real_escape_string($_POST['category']);
    $status = 'active'; // default job status

    // Insert job into the database
    $sql = "INSERT INTO jobs (employer_id, title, description, budget, category, status)
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("issdss", $employer_id, $title, $description, $budget, $category, $status);
        if ($stmt->execute()) {
            header("Location: dashboard-employer.php?job_posted=success");
            exit();
        } else {
            echo "Error executing statement: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "SQL Error: " . $conn->error;
    }
} else {
    header("Location: employer_dashboard.php");
    exit();
}

$conn->close();
?>
