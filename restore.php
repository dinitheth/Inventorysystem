<?php
  $page_title = 'Database Restore';
  require_once('includes/load.php');
  // Checking what level user has permission to view this page
  page_require_level(1);
?>
<?php include_once('layouts/header.php'); ?>

<?php
// Function to restore database from SQL file
function restoreDatabase($host, $user, $pass, $dbname, $sql_file) {
    try {
        // Check if file exists
        if (!file_exists($sql_file)) {
            return array('status' => 'error', 'message' => 'SQL file does not exist: ' . $sql_file);
        }

        // Connect to database
        $link = new mysqli($host, $user, $pass, $dbname);
        if ($link->connect_error) {
            return array('status' => 'error', 'message' => 'Connection failed: ' . $link->connect_error);
        }

        // Read SQL file content
        $sql = file_get_contents($sql_file);
        if (!$sql) {
            return array('status' => 'error', 'message' => 'Could not read SQL file');
        }

        // Split SQL by semicolon to get separate queries
        $queries = explode(';', $sql);
        
        // Begin transaction to ensure all or nothing
        $link->begin_transaction();
        
        // Execute each query
        $error = false;
        foreach ($queries as $query) {
            $query = trim($query);
            if (!empty($query)) {
                $result = $link->query($query);
                if (!$result) {
                    $error = true;
                    $error_message = $link->error;
                    break;
                }
            }
        }
        
        // Commit or rollback based on results
        if ($error) {
            $link->rollback();
            return array('status' => 'error', 'message' => 'Error executing SQL: ' . $error_message);
        } else {
            $link->commit();
            return array('status' => 'success', 'message' => 'Database restored successfully.');
        }
        
    } catch (Exception $e) {
        return array('status' => 'error', 'message' => 'Exception: ' . $e->getMessage());
    }
}

$message = '';
$restore_result = null;

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

// Process restore request
if (isset($_POST['restore']) && !empty($_POST['backup_file'])) {
    $sql_file = $backup_dir . basename($_POST['backup_file']);
    
    // Use database credentials directly from config
    $host = 'localhost';
    $user = 'root';
    $pass = '';
    $dbname = 'testinv';
    
    // Perform the restore
    $restore_result = restoreDatabase($host, $user, $pass, $dbname, $sql_file);
    
    if ($restore_result['status'] == 'success') {
        $session->msg('s', $restore_result['message']);
    } else {
        $session->msg('d', 'Restore failed: ' . $restore_result['message']);
    }
}

// Process uploaded backup file
if (isset($_FILES['backup_file']) && $_FILES['backup_file']['error'] === 0) {
    $allowed_ext = array('sql');
    $file_ext = pathinfo($_FILES['backup_file']['name'], PATHINFO_EXTENSION);
    
    if (in_array($file_ext, $allowed_ext)) {
        // Create directory if it doesn't exist
        if (!file_exists($backup_dir)) {
            mkdir($backup_dir, 0777, true);
        }
        
        // Generate unique filename
        $new_filename = 'uploaded_backup_' . date('Y-m-d_H-i-s') . '.sql';
        $upload_path = $backup_dir . $new_filename;
        
        if (move_uploaded_file($_FILES['backup_file']['tmp_name'], $upload_path)) {
            $session->msg('s', 'Backup file uploaded successfully.');
            redirect('restore.php');
        } else {
            $session->msg('d', 'Failed to upload backup file.');
        }
    } else {
        $session->msg('d', 'Invalid file format. Only SQL files are allowed.');
    }
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
                    <span class="glyphicon glyphicon-retweet"></span>
                    <span>Database Restore</span>
                </strong>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="panel panel-danger">
                            <div class="panel-heading">
                                <strong>Warning!</strong>
                            </div>
                            <div class="panel-body">
                                <p>Restoring a database will <strong>OVERWRITE</strong> your current database. All current data will be replaced with the data from the backup file.</p>
                                <p>This action cannot be undone. Make sure you have a recent backup before proceeding.</p>
                            </div>
                        </div>
                        
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <strong>Upload Backup File</strong>
                            </div>
                            <div class="panel-body">
                                <form method="post" action="" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label for="backup_file">Select SQL Backup File</label>
                                        <input type="file" name="backup_file" id="backup_file" class="form-control" accept=".sql">
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="glyphicon glyphicon-upload"></i> Upload
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-8">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <strong>Available Backups</strong>
                            </div>
                            <div class="panel-body">
                                <?php if (empty($backups)): ?>
                                    <div class="alert alert-warning">No backup files found. Please create a backup or upload a backup file.</div>
                                <?php else: ?>
                                    <form method="post" action="">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Select</th>
                                                    <th>Filename</th>
                                                    <th>Size</th>
                                                    <th>Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($backups as $backup): ?>
                                                    <tr>
                                                        <td>
                                                            <input type="radio" name="backup_file" value="<?php echo $backup['file']; ?>">
                                                        </td>
                                                        <td><?php echo $backup['file']; ?></td>
                                                        <td><?php echo $backup['size']; ?> KB</td>
                                                        <td><?php echo $backup['date']; ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                        <button type="submit" name="restore" class="btn btn-danger" onclick="return confirm('WARNING! This will overwrite your current database. Are you absolutely sure you want to continue?');">
                                            <i class="glyphicon glyphicon-refresh"></i> Restore Selected Backup
                                        </button>
                                    </form>
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