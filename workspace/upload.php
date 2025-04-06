<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $upload_dir = 'uploads/';
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $job_id = $_POST['job_id'];
    $filename = basename($_FILES['file']['name']);
    $target = $upload_dir . time() . "_" . $filename;

    if (move_uploaded_file($_FILES['file']['tmp_name'], $target)) {
        echo "File uploaded successfully. <a href='workspace.php?job_id=$job_id'>Back</a>";
    } else {
        echo "Upload failed.";
    }
} else {
    echo "Invalid request.";
}
?>
