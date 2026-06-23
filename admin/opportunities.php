<?php
require_once 'includes/config.php';
$stmt = $pdo->query("SELECT * FROM opportunities ORDER BY created_at DESC");
$opportunities = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Opportunities | Admin Panel</title>
  <link rel="stylesheet" href="css/admin.css">
  <style>
    .action-links a { color: var(--admin-primary); font-weight: 600; font-size: 0.85rem; text-decoration: none; margin-right: 12px; }
    .action-links a.delete { color: var(--admin-danger); }
    .action-links a:hover { text-decoration: underline; }
  </style>
</head>
<body>
  <?php include 'includes/sidebar.php'; ?>
  <main class="main-content">
    <div class="page-header">
      <div>
        <h1 class="page-title">Manage Opportunities</h1>
        <p style="color: #64748B; margin-top: 8px;">Manage Tenders, Grants, Jobs, and Vendor Opportunities.</p>
      </div>
      <a href="opportunities-add.php" class="btn-admin">+ Create Opportunity</a>
    </div>
    <div class="admin-table-wrapper">
      <table class="admin-table">
        <thead>
          <tr><th>Title</th><th>Type</th><th>Deadline</th><th>Status</th><th>Actions</th></tr>
        </thead>
        <tbody>
          <?php foreach ($opportunities as $opp): ?>
          <tr>
            <td style="font-weight: 500; color: var(--admin-navy);"><?php echo htmlspecialchars($opp['title']); ?></td>
            <td><?php echo htmlspecialchars($opp['opp_type']); ?></td>
            <td style="color: #64748B;"><?php echo $opp['deadline'] ? date('M d, Y', strtotime($opp['deadline'])) : 'N/A'; ?></td>
            <td><span class="status-badge status-<?php echo strtolower($opp['status']); ?>"><?php echo $opp['status']; ?></span></td>
            <td class="action-links">
              <a href="opportunities-edit.php?id=<?php echo $opp['id']; ?>">Edit</a>
              <a href="opportunities-delete.php?id=<?php echo $opp['id']; ?>" class="delete" onclick="return confirm('Delete this opportunity?');">Delete</a>
            </td>
          </tr>
          <?php endforeach; ?>
          <?php if(empty($opportunities)): ?>
          <tr><td colspan="5" style="text-align:center; padding:32px; color:#64748B;">No opportunities found. Create one above!</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </main>
</body>
</html>
