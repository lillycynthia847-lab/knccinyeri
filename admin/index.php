<?php
require_once 'includes/config.php';

// Fetch stats
$totalMembers = $pdo->query("SELECT COUNT(*) FROM members")->fetchColumn();
$activeEvents = $pdo->query("SELECT COUNT(*) FROM events WHERE status = 'Upcoming'")->fetchColumn();
$newsCount = $pdo->query("SELECT COUNT(*) FROM news")->fetchColumn();
$pendingOpps = $pdo->query("SELECT COUNT(*) FROM opportunities WHERE status = 'Open'")->fetchColumn();

// Fetch recent members
$recentMembers = $pdo->query("SELECT * FROM members ORDER BY registered_at DESC LIMIT 5")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard | KNCCI Nyeri</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/admin.css">
  <style>
    .status-badge { padding: 4px 10px; border-radius: 50px; font-size: 0.8rem; font-weight: 600; }
    .status-pending { background: #fef3c7; color: #d97706; }
    .status-active { background: #dcfce7; color: #166534; }
    .status-suspended { background: #fee2e2; color: #991b1b; }
  </style>
</head>
<body>

  <!-- Include Sidebar -->
  <?php include 'includes/sidebar.php'; ?>

  <!-- Main Content -->
  <main class="main-content">
    <div class="page-header">
      <h1 class="page-title">Dashboard Overview</h1>
    </div>

    <!-- Stats Grid -->
    <div class="dash-grid">
      <div class="dash-card">
        <h3>Total Members</h3>
        <div class="value"><?php echo $totalMembers; ?></div>
      </div>
      <div class="dash-card">
        <h3>Upcoming Events</h3>
        <div class="value"><?php echo $activeEvents; ?></div>
      </div>
      <div class="dash-card">
        <h3>News Articles</h3>
        <div class="value"><?php echo $newsCount; ?></div>
      </div>
      <div class="dash-card">
        <h3>Open Opportunities</h3>
        <div class="value"><?php echo $pendingOpps; ?></div>
      </div>
    </div>

    <!-- Recent Activity Table -->
    <h2 style="margin-bottom: 24px; color: var(--admin-navy); font-size: 1.2rem; margin-top: 40px;">Recent Member Registrations</h2>
    <div class="admin-table-wrapper">
      <table class="admin-table">
        <thead>
          <tr>
            <th>Date</th>
            <th>Business Name</th>
            <th>Sector</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($recentMembers as $m): ?>
          <tr>
            <td style="color: #64748B;"><?php echo date('M d, Y', strtotime($m['registered_at'])); ?></td>
            <td style="font-weight: 500; color: var(--admin-navy);"><?php echo htmlspecialchars($m['business_name']); ?></td>
            <td><?php echo htmlspecialchars($m['sector']); ?></td>
            <td><span class="status-badge status-<?php echo strtolower($m['status']); ?>"><?php echo $m['status']; ?></span></td>
          </tr>
          <?php endforeach; ?>
          <?php if(empty($recentMembers)): ?>
          <tr>
            <td colspan="4" style="text-align: center; padding: 24px; color: #64748B;">No recent registrations found.</td>
          </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

  </main>

</body>
</html>

