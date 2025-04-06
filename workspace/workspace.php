<?php
session_start();
require '../db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.html");
    exit();
}

if (!isset($_GET['job_id'])) {
    echo "Job ID is required.";
    exit();
}

$job_id = intval($_GET['job_id']);

// Fetch job details
$job_sql = "SELECT j.*, u.name as employer_name FROM jobs j
            JOIN users u ON j.employer_id = u.id
            WHERE j.id = $job_id";
$job_result = $conn->query($job_sql);
$job = $job_result->fetch_assoc();

if (!$job) {
    echo "Job not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Virtual Workspace</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="max-w-6xl mx-auto p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Workspace for: <?= htmlspecialchars($job['title']) ?></h1>
            <a href="../dashboard-freelancer.php" class="text-blue-500 hover:underline">← Back to Dashboard</a>
        </div>

        <!-- Grid layout for chat, files, and notes -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Chat Section -->
            <div class="bg-white p-4 rounded shadow-md md:col-span-2">
                <h2 class="text-xl font-semibold mb-4">Chat</h2>
                <div id="chat-box" class="h-64 overflow-y-auto border p-2 mb-4 bg-gray-50">
                    <p class="text-sm text-gray-500">Chat messages will appear here...</p>
                </div>
                <form id="chat-form" class="flex space-x-2">
                    <input type="text" id="chat-input" name="message" class="flex-grow p-2 border rounded" placeholder="Type your message..." required>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Send</button>
                </form>
            </div>

            <!-- Files and Notes -->
            <div class="space-y-6">
            <form action="save_note.php" method="POST">
    <input type="hidden" name="job_id" value="<?= $job_id ?>">
    <textarea name="note" class="w-full border rounded p-2 mb-2" placeholder="Write your note..."></textarea>
    <button type="submit" class="bg-purple-500 text-white px-4 py-1 rounded">Save Note</button>
</form>


                <!-- File Upload -->
                <div class="bg-white p-4 rounded shadow-md">
                    <h2 class="text-xl font-semibold mb-4">Shared Files</h2>
                    <form action="upload.php" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="job_id" value="<?= $job_id ?>">
                        <input type="file" name="file" class="mb-2" required>
                        <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">
                            Upload
                        </button>
                    </form>
                    <div class="mt-4 text-sm text-gray-600">
                        <!-- Placeholder for uploaded files -->
                        <p>No files uploaded yet.</p>
                    </div>
                </div>

                <!-- Task Notes -->
                <div class="bg-white p-4 rounded shadow-md">
                    <h2 class="text-xl font-semibold mb-4">Notes</h2>
                    <textarea class="w-full h-32 p-2 border rounded" placeholder="Write shared notes or task list here..."></textarea>
                </div>
            </div>
        </div>
    </div>

    <!-- Basic JS for chat frontend -->
    <script>
        document.getElementById('chat-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const message = document.getElementById('chat-input').value;
            const chatBox = document.getElementById('chat-box');

            const msgElem = document.createElement('p');
            msgElem.className = "text-sm text-gray-800";
            msgElem.textContent = "You: " + message;
            chatBox.appendChild(msgElem);
            document.getElementById('chat-input').value = "";
            chatBox.scrollTop = chatBox.scrollHeight;
        });
    </script>
</body>
</html>
