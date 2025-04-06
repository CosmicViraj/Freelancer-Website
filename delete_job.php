<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'employer') {
    header("Location: login.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['job_id'])) {
    $job_id = intval($_POST['job_id']);
    $employer_id = $_SESSION['user_id'];

    // Only allow deletion of the employer’s own job
    $sql = "DELETE FROM jobs WHERE id = ? AND employer_id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ii", $job_id, $employer_id);
        if ($stmt->execute()) {
            header("Location: dashboard-employer.php?delete=success");
            exit();
        } else {
            echo "Error deleting job: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Prepare failed: " . $conn->error;
    }
} else {
    header("Location: employer_dashboard.php");
    exit();
}

$conn->close();
?>
