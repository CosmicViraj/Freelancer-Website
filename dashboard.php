<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

if ($_SESSION['user_role'] === 'freelancer') {
    header("Location: dashboard-freelancer.php");
} else {
    header("Location: dashboard-employer.php");
}
exit();
?>
