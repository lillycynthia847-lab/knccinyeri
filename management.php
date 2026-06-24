<?php
require_once 'admin/includes/config.php';

// Fetch all site settings
$stmt = $pdo->query("SELECT setting_key, setting_value FROM site_settings");
$settings = [];
while ($row = $stmt->fetch()) {
    $settings[$row['setting_key']] = $row['setting_value'];
}

// Fetch Board of Directors
$stmt = $pdo->query("SELECT * FROM management_team WHERE status = 'Active' AND team_group = 'Board of Directors' ORDER BY display_order ASC");
$boardMembers = $stmt->fetchAll();

// Fetch Sub-County Heads
$stmt = $pdo->query("SELECT * FROM management_team WHERE status = 'Active' AND team_group = 'Sub-County Head' ORDER BY display_order ASC");
$subcountyHeads = $stmt->fetchAll();
?>
<?php
// SEO Settings for Management Page
$seo_title = "Management Team | KNCCI Nyeri Chapter";
$seo_desc = "Meet the dedicated Board of Directors and Sub-County Management Heads steering the KNCCI Nyeri Chapter and representing businesses across the county.";
$seo_image = SITE_MAIN_URL . "/Images/kncci.jpg";
$seo_url = SITE_MAIN_URL . "/management.php";
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
  <link rel="stylesheet" href="css/management.css" />
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
            <a href="opportunities.php" class="nav-link">Opportunities <span class="caret">▾</span></a>
            <ul class="dropdown">
              <li><a href="opportunities.php#vendor">Vendor Opportunities</a></li>
              <li><a href="opportunities.php#tenders">Government Tenders</a></li>
              <li><a href="opportunities.php#jobs">Job Opportunities</a></li>
              <li><a href="opportunities.php#grants">Grants</a></li>
            </ul>
          </li>
          <li><a href="events.php" class="nav-link">Events</a></li>
          <li><a href="news.php" class="nav-link">News</a></li>
          <li><a href="management.php" class="nav-link active">Management</a></li>
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
        <span class="bc-current">Management</span>
      </nav>
      <h1 class="ph-title">Chapter Management</h1>
      <p class="ph-subtitle">Meet the dedicated leaders steering KNCCI Nyeri Chapter and representing businesses across the county</p>
    </div>
  </section>

  <!-- ===== BOARD OF DIRECTORS ===== -->
  <section class="section mgmt-section" id="board">
    <div class="container">

      <div class="mgmt-section-header">
        <div class="mgmt-label-col">
          <span class="section-label">Governance</span>
          <h2 class="section-title">Board of Directors</h2>
          <p class="mgmt-intro">The Board of Directors provides strategic oversight and governance for KNCCI Nyeri Chapter, ensuring the chapter remains accountable, effective, and aligned with its mandate to serve the Nyeri business community.</p>
        </div>
        <div class="mgmt-divider-v"></div>
      </div>

      <div class="mgmt-top-grid">
        <?php 
        $topLeaders = array_slice($boardMembers, 0, 2);
        $otherMembers = array_slice($boardMembers, 2);
        
        foreach ($topLeaders as $member): 
            $initials = '';
            $nameParts = explode(' ', $member['full_name']);
            foreach ($nameParts as $part) { $initials .= strtoupper(substr($part, 0, 1)); }
            $initials = substr($initials, 0, 2);
        ?>
        <div class="mgmt-card mgmt-card-top">
          <div class="mgmt-photo">
            <?php if(!empty($member['profile_image'])): ?>
              <img src="<?php echo htmlspecialchars($member['profile_image']); ?>" alt="<?php echo htmlspecialchars($member['full_name']); ?>" style="width:100%; height:100%; object-fit:cover; object-position:top center; border-radius:inherit; position:absolute; inset:0;" />
            <?php else: ?>
              <div class="mgmt-initials"><?php echo $initials; ?></div>
            <?php endif; ?>
          </div>
          <div class="mgmt-info">
            <span class="mgmt-role"><?php echo htmlspecialchars($member['position']); ?></span>
            <h3 class="mgmt-name" style="font-size: 1.4rem;"><?php echo htmlspecialchars($member['full_name']); ?></h3>
            <?php if(!empty($member['bio'])): ?>
              <p class="mgmt-bio"><?php echo htmlspecialchars($member['bio']); ?></p>
            <?php endif; ?>
          </div>
        </div>
        <?php endforeach; ?>
      </div>

      <div class="mgmt-board-grid">
        <?php foreach ($otherMembers as $member): 
            $initials = '';
            $nameParts = explode(' ', $member['full_name']);
            foreach ($nameParts as $part) { $initials .= strtoupper(substr($part, 0, 1)); }
            $initials = substr($initials, 0, 2);
        ?>
        <div class="mgmt-card">
          <div class="mgmt-photo mgmt-photo-sm">
            <?php if(!empty($member['profile_image'])): ?>
              <img src="<?php echo htmlspecialchars($member['profile_image']); ?>" alt="<?php echo htmlspecialchars($member['full_name']); ?>" style="width:100%; height:100%; object-fit:cover; object-position:top center; border-radius:inherit; position:absolute; inset:0;" />
            <?php else: ?>
              <div class="mgmt-initials"><?php echo $initials; ?></div>
            <?php endif; ?>
          </div>
          <div class="mgmt-info">
            <span class="mgmt-role"><?php echo htmlspecialchars($member['position']); ?></span>
            <h3 class="mgmt-name"><?php echo htmlspecialchars($member['full_name']); ?></h3>
            <?php if(!empty($member['bio'])): ?>
              <p class="mgmt-bio"><?php echo htmlspecialchars($member['bio']); ?></p>
            <?php endif; ?>
          </div>
        </div>
        <?php endforeach; ?>
        <?php if(empty($boardMembers)): ?>
          <p style="grid-column: 1 / -1; color: #64748B;">Board of Directors will be updated soon.</p>
        <?php endif; ?>
      </div>



    </div>
  </section>

  <!-- DIVIDER -->
  <div class="mgmt-section-divider">
    <div class="container">
      <div class="mgmt-divider-line"></div>
    </div>
  </div>

  <!-- ===== SUB-COUNTY MANAGEMENT HEADS ===== -->
  <section class="section mgmt-section bg-light" id="subcounty">
    <div class="container">

      <div class="mgmt-section-header">
        <div class="mgmt-label-col">
          <span class="section-label">County Representation</span>
          <h2 class="section-title">Sub-County Management Heads</h2>
          <p class="mgmt-intro">KNCCI Nyeri Chapter is represented across all sub-counties in Nyeri. Sub-County Heads serve as the direct link between the chapter and businesses in their respective areas, mobilising members and facilitating local advocacy.</p>
        </div>
      </div>

      <div class="mgmt-subcounty-grid">
        <?php foreach ($subcountyHeads as $head): 
            $initials = '';
            $nameParts = explode(' ', $head['full_name']);
            foreach ($nameParts as $part) { $initials .= strtoupper(substr($part, 0, 1)); }
            $initials = substr($initials, 0, 2);
            
            // Try to extract county name from bio or default to something
            $countyName = '';
            // For now, let's just use the position or a part of it
        ?>
        <div class="mgmt-card">
          <div class="mgmt-photo">
            <?php if(!empty($head['profile_image'])): ?>
              <img src="<?php echo htmlspecialchars($head['profile_image']); ?>" alt="<?php echo htmlspecialchars($head['full_name']); ?>" style="width:100%; height:100%; object-fit:cover; object-position:top center; border-radius:inherit; position:absolute; inset:0;" />
            <?php else: ?>
              <div class="mgmt-initials"><?php echo $initials; ?></div>
            <?php endif; ?>
          </div>
          <div class="mgmt-info">
            <span class="mgmt-role"><?php echo htmlspecialchars($head['position']); ?></span>
            <h3 class="mgmt-name"><?php echo htmlspecialchars($head['full_name']); ?></h3>
            <?php if(!empty($head['bio'])): ?>
              <p class="mgmt-bio"><?php echo htmlspecialchars($head['bio']); ?></p>
            <?php endif; ?>
          </div>
        </div>
        <?php endforeach; ?>
        <?php if(empty($subcountyHeads)): ?>
          <p style="grid-column: 1 / -1; color: #64748B;">Sub-County heads will be updated soon.</p>
        <?php endif; ?>
      </div>



    </div>
  </section>

  <!-- CTA -->
  <section class="cta-banner">
    <div class="container cta-inner">
      <div class="cta-text">
        <h2>Work With Our Leadership</h2>
        <p>Have a business matter, policy concern, or partnership proposal? Reach out to the KNCCI Nyeri Chapter management team directly.</p>
      </div>
      <div class="cta-actions">
        <a href="mailto:<?php echo htmlspecialchars($settings['contact_email']); ?>" class="btn btn-gold">Contact the Chapter</a>
        <a href="#" class="btn btn-outline-white">Become a Member</a>
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
</body>
</html>

