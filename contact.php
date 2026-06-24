<?php
require_once 'admin/includes/config.php';

// Fetch all site settings
$stmt = $pdo->query("SELECT setting_key, setting_value FROM site_settings");
$settings = [];
while ($row = $stmt->fetch()) {
    $settings[$row['setting_key']] = $row['setting_value'];
}

// Process Contact Form
$status_msg = '';
$status_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (!empty($name) && !empty($email) && !empty($subject) && !empty($message)) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Get the destination email from settings
            $to = $settings['contact_email'];
            $email_subject = "New Contact Form Submission: " . $subject;
            $email_body = "You have received a new message from the KNCCI Nyeri website contact form.\n\n" .
                          "Name: $name\n" .
                          "Email: $email\n" .
                          "Subject: $subject\n\n" .
                          "Message:\n$message\n";
            
            // Headers
            $headers = "From: noreply@knccinyeri.co.ke\r\n";
            $headers .= "Reply-To: $email\r\n";
            
            // Send email
            if (mail($to, $email_subject, $email_body, $headers)) {
                $status_msg = "Thank you! Your message has been sent successfully. We will get back to you soon.";
                $status_type = "success";
            } else {
                $status_msg = "Sorry, there was an error sending your message. Please try again later or contact us directly via email or phone.";
                $status_type = "error";
            }
        } else {
            $status_msg = "Please enter a valid email address.";
            $status_type = "error";
        }
    } else {
        $status_msg = "Please fill in all required fields.";
        $status_type = "error";
    }
}

// SEO Settings for Contact Page
$seo_title = "Contact Us | KNCCI Nyeri Chapter";
$seo_desc = "Get in touch with the Kenya National Chamber of Commerce and Industry (KNCCI) Nyeri Chapter. Find our location, email, and phone number here.";
$seo_image = SITE_MAIN_URL . "/Images/kncci.jpg";
$seo_url = SITE_MAIN_URL . "/contact.php";
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
  <link rel="stylesheet" href="css/contact.css" />
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
          <li><a href="management.php" class="nav-link">Management</a></li>
          <li><a href="contact.php" class="nav-link active">Contact</a></li>
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
        <span class="bc-current">Contact Us</span>
      </nav>
      <h1 class="ph-title">Get In Touch</h1>
      <p class="ph-subtitle">We are here to answer any questions you may have about our services, membership, or opportunities in Nyeri County.</p>
    </div>
  </section>

  <!-- ===== CONTACT CONTENT ===== -->
  <section class="section contact-main">
    <div class="container">
      <div class="contact-grid">
        
        <!-- Contact Info Cards -->
        <div class="contact-info-col">
          <div class="info-card">
            <div class="info-icon">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
            </div>
            <div class="info-details">
              <h3>Our Location</h3>
              <p><?php echo htmlspecialchars($settings['contact_location']); ?></p>
            </div>
          </div>
          
          <div class="info-card">
            <div class="info-icon">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
            </div>
            <div class="info-details">
              <h3>Call Us</h3>
              <p><a href="tel:<?php echo str_replace(' ', '', $settings['contact_phone']); ?>"><?php echo htmlspecialchars($settings['contact_phone']); ?></a></p>
            </div>
          </div>
          
          <div class="info-card">
            <div class="info-icon">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
            </div>
            <div class="info-details">
              <h3>Email Us</h3>
              <p><a href="mailto:<?php echo htmlspecialchars($settings['contact_email']); ?>"><?php echo htmlspecialchars($settings['contact_email']); ?></a></p>
            </div>
          </div>
          
          <div class="info-card">
            <div class="info-icon">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
            </div>
            <div class="info-details">
              <h3>Business Hours</h3>
              <p><?php echo htmlspecialchars($settings['contact_hours']); ?></p>
            </div>
          </div>
        </div>

        <!-- Contact Form -->
        <div class="contact-form-col">
          <div class="form-wrapper">
            <h2>Send us a Message</h2>
            <p>Fill out the form below and our team will get back to you promptly.</p>
            
            <?php if (!empty($status_msg)): ?>
              <div class="alert alert-<?php echo $status_type; ?>" style="padding: 16px; border-radius: 8px; margin-bottom: 24px; font-weight: 500; font-size: 0.95rem; <?php echo $status_type === 'success' ? 'background: #ecfdf5; color: #065f46; border: 1px solid #10b981;' : 'background: #fef2f2; color: #b91c1c; border: 1px solid #ef4444;'; ?>">
                <?php echo htmlspecialchars($status_msg); ?>
              </div>
            <?php endif; ?>
            
            <form action="#" method="POST" class="contact-form">
              <div class="form-row">
                <div class="form-group">
                  <label for="name">Full Name</label>
                  <input type="text" id="name" name="name" class="form-control" placeholder="John Doe" required>
                </div>
                <div class="form-group">
                  <label for="email">Email Address</label>
                  <input type="email" id="email" name="email" class="form-control" placeholder="john@example.com" required>
                </div>
              </div>
              <div class="form-group">
                <label for="subject">Subject</label>
                <input type="text" id="subject" name="subject" class="form-control" placeholder="How can we help you?" required>
              </div>
              <div class="form-group">
                <label for="message">Message</label>
                <textarea id="message" name="message" class="form-control" rows="5" placeholder="Write your message here..." required></textarea>
              </div>
              <button type="submit" class="btn btn-primary form-submit">Send Message →</button>
            </form>
          </div>
        </div>
        
      </div>
    </div>
  </section>

  <!-- ===== MAP SECTION ===== -->
  <section class="map-section">
    <div class="map-container">
      <iframe src="https://maps.google.com/maps?q=Gatemu%20Building,%20Chania%20Bridge,%20Nyeri&t=&z=16&ie=UTF8&iwloc=&output=embed" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
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

