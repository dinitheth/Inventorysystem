<?php
  $page_title = 'Database Backup';
  require_once('includes/load.php');
  // Checking what level user has permission to view this page
  page_require_level(1);
?>
<?php include_once('layouts/header.php'); ?>

<?php
// Function to create database backup
function backupDatabaseTables($host, $user, $pass, $dbname, $tables = '*') {
    $link = new mysqli($host, $user, $pass, $dbname);
    if ($link->connect_error) {
        return "Connection failed: " . $link->connect_error;
    }

    // Get all tables if not specified
    if ($tables == '*') {
        $tables = array();
        $result = $link->query("SHOW TABLES");
        while ($row = $result->fetch_row()) {
            $tables[] = $row[0];
        }
    } else {
        $tables = is_array($tables) ? $tables : explode(',', $tables);
    }

    // Start the backup output
    $output = "-- Database Backup for $dbname\n";
    $output .= "-- Generated: " . date('Y-m-d H:i:s') . "\n\n";
    
    // Process each table
    foreach ($tables as $table) {
        // Add table structure
        $output .= "DROP TABLE IF EXISTS `$table`;\n";
        $res = $link->query("SHOW CREATE TABLE `$table`");
        $row = $res->fetch_row();
        $output .= $row[1] . ";\n\n";
        
        // Add table data
        $result = $link->query("SELECT * FROM `$table`");
        $num_fields = $result->field_count;
        
        while ($row = $result->fetch_row()) {
            $output .= "INSERT INTO `$table` VALUES(";
            for ($j = 0; $j < $num_fields; $j++) {
                $row[$j] = addslashes($row[$j]);
                $row[$j] = str_replace("\n", "\\n", $row[$j]);
                if (isset($row[$j])) {
                    $output .= "'" . $row[$j] . "'";
                } else {
                    $output .= "''";
                }
                if ($j < ($num_fields - 1)) {
                    $output .= ',';
                }
            }
            $output .= ");\n";
        }
        $output .= "\n\n";
    }

    // Save the backup file
    $backup_dir = 'backups/';
    
    // Create backup directory if it doesn't exist
    if (!file_exists($backup_dir)) {
        mkdir($backup_dir, 0777, true);
    }
    
    // Generate unique filename
    $backup_file = $backup_dir . $dbname . '_backup_' . date('Y-m-d_H-i-s') . '.sql';
    
    // Write backup file
    if (file_put_contents($backup_file, $output)) {
        return array(
            'status' => 'success',
            'message' => 'Database backup created successfully.',
            'file' => $backup_file
        );
    } else {
        return array(
            'status' => 'error',
            'message' => 'Error creating backup file. Check directory permissions.'
        );
    }
}

$message = '';
$backup_result = null;

// Process backup request
if (isset($_POST['backup'])) {
    // Use database credentials directly from config
    $host = 'localhost';
    $user = 'root';
    $pass = '';
    $dbname = 'testinv';
    
    // Perform the backup
    $backup_result = backupDatabaseTables($host, $user, $pass, $dbname);
    
    if (isset($backup_result['status']) && $backup_result['status'] == 'success') {
        $session->msg('s', $backup_result['message']);
    } else {
        $session->msg('d', 'Backup failed: ' . (is_array($backup_result) ? $backup_result['message'] : $backup_result));
    }
}

// List existing backups
$backups = array();
$backup_dir = 'backups/';
if (file_exists($backup_dir)) {
    $files = scandir($backup_dir);
    foreach ($files as $file) {
        if ($file != '.' && $file != '..' && pathinfo($file, PATHINFO_EXTENSION) == 'sql') {
            $backups[] = array(
                'file' => $file,
                'size' => round(filesize($backup_dir . $file) / 1024, 2), // size in KB
                'date' => date('Y-m-d H:i:s', filemtime($backup_dir . $file))
            );
        }
    }
    // Sort by date (newest first)
    usort($backups, function($a, $b) {
        return strtotime($b['date']) - strtotime($a['date']);
    });
}

// Handle download request
if (isset($_GET['download']) && !empty($_GET['download'])) {
    // Redirect to the download handler
    redirect('download_backup.php?file=' . urlencode($_GET['download']));
    exit;
}

// Handle delete request
if (isset($_GET['delete']) && !empty($_GET['delete'])) {
    $file = $backup_dir . basename($_GET['delete']);
    if (file_exists($file)) {
        // Try to delete the file and handle the result
        if (unlink($file)) {
            $session->msg('s', 'Backup file deleted successfully.');
        } else {
            $session->msg('d', 'Failed to delete backup file. Check file permissions.');
        }
    } else {
        $session->msg('d', 'Backup file not found.');
    }
    
    // Redirect back to the backup page regardless of success or failure
    header('Location: backup.php');
    exit;
}
?>

<div class="row">
    <div class="col-md-12">
        <?php echo display_msg($msg); ?>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <strong>
                    <span class="glyphicon glyphicon-floppy-save"></span>
                    <span>Database Backup</span>
                </strong>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-4">
                        <form method="post" action="">
                            <div class="form-group">
                                <button type="submit" name="backup" class="btn btn-primary btn-lg">
                                    <i class="glyphicon glyphicon-download-alt"></i> Create Database Backup
                                </button>
                            </div>
                            <div class="alert alert-info">
                                <p>Click the button above to create a new backup of your database. This may take a moment depending on the database size.</p>
                            </div>
                        </form>
                    </div>
                    
                    <div class="col-md-8">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <strong>Available Backups</strong>
                            </div>
                            <div class="panel-body">
                                <?php if (empty($backups)): ?>
                                    <div class="alert alert-warning">No backup files found.</div>
                                <?php else: ?>
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Filename</th>
                                                <th>Size</th>
                                                <th>Date</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($backups as $backup): ?>
                                                <tr>
                                                    <td><?php echo $backup['file']; ?></td>
                                                    <td><?php echo $backup['size']; ?> KB</td>
                                                    <td><?php echo $backup['date']; ?></td>
                                                    <td>
                                                    <a href="download_backup.php?file=<?php echo urlencode($backup['file']); ?>" class="btn btn-xs btn-primary">
                                                     <i class="glyphicon glyphicon-download"></i> Download
                                                    </a>
                                                    <a href="delete_backup.php?file=<?php echo urlencode($backup['file']); ?>" class="btn btn-xs btn-danger" onclick="return confirm('Are you sure you want to delete this backup file?');">
                                                     <i class="glyphicon glyphicon-trash"></i> Delete
                                                     </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>