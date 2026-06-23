<?php
require_once 'includes/config.php';
$stmt = $pdo->query("SELECT * FROM events ORDER BY event_date DESC");
$events = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Events | Admin Panel</title>
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
        <h1 class="page-title">Manage Events</h1>
        <p style="color: #64748B; margin-top: 8px;">Create, edit, and manage upcoming and past events.</p>
      </div>
      <a href="events-add.php" class="btn-admin">+ Create Event</a>
    </div>
    <div class="admin-table-wrapper">
      <table class="admin-table">
        <thead>
          <tr><th>Date</th><th>Title</th><th>Location</th><th>Type</th><th>Status</th><th>Actions</th></tr>
        </thead>
        <tbody>
          <?php foreach ($events as $e): ?>
          <tr>
            <td style="color: #64748B;"><?php echo date('M d, Y', strtotime($e['event_date'])); ?></td>
            <td style="font-weight: 500; color: var(--admin-navy);"><?php echo htmlspecialchars($e['title']); ?></td>
            <td><?php echo htmlspecialchars($e['location']); ?></td>
            <td><?php echo htmlspecialchars($e['event_type']); ?></td>
            <td><span class="status-badge status-<?php echo strtolower($e['status']); ?>"><?php echo $e['status']; ?></span></td>
            <td class="action-links">
              <a href="events-edit.php?id=<?php echo $e['id']; ?>">Edit</a>
              <a href="events-delete.php?id=<?php echo $e['id']; ?>" class="delete" onclick="return confirm('Delete this event?');">Delete</a>
            </td>
          </tr>
          <?php endforeach; ?>
          <?php if(empty($events)): ?>
          <tr><td colspan="6" style="text-align:center; padding:32px; color:#64748B;">No events found. Create one above!</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </main>
</body>
</html>
