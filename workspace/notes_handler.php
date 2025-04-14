<?php
session_start();
require '../db_connect.php';

// Verify user session
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    die(json_encode(['error' => 'Unauthorized']));
}

header('Content-Type: application/json');

// Handle note saving
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = htmlspecialchars($_POST['title']);
    $content = htmlspecialchars($_POST['content']);

    $stmt = $conn->prepare("INSERT INTO workspace_notes (workspace_id, user_id, title, content) VALUES (1, ?, ?, ?)");
    $stmt->bind_param("iss", $_SESSION['user_id'], $title, $content);
    $stmt->execute();

    echo json_encode(['status' => 'success', 'note_id' => $stmt->insert_id]);
} 
// Handle note retrieval
elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $conn->prepare("SELECT wn.*, u.name FROM workspace_notes wn JOIN users u ON wn.user_id = u.id WHERE wn.workspace_id = 1 ORDER BY wn.created_at DESC");
    $stmt->execute();
    $result = $stmt->get_result();
    
    $notes = [];
    while ($row = $result->fetch_assoc()) {
        $notes[] = $row;
    }
    
    echo json_encode($notes);
} 
else {
    http_response_code(405);
    die(json_encode(['error' => 'Method not allowed']));
}
