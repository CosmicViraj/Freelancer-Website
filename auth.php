<?php
session_start();
require 'db_connect.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check database connection
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Check if form data is received
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'signup') {
        // Handle signup
        $name = $conn->real_escape_string($_POST['name']);
        $email = $conn->real_escape_string($_POST['email']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $role = $conn->real_escape_string($_POST['role']);

        // Check if email already exists
        $check_sql = "SELECT id FROM users WHERE email = '$email'";
        $result = $conn->query($check_sql);
        
        if (!$result) {
            die("Error checking existing user: " . $conn->error);
        }

        if ($result->num_rows > 0) {
            die("Email already exists. Please use a different email.");
        }

        // Insert new user
        $sql = "INSERT INTO users (name, email, password, role) VALUES ('$name', '$email', '$password', '$role')";
        
        if ($conn->query($sql) === TRUE) {
            $_SESSION['user_id'] = $conn->insert_id;
            $_SESSION['user_role'] = $role;
            header("Location: dashboard.html");
            exit();
        } else {
            die("Error inserting user: " . $conn->error);
        }

    } elseif ($action === 'login') {
        // Handle login
        $email = $conn->real_escape_string($_POST['email']);
        $password = $_POST['password'];

        // Debug: Check received values
        echo "Debug: Received email - $email <br>";

        $sql = "SELECT id, password, role FROM users WHERE email = '$email'";
        $result = $conn->query($sql);

        // Debug: Check if query executed successfully
        if (!$result) {
            die("Query Error: " . $conn->error);
        }

        echo "Debug: Query executed successfully <br>";

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            // Debug: Print stored password hash
            echo "Debug: Stored password hash - " . $user['password'] . "<br>";

            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_role'] = $user['role'];
                header("Location: dashboard.php");
                exit();
            } else {
                die("Invalid email or password.");
            }
        } else {
            die("Invalid email or password.");
        }
    }
}

$conn->close();
?>
