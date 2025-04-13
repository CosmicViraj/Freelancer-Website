// list_files.php
<?php
$files = array_diff(scandir('uploads'), array('.', '..'));
foreach ($files as $file) {
    echo "<div class='file'><a href='uploads/" . urlencode($file) . "' download>" . htmlentities($file) . "</a></div>";
}
?>
