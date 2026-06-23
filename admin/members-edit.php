<?php
require_once 'includes/config.php';
if (!isset($_GET['id'])) { header("Location: members.php"); exit; }
$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM members WHERE id = ?"); $stmt->execute([$id]); $member = $stmt->fetch();
if (!$member) { header("Location: members.php"); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("UPDATE members SET status=? WHERE id=?");
    $stmt->execute([$_POST['status'], $id]);
    header("Location: members.php"); exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Update Member Status | Admin Panel</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/admin.css">
  <style>
    .form-wrapper { background: var(--admin-card); border: 1px solid var(--admin-border); border-radius: 12px; padding: 32px; max-width: 500px; }
    .form-group { margin-bottom: 24px; }
    .form-group label { display: block; font-size: 0.9rem; font-weight: 600; color: var(--admin-navy); margin-bottom: 8px; }
    .form-control { width: 100%; padding: 12px 16px; border: 1px solid var(--admin-border); border-radius: 8px; font-family: inherit; font-size: 0.95rem; transition: var(--transition); }
    .form-control:focus { outline: none; border-color: var(--admin-green); box-shadow: 0 0 0 3px rgba(22,163,74,0.1); }
    .btn-admin { background: var(--admin-green); color: #fff; padding: 12px 24px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; }
    .btn-cancel { background: #F1F5F9; color: #475569; padding: 12px 24px; border: none; border-radius: 8px; font-weight: 600; text-decoration: none; margin-right: 12px; display: inline-block; }
  </style>
</head>
<body>
  <?php include 'includes/sidebar.php'; ?>
  <main class="main-content">
    <div class="page-header"><h1 class="page-title">Change Membership Status</h1></div>
    <div class="form-wrapper">
      <div style="margin-bottom:24px;">
        <h3 style="color:var(--admin-navy); margin-bottom:8px;"><?php echo htmlspecialchars($member['business_name']); ?></h3>
        <p style="color:#64748B; font-size:0.95rem;">Contact: <?php echo htmlspecialchars($member['contact_person']); ?> (<?php echo htmlspecialchars($member['email']); ?>)</p>
      </div>
      <form action="" method="POST">
        <div class="form-group">
          <label>Membership Status</label>
          <select name="status" class="form-control" required>
            <option <?php echo $member['status']=='Pending'?'selected':''; ?>>Pending</option>
            <option <?php echo $member['status']=='Active'?'selected':''; ?>>Active</option>
            <option <?php echo $member['status']=='Suspended'?'selected':''; ?>>Suspended</option>
          </select>
        </div>
        <div style="margin-top:32px; border-top:1px solid var(--admin-border); padding-top:24px;">
          <a href="members.php" class="btn-cancel">Cancel</a>
          <button type="submit" class="btn-admin">Update Status</button>
        </div>
      </form>
    </div>
  </main>
</body>
</html>
