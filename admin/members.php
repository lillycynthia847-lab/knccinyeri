<?php
require_once 'includes/config.php';
$stmt = $pdo->query("SELECT * FROM members ORDER BY registered_at DESC");
$members = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Members | Admin Panel</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/admin.css">
  <style>
    .status-badge { padding: 4px 10px; border-radius: 50px; font-size: 0.8rem; font-weight: 600; }
    .status-pending { background: #fef3c7; color: #d97706; }
    .status-active { background: #dcfce7; color: #166534; }
    .status-suspended { background: #fee2e2; color: #991b1b; }
    .action-links a { color: var(--admin-green); font-weight: 600; font-size: 0.85rem; text-decoration: none; margin-right: 12px; }
    .action-links a.delete { color: #ef4444; }
    .action-links a:hover { text-decoration: underline; }
  </style>
</head>
<body>
  <?php include 'includes/sidebar.php'; ?>
  <main class="main-content">
    <div class="page-header">
      <div>
        <h1 class="page-title">Members & Applications</h1>
        <p style="color: #64748B; margin-top: 8px;">Review, approve, and manage chapter registrations.</p>
      </div>
    </div>
    <div class="admin-table-wrapper">
      <table class="admin-table">
        <thead>
          <tr><th>Business Name</th><th>Contact Person</th><th>Email / Phone</th><th>Sector</th><th>Registered</th><th>Status</th><th>Actions</th></tr>
        </thead>
        <tbody>
          <?php foreach ($members as $m): ?>
          <tr>
            <td style="font-weight: 500; color: var(--admin-navy);"><?php echo htmlspecialchars($m['business_name']); ?></td>
            <td><?php echo htmlspecialchars($m['contact_person']); ?></td>
            <td>
              <div style="font-size:0.9rem;"><?php echo htmlspecialchars($m['email']); ?></div>
              <div style="font-size:0.8rem; color:#64748B;"><?php echo htmlspecialchars($m['phone']); ?></div>
            </td>
            <td><?php echo htmlspecialchars($m['sector']); ?></td>
            <td style="color: #64748B;"><?php echo date('M d, Y', strtotime($m['registered_at'])); ?></td>
            <td><span class="status-badge status-<?php echo strtolower($m['status']); ?>"><?php echo $m['status']; ?></span></td>
            <td class="action-links">
              <a href="members-edit.php?id=<?php echo $m['id']; ?>">Status</a>
              <a href="members-delete.php?id=<?php echo $m['id']; ?>" class="delete" onclick="return confirm('Delete this member?');">Delete</a>
            </td>
          </tr>
          <?php endforeach; ?>
          <?php if(empty($members)): ?>
          <tr><td colspan="7" style="text-align:center; padding:32px; color:#64748B;">No member registrations found.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </main>
</body>
</html>
