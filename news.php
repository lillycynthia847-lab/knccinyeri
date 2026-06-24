<?php
require_once 'admin/includes/config.php';

// Fetch all site settings into an easy-to-use array
$stmt = $pdo->query("SELECT setting_key, setting_value FROM site_settings");
$settings = [];
while ($row = $stmt->fetch()) {
    $settings[$row['setting_key']] = $row['setting_value'];
}

// Fetch all published news articles
$stmt = $pdo->query("SELECT * FROM news WHERE status = 'Published' ORDER BY publish_date DESC");
$articles = $stmt->fetchAll();

// Map database categories to CSS filter categories
$catMap = [
    'Advocacy' => 'advocacy',
    'Awards' => 'awards',
    'Partnership' => 'partnership',
    'Announcement' => 'announcement',
    'Training' => 'training',
    'Press Release' => 'press',
    'General' => 'general'
];

// Count articles per category
$catCounts = [
    'all' => count($articles),
    'advocacy' => 0,
    'awards' => 0,
    'partnership' => 0,
    'announcement' => 0,
    'training' => 0,
    'press' => 0
];
foreach ($articles as $art) {
    $c = $catMap[$art['category']] ?? 'all';
    if (isset($catCounts[$c])) {
        $catCounts[$c]++;
    }
}

// Display all articles in the grid (no featured article)
$gridArticles = $articles;
?>
<?php
// SEO Settings for News Page
$seo_title = "News & Announcements | KNCCI Nyeri Chapter";
$seo_desc = "Get the latest updates, announcements, partnerships, and advocacy news from the KNCCI Nyeri business community.";
$seo_image = SITE_MAIN_URL . "/Images/kncci.jpg";
$seo_url = SITE_MAIN_URL . "/news.php";
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
  <meta property="og:type" content="website" />
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
  <link rel="stylesheet" href="css/about.css" />
  <link rel="stylesheet" href="css/news.css" />
</head>
<body>

  <!-- TOP BAR -->
  <div class="top-bar">
    <div class="container top-bar-inner">
      <div class="top-bar-left">
        <a href="tel:<?php echo str_replace(' ', '', $settings['contact_phone']); ?>"><?php echo htmlspecialchars($settings['contact_phone']); ?></a>
        <a href="mailto:<?php echo htmlspecialchars($settings['contact_email']); ?>"><?php echo htmlspecialchars($settings['contact_email']); ?></a>
        
      </div>
      <div class="top-bar-right">
        <span>Mon – Fri &nbsp;|&nbsp; 8:00 AM – 5:00 PM</span>
        <div class="top-socials">
          <a href="<?php echo htmlspecialchars($settings['social_facebook']); ?>" class="tsoc" aria-label="Facebook">f</a>
          <a href="<?php echo htmlspecialchars($settings['social_twitter']); ?>" class="tsoc" aria-label="Twitter">X</a>
          <a href="<?php echo htmlspecialchars($settings['social_linkedin']); ?>" class="tsoc" aria-label="LinkedIn">in</a>
        </div>
      </div>
    </div>
  </div>

  <!-- HEADER -->
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
        <a href="#" class="btn btn-primary nav-cta">Become a Member</a>
        <button class="hamburger" id="hamburger" aria-label="Toggle menu">
          <span></span><span></span><span></span>
        </button>
      </div>
    </nav>
  </header>

  <!-- PAGE HERO -->
  <section class="page-hero">
    <div class="ph-pattern"></div>
    <div class="container ph-inner">
      <nav class="breadcrumb" aria-label="Breadcrumb">
        <a href="index.php">Home</a>
        <span class="bc-sep">›</span>
        <span class="bc-current">News</span>
      </nav>
      <h1 class="ph-title">News & Announcements</h1>
      <p class="ph-subtitle">Stay informed on advocacy, partnerships, events, and developments shaping business in Nyeri County</p>
    </div>
  </section>

  <!-- FILTER TABS -->
  <div class="news-tabs-bar" id="newsTabsBar">
    <div class="container">
      <div class="news-tabs">
        <button class="news-tab active" data-filter="all">All</button>
        <button class="news-tab" data-filter="advocacy">Advocacy</button>
        <button class="news-tab" data-filter="awards">Awards</button>
        <button class="news-tab" data-filter="partnership">Partnership</button>
        <button class="news-tab" data-filter="announcement">Announcements</button>
        <button class="news-tab" data-filter="training">Training</button>
        <button class="news-tab" data-filter="press">Press Releases</button>
      </div>
    </div>
  </div>



  <!-- NEWS GRID -->
  <section class="section news-section" id="newsSection">
    <div class="container">

      <div class="news-layout">

        <!-- Articles Column -->
        <div class="news-main">
          <div class="news-grid" id="newsGrid">
            <?php foreach ($gridArticles as $article): 
                $slugCat = $catMap[$article['category']] ?? 'general';
            ?>
            <article class="n-card" data-category="<?php echo $slugCat; ?>">
              <div class="n-card-thumb">
                <div class="n-card-blur" <?php if($article['cover_image']): ?>style="background-image: url('<?php echo htmlspecialchars($article['cover_image']); ?>')"<?php endif; ?>></div>
                <?php if($article['cover_image']): ?>
                  <img src="<?php echo htmlspecialchars($article['cover_image']); ?>" class="n-card-img" alt="">
                <?php endif; ?>
              </div>
              <div class="n-card-body">
                <div class="n-card-top">
                  <span class="n-tag n-tag-<?php echo $slugCat; ?>"><?php echo htmlspecialchars($article['category']); ?></span>
                  <span class="n-date"><?php echo date('M d, Y', strtotime($article['publish_date'])); ?></span>
                </div>
                <h3><?php echo htmlspecialchars($article['title']); ?></h3>
                <div class="n-card-foot">
                  <span class="n-read">Read Details</span>
                  <a href="news-single.php?id=<?php echo $article['id']; ?>" class="n-link">Read More</a>
                </div>
              </div>
            </article>
            <?php endforeach; ?>
          </div><!-- /news-grid -->

          <!-- Empty state -->
          <div class="n-empty" id="nEmpty" style="display:none;">
            <h3>No articles in this category</h3>
            <p>Check back soon or browse all articles.</p>
            <button class="btn btn-primary" onclick="resetNewsFilter()">View All</button>
          </div>

          <!-- Load More -->
          <div class="n-load-more" id="loadMore">
            <button class="btn btn-outline-dark" id="loadMoreBtn">Load More Articles</button>
          </div>

        </div><!-- /news-main -->

        <!-- Sidebar -->
        <aside class="news-sidebar">

          <!-- Search -->
          <div class="sidebar-widget">
            <h4 class="sw-title">Search</h4>
            <div class="sw-search">
              <input type="text" id="newsSearch" placeholder="Search articles..." />
              <button type="button" id="searchBtn">Go</button>
            </div>
          </div>

          <!-- Categories -->
          <div class="sidebar-widget">
            <h4 class="sw-title">Categories</h4>
            <ul class="sw-categories">
              <li><a href="#" data-cat="all" class="sw-cat-link active">All Articles <span><?php echo $catCounts['all']; ?></span></a></li>
              <li><a href="#" data-cat="advocacy" class="sw-cat-link">Advocacy <span><?php echo $catCounts['advocacy']; ?></span></a></li>
              <li><a href="#" data-cat="awards" class="sw-cat-link">Awards <span><?php echo $catCounts['awards']; ?></span></a></li>
              <li><a href="#" data-cat="partnership" class="sw-cat-link">Partnership <span><?php echo $catCounts['partnership']; ?></span></a></li>
              <li><a href="#" data-cat="announcement" class="sw-cat-link">Announcements <span><?php echo $catCounts['announcement']; ?></span></a></li>
              <li><a href="#" data-cat="training" class="sw-cat-link">Training <span><?php echo $catCounts['training']; ?></span></a></li>
              <li><a href="#" data-cat="press" class="sw-cat-link">Press Releases <span><?php echo $catCounts['press']; ?></span></a></li>
            </ul>
          </div>

          <!-- Recent Posts -->
          <div class="sidebar-widget">
            <h4 class="sw-title">Recent Articles</h4>
            <ul class="sw-recent">
              <?php foreach(array_slice($articles, 0, 5) as $art): ?>
              <li>
                <a href="news-single.php?id=<?php echo $art['id']; ?>">
                  <span class="sr-date"><?php echo date('M d, Y', strtotime($art['publish_date'])); ?></span>
                  <span class="sr-title"><?php echo htmlspecialchars($art['title']); ?></span>
                </a>
              </li>
              <?php endforeach; ?>
            </ul>
          </div>

          <!-- Newsletter -->
          <div class="sidebar-widget sw-newsletter">
            <h4 class="sw-title">Newsletter</h4>
            <p>Get the latest KNCCI Nyeri news delivered to your inbox.</p>
            <form class="sw-newsletter-form" id="newsletterForm">
              <input type="email" placeholder="Your email address" required />
              <button type="submit" class="btn btn-primary">Subscribe</button>
            </form>
          </div>

        </aside>

      </div><!-- /news-layout -->

    </div>
  </section>

  <!-- CTA -->
  <section class="cta-banner">
    <div class="container cta-inner">
      <div class="cta-text">
        <h2>Stay Ahead of the Curve</h2>
        <p>KNCCI Nyeri members receive priority access to news, policy updates, and business intelligence before they go public.</p>
      </div>
      <div class="cta-actions">
        <a href="#" class="btn btn-gold">Become a Member</a>
        <a href="contact.php" class="btn btn-outline-white">Contact Us</a>
      </div>
    </div>
  </section>

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
  <script src="js/news.js"></script>
</body>
</html>

