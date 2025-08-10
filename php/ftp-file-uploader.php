<?php

/**
 * FTP File Uploader - Pure PHP Version
 * Description: Upload files from current directory to a remote FTP server
 * Version: 1.0
 * Author: WebPlover
 */

// Set timezone to Pakistan Standard Time
date_default_timezone_set('Asia/Karachi');

function formatFileSize($bytes, $precision = 2)
{
  $units = array('B', 'KB', 'MB', 'GB', 'TB');

  for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
    $bytes /= 1024;
  }

  return round($bytes, $precision) . ' ' . $units[$i];
}

function getCurrentDirectoryFiles()
{
  $current_dir = getcwd();
  $files = [];

  $scan = scandir($current_dir);

  foreach ($scan as $file) {
    if ($file !== '.' && $file !== '..' && is_file($file)) {
      $files[] = [
        'path' => $current_dir . DIRECTORY_SEPARATOR . $file,
        'name' => $file,
        'size' => formatFileSize(filesize($file), 2),
        'modified' => date("F j, Y g:i A", filemtime($file)),
        'extension' => pathinfo($file, PATHINFO_EXTENSION)
      ];
    }
  }

  // Sort files by modification time (newest first)
  usort($files, function ($a, $b) {
    return filemtime($b['path']) - filemtime($a['path']);
  });

  return $files;
}

// Get all files in current directory
$current_files = getCurrentDirectoryFiles();
$upload_message = '';
$message_type = '';

// Process FTP upload
if (isset($_POST['upload_ftp'])) {
  $ftp_host = trim($_POST['ftp_host']);
  $ftp_user = trim($_POST['ftp_user']);
  $ftp_pass = $_POST['ftp_pass']; // Don't trim password as it might have spaces
  $remote_path = trim($_POST['remote_path']);
  $selected_file = trim($_POST['selected_file']);
  $custom_file_path = trim($_POST['custom_file_path']);

  $local_path = '';

  // Determine which file to upload
  if (!empty($custom_file_path)) {
    $local_path = getcwd() . DIRECTORY_SEPARATOR . ltrim($custom_file_path, '/\\');
  } elseif (!empty($selected_file)) {
    $local_path = getcwd() . DIRECTORY_SEPARATOR . $selected_file;
  }

  // Validate file
  if (empty($local_path)) {
    $upload_message = "Please select a file or enter a custom file path.";
    $message_type = 'error';
  } elseif (!file_exists($local_path)) {
    $upload_message = "Selected file does not exist: " . htmlspecialchars(basename($local_path));
    $message_type = 'error';
  } elseif (!is_file($local_path)) {
    $upload_message = "Selected path is not a file: " . htmlspecialchars(basename($local_path));
    $message_type = 'error';
  } else {
    // Attempt FTP connection and upload
    $conn_id = ftp_connect($ftp_host);

    if (!$conn_id) {
      $upload_message = "Failed to connect to FTP server: " . htmlspecialchars($ftp_host);
      $message_type = 'error';
    } elseif (!ftp_login($conn_id, $ftp_user, $ftp_pass)) {
      $upload_message = "FTP login failed. Please check your credentials.";
      $message_type = 'error';
      ftp_close($conn_id);
    } else {
      ftp_pasv($conn_id, true); // Enable passive mode

      // Determine target file path
      if (empty($remote_path) || $remote_path === '.') {
        $target_file = basename($local_path);
      } else {
        $target_file = rtrim($remote_path, '/') . '/' . basename($local_path);
      }

      // Upload file
      if (ftp_put($conn_id, $target_file, $local_path, FTP_BINARY)) {
        $file_size = formatFileSize(filesize($local_path));
        $upload_message = "File '" . htmlspecialchars(basename($local_path)) . "' ({$file_size}) uploaded successfully to: " . htmlspecialchars($target_file);
        $message_type = 'success';
      } else {
        $upload_message = "FTP upload failed. Please check the remote path and permissions.";
        $message_type = 'error';
      }

      ftp_close($conn_id);
    }
  }

  // Refresh file list after upload
  $current_files = getCurrentDirectoryFiles();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FTP File Uploader</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      max-width: 1000px;
      margin: 0 auto;
      padding: 20px;
      background-color: #f5f5f5;
    }

    .container {
      background: white;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    h1 {
      color: #333;
      border-bottom: 3px solid #007cba;
      padding-bottom: 10px;
    }

    h2 {
      color: #444;
      margin-top: 30px;
    }

    h3 {
      color: #666;
      margin-top: 25px;
    }

    .file-table {
      width: 100%;
      border-collapse: collapse;
      margin: 15px 0;
      border: 1px solid #ddd;
    }

    .file-table th,
    .file-table td {
      padding: 12px;
      text-align: left;
      border-bottom: 1px solid #ddd;
    }

    .file-table th {
      background-color: #f8f9fa;
      font-weight: bold;
      color: #333;
    }

    .file-table tr:hover {
      background-color: #f8f9fa;
    }

    .file-table input[type="radio"] {
      transform: scale(1.2);
    }

    .form-group {
      margin-bottom: 15px;
    }

    .form-group label {
      display: block;
      margin-bottom: 5px;
      font-weight: bold;
      color: #333;
    }

    .form-group input[type="text"],
    .form-group input[type="password"] {
      width: 100%;
      max-width: 400px;
      padding: 8px 12px;
      border: 1px solid #ddd;
      border-radius: 4px;
      font-size: 14px;
    }

    .custom-path-input {
      width: 100%;
      max-width: 600px;
    }

    .submit-btn {
      background-color: #007cba;
      color: white;
      padding: 12px 24px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-size: 16px;
      margin-top: 20px;
    }

    .submit-btn:hover {
      background-color: #005a87;
    }

    .message {
      padding: 15px;
      margin: 20px 0;
      border-radius: 4px;
      font-weight: bold;
    }

    .message.success {
      background-color: #d4edda;
      color: #155724;
      border: 1px solid #c3e6cb;
    }

    .message.error {
      background-color: #f8d7da;
      color: #721c24;
      border: 1px solid #f5c6cb;
    }

    .message.warning {
      background-color: #fff3cd;
      color: #856404;
      border: 1px solid #ffeaa7;
    }

    .info-text {
      color: #666;
      font-size: 13px;
      margin-top: 5px;
    }

    .current-dir {
      background-color: #e9ecef;
      padding: 10px;
      border-radius: 4px;
      margin-bottom: 20px;
      font-family: monospace;
    }

    .file-extension {
      background-color: #6c757d;
      color: white;
      padding: 2px 6px;
      border-radius: 3px;
      font-size: 11px;
      text-transform: uppercase;
    }
  </style>
</head>

<body>
  <div class="container">
    <h1>FTP File Uploader</h1>

    <div class="current-dir">
      <strong>Current Directory:</strong> <?php echo htmlspecialchars(getcwd()); ?>
    </div>

    <div class="message warning">
      <strong>Note:</strong> If your browser shows a timeout error during upload, don't worry! The upload process continues running in the background on the server and will complete successfully. Check your FTP server after a few minutes to confirm the file has been uploaded.
    </div>

    <?php if (!empty($upload_message)): ?>
      <div class="message <?php echo $message_type; ?>">
        <?php echo $upload_message; ?>
      </div>
    <?php endif; ?>

    <form method="post">
      <h2>1. Select File to Upload</h2>

      <?php if (count($current_files) > 0): ?>
        <table class="file-table">
          <thead>
            <tr>
              <th style="width: 50px;">Select</th>
              <th>File Name</th>
              <th style="width: 100px;">Size</th>
              <th style="width: 150px;">Last Modified</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($current_files as $file): ?>
              <tr>
                <td>
                  <input type="radio" name="selected_file" value="<?php echo htmlspecialchars($file['name']); ?>">
                </td>
                <td>
                  <?php echo htmlspecialchars($file['name']); ?>
                  <?php if (!empty($file['extension'])): ?>
                    <span class="file-extension"><?php echo htmlspecialchars($file['extension']); ?></span>
                  <?php endif; ?>
                </td>
                <td><?php echo htmlspecialchars($file['size']); ?></td>
                <td><?php echo htmlspecialchars($file['modified']); ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php else: ?>
        <div class="message warning">
          No files found in the current directory.
        </div>
      <?php endif; ?>

      <h3>OR Enter Custom File Path</h3>
      <div class="form-group">
        <input type="text" name="custom_file_path" class="custom-path-input"
          placeholder="e.g. subfolder/myfile.zip" size="60">
        <div class="info-text">
          Relative to current directory. If entered, this will override the selection above.
        </div>
      </div>

      <h2>2. FTP Connection Settings</h2>

      <div class="form-group">
        <label for="ftp_host">FTP Host:</label>
        <input type="text" id="ftp_host" name="ftp_host" required
          placeholder="ftp.example.com"
          value="<?php echo isset($_POST['ftp_host']) ? htmlspecialchars($_POST['ftp_host']) : ''; ?>">
      </div>

      <div class="form-group">
        <label for="ftp_user">FTP Username:</label>
        <input type="text" id="ftp_user" name="ftp_user" required
          placeholder="your-username"
          value="<?php echo isset($_POST['ftp_user']) ? htmlspecialchars($_POST['ftp_user']) : ''; ?>">
      </div>

      <div class="form-group">
        <label for="ftp_pass">FTP Password:</label>
        <input type="password" id="ftp_pass" name="ftp_pass" required
          placeholder="your-password">
      </div>

      <div class="form-group">
        <label for="remote_path">Remote FTP Path (optional):</label>
        <input type="text" id="remote_path" name="remote_path"
          placeholder="/backups or leave blank for root"
          value="<?php echo isset($_POST['remote_path']) ? htmlspecialchars($_POST['remote_path']) : ''; ?>">
        <div class="info-text">
          Leave blank to upload to the FTP root directory.
        </div>
      </div>

      <button type="submit" name="upload_ftp" class="submit-btn">
        Upload File to FTP
      </button>
    </form>
  </div>
</body>

</html>
