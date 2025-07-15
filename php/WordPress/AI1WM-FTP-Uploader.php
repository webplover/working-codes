<?php

/**
 * Plugin Name: AI1WM FTP Uploader
 * Description: Upload All-in-One WP Migration (.wpress) backups to a remote FTP server.
 * Version: 2.0
 * Author: WebPlover
 */

add_action('admin_menu', function () {
  add_menu_page('AI1WM FTP Uploader', 'AI1WM FTP Uploader', 'manage_options', 'ai1wm-ftp-uploader', 'ai1wm_ftp_uploader_page');
});

function ai1wm_ftp_uploader_page()
{
  $backup_dir = WP_CONTENT_DIR . '/ai1wm-backups';
  $wpress_files = [];

  // Collect all .wpress files
  $files = glob($backup_dir . '/*.wpress');

  foreach ($files as $file) {
    if (is_file($file)) {
      $wpress_files[] = [
        'path' => $file,
        'name' => basename($file),
        'size' => size_format(filesize($file), 2),
        'modified' => date("Y-m-d H:i:s", filemtime($file)),
      ];
    }
  }


  if (isset($_POST['upload_ftp'])) {
    $ftp_host = sanitize_text_field($_POST['ftp_host']);
    $ftp_user = sanitize_text_field($_POST['ftp_user']);
    $ftp_pass = sanitize_text_field($_POST['ftp_pass']);
    $remote_path = trim(sanitize_text_field($_POST['remote_path']));

    $selected_file = sanitize_text_field($_POST['selected_file']);
    $custom_file_path = sanitize_text_field($_POST['custom_file_path']);
    $local_path = '';

    if (!empty($custom_file_path)) {
      $local_path = ABSPATH . ltrim($custom_file_path, '/');
    } elseif (!empty($selected_file)) {
      $local_path = $backup_dir . '/' . basename($selected_file);
    }

    if (empty($local_path) || !file_exists($local_path)) {
      echo "<p style='color:red;'>Selected file does not exist.</p>";
      return;
    }

    if (!is_file($local_path)) {
      echo "<p style='color:red;'>Selected path is not a file.</p>";
      return;
    }


    if (empty($remote_path)) {
      $remote_path = '.'; // Upload to root
    }

    $conn_id = ftp_connect($ftp_host);
    if (!$conn_id || !ftp_login($conn_id, $ftp_user, $ftp_pass)) {
      echo "<p style='color:red;'>FTP connection or login failed.</p>";
      return;
    }

    ftp_pasv($conn_id, true); // Passive mode

    $target_file = ($remote_path === '.') ? basename($local_path) : "$remote_path/" . basename($local_path);
    if (ftp_put($conn_id, $target_file, $local_path, FTP_BINARY)) {
      echo "<p style='color:green;'>Backup uploaded to FTP successfully!</p>";
    } else {
      echo "<p style='color:red;'>FTP upload failed.</p>";
    }

    ftp_close($conn_id);
  }

?>
  <div class="wrap">
    <h1>Upload AI1WM Backups to FTP</h1>
    <form method="post">
      <h2>1. Select Backup File</h2>
      <?php if (count($wpress_files) > 0): ?>
        <table class="widefat">
          <thead>
            <tr>
              <th>Select</th>
              <th>File</th>
              <th>Size</th>
              <th>Last Modified</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($wpress_files as $file): ?>
              <tr>
                <td><input type="radio" name="selected_file" value="<?php echo esc_attr($file['name']); ?>"></td>
                <td><?php echo esc_html($file['name']); ?></td>
                <td><?php echo esc_html($file['size']); ?></td>
                <td><?php echo esc_html($file['modified']); ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php else: ?>
        <p style="color:orange;">No .wpress files found in wp-content/ai1wm-backups/</p>
      <?php endif; ?>

      <h3>OR Enter Custom File Path</h3>
      <p>
        <input type="text" name="custom_file_path" placeholder="e.g. wp-content/myfolder/mybackup.wpress" size="60">
        <br><small>Relative to your WordPress root. If entered, this will override selection above.</small>
      </p>

      <h2>2. FTP Settings</h2>
      <label>FTP Host:</label><br>
      <input type="text" name="ftp_host" required><br><br>

      <label>FTP Username:</label><br>
      <input type="text" name="ftp_user" required><br><br>

      <label>FTP Password:</label><br>
      <input type="password" name="ftp_pass" required><br><br>

      <label>Remote FTP Path (leave blank for root):</label><br>
      <input type="text" name="remote_path" placeholder="e.g. /backups"><br><br>

      <input type="submit" name="upload_ftp" value="Upload Backup to FTP" class="button button-primary">
    </form>
  </div>
<?php
}
