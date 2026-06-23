<?php
require_once 'includes/config.php';

if (!isset($_GET['id'])) {
    header("Location: patrons.php");
    exit;
}

$id = (int)$_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM patrons WHERE id = ?");
$stmt->execute([$id]);
$patron = $stmt->fetch();

if (!$patron) {
    header("Location: patrons.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $company_name = trim($_POST['company_name']);
    $display_order = (int)$_POST['display_order'];
    $status = $_POST['status'];

    $logo_url = $patron['logo_url']; // Keep old by default

    // Handle logo upload
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        $allowedExts = ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'];
        $fileExt = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));

        if (in_array($fileExt, $allowedExts)) {
            $uploadDir = '../Images/patrons/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $fileName = time() . '_' . preg_replace("/[^a-zA-Z0-9.-]/", "_", $_FILES['logo']['name']);
            $destination = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['logo']['tmp_name'], $destination)) {
                // Delete old logo
                if (!empty($patron['logo_url']) && file_exists('../' . $patron['logo_url'])) {
                    unlink('../' . $patron['logo_url']);
                }
                $logo_url = 'Images/patrons/' . $fileName;
            }
        }
    }

    $stmt = $pdo->prepare("UPDATE patrons SET company_name = ?, logo_url = ?, display_order = ?, status = ? WHERE id = ?");
    $stmt->execute([$company_name, $logo_url, $display_order, $status, $id]);

    header("Location: patrons.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Patron | Admin Panel</title>
  <link rel="stylesheet" href="css/admin.css">
  <style>
    .current-logo-box { margin-bottom: 16px; border: 1px solid var(--admin-border); border-radius: var(--radius-md); padding: 16px; background: var(--admin-bg); display: inline-block; }
    .current-logo-box img { max-height: 80px; max-width: 200px; object-fit: contain; }
  </style>
</head>
<body>

  <?php include 'includes/sidebar.php'; ?>

  <main class="main-content">
    <div class="page-header">
      <div>
        <h1 class="page-title">Edit Patron</h1>
        <p class="page-subtitle">Update the details for <?php echo htmlspecialchars($patron['company_name']); ?>.</p>
      </div>
    </div>

    <div class="form-wrapper">
      <form action="" method="POST" enctype="multipart/form-data">
        
        <div class="form-group">
          <label for="company_name">Company Name</label>
          <input type="text" id="company_name" name="company_name" class="form-control" value="<?php echo htmlspecialchars($patron['company_name']); ?>" required>
        </div>

        <div class="form-group">
          <label>Current Logo</label>
          <?php if (!empty($patron['logo_url'])): ?>
            <div class="current-logo-box">
              <img src="../<?php echo htmlspecialchars($patron['logo_url']); ?>" alt="Current Logo">
            </div>
          <?php else: ?>
            <p style="color:#64748B; margin-bottom:12px;">No logo uploaded yet.</p>
          <?php endif; ?>
          
          <label for="logo">Upload New Logo (Optional)</label>
          <input type="file" id="logo" name="logo" class="form-control" accept="image/*">
          <small style="color:#64748B; display:block; margin-top:6px;">Recommended: 200×120px. Leave blank to keep current logo.</small>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="display_order">Display Order</label>
            <input type="number" id="display_order" name="display_order" class="form-control" value="<?php echo $patron['display_order']; ?>">
          </div>
          
          <div class="form-group">
            <label for="status">Status</label>
            <select id="status" name="status" class="form-control">
              <option value="Active" <?php if($patron['status'] === 'Active') echo 'selected'; ?>>Active</option>
              <option value="Inactive" <?php if($patron['status'] === 'Inactive') echo 'selected'; ?>>Inactive</option>
            </select>
          </div>
        </div>

        <div style="margin-top: 32px; border-top: 1px solid var(--admin-border); padding-top: 24px;">
          <a href="patrons.php" class="btn-cancel">Cancel</a>
          <button type="submit" class="btn-admin">Update Patron</button>
        </div>

      </form>
    </div>

  </main>

</body>
</html>
