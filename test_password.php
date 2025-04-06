<?php
// Test password verification
$enteredPassword = "your-actual-password"; // Replace with your real password
$storedHash = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'; // Replace with actual hash from DB

if (password_verify($enteredPassword, $storedHash)) {
    echo "Password matches!";
} else {
    echo "Password does NOT match!";
}
?>
