<?php
session_start();
require '../db_connect.php';

// Verify user session
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.html");
    exit();
}

// Initialize workspace components
$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['user_role'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workspace | Freelancer Platform</title>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        /* Workspace-specific styles */
        .workspace-container {
            display: flex;
            height: 100vh;
        }
        .tab-content {
            display: none;
            padding: 20px;
        }
        .tab-content.active {
            display: block;
        }
    </style>
</head>
<body>
    <div class="workspace-container">
        <!-- Navigation Tabs -->
        <div class="workspace-nav">
            <button class="tab-btn active" data-tab="chat">Live Chat</button>
            <button class="tab-btn" data-tab="files">File Sharing</button>
            <button class="tab-btn" data-tab="notes">Notes & Reports</button>
        </div>

        <!-- Tab Contents -->
        <div class="workspace-content">
            <!-- Chat Tab -->
            <div id="chat" class="tab-content active">
                <div class="chat-messages" id="message-container">
                    <!-- Messages will be loaded here via AJAX -->
                </div>
                <div class="chat-input">
                    <textarea id="message-input" placeholder="Type your message..."></textarea>
                    <input type="file" id="file-upload" style="display:none">
                    <button id="send-btn">Send</button>
                    <button id="attach-btn">Attach File</button>
                </div>
            </div>

            <!-- Files Tab -->
            <div id="files" class="tab-content">
                <div class="file-upload-area">
                    <h3>Upload Files</h3>
                    <input type="file" id="file-upload" multiple>
                    <button id="upload-btn">Upload</button>
                </div>
                <div class="file-list" id="file-container">
                    <!-- Files will be listed here -->
                </div>
            </div>

            <!-- Notes Tab -->
            <div id="notes" class="tab-content">
                <div class="note-editor">
                    <input type="text" id="note-title" placeholder="Note title">
                    <textarea id="note-content" placeholder="Write your note here..."></textarea>
                    <button id="save-note">Save Note</button>
                    <button id="new-note">New Note</button>
                </div>
                <div class="note-list" id="note-container">
                    <!-- Notes will be listed here -->
                </div>
            </div>
        </div>
    </div>

    <script src="../js/workspace.js"></script>
</body>
</html>
