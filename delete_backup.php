<?php
// This file handles only deletion without any output
require_once('includes/load.php');
// Checkin What level user has permission
page_require_level(1);

// Handle delete request
if (isset($_GET['file']) && !empty($_GET['file'])) {
    $backup_dir = 'backups/';
    $file = $backup_dir . basename($_GET['file']);
    
    if (file_exists($file)) {
        if (unlink($file)) {
            $session->msg('s', 'Backup file deleted successfully.');
        } else {
            $session->msg('d', 'Failed to delete backup file. Check file permissions.');
        }
    } else {
        $session->msg('d', 'Backup file not found.');
    }
}

// Always redirect back to backup page
redirect('backup.php');
exit;
?>