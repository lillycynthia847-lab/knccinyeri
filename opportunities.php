<?php
require_once 'admin/includes/config.php';

// Fetch all site settings
$stmt = $pdo->query("SELECT setting_key, setting_value FROM site_settings");
$settings = [];
while ($row = $stmt->fetch()) {
    $settings[$row['setting_key']] = $row['setting_value'];
}

// Fetch all open opportunities grouped by type
$stmt = $pdo->query("SELECT * FROM opportunities WHERE status = 'Open' ORDER BY deadline ASC");
$allOpps = $stmt->fetchAll();

// Map DB opp_type to CSS filter slug
$oppTypeMap = [
    'Vendor' => 'vendor',
    'Government Tender' => 'tender',
    'Job' => 'job',
    'Grant' => 'grant'
];

// Group by type
$vendorOpps = [];
$tenderOpps = [];
$jobOpps = [];
$grantOpps = [];
$today = date('Y-m-d');

foreach ($allOpps as $opp) {
    switch ($opp['opp_type']) {
        case 'Vendor': $vendorOpps[] = $opp; break;
        case 'Government Tender': $tenderOpps[] = $opp; break;
        case 'Job': $jobOpps[] = $opp; break;
        case 'Grant': $grantOpps[] = $opp; break;
    }
}
?>
<?php
// SEO Settings for Opportunities Page
$seo_title = "Opportunities | KNCCI Nyeri Chapter";
$seo_desc = "Explore vendor bids, government tenders, job openings, and grants available for businesses in Nyeri County.";
$seo_image = SITE_MAIN_URL . "/Images/kncci.jpg";
$seo_url = SITE_MAIN_URL . "/opportunities.php";
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
  <link rel="stylesheet" href="css/opportunities.css" />
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
          <a href="<?php echo htmlspecialchars($settings['social_twitter']); ?>" class="tsoc" aria-label="Twitter">𝕏</a>
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
            <a href="opportunities.php" class="nav-link active">Opportunities <span class="caret">▾</span></a>
            <ul class="dropdown">
              <li><a href="opportunities.php#vendor">Vendor Opportunities</a></li>
              <li><a href="opportunities.php#tenders">Government Tenders</a></li>
              <li><a href="opportunities.php#jobs">Job Opportunities</a></li>
              <li><a href="opportunities.php#grants">Grants</a></li>
            </ul>
          </li>
          <li><a href="events.php" class="nav-link">Events</a></li>
          <li><a href="news.php" class="nav-link">News</a></li>
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
        <span class="bc-current">Opportunities</span>
      </nav>
      <h1 class="ph-title">Business Opportunities</h1>
      <p class="ph-subtitle">Vendor bids, government tenders, jobs, and grants — all in one place for Nyeri's business community</p>
    </div>
  </section>

  <!-- TAB FILTER -->
  <div class="opp-tabs-bar" id="oppTabsBar">
    <div class="container">
      <div class="opp-tabs">
        <button class="opp-tab active" data-filter="all" id="tabAll">All Opportunities</button>
        <button class="opp-tab" data-filter="vendor" id="tabVendor">Vendor Opportunities</button>
        <button class="opp-tab" data-filter="tender" id="tabTender">Government Tenders</button>
        <button class="opp-tab" data-filter="job" id="tabJob">Job Opportunities</button>
        <button class="opp-tab" data-filter="grant" id="tabGrant">Grants</button>
      </div>
    </div>
  </div>

  <!-- MAIN CONTENT -->
  <div class="section opp-content">
    <div class="container">

      <?php
      // Helper function to render an opportunity section
      function renderOppSection($sectionId, $title, $subtitle, $opps, $tagSlug, $tagLabel, $today) {
          echo '<div class="opp-section" id="' . $sectionId . '">';
          echo '<div class="opp-section-header"><div>';
          echo '<h2>' . htmlspecialchars($title) . '</h2>';
          echo '<p>' . htmlspecialchars($subtitle) . '</p>';
          echo '</div></div>';
          echo '<div class="opp-grid" id="' . $sectionId . 'Grid">';
          
          if (empty($opps)) {
              echo '<div style="grid-column: 1 / -1; text-align: center; color: #64748B; padding: 30px 0;">No ' . strtolower($title) . ' available at the moment. Check back soon!</div>';
          }
          
          foreach ($opps as $opp) {
              $isUrgent = ($opp['deadline'] && $opp['deadline'] <= date('Y-m-d', strtotime('+7 days'))) ? 'urgent' : '';
              $deadlineStr = $opp['deadline'] ? date('M d, Y', strtotime($opp['deadline'])) : 'Open';
              
              echo '<div class="opp-card" data-type="' . $tagSlug . '">';
              echo '<div class="opp-card-header">';
              echo '<span class="opp-tag opp-tag-' . $tagSlug . '">' . $tagLabel . '</span>';
              echo '<span class="opp-deadline ' . $isUrgent . '">Closes: ' . $deadlineStr . '</span>';
              echo '</div>';
              echo '<h3>' . htmlspecialchars($opp['title']) . '</h3>';
              echo '<div class="opp-desc">' . $opp['description'] . '</div>';
              echo '<div class="opp-card-footer">';
              
              if (!empty($opp['application_link'])) {
                  echo '<a href="' . htmlspecialchars($opp['application_link']) . '" target="_blank" class="btn btn-primary opp-btn">Apply / View</a>';
              } elseif (!empty($opp['document'])) {
                  echo '<a href="' . htmlspecialchars($opp['document']) . '" target="_blank" class="btn btn-primary opp-btn">Download Details</a>';
              } else {
                  echo '<span class="opp-btn" style="opacity:0.6">Details Coming Soon</span>';
              }
              
              echo '</div></div>';
          }
          
          echo '</div></div>';
      }
      
      renderOppSection('vendor', 'Vendor Opportunities', 'Supply and service contracts open to registered businesses in Nyeri County', $vendorOpps, 'vendor', 'Vendor', $today);
      renderOppSection('tenders', 'Government Tenders', 'Active procurement tenders from county and national government entities', $tenderOpps, 'tender', 'Tender', $today);
      renderOppSection('jobs', 'Job Opportunities', 'Employment openings from KNCCI member businesses and partner organisations', $jobOpps, 'job', 'Job', $today);
      renderOppSection('grants', 'Grants & Funding', 'Grants, subsidies, and funding programmes available for Nyeri businesses', $grantOpps, 'grant', 'Grant', $today);
      ?>

    </div>
  </div>

  <!-- CTA BANNER -->
  <section class="cta-banner">
    <div class="container cta-inner">
      <div class="cta-text">
        <h2>Want to Post an Opportunity?</h2>
        <p>KNCCI Nyeri members can post tenders, jobs, vendor bids, and grants directly to this platform. Reach hundreds of active businesses in Nyeri County.</p>
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
  <script src="js/opportunities.js"></script>
</body>
</html>

