<?php
require_once 'includes/config.php';
$stmt = $pdo->query("SELECT * FROM management_team ORDER BY team_group ASC, display_order ASC");
$team = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Leadership | Admin Panel</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/admin.css">
  <style>
    .btn-admin { background: var(--admin-primary); color: #fff; padding: 10px 20px; border: none; border-radius: 8px; font-weight: 600; font-size: 0.9rem; cursor: pointer; text-decoration: none; display: inline-block; transition: var(--transition); }
    .btn-admin:hover { background: var(--admin-primary-hover); }
    .status-badge { padding: 4px 10px; border-radius: 50px; font-size: 0.8rem; font-weight: 600; }
    .status-active { background: #dcfce7; color: #166534; }
    .status-former { background: #f1f5f9; color: #475569; }
    .action-links a { color: var(--admin-green); font-weight: 600; font-size: 0.85rem; text-decoration: none; margin-right: 12px; }
    .action-links a.delete { color: #ef4444; }
    .action-links a:hover { text-decoration: underline; }
    .avatar-preview { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; background: #e2e8f0; display: inline-block; vertical-align: middle; margin-right: 8px; }
  </style>
</head>
<body>
  <?php include 'includes/sidebar.php'; ?>
  <main class="main-content">
    <div class="page-header">
      <div>
        <h1 class="page-title">Manage Leadership & Management</h1>
        <p style="color: #64748B; margin-top: 8px;">Add and update Board of Directors and Sub-County Heads.</p>
      </div>
      <a href="management-add.php" class="btn-admin">+ Add Leader</a>
    </div>
    <div class="admin-table-wrapper">
      <table class="admin-table">
        <thead>
          <tr><th>Leader</th><th>Position</th><th>Group</th><th>Order</th><th>Status</th><th>Actions</th></tr>
        </thead>
        <tbody>
          <?php foreach ($team as $member): ?>
          <tr>
            <td style="font-weight: 500; color: var(--admin-navy);">
              <?php if($member['profile_image']): ?>
                <img src="../<?php echo $member['profile_image']; ?>" class="avatar-preview" alt="">
              <?php else: ?>
                <div class="avatar-preview" style="display:inline-flex; align-items:center; justify-content:center; font-weight:bold; color:#64748B;">
                  <?php 
                    $words = explode(' ', $member['full_name']);
                    echo htmlspecialchars(strtoupper(substr($words[0] ?? '', 0, 1) . substr($words[1] ?? '', 0, 1)));
                  ?>
                </div>
              <?php endif; ?>
              <?php echo htmlspecialchars($member['full_name']); ?>
            </td>
            <td><?php echo htmlspecialchars($member['position']); ?></td>
            <td><?php echo htmlspecialchars($member['team_group']); ?></td>
            <td><?php echo $member['display_order']; ?></td>
            <td><span class="status-badge status-<?php echo strtolower($member['status']); ?>"><?php echo $member['status']; ?></span></td>
            <td class="action-links">
              <a href="management-edit.php?id=<?php echo $member['id']; ?>">Edit</a>
              <a href="management-delete.php?id=<?php echo $member['id']; ?>" class="delete" onclick="return confirm('Delete this member from leadership?');">Delete</a>
            </td>
          </tr>
          <?php endforeach; ?>
          <?php if(empty($team)): ?>
          <tr><td colspan="6" style="text-align:center; padding:32px; color:#64748B;">No team members registered. Add one above!</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </main>
</body>
</html>
