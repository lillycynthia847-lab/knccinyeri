<?php
require_once 'includes/config.php';

// Fetch all uploaded files across all directories
$uploadDir = '../uploads/';
$allFiles = [];

// Recursive function to get files
function getFiles($dir, &$results = []) {
    $files = scandir($dir);
    foreach ($files as $key => $value) {
        $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
        if (!is_dir($path)) {
            // Only grab images for the media library preview
            $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'])) {
                $results[] = [
                    'path' => str_replace('\\', '/', str_replace(realpath('../'), '', $path)),
                    'name' => basename($path),
                    'size' => round(filesize($path) / 1024, 1) . ' KB',
                    'date' => filemtime($path)
                ];
            }
        } else if ($value != "." && $value != "..") {
            getFiles($path, $results);
        }
    }
    return $results;
}

if (is_dir($uploadDir)) {
    getFiles($uploadDir, $allFiles);
}

// Sort by newest first
usort($allFiles, function($a, $b) {
    return $b['date'] - $a['date'];
});

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Media Library | Admin Panel</title>
  <link rel="stylesheet" href="css/admin.css">
  <style>
    .media-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
      gap: 24px;
    }
    .media-card {
      background: var(--admin-card);
      border: 1px solid var(--admin-border);
      border-radius: var(--radius-lg);
      overflow: hidden;
      box-shadow: var(--shadow-sm);
      transition: var(--transition);
      position: relative;
    }
    .media-card:hover {
      box-shadow: var(--shadow-md);
      transform: translateY(-2px);
    }
    .media-img-container {
      width: 100%;
      height: 160px;
      background: #f1f5f9;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
      border-bottom: 1px solid var(--admin-border);
    }
    .media-img-container img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.3s ease;
    }
    .media-card:hover .media-img-container img {
      transform: scale(1.05);
    }
    .media-info {
      padding: 16px;
    }
    .media-name {
      font-size: 0.8rem;
      font-weight: 600;
      color: var(--admin-text-main);
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      margin-bottom: 4px;
    }
    .media-meta {
      font-size: 0.75rem;
      color: var(--admin-text-muted);
      display: flex;
      justify-content: space-between;
    }
    .copy-btn {
      position: absolute;
      top: 8px;
      right: 8px;
      background: rgba(15, 23, 42, 0.8);
      color: white;
      border: none;
      padding: 6px 10px;
      border-radius: var(--radius-md);
      font-size: 0.7rem;
      cursor: pointer;
      opacity: 0;
      transition: var(--transition);
      backdrop-filter: blur(4px);
    }
    .media-card:hover .copy-btn {
      opacity: 1;
    }
    .copy-btn:hover {
      background: var(--admin-primary);
    }
  </style>
</head>
<body>

  <?php include 'includes/sidebar.php'; ?>

  <main class="main-content">
    <div class="page-header">
      <div>
        <h1 class="page-title">Media Library</h1>
        <p class="page-subtitle">All images uploaded to the system.</p>
      </div>
      <!-- Future feature: Upload direct from media library -->
      <button class="btn-admin" style="opacity: 0.5; cursor: not-allowed;" title="Upload via forms for now">
        Upload Image
      </button>
    </div>

    <div class="media-grid">
      <?php foreach ($allFiles as $file): ?>
      <div class="media-card">
        <button class="copy-btn" onclick="copyPath('..<?php echo htmlspecialchars($file['path']); ?>', this)">Copy Path</button>
        <div class="media-img-container">
          <img src="..<?php echo htmlspecialchars($file['path']); ?>" alt="<?php echo htmlspecialchars($file['name']); ?>" loading="lazy">
        </div>
        <div class="media-info">
          <div class="media-name" title="<?php echo htmlspecialchars($file['name']); ?>"><?php echo htmlspecialchars($file['name']); ?></div>
          <div class="media-meta">
            <span><?php echo $file['size']; ?></span>
            <span><?php echo date('M d, Y', $file['date']); ?></span>
          </div>
        </div>
      </div>
      <?php endforeach; ?>

      <?php if(empty($allFiles)): ?>
        <p style="grid-column: 1 / -1; color: #64748B;">No images found in the uploads directory.</p>
      <?php endif; ?>
    </div>

  </main>

  <script>
    function copyPath(path, btn) {
      navigator.clipboard.writeText(path).then(function() {
        const originalText = btn.innerText;
        btn.innerText = "Copied!";
        btn.style.background = "var(--admin-success)";
        setTimeout(() => {
          btn.innerText = originalText;
          btn.style.background = "rgba(15, 23, 42, 0.8)";
        }, 2000);
      });
    }
  </script>
</body>
</html>
