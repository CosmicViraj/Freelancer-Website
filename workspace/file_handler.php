<?php
session_start();
require '../db_connect.php';

// Verify user session
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    die(json_encode(['error' => 'Unauthorized']));
}

header('Content-Type: application/json');

// Handle file uploads
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['file'])) {
        $uploadDir = '../uploads/workspace/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = basename($_FILES['file']['name']);
        $filePath = $uploadDir . uniqid() . '_' . $fileName;
        $fileType = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $fileSize = $_FILES['file']['size'];

        // Validate file
        $allowedTypes = ['pdf', 'doc', 'docx', 'txt', 'jpg', 'png', 'zip'];
        if (!in_array($fileType, $allowedTypes)) {
            http_response_code(400);
            die(json_encode(['error' => 'Invalid file type']));
        }

        if ($fileSize > 5000000) { // 5MB max
            http_response_code(400);
            die(json_encode(['error' => 'File too large']));
        }

        if (move_uploaded_file($_FILES['file']['tmp_name'], $filePath)) {
            // Save to database
            $stmt = $conn->prepare("INSERT INTO workspace_files (workspace_id, user_id, filename, filepath, filesize, filetype) VALUES (1, ?, ?, ?, ?, ?)");
            $stmt->bind_param("issis", $_SESSION['user_id'], $fileName, $filePath, $fileSize, $fileType);
            $stmt->execute();

            echo json_encode([
                'status' => 'success',
                'file_id' => $stmt->insert_id,
                'filename' => $fileName
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'File upload failed']);
        }
    }
} 
// Handle file listing
elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $conn->prepare("SELECT wf.*, u.name FROM workspace_files wf JOIN users u ON wf.user_id = u.id ORDER BY wf.created_at DESC");
    $stmt->execute();
    $result = $stmt->get_result();
    
    $files = [];
    while ($row = $result->fetch_assoc()) {
        $files[] = $row;
    }
    
    echo json_encode($files);
}
else {
    http_response_code(405);
    die(json_encode(['error' => 'Method not allowed']));
}
