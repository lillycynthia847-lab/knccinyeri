<?php
require_once 'includes/config.php';
if (!isset($_GET['id'])) { header("Location: management.php"); exit; }
$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM management_team WHERE id = ?"); $stmt->execute([$id]); $member = $stmt->fetch();
if (!$member) { header("Location: management.php"); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $profile_image = $member['profile_image'];
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/team/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
        $fileName = time() . '_' . basename($_FILES['profile_image']['name']);
        if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $uploadDir . $fileName)) {
            if ($profile_image && file_exists('../' . $profile_image)) unlink('../' . $profile_image);
            $profile_image = 'uploads/team/' . $fileName;
        }
    }
    
    $stmt = $pdo->prepare("UPDATE management_team SET full_name=?, position=?, team_group=?, bio=?, profile_image=?, display_order=?, status=? WHERE id=?");
    $stmt->execute([
        $_POST['full_name'],
        $_POST['position'],
        $_POST['team_group'],
        $_POST['bio'] ?: null,
        $profile_image,
        (int)$_POST['display_order'],
        $_POST['status'],
        $id
    ]);
    header("Location: management.php"); exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Leader | Admin Panel</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/admin.css">
  <style>
    .form-wrapper { background: var(--admin-card); border: 1px solid var(--admin-border); border-radius: 12px; padding: 32px; max-width: 800px; }
    .form-group { margin-bottom: 24px; }
    .form-group label { display: block; font-size: 0.9rem; font-weight: 600; color: var(--admin-navy); margin-bottom: 8px; }
    .form-control { width: 100%; padding: 12px 16px; border: 1px solid var(--admin-border); border-radius: 8px; font-family: inherit; font-size: 0.95rem; transition: var(--transition); }
    .form-control:focus { outline: none; border-color: var(--admin-primary); box-shadow: 0 0 0 3px rgba(59,130,246,0.1); }
    textarea.form-control { min-height: 120px; resize: vertical; }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
    .form-row-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 24px; }
    .btn-admin { background: var(--admin-primary); color: #fff; padding: 12px 24px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: var(--transition); }
    .btn-admin:hover { background: var(--admin-primary-hover); }
    .btn-cancel { background: #F1F5F9; color: #475569; padding: 12px 24px; border: none; border-radius: 8px; font-weight: 600; text-decoration: none; margin-right: 12px; display: inline-block; }
  </style>
</head>
<body>
  <?php include 'includes/sidebar.php'; ?>
  <main class="main-content">
    <div class="page-header"><h1 class="page-title">Edit Team Member / Leader</h1></div>
    <div class="form-wrapper">
      <form action="" method="POST" enctype="multipart/form-data">
        <div class="form-group"><label>Full Name</label><input type="text" name="full_name" class="form-control" value="<?php echo htmlspecialchars($member['full_name']); ?>" required></div>
        <div class="form-row">
          <div class="form-group"><label>Position / Title</label><input type="text" name="position" class="form-control" value="<?php echo htmlspecialchars($member['position']); ?>" required></div>
          <div class="form-group"><label>Group / Category</label>
            <select name="team_group" class="form-control" required>
              <option value="Board of Directors" <?php echo $member['team_group']=='Board of Directors'?'selected':''; ?>>Board of Directors</option>
              <option value="Sub-County Head" <?php echo $member['team_group']=='Sub-County Head'?'selected':''; ?>>Sub-County Head</option>
            </select>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group"><label>Display Order (Lower numbers show first)</label><input type="number" name="display_order" class="form-control" value="<?php echo $member['display_order']; ?>"></div>
          <div class="form-group"><label>Status</label>
            <select name="status" class="form-control">
              <option <?php echo $member['status']=='Active'?'selected':''; ?>>Active</option>
              <option <?php echo $member['status']=='Former'?'selected':''; ?>>Former</option>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label>Profile Photo</label>
          <?php if($member['profile_image']): ?><img src="../<?php echo $member['profile_image']; ?>" style="max-height:80px;border-radius:8px;margin-bottom:8px;display:block;"><?php endif; ?>
          <input type="file" name="profile_image" class="form-control" accept="image/*">
        </div>
        <div class="form-group"><label>Short Bio (optional)</label><textarea name="bio" class="form-control"><?php echo htmlspecialchars($member['bio'] ?? ''); ?></textarea></div>
        <div style="margin-top:32px; border-top:1px solid var(--admin-border); padding-top:24px;">
          <a href="management.php" class="btn-cancel">Cancel</a>
          <button type="submit" class="btn-admin">Update Member</button>
        </div>
      </form>
    </div>
  </main>
</body>
</html>
