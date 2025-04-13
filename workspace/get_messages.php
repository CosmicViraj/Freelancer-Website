// get_messages.php
<?php
require 'db_connect.php';
$result = $conn->query("SELECT content, created_at FROM messages ORDER BY created_at DESC LIMIT 50");
while ($row = $result->fetch_assoc()) {
    echo "<div class='message'><strong>[" . $row['created_at'] . "]</strong> " . htmlentities($row['content']) . "</div>";
}
?>
