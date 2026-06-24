<?php
require_once 'admin/includes/config.php';

// Fetch all site settings into an easy-to-use array
$stmt = $pdo->query("SELECT setting_key, setting_value FROM site_settings");
$settings = [];
while ($row = $stmt->fetch()) {
    $settings[$row['setting_key']] = $row['setting_value'];
}

// Fetch all events
$stmt = $pdo->query("SELECT * FROM events ORDER BY event_date DESC");
$events = $stmt->fetchAll();

// Map event types to CSS filter classes
$typeMap = [
    'Annual General Meeting' => 'agm',
    'Workshop' => 'workshop',
    'Networking' => 'networking',
    'Training' => 'training',
    'Conference' => 'conference',
    'General' => 'general'
];

$today = date('Y-m-d');
$upcomingEvents = [];
$pastEvents = [];
foreach ($events as $ev) {
    if ($ev['event_date'] >= $today) {
        $upcomingEvents[] = $ev;
    } else {
        $pastEvents[] = $ev;
    }
}

// Find a featured event (the closest upcoming event)
$upcomingEventsSorted = array_reverse($upcomingEvents);
$featuredEvent = !empty($upcomingEventsSorted) ? $upcomingEventsSorted[0] : null;
?>
<?php
// SEO Settings for Events Page
$seo_title = "Events | KNCCI Nyeri Chapter";
$seo_desc = "Discover upcoming AGMs, workshops, networking forums, and training programmes for the Nyeri business community.";
$seo_image = SITE_MAIN_URL . "/Images/kncci.jpg";
$seo_url = SITE_MAIN_URL . "/events.php";
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
  <link rel="stylesheet" href="css/events.css" />
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
          <li><a href="events.php" class="nav-link active">Events</a></li>
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
        <span class="bc-current">Events</span>
      </nav>
      <h1 class="ph-title">Events & Programmes</h1>
      <p class="ph-subtitle">AGMs, workshops, networking forums, and training programmes for the Nyeri business community</p>
    </div>
  </section>

  <!-- FILTER TABS -->
  <div class="ev-tabs-bar" id="evTabsBar">
    <div class="container ev-tabs-inner">
      <div class="ev-tabs">
        <button class="ev-tab active" data-filter="all">All Events</button>
        <button class="ev-tab" data-filter="agm">AGM</button>
        <button class="ev-tab" data-filter="workshop">Workshop</button>
        <button class="ev-tab" data-filter="networking">Networking</button>
        <button class="ev-tab" data-filter="training">Training</button>
        <button class="ev-tab" data-filter="conference">Conference</button>
        <button class="ev-tab" data-filter="past">Past Events</button>
      </div>
      <div class="ev-view-toggle">
        <button class="view-btn active" id="gridView" aria-label="Grid view" title="Grid view">&#9632;&#9632;</button>
        <button class="view-btn" id="listView" aria-label="List view" title="List view">&#9644;</button>
      </div>
    </div>
  </div>

  <?php if ($featuredEvent): 
      $day = date('d', strtotime($featuredEvent['event_date']));
      $month = strtoupper(date('M', strtotime($featuredEvent['event_date'])));
      $year = date('Y', strtotime($featuredEvent['event_date']));
      $slugType = $typeMap[$featuredEvent['event_type']] ?? 'general';
  ?>
  <!-- FEATURED EVENT -->
  <div class="ev-featured-wrap" id="featuredWrap">
    <div class="container">
      <div class="ev-featured-card">
        <div class="ev-featured-date">
          <span class="efd-day"><?php echo $day; ?></span>
          <span class="efd-month"><?php echo $month; ?></span>
          <span class="efd-year"><?php echo $year; ?></span>
        </div>
        <div class="ev-featured-body">
          <div class="ev-featured-top">
            <span class="ev-badge ev-badge-<?php echo $slugType; ?>"><?php echo htmlspecialchars($featuredEvent['event_type']); ?></span>
            <span class="ev-status upcoming">Upcoming</span>
          </div>
          <h2 class="ev-featured-title"><?php echo htmlspecialchars($featuredEvent['title']); ?></h2>
          <div class="ev-featured-desc"><?php echo $featuredEvent['description']; ?></div>
          <div class="ev-featured-meta">
            <div class="ev-meta-item">
              <span class="ev-meta-label">Location</span>
              <span class="ev-meta-value"><?php echo htmlspecialchars($featuredEvent['location']); ?></span>
            </div>
            <?php if($featuredEvent['event_time_start']): ?>
            <div class="ev-meta-item">
              <span class="ev-meta-label">Time</span>
              <span class="ev-meta-value"><?php echo date('g:i A', strtotime($featuredEvent['event_time_start'])); ?><?php echo $featuredEvent['event_time_end'] ? ' – ' . date('g:i A', strtotime($featuredEvent['event_time_end'])) : ''; ?></span>
            </div>
            <?php endif; ?>
          </div>
          <div class="ev-featured-actions">
            <?php if(!empty($featuredEvent['registration_link'])): ?>
              <a href="<?php echo htmlspecialchars($featuredEvent['registration_link']); ?>" target="_blank" class="btn btn-gold">Register Now</a>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php endif; ?>

  <!-- UPCOMING & PAST EVENTS GRID -->
  <section class="section ev-section" id="evSection">
    <div class="container">
      <div class="section-header">
        <span class="section-label" id="sectionLabel">Events & Programmes</span>
        <h2 class="section-title" id="sectionTitle">Mark Your Calendar</h2>
        <p class="section-sub" id="sectionSub">Register early — spaces are limited for most events</p>
      </div>

      <div class="ev-grid" id="evGrid">
        <?php foreach ($events as $ev): 
            $day = date('d', strtotime($ev['event_date']));
            $month = strtoupper(date('M', strtotime($ev['event_date'])));
            $year = date('Y', strtotime($ev['event_date']));
            $slugType = $typeMap[$ev['event_type']] ?? 'general';
            $isPast = ($ev['event_date'] < $today);
            $statusClass = $isPast ? 'past' : 'upcoming';
            $statusLabel = $isPast ? 'Past' : 'Upcoming';
            $dateClass = $isPast ? 'past-date' : '';
        ?>
        <!-- Event Card -->
        <div class="ev-card" data-category="<?php echo $slugType; ?>" data-status="<?php echo $statusClass; ?>">
          <div class="ev-card-date <?php echo $dateClass; ?>">
            <span class="ecd-day"><?php echo $day; ?></span>
            <span class="ecd-month"><?php echo $month; ?></span>
            <span class="ecd-year"><?php echo $year; ?></span>
          </div>
          <div class="ev-card-body">
            <div class="ev-card-top">
              <span class="ev-badge ev-badge-<?php echo $slugType; ?>"><?php echo htmlspecialchars($ev['event_type']); ?></span>
              <span class="ev-status <?php echo $statusClass; ?>"><?php echo $statusLabel; ?></span>
            </div>
            <h3><?php echo htmlspecialchars($ev['title']); ?></h3>
            <div class="ev-card-meta">
              <span><b>Location:</b> <?php echo htmlspecialchars($ev['location']); ?></span>
              <?php if($ev['event_time_start']): ?>
                <span><b>Time:</b> <?php echo date('g:i A', strtotime($ev['event_time_start'])); ?><?php echo $ev['event_time_end'] ? ' – ' . date('g:i A', strtotime($ev['event_time_end'])) : ''; ?></span>
              <?php endif; ?>
            </div>
            <?php if($isPast): ?>
              <span class="btn btn-outline-dark ev-register-btn disabled" style="cursor: default; opacity: 0.6;">Event Concluded</span>
            <?php elseif(!empty($ev['registration_link'])): ?>
              <a href="<?php echo htmlspecialchars($ev['registration_link']); ?>" target="_blank" class="btn btn-primary ev-register-btn">Register</a>
            <?php else: ?>
              <a href="mailto:<?php echo htmlspecialchars($settings['contact_email']); ?>?subject=Inquiry about <?php echo rawurlencode($ev['title']); ?>" class="btn btn-primary ev-register-btn">Inquire</a>
            <?php endif; ?>
          </div>
        </div>
        <?php endforeach; ?>
        <?php if(empty($events)): ?>
          <div style="grid-column: 1 / -1; text-align: center; color: #64748B; padding: 40px 0;">No events scheduled or recorded at this time. Check back soon!</div>
        <?php endif; ?>
      </div><!-- /ev-grid -->

      <!-- Empty state -->
      <div class="ev-empty" id="evEmpty" style="display:none;">
        <div class="ev-empty-icon">—</div>
        <h3>No events in this category</h3>
        <p>Check back soon or view all upcoming events.</p>
        <button class="btn btn-primary" onclick="resetFilter()">View All Events</button>
      </div>

    </div>
  </section>

  <!-- CTA -->
  <section class="cta-banner">
    <div class="container cta-inner">
      <div class="cta-text">
        <h2>Never Miss an Event</h2>
        <p>KNCCI Nyeri members receive priority invitations, early-bird access, and discounted registration for all chapter events.</p>
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
  <script src="js/events.js"></script>
</body>
</html>

