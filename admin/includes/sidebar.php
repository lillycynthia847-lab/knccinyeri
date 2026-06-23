<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<aside class="sidebar">
  <div class="sidebar-header">
    <div class="sidebar-logo-icon">K</div>
    <div>
      <h2>KNCCI Nyeri</h2>
      <span>Admin Portal</span>
    </div>
  </div>
  
  <nav class="sidebar-nav">
    <div class="sidebar-label">Main</div>
    <a href="index.php" class="sidebar-link <?php echo $currentPage == 'index.php' ? 'active' : ''; ?>">
      <span class="sidebar-link-icon">⊞</span> Overview
    </a>
    
    <div class="sidebar-label">Content</div>
    <a href="news.php" class="sidebar-link <?php echo strpos($currentPage, 'news') !== false ? 'active' : ''; ?>">
      <span class="sidebar-link-icon">✎</span> News Articles
    </a>
    <a href="events.php" class="sidebar-link <?php echo strpos($currentPage, 'events') !== false ? 'active' : ''; ?>">
      <span class="sidebar-link-icon">📅</span> Events
    </a>
    <a href="opportunities.php" class="sidebar-link <?php echo strpos($currentPage, 'opportunit') !== false ? 'active' : ''; ?>">
      <span class="sidebar-link-icon">💼</span> Opportunities
    </a>
    
    <div class="sidebar-label">Directory</div>
    <a href="members.php" class="sidebar-link <?php echo strpos($currentPage, 'member') !== false ? 'active' : ''; ?>">
      <span class="sidebar-link-icon">👥</span> Members
    </a>
    <a href="management.php" class="sidebar-link <?php echo strpos($currentPage, 'management') !== false ? 'active' : ''; ?>">
      <span class="sidebar-link-icon">⭐</span> Leadership
    </a>
    <a href="patrons.php" class="sidebar-link <?php echo strpos($currentPage, 'patrons') !== false ? 'active' : ''; ?>">
      <span class="sidebar-link-icon">🤝</span> Patrons
    </a>
    
    <div class="sidebar-label">System</div>
    <a href="media.php" class="sidebar-link <?php echo strpos($currentPage, 'media') !== false ? 'active' : ''; ?>">
      <span class="sidebar-link-icon">🖼️</span> Media Library
    </a>
    <a href="settings.php" class="sidebar-link <?php echo $currentPage == 'settings.php' ? 'active' : ''; ?>">
      <span class="sidebar-link-icon">⚙️</span> Site Settings
    </a>
  </nav>

  <div class="sidebar-bottom">
    <a href="../index.php" class="sidebar-link" target="_blank" style="margin-bottom: 8px;">
      <span class="sidebar-link-icon">↗</span> View Live Site
    </a>
    <a href="logout.php" class="sidebar-link">
      <span class="sidebar-link-icon">⎋</span> Log Out
    </a>
  </div>
</aside>
