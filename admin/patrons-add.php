<?php
require_once 'includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $company_name = trim($_POST['company_name']);
    $display_order = (int)$_POST['display_order'];
    $status = $_POST['status'];

    $logo_url = '';

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
                $logo_url = 'Images/patrons/' . $fileName;
            }
        }
    }

    $stmt = $pdo->prepare("INSERT INTO patrons (company_name, logo_url, display_order, status) VALUES (?, ?, ?, ?)");
    $stmt->execute([$company_name, $logo_url, $display_order, $status]);

    header("Location: patrons.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Patron | Admin Panel</title>
  <link rel="stylesheet" href="css/admin.css">
</head>
<body>

  <?php include 'includes/sidebar.php'; ?>

  <main class="main-content">
    <div class="page-header">
      <div>
        <h1 class="page-title">Add New Patron</h1>
        <p class="page-subtitle">Add a company logo to the homepage patrons marquee.</p>
      </div>
    </div>

    <div class="form-wrapper">
      <form action="" method="POST" enctype="multipart/form-data">
        
        <div class="form-group">
          <label for="company_name">Company Name</label>
          <input type="text" id="company_name" name="company_name" class="form-control" placeholder="Enter the company name..." required>
        </div>

        <div class="form-group">
          <label for="logo">Company Logo</label>
          <input type="file" id="logo" name="logo" class="form-control" accept="image/*">
          <small style="color:#64748B; display:block; margin-top:6px;">Recommended: 200×120px. Formats: PNG, JPG, SVG, WebP.</small>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="display_order">Display Order</label>
            <input type="number" id="display_order" name="display_order" class="form-control" value="0">
            <small style="color:#64748B;">Lower numbers appear first in the marquee.</small>
          </div>
          
          <div class="form-group">
            <label for="status">Status</label>
            <select id="status" name="status" class="form-control">
              <option value="Active">Active</option>
              <option value="Inactive">Inactive</option>
            </select>
          </div>
        </div>

        <div style="margin-top: 32px; border-top: 1px solid var(--admin-border); padding-top: 24px;">
          <a href="patrons.php" class="btn-cancel">Cancel</a>
          <button type="submit" class="btn-admin">Save Patron</button>
        </div>

      </form>
    </div>

  </main>

</body>
</html>
