<?php
// This file handles only the download functionality without any output
// No session_start or includes to avoid any output before headers
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Handle download request
if (isset($_GET['file']) && !empty($_GET['file'])) {
    $backup_dir = 'backups/';
    $file = $backup_dir . basename($_GET['file']);
    
    // Debug - uncomment these lines if needed
    /*
    echo "Requested file: " . $_GET['file'] . "<br>";
    echo "Full path: " . $file . "<br>";
    echo "File exists: " . (file_exists($file) ? 'Yes' : 'No') . "<br>";
    echo "Is readable: " . (is_readable($file) ? 'Yes' : 'No') . "<br>";
    echo "File size: " . (file_exists($file) ? filesize($file) : 'N/A') . "<br>";
    die("Debug mode - download stopped");
    */
    
    // Check if file exists
    if (file_exists($file) && is_readable($file)) {
        // Disable output buffering
        if (ob_get_level()) {
            ob_end_clean();
        }
        
        // Force download by directly streaming the file
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($file) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        
        // Stream the file directly
        readfile($file);
        exit;
    } else {
        // File not found
        echo "Error: File not found or not readable. Please check the backups directory.";
        echo "<p><a href='backup.php'>Return to backup page</a></p>";
        exit;
    }
} else {
    // No file parameter provided
    echo "Error: No file specified.";
    echo "<p><a href='backup.php'>Return to backup page</a></p>";
    exit;
}
?>