document.addEventListener('DOMContentLoaded', function() {
    // Tab switching functionality
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');
    
    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            // Remove active class from all buttons and contents
            tabButtons.forEach(btn => btn.classList.remove('active'));
            tabContents.forEach(content => content.classList.remove('active'));
            
            // Add active class to clicked button and corresponding content
            button.classList.add('active');
            const tabId = button.getAttribute('data-tab');
            document.getElementById(tabId).classList.add('active');
        });
    });

    // Chat functionality
    const messageInput = document.getElementById('message-input');
    const sendBtn = document.getElementById('send-btn');
    const messageContainer = document.getElementById('message-container');
    
    function loadMessages() {
        fetch('../workspace/chat_handler.php')
            .then(response => response.json())
            .then(messages => {
                messageContainer.innerHTML = '';
                messages.forEach(msg => {
                    const messageDiv = document.createElement('div');
                    messageDiv.className = 'message';
                    messageDiv.innerHTML = `
                        <strong>${msg.name}:</strong>
                        <p>${msg.message}</p>
                        <small>${new Date(msg.created_at).toLocaleString()}</small>
                    `;
                    messageContainer.appendChild(messageDiv);
                });
                messageContainer.scrollTop = messageContainer.scrollHeight;
            });
    }

    sendBtn.addEventListener('click', () => {
        const message = messageInput.value.trim();
        if (message) {
            fetch('../workspace/chat_handler.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ message })
            })
            .then(() => {
                messageInput.value = '';
                loadMessages();
            });
        }
    });

    // File sharing functionality
    const fileUpload = document.getElementById('file-upload');
    const uploadBtn = document.getElementById('upload-btn');
    const fileContainer = document.getElementById('file-container');
    
    function loadFiles() {
        fetch('../workspace/file_handler.php')
            .then(response => response.json())
            .then(files => {
                fileContainer.innerHTML = '';
                files.forEach(file => {
                    const fileDiv = document.createElement('div');
                    fileDiv.className = 'file-item';
                    fileDiv.innerHTML = `
                        <a href="${file.filepath}" download="${file.filename}">${file.filename}</a>
                        <small>Uploaded by ${file.name} (${Math.round(file.filesize/1024)}KB)</small>
                    `;
                    fileContainer.appendChild(fileDiv);
                });
            });
    }

    uploadBtn.addEventListener('click', () => {
        const formData = new FormData();
        formData.append('file', fileUpload.files[0]);
        
        fetch('../workspace/file_handler.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(() => {
            fileUpload.value = '';
            loadFiles();
        });
    });

    // Notes functionality
    const noteTitle = document.getElementById('note-title');
    const noteContent = document.getElementById('note-content');
    const saveNoteBtn = document.getElementById('save-note');
    const newNoteBtn = document.getElementById('new-note');
    const noteContainer = document.getElementById('note-container');
    
    function loadNotes() {
        fetch('../workspace/notes_handler.php')
            .then(response => response.json())
            .then(notes => {
                noteContainer.innerHTML = '';
                notes.forEach(note => {
                    const noteDiv = document.createElement('div');
                    noteDiv.className = 'note-item';
                    noteDiv.innerHTML = `
                        <h4>${note.title}</h4>
                        <p>${note.content}</p>
                        <small>Created by ${note.name} on ${new Date(note.created_at).toLocaleString()}</small>
                    `;
                    noteContainer.appendChild(noteDiv);
                });
            });
    }

    saveNoteBtn.addEventListener('click', () => {
        const title = noteTitle.value.trim();
        const content = noteContent.value.trim();
        
        if (title && content) {
            const formData = new FormData();
            formData.append('title', title);
            formData.append('content', content);
            
            fetch('../workspace/notes_handler.php', {
                method: 'POST',
                body: formData
            })
            .then(() => {
                noteTitle.value = '';
                noteContent.value = '';
                loadNotes();
            });
        }
    });

    newNoteBtn.addEventListener('click', () => {
        noteTitle.value = '';
        noteContent.value = '';
    });

    // Initial load
    loadMessages();
    loadFiles();
    loadNotes();

    // Auto-refresh messages every 5 seconds
    setInterval(loadMessages, 5000);
});
