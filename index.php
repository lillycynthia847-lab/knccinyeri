<?php
require_once 'admin/includes/config.php';

// Fetch all site settings into an easy-to-use array
$stmt = $pdo->query("SELECT setting_key, setting_value FROM site_settings");
$settings = [];
while ($row = $stmt->fetch()) {
    $settings[$row['setting_key']] = $row['setting_value'];
}

// Fetch top 3 upcoming events
$stmt = $pdo->query("SELECT * FROM events WHERE status = 'Upcoming' ORDER BY event_date ASC LIMIT 3");
$upcomingEvents = $stmt->fetchAll();

// Fetch top 3 published news articles
$stmt = $pdo->query("SELECT * FROM news WHERE status = 'Published' ORDER BY publish_date DESC LIMIT 3");
$latestNews = $stmt->fetchAll();

// Fetch Active Patrons
try {
    $stmt = $pdo->query("SELECT * FROM patrons WHERE status = 'Active' ORDER BY display_order ASC, created_at DESC");
    $activePatrons = $stmt->fetchAll();
} catch (PDOException $e) {
    $activePatrons = [];
}

// Fetch Chairman
$stmt = $pdo->query("SELECT * FROM management_team WHERE status = 'Active' AND position LIKE '%Chairman%' ORDER BY display_order ASC LIMIT 1");
$chairman = $stmt->fetch();
?>
<?php
// SEO Settings for Homepage
$seo_title = "KNCCI Nyeri Chapter | Empowering Business in Nyeri County";
$seo_desc = "The official portal for the Kenya National Chamber of Commerce and Industry, Nyeri Chapter. We advocate, connect, and grow businesses across Nyeri County.";
$seo_image = SITE_MAIN_URL . "/Images/kncci.jpg";
$seo_url = SITE_MAIN_URL . "/index.php";
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
  <link rel="stylesheet" href="css/management.css" />
</head>
<body>

  <!-- ===== TOP BAR ===== -->
  <div class="top-bar">
    <div class="container top-bar-inner">
      <div class="top-bar-left">
        <a href="tel:<?php echo str_replace(' ', '', $settings['contact_phone']); ?>">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 6px; vertical-align: text-bottom;"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
          <?php echo htmlspecialchars($settings['contact_phone']); ?>
        </a>
        <a href="mailto:<?php echo htmlspecialchars($settings['contact_email']); ?>">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 6px; vertical-align: text-bottom;"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
          <?php echo htmlspecialchars($settings['contact_email']); ?>
        </a>
        
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

        <!-- Logo -->
        <a href="#" class="logo" id="logo">
          <img src="Images/kncci.jpg" alt="KNCCI Nyeri Chapter Logo" class="logo-img" />
          <div class="logo-label">
            <span class="logo-label-main">KNCCI Nyeri</span>
            <span class="logo-label-sub">Nyeri County Chapter</span>
          </div>
        </a>

        <!-- Nav Links -->
        <ul class="nav-links" id="navLinks">
          <li><a href="index.php" class="nav-link active">Home</a></li>
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

  <!-- ===== HERO SLIDESHOW ===== -->
  <?php
    // Gather gallery slides — add more gallery_image keys here if you extend the admin
    $slides = [];
    for ($i = 1; $i <= 5; $i++) {
        $imgKey = 'gallery_image_' . $i;
        $capKey = 'gallery_caption_' . $i;
        if (!empty($settings[$imgKey])) {
            $slides[] = [
                'image'   => $settings[$imgKey],
                'caption' => $settings[$capKey] ?? ''
            ];
        }
    }
    // Fallback: if no gallery images exist, show at least a placeholder slide
    if (empty($slides)) {
        $slides[] = ['image' => '', 'caption' => 'KNCCI Nyeri Chapter'];
    }
  ?>
  <section class="hero-slider" id="hero">

    <!-- Slides -->
    <div class="slider-track">
      <?php foreach ($slides as $idx => $slide): ?>
      <div class="slide <?php echo $idx === 0 ? 'slide--active' : ''; ?>"
           style="background-image: url('<?php echo htmlspecialchars($slide['image']); ?>');">
        <div class="slide-overlay"></div>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- Text Overlay -->
    <div class="slider-content">
      <div class="container">
        <div class="hero-badge"><?php echo htmlspecialchars($settings['hero_badge']); ?></div>
        <h1 class="hero-title">
          <?php echo htmlspecialchars($settings['hero_title_line1']); ?><br>
          <em><?php echo htmlspecialchars($settings['hero_title_highlight']); ?></em><br>
          <?php echo htmlspecialchars($settings['hero_title_line3']); ?>
        </h1>
        <p class="hero-subtitle">
          <?php echo htmlspecialchars($settings['hero_subtitle']); ?>
        </p>
        <div class="hero-actions">
          <a href="#" class="btn btn-ghost">Join as a Member</a>
        </div>

        <!-- Navigation Dots (Inline with content) -->
        <?php if (count($slides) > 1): ?>
        <div class="slider-dots">
          <?php foreach ($slides as $idx => $slide): ?>
          <button class="slider-dot <?php echo $idx === 0 ? 'dot--active' : ''; ?>"
                  data-index="<?php echo $idx; ?>"
                  aria-label="Go to slide <?php echo $idx + 1; ?>"></button>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>

      </div>
    </div>



    <!-- Arrows -->
    <?php if (count($slides) > 1): ?>
    <button class="slider-arrow slider-arrow--prev" aria-label="Previous slide">‹</button>
    <button class="slider-arrow slider-arrow--next" aria-label="Next slide">›</button>
    <?php endif; ?>

  </section>



  <!-- ===== ABOUT ===== -->
  <section class="section about" id="about">
    <div class="container about-grid">

      <div class="about-visual">
        <div class="about-box" style="background-image: url('Images/KNCCI Nyeri Chapter House.jpg'); background-size: cover; background-position: center;">
          <div class="about-float-badge">
            <strong><?php echo htmlspecialchars($settings['about_established']); ?></strong>
            <small><?php echo htmlspecialchars($settings['about_established_sub']); ?></small>
          </div>
        </div>
        <div class="about-pillars">
          <div class="pillar">Advocacy</div>
          <div class="pillar">Networking</div>
          <div class="pillar">Training</div>
          <div class="pillar">Partnerships</div>
        </div>
      </div>

      <div class="about-content">
        <span class="section-label">About Us</span>
        <h2 class="section-title"><?php echo nl2br(htmlspecialchars($settings['about_title'])); ?></h2>
        <p><?php echo htmlspecialchars($settings['about_paragraph_1']); ?></p>
        <p><?php echo htmlspecialchars($settings['about_paragraph_2']); ?></p>
        <ul class="checklist">
          <li>Government-recognised business chamber</li>
          <li>Affiliated to the national KNCCI body</li>
          <li>Active membership across all business sectors</li>
          <li>Strong partnerships with county and national government</li>
        </ul>
        <a href="about.php" class="btn btn-primary">Learn More About Us</a>
      </div>

    </div>
  </section>

  <!-- ===== CHAIRMAN QUOTE ===== -->
  <?php if (!empty($chairman)): 
      $initials = '';
      $nameParts = explode(' ', $chairman['full_name']);
      foreach ($nameParts as $part) { $initials .= strtoupper(substr($part, 0, 1)); }
      $initials = substr($initials, 0, 2);
  ?>
  <div class="section-divider bg-white"><div class="sd-line"></div><div class="sd-dot"></div><div class="sd-line"></div></div>
  <section class="section" style="background: var(--white); padding: 20px 0 80px 0;">
    <div class="container">
      <div class="mgmt-top-grid" style="grid-template-columns: 1fr; max-width: 900px; margin: 0 auto;">
        <div class="mgmt-card mgmt-card-top" style="border: none; box-shadow: 0 10px 40px rgba(12,30,53,.08); background: #f8fafc; overflow: hidden;">
          <div class="mgmt-photo">
            <?php if(!empty($chairman['profile_image'])): ?>
              <img src="<?php echo htmlspecialchars($chairman['profile_image']); ?>" alt="<?php echo htmlspecialchars($chairman['full_name']); ?>" style="width:100%; height:100%; object-fit:cover; object-position:top center; border-radius:inherit; position:absolute; inset:0;" />
            <?php else: ?>
              <div class="mgmt-initials"><?php echo $initials; ?></div>
            <?php endif; ?>
          </div>
          <div class="mgmt-info" style="padding: 40px 48px;">
            <div style="font-size: 4rem; color: var(--green); line-height: 0.5; margin-bottom: 24px; font-family: serif; opacity: 0.8;">"</div>
            <p style="font-size: 1.15rem; font-style: italic; color: var(--navy); line-height: 1.8; margin-bottom: 32px;">
              The Chamber has a broader vision of enhancing business vibrancy in the region through research, information sharing, and collective advocacy.
            </p>
            <h3 class="mgmt-name" style="font-size: 1.35rem; margin-bottom: 6px;"><?php echo htmlspecialchars($chairman['full_name']); ?></h3>
            <span class="mgmt-role"><?php echo htmlspecialchars($chairman['position']); ?></span>
          </div>
        </div>
      </div>
    </div>
  </section>
  <?php endif; ?>


  <!-- ===== EVENTS ===== -->
  <section class="section events" id="events">
    <div class="container">
      <div class="section-header light">
        <span class="section-label">Calendar</span>
        <h2 class="section-title">Upcoming Events</h2>
        <p class="section-sub">Stay connected — join our events and grow your network</p>
      </div>
      <div class="events-grid">
        <?php foreach ($upcomingEvents as $index => $e): 
            $day = date('d', strtotime($e['event_date']));
            $month = strtoupper(date('M', strtotime($e['event_date'])));
            $year = date('Y', strtotime($e['event_date']));
            $isFeatured = ($index === 0) ? 'event-featured' : '';
        ?>
        <div class="event-card <?php echo $isFeatured; ?>">
          <div class="ev-date">
            <span class="ev-day"><?php echo $day; ?></span>
            <span class="ev-month"><?php echo $month; ?></span>
            <span class="ev-year"><?php echo $year; ?></span>
          </div>
          <div class="ev-body">
            <span class="ev-tag"><?php echo htmlspecialchars($e['event_type']); ?></span>
            <h3><?php echo htmlspecialchars($e['title']); ?></h3>
            <?php if ($index === 0): ?>
              <div class="ev-body-desc"><?php echo $e['description']; ?></div>
            <?php endif; ?>
            <div class="ev-meta">
              <span><b>Location:</b> <?php echo htmlspecialchars($e['location']); ?></span>
              <?php if($e['event_time_start']): ?>
                <span><b>Time:</b> <?php echo date('g:i A', strtotime($e['event_time_start'])); ?><?php echo $e['event_time_end'] ? ' – ' . date('g:i A', strtotime($e['event_time_end'])) : ''; ?></span>
              <?php endif; ?>
            </div>
            <?php if(!empty($e['registration_link'])): ?>
              <a href="<?php echo htmlspecialchars($e['registration_link']); ?>" target="_blank" class="<?php echo $index === 0 ? 'btn btn-gold' : 'ev-link'; ?>">Register Now</a>
            <?php else: ?>
              <a href="events.php" class="<?php echo $index === 0 ? 'btn btn-gold' : 'ev-link'; ?>">View Details →</a>
            <?php endif; ?>
          </div>
        </div>
        <?php endforeach; ?>
        <?php if(empty($upcomingEvents)): ?>
          <div style="grid-column: 1 / -1; text-align: center; color: rgba(255,255,255,0.7); padding: 40px 0;">No upcoming events scheduled at this time. Check back soon!</div>
        <?php endif; ?>
      </div>
      <div class="section-cta">
        <a href="events.php" class="btn btn-outline-white">View All Events →</a>
      </div>
    </div>
  </section>

  <!-- ===== NEWS ===== -->
  <section class="section news bg-light" id="news">
    <div class="container">
      <div class="section-header" style="text-align: center; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 4px;">
        <span class="section-label">Latest Updates</span>
        <h2 class="section-title" style="margin-bottom: 8px;">News & Announcements</h2>
        <p class="section-sub" style="margin-bottom: 24px;">Stay informed on developments from KNCCI Nyeri and the business community</p>
        <a href="news.php" class="btn btn-outline-dark">See All News →</a>
      </div>
      <div class="news-grid">
        <?php foreach ($latestNews as $article): ?>
        <div class="news-card">
          <div class="news-thumb">
            <div class="news-blur" <?php if(!empty($article['cover_image'])): ?>style="background-image: url('<?php echo htmlspecialchars($article['cover_image']); ?>')"<?php endif; ?>></div>
            <?php if(!empty($article['cover_image'])): ?>
              <img src="<?php echo htmlspecialchars($article['cover_image']); ?>" class="news-img" alt="">
            <?php endif; ?>
          </div>
          <div class="news-body">
            <span class="news-tag"><?php echo htmlspecialchars($article['category']); ?></span>
            <h3><?php echo htmlspecialchars($article['title']); ?></h3>
            <div class="news-foot">
              <span class="news-date"><?php echo date('F d, Y', strtotime($article['publish_date'])); ?></span>
              <a href="news-single.php?id=<?php echo $article['id']; ?>" class="news-link">Read More →</a>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
        <?php if(empty($latestNews)): ?>
          <div style="grid-column: 1 / -1; text-align: center; color: #64748B; padding: 40px 0;">No news articles available. Check back soon!</div>
        <?php endif; ?>
      </div>
      <div class="section-cta">
        <!-- Button moved to top header -->
      </div>
    </div>
  </section>

  <!-- ===== OUR PATRONS ===== -->
  <div class="section-divider bg-light"><div class="sd-line"></div><div class="sd-dot"></div><div class="sd-line"></div></div>
  <section class="section patrons bg-light" id="patrons" style="padding-top: 20px;">
    <div class="container">
      <div class="section-header" style="text-align: center; margin-bottom: 48px;">
        <span class="section-label">Our Network</span>
        <h2 class="section-title">Our Patrons</h2>
        <p class="section-sub">Proudly supported by leading companies and institutions in Nyeri County</p>
      </div>
      
      <?php if (empty($activePatrons)): ?>
        <div style="text-align: center; padding: 40px 0; color: var(--muted); font-style: italic; font-size: 1.1rem;">
          Will be updated soon...
        </div>
      <?php else: ?>
        <!-- Patrons Slideshow Gallery (Marquee) -->
        <div class="patrons-marquee-container">
          <div class="patrons-marquee">
            <!-- Duplicate set 1 -->
            <?php foreach ($activePatrons as $patron): ?>
              <div class="patron-logo">
                <?php if (!empty($patron['logo_url'])): ?>
                  <img src="<?php echo htmlspecialchars($patron['logo_url']); ?>" alt="<?php echo htmlspecialchars($patron['company_name']); ?>" style="max-width:100%; max-height:100%; object-fit:contain;">
                <?php else: ?>
                  <?php echo htmlspecialchars($patron['company_name']); ?>
                <?php endif; ?>
              </div>
            <?php endforeach; ?>
            
            <!-- Duplicate set 2 (for infinite loop) -->
            <?php foreach ($activePatrons as $patron): ?>
              <div class="patron-logo">
                <?php if (!empty($patron['logo_url'])): ?>
                  <img src="<?php echo htmlspecialchars($patron['logo_url']); ?>" alt="<?php echo htmlspecialchars($patron['company_name']); ?>" style="max-width:100%; max-height:100%; object-fit:contain;">
                <?php else: ?>
                  <?php echo htmlspecialchars($patron['company_name']); ?>
                <?php endif; ?>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </section>

  <!-- ===== CTA BANNER ===== -->
  <section class="cta-banner">
    <div class="container cta-inner">
      <div class="cta-text">
        <h2><?php echo htmlspecialchars($settings['cta_title']); ?></h2>
        <p><?php echo htmlspecialchars($settings['cta_text']); ?></p>
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
          <li style="margin-bottom: 16px;">
            <span class="fc-label" style="display: inline-flex; align-items: center; gap: 8px; margin-bottom: 4px;">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
              Location
            </span>
            <span style="display: block; opacity: 0.8;"><?php echo htmlspecialchars($settings['contact_location']); ?></span>
          </li>
          <li style="margin-bottom: 16px;">
            <span class="fc-label" style="display: inline-flex; align-items: center; gap: 8px; margin-bottom: 4px;">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
              Tel
            </span>
            <a href="tel:<?php echo str_replace(' ', '', $settings['contact_phone']); ?>" style="display: block; opacity: 0.8;"><?php echo htmlspecialchars($settings['contact_phone']); ?></a>
          </li>
          <li style="margin-bottom: 16px;">
            <span class="fc-label" style="display: inline-flex; align-items: center; gap: 8px; margin-bottom: 4px;">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
              Email
            </span>
            <a href="mailto:<?php echo htmlspecialchars($settings['contact_email']); ?>" style="display: block; opacity: 0.8;"><?php echo htmlspecialchars($settings['contact_email']); ?></a>
          </li>
          <li style="margin-bottom: 16px;">
            <span class="fc-label" style="display: inline-flex; align-items: center; gap: 8px; margin-bottom: 4px;">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
              Hours
            </span>
            <span style="display: block; opacity: 0.8;"><?php echo htmlspecialchars($settings['contact_hours']); ?></span>
          </li>
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

