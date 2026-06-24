<?php
require_once 'admin/includes/config.php';

// Fetch all site settings
$stmt = $pdo->query("SELECT setting_key, setting_value FROM site_settings");
$settings = [];
while ($row = $stmt->fetch()) {
    $settings[$row['setting_key']] = $row['setting_value'];
}

// Fetch leadership team ordered by display_order (Board Members only)
$stmt = $pdo->query("SELECT * FROM management_team WHERE status = 'Active' AND team_group = 'Board of Directors' ORDER BY display_order ASC");
$leaders = $stmt->fetchAll();
?>
<?php
// SEO Settings for About Page
$seo_title = "About Us | KNCCI Nyeri Chapter";
$seo_desc = "Learn about the KNCCI Nyeri Chapter's history, mission, vision, and the leadership team driving business advocacy in Nyeri County.";
$seo_image = SITE_MAIN_URL . "/Images/kncci.jpg";
$seo_url = SITE_MAIN_URL . "/about.php";
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
            <a href="about.php" class="nav-link active">About <span class="caret">▾</span></a>
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

  <!-- ===== PAGE HERO ===== -->
  <section class="page-hero">
    <div class="ph-pattern"></div>
    <div class="container ph-inner">
      <nav class="breadcrumb" aria-label="Breadcrumb">
        <a href="index.php">Home</a>
        <span class="bc-sep">›</span>
        <span class="bc-current">About Us</span>
      </nav>
      <h1 class="ph-title">About KNCCI Nyeri Chapter</h1>
      <p class="ph-subtitle">Championing the growth of business in Nyeri County since the 1990s</p>
    </div>
  </section>

  <!-- ===== OUR STORY ===== -->
  <section class="section story" id="story">
    <div class="container story-grid">

      <div class="story-content">
        <span class="section-label">Our Story</span>
        <h2 class="section-title">Over Three Decades of<br>Business Advocacy</h2>
        <p>The Kenya National Chamber of Commerce and Industry (KNCCI) Nyeri Chapter was established to give the business community in Nyeri County a unified, powerful voice in matters of policy, trade, and economic development.</p>
        <p>Since its founding, the chapter has grown from a small group of business leaders into a vibrant organisation representing hundreds of members across all sectors — from agriculture and manufacturing to trade, services, and the digital economy.</p>
        <p>Today, KNCCI Nyeri Chapter is a key stakeholder in the county's economic development agenda, working closely with both the Nyeri County Government and national institutions to create a business environment where enterprises of all sizes can thrive.</p>
        <a href="#leadership" class="btn btn-primary" style="margin-top:8px;">Meet Our Leadership</a>
      </div>

      <div class="story-timeline">
        <div class="tl-item">
          <div class="tl-dot"></div>
          <div class="tl-body">
            <span class="tl-year">1990s</span>
            <h4>Chapter Founded</h4>
            <p>KNCCI Nyeri Chapter established to represent local business interests.</p>
          </div>
        </div>
        <div class="tl-item">
          <div class="tl-dot"></div>
          <div class="tl-body">
            <span class="tl-year">2000s</span>
            <h4>Growing Membership</h4>
            <p>Membership expanded across all major sectors of the Nyeri economy.</p>
          </div>
        </div>
        <div class="tl-item">
          <div class="tl-dot"></div>
          <div class="tl-body">
            <span class="tl-year">2010s</span>
            <h4>Devolution Era</h4>
            <p>Became a key partner in county-level business policy under devolution.</p>
          </div>
        </div>
        <div class="tl-item">
          <div class="tl-dot"></div>
          <div class="tl-body">
            <span class="tl-year">2020s</span>
            <h4>Digital & Inclusive Growth</h4>
            <p>Embracing technology, youth enterprise, and inclusive economic growth agendas.</p>
          </div>
        </div>
        <div class="tl-item tl-item-current">
          <div class="tl-dot tl-dot-active"></div>
          <div class="tl-body">
            <span class="tl-year">Today</span>
            <h4>500+ Members Strong</h4>
            <p>Leading business advocacy across Nyeri with stronger partnerships than ever.</p>
          </div>
        </div>
      </div>

    </div>
  </section>

  <!-- ===== MISSION VISION VALUES ===== -->
  <section class="section mvv bg-light" id="mvv">
    <div class="container">
      <div class="section-header">
        <span class="section-label">Who We Are</span>
        <h2 class="section-title">Mission, Vision & Core Values</h2>
        <p class="section-sub">The principles that guide everything we do for the Nyeri business community</p>
      </div>
      <div class="mvv-grid">

        <div class="mvv-card mvv-mission">
          <div class="mvv-icon"></div>
          <h3>Our Mission</h3>
          <p><?php echo htmlspecialchars($settings['mission'] ?? 'To promote, support, and advocate for the interests of businesses in Nyeri County by providing relevant services, fostering partnerships, and engaging government for a conducive business environment.'); ?></p>
        </div>

        <div class="mvv-card mvv-vision">
          <div class="mvv-icon"></div>
          <h3>Our Vision</h3>
          <p><?php echo htmlspecialchars($settings['vision'] ?? 'To be the leading, most trusted, and most impactful business membership organisation in Nyeri County — driving a prosperous, inclusive, and globally competitive local economy.'); ?></p>
        </div>

        <div class="mvv-card mvv-values">
          <div class="mvv-icon"></div>
          <h3>Core Values</h3>
          <ul class="values-list">
            <li><span class="vl-dot"></span>Integrity & Transparency</li>
            <li><span class="vl-dot"></span>Inclusivity & Diversity</li>
            <li><span class="vl-dot"></span>Innovation & Excellence</li>
            <li><span class="vl-dot"></span>Accountability</li>
            <li><span class="vl-dot"></span>Collaboration & Partnership</li>
          </ul>
        </div>

      </div>
    </div>
  </section>

  <!-- ===== KEY PILLARS ===== -->
  <section class="section pillars">
    <div class="container">
      <div class="section-header">
        <span class="section-label">What We Stand For</span>
        <h2 class="section-title">Our Strategic Pillars</h2>
        <p class="section-sub">Four focus areas that define our work and impact</p>
      </div>
      <div class="pillars-grid">
        <div class="pillar-card">
          <div class="pillar-num">01</div>
          <div class="pillar-icon-lg"></div>
          <h3>Policy Advocacy</h3>
          <p>Engaging government at county and national level to shape business-friendly laws, regulations, and budgets.</p>
        </div>
        <div class="pillar-card">
          <div class="pillar-num">02</div>
          <div class="pillar-icon-lg"></div>
          <h3>Trade & Investment</h3>
          <p>Connecting Nyeri businesses to local, regional, and international trade and investment opportunities.</p>
        </div>
        <div class="pillar-card">
          <div class="pillar-num">03</div>
          <div class="pillar-icon-lg"></div>
          <h3>Capacity Building</h3>
          <p>Equipping members with skills, knowledge, and tools to grow sustainable and competitive enterprises.</p>
        </div>
        <div class="pillar-card">
          <div class="pillar-num">04</div>
          <div class="pillar-icon-lg"></div>
          <h3>Networking & Partnerships</h3>
          <p>Building relationships between businesses, government, development partners, and civil society.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- ===== LEADERSHIP ===== -->
  <section class="section leadership bg-light" id="leadership">
    <div class="container">
      <div class="section-header">
        <span class="section-label">Governance</span>
        <h2 class="section-title">Our Leadership Team</h2>
        <p class="section-sub">Experienced business leaders steering KNCCI Nyeri Chapter forward</p>
      </div>
      <div class="mgmt-top-grid">
        <?php foreach ($leaders as $index => $leader): 
            $initials = '';
            $nameParts = explode(' ', $leader['full_name']);
            foreach ($nameParts as $part) { $initials .= strtoupper(substr($part, 0, 1)); }
            $initials = substr($initials, 0, 2);
        ?>
        <div class="mgmt-card mgmt-card-top">
          <div class="mgmt-photo">
            <?php if(!empty($leader['profile_image'])): ?>
              <img src="<?php echo htmlspecialchars($leader['profile_image']); ?>" alt="<?php echo htmlspecialchars($leader['full_name']); ?>" style="width:100%; height:100%; object-fit:cover; object-position:top center; border-radius:inherit; position:absolute; inset:0;" />
            <?php else: ?>
              <div class="mgmt-initials"><?php echo $initials; ?></div>
            <?php endif; ?>
          </div>
          <div class="mgmt-info">
            <span class="mgmt-role"><?php echo htmlspecialchars($leader['position']); ?></span>
            <h3 class="mgmt-name" style="font-size: 1.4rem;"><?php echo htmlspecialchars($leader['full_name']); ?></h3>
            <?php if(!empty($leader['bio'])): ?>
              <p class="mgmt-bio"><?php echo htmlspecialchars($leader['bio']); ?></p>
            <?php endif; ?>
            <?php if(!empty($leader['linkedin_url'])): ?>
            <div class="mgmt-contacts">
              <a href="<?php echo htmlspecialchars($leader['linkedin_url']); ?>" aria-label="LinkedIn" target="_blank" class="mgmt-contact-btn">LinkedIn</a>
            </div>
            <?php endif; ?>
          </div>
        </div>
        <?php endforeach; ?>
        <?php if(empty($leaders)): ?>
          <p style="grid-column: 1 / -1; text-align: center; color: #64748B; padding: 40px 0;">Leadership team details coming soon.</p>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <!-- ===== AFFILIATIONS ===== -->
  <section class="section affiliations">
    <div class="container">
      <div class="section-header">
        <span class="section-label">Partners & Affiliations</span>
        <h2 class="section-title">Who We Work With</h2>
        <p class="section-sub">Building strong institutional relationships for maximum impact</p>
      </div>
      <div class="affiliations-grid">
        <div class="affil-card">
          <div class="affil-icon"></div>
          <h4>National KNCCI</h4>
          <p>Parent body — Kenya National Chamber of Commerce & Industry</p>
        </div>
        <div class="affil-card">
          <div class="affil-icon"></div>
          <h4>Nyeri County Government</h4>
          <p>Strategic partner in county-level economic planning and policy</p>
        </div>
        <div class="affil-card">
          <div class="affil-icon"></div>
          <h4>Kenya Revenue Authority</h4>
          <p>MOU partner for tax advisory and compliance support to members</p>
        </div>
        <div class="affil-card">
          <div class="affil-icon"></div>
          <h4>East Africa Chamber Network</h4>
          <p>Regional business chamber collaboration and trade facilitation</p>
        </div>
        <div class="affil-card">
          <div class="affil-icon"></div>
          <h4>Financial Institutions</h4>
          <p>Partnerships with local banks and SACCOs for member credit access</p>
        </div>
        <div class="affil-card">
          <div class="affil-icon"></div>
          <h4>Training Institutions</h4>
          <p>Collaboration with universities and TVETs for capacity building</p>
        </div>
      </div>
    </div>
  </section>

  <!-- ===== CTA BANNER ===== -->
  <section class="cta-banner">
    <div class="container cta-inner">
      <div class="cta-text">
        <h2>Become Part of Our Story</h2>
        <p>Join over 500 businesses in Nyeri County already benefiting from KNCCI membership. Together, we build a stronger Nyeri economy.</p>
      </div>
      <div class="cta-actions">
        <a href="#" class="btn btn-gold">Become a Member Today</a>
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
</body>
</html>

