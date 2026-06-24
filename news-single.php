<?php
require_once 'admin/includes/config.php';

// Fetch all site settings into an easy-to-use array
$stmt = $pdo->query("SELECT setting_key, setting_value FROM site_settings");
$settings = [];
while ($row = $stmt->fetch()) {
    $settings[$row['setting_key']] = $row['setting_value'];
}

if (!isset($_GET['id'])) {
    header("Location: news.php");
    exit;
}
$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM news WHERE id = ? AND status = 'Published'");
$stmt->execute([$id]);
$article = $stmt->fetch();

if (!$article) {
    header("Location: news.php");
    exit;
}

// Fetch 3 other latest news articles for the sidebar
$stmt = $pdo->prepare("SELECT * FROM news WHERE id != ? AND status = 'Published' ORDER BY publish_date DESC LIMIT 3");
$stmt->execute([$id]);
$otherNews = $stmt->fetchAll();
?>
<?php
// Dynamic SEO Extraction for News Article
$seo_title = htmlspecialchars($article['title']) . " | KNCCI Nyeri";
$clean_content = strip_tags($article['content']);
$seo_desc = htmlspecialchars(substr($clean_content, 0, 150)) . '...';
$seo_image = SITE_MAIN_URL . "/" . ($article['cover_image'] ? $article['cover_image'] : 'Images/kncci.jpg');
$seo_url = SITE_MAIN_URL . "/news-single.php?id=" . $id;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  
  <!-- Primary Meta Tags -->
  <link rel="icon" type="image/jpeg" href="<?php echo SITE_MAIN_URL; ?>/Images/favicon.jpg" />
  <title><?php echo $seo_title; ?></title>
  <meta name="title" content="<?php echo $seo_title; ?>" />
  <meta name="description" content="<?php echo $seo_desc; ?>" />

  <!-- Open Graph / Facebook -->
  <meta property="og:type" content="article" />
  <meta property="og:url" content="<?php echo $seo_url; ?>" />
  <meta property="og:title" content="<?php echo $seo_title; ?>" />
  <meta property="og:description" content="<?php echo $seo_desc; ?>" />
  <meta property="og:image" content="<?php echo $seo_image; ?>" />

  <!-- Twitter -->
  <meta property="twitter:card" content="summary_large_image" />
  <meta property="twitter:url" content="<?php echo $seo_url; ?>" />
  <meta property="twitter:title" content="<?php echo $seo_title; ?>" />
  <meta property="twitter:description" content="<?php echo $seo_desc; ?>" />
  <meta property="twitter:image" content="<?php echo $seo_image; ?>" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="css/styles.css" />
  <style>
    .single-news-hero {
      background: linear-gradient(rgba(15, 23, 42, 0.8), rgba(15, 23, 42, 0.95)), url('../<?php echo $article['cover_image'] ?: 'Images/kncci-hero.jpg'; ?>') center/cover no-repeat;
      padding: 120px 0 60px;
      color: #fff;
      text-align: center;
    }
    .single-news-meta {
      font-size: 0.9rem;
      color: var(--gold);
      text-transform: uppercase;
      letter-spacing: 1.5px;
      font-weight: 600;
      margin-bottom: 12px;
    }
    .single-news-title {
      font-family: 'Playfair Display', serif;
      font-size: clamp(2rem, 4vw, 3rem);
      font-weight: 600;
      line-height: 1.2;
      max-width: 900px;
      margin: 0 auto 16px;
    }
    .single-news-date {
      color: rgba(255, 255, 255, 0.6);
      font-size: 0.95rem;
    }
    .single-news-grid {
      display: grid;
      grid-template-columns: 2.3fr 1fr;
      gap: 50px;
      padding: 60px 0;
    }
    @media (max-width: 992px) {
      .single-news-grid {
        grid-template-columns: 1fr;
        gap: 40px;
      }
    }
    .news-content {
      font-size: 1.05rem;
      line-height: 1.8;
      color: #334155;
    }
    .news-content p {
      margin-bottom: 24px;
    }
    .news-content img {
      max-width: 100%;
      border-radius: 12px;
      margin: 32px 0;
    }
    .sidebar-widget {
      background: #f8fafc;
      border: 1px solid #e2e8f0;
      border-radius: 12px;
      padding: 30px;
      margin-bottom: 30px;
      position: sticky;
      top: 100px;
    }
    .sidebar-widget h3 {
      font-family: 'Playfair Display', serif;
      font-size: 1.3rem;
      color: var(--navy);
      margin-bottom: 20px;
      border-bottom: 2px solid var(--gold);
      padding-bottom: 10px;
    }
    .rec-post {
      display: flex;
      gap: 16px;
      margin-bottom: 20px;
      align-items: center;
      text-decoration: none;
    }
    .rec-post img {
      width: 70px;
      height: 70px;
      object-fit: cover;
      border-radius: 8px;
    }
    .rec-post-info h4 {
      font-size: 0.95rem;
      color: var(--navy);
      margin-bottom: 4px;
      line-height: 1.3;
    }
    .rec-post-info span {
      font-size: 0.8rem;
      color: #64748b;
    }
    .article-rich-text img {
      max-width: 100%;
      height: auto;
      border-radius: 8px;
      margin: 16px 0;
    }
    .article-rich-text p {
      margin-bottom: 1.2rem;
      line-height: 1.8;
      color: var(--text);
    }
  </style>
</head>
<body>

  <!-- ===== TOP BAR ===== -->
  <div class="top-bar">
    <div class="container top-bar-inner">
      <div class="top-bar-left">
        <a href="tel:<?php echo str_replace(' ', '', $settings['contact_phone']); ?>"><?php echo htmlspecialchars($settings['contact_phone']); ?></a>
        <a href="mailto:<?php echo htmlspecialchars($settings['contact_email']); ?>"><?php echo htmlspecialchars($settings['contact_email']); ?></a>
        
      </div>
      <div class="top-bar-right">
        <span>Mon – Fri &nbsp;|&nbsp; 8:00 AM – 5:00 PM</span>
        <div class="top-socials">
          <a href="<?php echo htmlspecialchars($settings['social_facebook']); ?>" aria-label="Facebook" class="tsoc">f</a>
          <a href="<?php echo htmlspecialchars($settings['social_twitter']); ?>" aria-label="Twitter" class="tsoc">𝕏</a>
          <a href="<?php echo htmlspecialchars($settings['social_linkedin']); ?>" aria-label="LinkedIn" class="tsoc">in</a>
        </div>
      </div>
    </div>
  </div>

  <!-- ===== HEADER ===== -->
  <header class="header" id="header">
    <nav class="navbar">
      <div class="container nav-inner">
        <a href="index.php" class="logo" id="logo">
          <img src="Images/kncci.jpg" alt="KNCCI Nyeri Chapter Logo" class="logo-img" />
          <div class="logo-label">
            <span class="logo-label-main">KNCCI Nyeri</span>
            <span class="logo-label-sub">Nyeri County Chapter</span>
          </div>
        </a>
        <ul class="nav-links" id="navLinks">
          <li><a href="index.php" class="nav-link">Home</a></li>
          <li class="has-dropdown">
            <a href="about.php" class="nav-link">About <span class="caret">▾</span></a>
            <ul class="dropdown">
              <li><a href="about.php#story">Our Story</a></li>
              <li><a href="about.php#mvv">Mission & Vision</a></li>
              <li><a href="about.php#leadership">Leadership</a></li>
            </ul>
          </li>
          <li class="has-dropdown">
            <a href="opportunities.php" class="nav-link">Opportunities <span class="caret">▾</span></a>
            <ul class="dropdown">
              <li><a href="opportunities.php#vendor">Vendor Opportunities</a></li>
              <li><a href="opportunities.php#tenders">Government Tenders</a></li>
              <li><a href="opportunities.php#jobs">Job Opportunities</a></li>
              <li><a href="opportunities.php#grants">Grants</a></li>
            </ul>
          </li>
          <li><a href="events.php" class="nav-link">Events</a></li>
          <li><a href="news.php" class="nav-link active">News</a></li>
          <li><a href="management.php" class="nav-link">Management</a></li>
          <li><a href="contact.php" class="nav-link">Contact</a></li>
        </ul>
        <a href="#" class="btn btn-primary nav-cta" id="memberCta">Become a Member</a>
        <button class="hamburger" id="hamburger" aria-label="Toggle menu">
          <span></span><span></span><span></span>
        </button>
      </div>
    </nav>
  </header>

  <!-- ===== SINGLE NEWS HERO ===== -->
  <section class="single-news-hero">
    <div class="container">
      <div class="single-news-meta"><?php echo htmlspecialchars($article['category']); ?></div>
      <h1 class="single-news-title"><?php echo htmlspecialchars($article['title']); ?></h1>
      <div class="single-news-date">Published on <?php echo date('F d, Y', strtotime($article['publish_date'])); ?></div>
    </div>
  </section>

  <!-- ===== MAIN CONTENT AREA ===== -->
  <div class="container">
    <div class="single-news-grid">
      
      <!-- News Content -->
      <article class="news-content">
        <?php if($article['cover_image']): ?>
          <img src="<?php echo htmlspecialchars($article['cover_image']); ?>" alt="<?php echo htmlspecialchars($article['title']); ?>" />
        <?php endif; ?>
        <div class="article-rich-text">
          <?php echo $article['content']; ?>
        </div>
      </article>

      <!-- Sidebar -->
      <aside>
        <div class="sidebar-widget">
          <h3>Other Latest News</h3>
          <?php foreach($otherNews as $o): ?>
            <a href="news-single.php?id=<?php echo $o['id']; ?>" class="rec-post">
              <?php if($o['cover_image']): ?>
                <img src="<?php echo htmlspecialchars($o['cover_image']); ?>" alt="">
              <?php else: ?>
                <div style="width:70px; height:70px; background:#e2e8f0; border-radius:8px;"></div>
              <?php endif; ?>
              <div class="rec-post-info">
                <h4><?php echo htmlspecialchars($o['title']); ?></h4>
                <span><?php echo date('M d, Y', strtotime($o['publish_date'])); ?></span>
              </div>
            </a>
          <?php endforeach; ?>
          <?php if(empty($otherNews)): ?>
            <p style="color:#64748b; font-size:0.95rem;">No other articles available.</p>
          <?php endif; ?>
        </div>
      </aside>

    </div>
  </div>

  <!-- FOOTER -->
  <footer class="footer">
    <div class="container footer-grid">
      <div class="footer-col footer-brand">
        <a href="index.php" class="footer-logo">
          <img src="Images/kncci.jpg" alt="KNCCI Nyeri Chapter Logo" class="logo-img logo-img-footer" />
          <div class="logo-label">
            <span class="logo-label-main">KNCCI Nyeri</span>
            <span class="logo-label-sub">Nyeri County Chapter</span>
          </div>
        </a>
        <p class="footer-tagline"><?php echo htmlspecialchars($settings['footer_tagline']); ?></p>
        <div class="footer-socials">
          <a href="<?php echo htmlspecialchars($settings['social_facebook']); ?>" class="fsoc" aria-label="Facebook">f</a>
          <a href="<?php echo htmlspecialchars($settings['social_twitter']); ?>" class="fsoc" aria-label="Twitter">X</a>
          <a href="<?php echo htmlspecialchars($settings['social_linkedin']); ?>" class="fsoc" aria-label="LinkedIn">in</a>
          <a href="<?php echo htmlspecialchars($settings['social_youtube']); ?>" class="fsoc" aria-label="YouTube">▶</a>
        </div>
      </div>
      <div class="footer-col">
        <h4>Quick Links</h4>
        <ul class="footer-links">
          <li><a href="index.php">Home</a></li>
          <li><a href="about.php">About Us</a></li>
          <li><a href="opportunities.php">Opportunities</a></li>
          <li><a href="events.php">Events</a></li>
          <li><a href="news.php">News</a></li>
          <li><a href="contact.php">Contact</a></li>
        </ul>
      </div>
      <div class="footer-col">
        <h4>Management</h4>
        <ul class="footer-links">
          <li><a href="management.php#board">Board of Directors</a></li>
          <li><a href="management.php#subcounty">Sub-County Heads</a></li>
        </ul>
      </div>
      <div class="footer-col">
        <h4>Contact Us</h4>
        <ul class="footer-contact">
          <li><span class="fc-label">Location</span> <?php echo htmlspecialchars($settings['contact_location']); ?></li>
          <li><span class="fc-label">Tel</span> <a href="tel:<?php echo str_replace(' ', '', $settings['contact_phone']); ?>"><?php echo htmlspecialchars($settings['contact_phone']); ?></a></li>
          <li><span class="fc-label">Email</span> <a href="mailto:<?php echo htmlspecialchars($settings['contact_email']); ?>"><?php echo htmlspecialchars($settings['contact_email']); ?></a></li>
          <li><span class="fc-label">Hours</span> <?php echo htmlspecialchars($settings['contact_hours']); ?></li>
        </ul>
      </div>
    </div>
    <div class="footer-bottom">
      <div class="container footer-bottom-inner">
        <p>&copy; <?php echo date('Y'); ?> KNCCI Nyeri Chapter. All rights reserved.</p>
        <div class="footer-bottom-links">
          <a href="#">Privacy Policy</a>
          <a href="#">Terms of Use</a>
          <a href="#">Sitemap</a>
        </div>
      </div>
    </div>
  </footer>

  <script src="js/main.js"></script>
</body>
</html>

