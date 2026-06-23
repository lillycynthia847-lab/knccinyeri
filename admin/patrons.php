<?php
require_once 'includes/config.php';

// Auto-create table if it doesn't exist
$pdo->exec("CREATE TABLE IF NOT EXISTS patrons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    company_name VARCHAR(255) NOT NULL,
    logo_url VARCHAR(255) DEFAULT NULL,
    display_order INT DEFAULT 0,
    status ENUM('Active','Inactive') DEFAULT 'Active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    
    // Get logo to delete file
    $stmt = $pdo->prepare("SELECT logo_url FROM patrons WHERE id = ?");
    $stmt->execute([$id]);
    $patron = $stmt->fetch();
    
    if ($patron && !empty($patron['logo_url'])) {
        $filePath = '../' . $patron['logo_url'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    $stmt = $pdo->prepare("DELETE FROM patrons WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: patrons.php");
    exit;
}

// Fetch Patrons
$stmt = $pdo->query("SELECT * FROM patrons ORDER BY display_order ASC, created_at DESC");
$patrons = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Patrons | Admin Panel</title>
  <link rel="stylesheet" href="css/admin.css">
  <style>
    .action-links a { color: var(--admin-primary); font-weight: 600; font-size: 0.85rem; text-decoration: none; margin-right: 12px; }
    .action-links a.delete { color: var(--admin-danger); }
    .action-links a:hover { text-decoration: underline; }
    .patron-logo-preview { max-width: 100px; max-height: 60px; object-fit: contain; border-radius: 4px; }
  </style>
</head>
<body>

  <?php include 'includes/sidebar.php'; ?>

  <main class="main-content">
    <div class="page-header">
      <div>
        <h1 class="page-title">Manage Patrons</h1>
        <p style="color: #64748B; margin-top: 8px;">Add and manage your patron companies displayed on the homepage.</p>
      </div>
      <a href="patrons-add.php" class="btn-admin">+ Add Patron</a>
    </div>

    <div class="admin-table-wrapper">
      <table class="admin-table">
        <thead>
          <tr>
            <th>Logo</th>
            <th>Company Name</th>
            <th>Status</th>
            <th>Order</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($patrons as $p): ?>
          <tr>
            <td>
              <?php if (!empty($p['logo_url'])): ?>
                <img src="../<?php echo htmlspecialchars($p['logo_url']); ?>" alt="Logo" class="patron-logo-preview">
              <?php else: ?>
                <span style="color:#94a3b8;">No Logo</span>
              <?php endif; ?>
            </td>
            <td style="font-weight: 500; color: var(--admin-text-main);"><?php echo htmlspecialchars($p['company_name']); ?></td>
            <td>
              <span class="status-badge <?php echo $p['status'] === 'Active' ? 'status-published' : 'status-draft'; ?>">
                <?php echo $p['status']; ?>
              </span>
            </td>
            <td style="color: #64748B;"><?php echo $p['display_order']; ?></td>
            <td class="action-links">
              <a href="patrons-edit.php?id=<?php echo $p['id']; ?>">Edit</a>
              <a href="patrons.php?delete=<?php echo $p['id']; ?>" class="delete" onclick="return confirm('Are you sure you want to delete this patron?');">Delete</a>
            </td>
          </tr>
          <?php endforeach; ?>
          
          <?php if(empty($patrons)): ?>
          <tr>
            <td colspan="5" style="text-align: center; padding: 32px; color: #64748B;">No patrons found. Add one above!</td>
          </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

  </main>

</body>
</html>
