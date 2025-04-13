<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Real-time Workspace</title>
  <style>
    body { font-family: Arial, sans-serif; margin: 0; padding: 0; background: #f4f4f4; }
    .container { display: flex; height: 100vh; }
    .chat, .notes, .files { flex: 1; padding: 1em; overflow-y: auto; }
    .chat { background: #e3f2fd; }
    .notes { background: #fff3e0; }
    .files { background: #e8f5e9; }
    .section-title { font-weight: bold; font-size: 1.2em; margin-bottom: 0.5em; }
    .input-area { margin-top: 1em; }
    textarea, input[type="text"] { width: 100%; padding: 0.5em; margin-bottom: 0.5em; }
    button { padding: 0.5em 1em; }
    .message, .note, .file { margin-bottom: 0.5em; background: #fff; padding: 0.5em; border-radius: 5px; }
  </style>
</head>
<body>
  <div class="container">
    <!-- Chat Section -->
    <div class="chat">
      <div class="section-title">Chat</div>
      <div id="chat-box"></div>
      <div class="input-area">
        <input type="text" id="chat-msg" placeholder="Type your message...">
        <button onclick="sendMessage()">Send</button>
      </div>
    </div>

    <!-- Notes Section -->
    <div class="notes">
      <div class="section-title">Notes</div>
      <div id="notes-box"></div>
      <div class="input-area">
        <textarea id="note-text" placeholder="Write a note..."></textarea>
        <button onclick="saveNote()">Save Note</button>
      </div>
    </div>

    <!-- Files Section -->
    <div class="files">
      <div class="section-title">Files</div>
      <form id="upload-form" enctype="multipart/form-data">
        <input type="file" name="file" id="file">
        <button type="submit">Upload</button>
      </form>
      <div id="file-list"></div>
    </div>
  </div>

  <script>
    function sendMessage() {
      const msg = document.getElementById('chat-msg').value;
      if (!msg) return;
      fetch('send_message.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'message=' + encodeURIComponent(msg)
      }).then(() => {
        document.getElementById('chat-msg').value = '';
        loadMessages();
      });
    }

    function loadMessages() {
      fetch('get_messages.php')
        .then(res => res.text())
        .then(data => { document.getElementById('chat-box').innerHTML = data; });
    }

    function saveNote() {
      const note = document.getElementById('note-text').value;
      if (!note) return;
      fetch('save_note.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'note=' + encodeURIComponent(note)
      }).then(() => {
        document.getElementById('note-text').value = '';
        loadNotes();
      });
    }

    function loadNotes() {
      fetch('get_notes.php')
        .then(res => res.text())
        .then(data => { document.getElementById('notes-box').innerHTML = data; });
    }

    function loadFiles() {
      fetch('list_files.php')
        .then(res => res.text())
        .then(data => { document.getElementById('file-list').innerHTML = data; });
    }

    document.getElementById('upload-form').addEventListener('submit', function(e) {
      e.preventDefault();
      const formData = new FormData(this);
      fetch('upload.php', {
        method: 'POST',
        body: formData
      }).then(() => {
        document.getElementById('file').value = '';
        loadFiles();
      });
    });

    // Poll every 5 seconds
    setInterval(() => {
      loadMessages();
      loadNotes();
      loadFiles();
    }, 5000);

    // Initial load
    loadMessages();
    loadNotes();
    loadFiles();
  </script>
</body>
</html>
