// send_message.php
<?php
// Save chat message to DB
require 'db_connect.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['message'])) {
    $msg = htmlspecialchars($_POST['message']);
    $stmt = $conn->prepare("INSERT INTO messages (content, created_at) VALUES (?, NOW())");
    $stmt->bind_param("s", $msg);
    $stmt->execute();
    $stmt->close();
}
?>