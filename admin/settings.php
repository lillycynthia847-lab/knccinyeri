<?php
require_once 'includes/config.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST['settings'] as $key => $value) {
        $stmt = $pdo->prepare("UPDATE site_settings SET setting_value = ? WHERE setting_key = ?");
        $stmt->execute([$value, $key]);
    }

    // Handle image uploads
    if (!empty($_FILES['images']['name'])) {
        foreach ($_FILES['images']['name'] as $key => $fileName) {
            if ($_FILES['images']['error'][$key] === UPLOAD_ERR_OK) {
                $uploadDir = '../uploads/site/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

                $newName = time() . '_' . basename($fileName);
                $target = $uploadDir . $newName;

                if (move_uploaded_file($_FILES['images']['tmp_name'][$key], $target)) {
                    $imgPath = 'uploads/site/' . $newName;
                    $stmt = $pdo->prepare("UPDATE site_settings SET setting_value = ? WHERE setting_key = ?");
                    $stmt->execute([$imgPath, $key]);
                }
            }
        }
    }

    header("Location: settings.php?saved=1");
    exit;
}

// Fetch only specific settings grouped
$stmt = $pdo->query("SELECT * FROM site_settings WHERE setting_group IN ('hero_gallery', 'contact', 'social') ORDER BY id ASC");
$allSettings = $stmt->fetchAll();

$grouped = [];
foreach ($allSettings as $s) {
    $grouped[$s['setting_group']][] = $s;
}

// Friendly group names
$groupLabels = [
    'hero_gallery' => 'Hero Gallery Images',
    'contact' => 'Contact Info & Footer',
    'social' => 'Social Media Links',
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Site Settings | Admin Panel</title>
  <link rel="stylesheet" href="css/admin.css">
  <style>
    .settings-section {
      background: var(--admin-card);
      border: 1px solid var(--admin-border);
      border-radius: var(--radius-xl);
      padding: 40px;
      margin-bottom: 32px;
      box-shadow: var(--shadow-sm);
    }
    .settings-section h2 {
      font-size: 1.2rem;
      color: var(--admin-text-main);
      margin-bottom: 32px;
      padding-bottom: 16px;
      border-bottom: 1px solid var(--admin-border);
      font-weight: 700;
    }
    .img-preview-container {
      width: 100%;
      max-width: 400px;
      height: 200px;
      border-radius: var(--radius-md);
      overflow: hidden;
      margin-bottom: 16px;
      border: 1px solid var(--admin-border);
      background: #f1f5f9;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .img-preview-container img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
    .img-preview-placeholder {
      color: #94a3b8;
      font-size: 0.9rem;
      font-weight: 500;
    }
    .custom-file-upload {
      border: 1px dashed var(--admin-primary);
      display: inline-block;
      padding: 12px 24px;
      cursor: pointer;
      border-radius: var(--radius-md);
      color: var(--admin-primary);
      font-weight: 600;
      background: rgba(59, 130, 246, 0.05);
      transition: var(--transition);
      width: 100%;
      text-align: center;
    }
    .custom-file-upload:hover {
      background: rgba(59, 130, 246, 0.1);
    }
    input[type="file"] {
      display: none;
    }
  </style>
</head>
<body>

  <?php include 'includes/sidebar.php'; ?>

  <main class="main-content">
    <div class="page-header">
      <div>
        <h1 class="page-title">Site Settings</h1>
        <p class="page-subtitle">Manage gallery images, contact info, and social links.</p>
      </div>
      <button type="submit" form="settingsForm" class="btn-admin">
        Save Changes
      </button>
    </div>

    <?php if (isset($_GET['saved'])): ?>
    <div style="background: #dcfce7; color: #166534; padding: 16px 24px; border-radius: var(--radius-md); margin-bottom: 32px; font-weight: 500;">
      ✓ Settings saved successfully. Refresh your live website to see the changes.
    </div>
    <?php endif; ?>

    <form id="settingsForm" action="" method="POST" enctype="multipart/form-data">

      <?php foreach ($grouped as $group => $settings): ?>
      <div class="settings-section">
        <h2><?php echo $groupLabels[$group] ?? ucfirst(str_replace('_', ' ', $group)); ?></h2>

        <div class="form-row">
        <?php foreach ($settings as $s): ?>
          <?php if (strpos($s['setting_key'], 'caption') !== false) continue; ?>
          <div class="form-group" style="<?php echo $s['setting_type'] === 'textarea' ? 'grid-column: 1 / -1;' : ''; ?>">
            <label for="<?php echo $s['setting_key']; ?>">
              <?php echo htmlspecialchars($s['setting_label'] ?? ucwords(str_replace('_', ' ', $s['setting_key']))); ?>
            </label>

            <?php if ($s['setting_type'] === 'textarea'): ?>
              <textarea id="<?php echo $s['setting_key']; ?>" name="settings[<?php echo $s['setting_key']; ?>]" class="form-control"><?php echo htmlspecialchars($s['setting_value']); ?></textarea>

            <?php elseif ($s['setting_type'] === 'image'): ?>
              <div class="img-preview-container">
                <?php if ($s['setting_value']): ?>
                  <img src="../<?php echo htmlspecialchars($s['setting_value']); ?>" id="preview-<?php echo $s['setting_key']; ?>" alt="Preview">
                <?php else: ?>
                  <img src="" id="preview-<?php echo $s['setting_key']; ?>" alt="Preview" style="display: none;">
                  <span class="img-preview-placeholder" id="placeholder-<?php echo $s['setting_key']; ?>">No image uploaded</span>
                <?php endif; ?>
              </div>
              <label class="custom-file-upload">
                <input type="file" name="images[<?php echo $s['setting_key']; ?>]" accept="image/*" onchange="previewImage(this, '<?php echo $s['setting_key']; ?>')">
                Choose New Image
              </label>

            <?php else: ?>
              <input type="<?php echo $s['setting_type'] === 'number' ? 'number' : 'text'; ?>" id="<?php echo $s['setting_key']; ?>" name="settings[<?php echo $s['setting_key']; ?>]" class="form-control" value="<?php echo htmlspecialchars($s['setting_value']); ?>">
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
        </div>

      </div>
      <?php endforeach; ?>

    </form>
  </main>

  <script>
    function previewImage(input, key) {
      if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
          const img = document.getElementById('preview-' + key);
          const placeholder = document.getElementById('placeholder-' + key);
          
          img.src = e.target.result;
          img.style.display = 'block';
          if(placeholder) placeholder.style.display = 'none';
        }
        reader.readAsDataURL(input.files[0]);
      }
    }
  </script>
</body>
</html>l>
