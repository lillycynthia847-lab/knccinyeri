<?php
require_once 'includes/config.php';

// Fetch all news articles ordered by newest first
$stmt = $pdo->query("SELECT * FROM news ORDER BY created_at DESC");
$newsArticles = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage News | Admin Panel</title>
  <link rel="stylesheet" href="css/admin.css">
  <style>
    .action-links a { color: var(--admin-primary); font-weight: 600; font-size: 0.85rem; text-decoration: none; margin-right: 12px; }
    .action-links a.delete { color: var(--admin-danger); }
    .action-links a:hover { text-decoration: underline; }
  </style>
</head>
<body>

  <!-- Include Sidebar -->
  <?php include 'includes/sidebar.php'; ?>

  <!-- Main Content -->
  <main class="main-content">
    <div class="page-header">
      <div>
        <h1 class="page-title">Manage News</h1>
        <p style="color: #64748B; margin-top: 8px;">Create, edit, and publish news articles.</p>
      </div>
      <a href="news-add.php" class="btn-admin">+ Create Article</a>
    </div>

    <!-- News Table -->
    <div class="admin-table-wrapper">
      <table class="admin-table">
        <thead>
          <tr>
            <th>Date</th>
            <th>Title</th>
            <th>Category</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($newsArticles as $article): ?>
          <tr>
            <td style="color: #64748B;"><?php echo date('M d, Y', strtotime($article['publish_date'])); ?></td>
            <td style="font-weight: 500; color: var(--admin-navy);"><?php echo htmlspecialchars($article['title']); ?></td>
            <td><?php echo htmlspecialchars($article['category']); ?></td>
            <td>
              <span class="status-badge <?php echo $article['status'] === 'Published' ? 'status-published' : 'status-draft'; ?>">
                <?php echo $article['status']; ?>
              </span>
            </td>
            <td class="action-links">
              <a href="news-edit.php?id=<?php echo $article['id']; ?>">Edit</a>
              <a href="news-delete.php?id=<?php echo $article['id']; ?>" class="delete" onclick="return confirm('Are you sure you want to delete this article?');">Delete</a>
            </td>
          </tr>
          <?php endforeach; ?>
          
          <?php if(empty($newsArticles)): ?>
          <tr>
            <td colspan="5" style="text-align: center; padding: 32px; color: #64748B;">No news articles found. Create one above!</td>
          </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

  </main>

</body>
</html>
