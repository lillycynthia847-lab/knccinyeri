<?php
$host = '127.0.0.1';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create Database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS kncci_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo = new PDO("mysql:host=$host;dbname=kncci_db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✓ Connected to kncci_db\n";

    // =============================================
    // TABLE 1: SITE SETTINGS (Key-Value pairs)
    // Covers: Hero text, stats, gallery, about text,
    //         footer contact info, social links, CTA text
    // =============================================
    $pdo->exec("CREATE TABLE IF NOT EXISTS site_settings (
        id INT AUTO_INCREMENT PRIMARY KEY,
        setting_key VARCHAR(100) NOT NULL UNIQUE,
        setting_value TEXT,
        setting_group VARCHAR(50) DEFAULT 'general',
        setting_label VARCHAR(150) DEFAULT NULL,
        setting_type ENUM('text','textarea','image','number') DEFAULT 'text'
    )");
    echo "✓ site_settings table created\n";

    // =============================================
    // TABLE 2: NEWS (already exists, recreate if needed)
    // =============================================
    $pdo->exec("CREATE TABLE IF NOT EXISTS news (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        category VARCHAR(100) NOT NULL,
        publish_date DATE NOT NULL,
        cover_image VARCHAR(255) DEFAULT NULL,
        content TEXT NOT NULL,
        status ENUM('Published','Draft') DEFAULT 'Published',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    echo "✓ news table created\n";

    // =============================================
    // TABLE 3: EVENTS
    // =============================================
    $pdo->exec("CREATE TABLE IF NOT EXISTS events (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        event_type VARCHAR(100) DEFAULT 'General',
        event_date DATE NOT NULL,
        event_time_start TIME DEFAULT NULL,
        event_time_end TIME DEFAULT NULL,
        location VARCHAR(255) DEFAULT NULL,
        description TEXT,
        cover_image VARCHAR(255) DEFAULT NULL,
        registration_link VARCHAR(500) DEFAULT NULL,
        status ENUM('Upcoming','Completed','Cancelled') DEFAULT 'Upcoming',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    echo "✓ events table created\n";

    // =============================================
    // TABLE 4: OPPORTUNITIES
    // =============================================
    $pdo->exec("CREATE TABLE IF NOT EXISTS opportunities (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        opp_type ENUM('Vendor','Government Tender','Job','Grant') NOT NULL,
        deadline DATE DEFAULT NULL,
        description TEXT,
        application_link VARCHAR(500) DEFAULT NULL,
        document VARCHAR(255) DEFAULT NULL,
        status ENUM('Open','Closed') DEFAULT 'Open',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    echo "✓ opportunities table created\n";

    // =============================================
    // TABLE 5: MANAGEMENT TEAM
    // =============================================
    $pdo->exec("CREATE TABLE IF NOT EXISTS management_team (
        id INT AUTO_INCREMENT PRIMARY KEY,
        full_name VARCHAR(255) NOT NULL,
        position VARCHAR(150) NOT NULL,
        team_group ENUM('Board of Directors','Sub-County Head') NOT NULL,
        bio TEXT DEFAULT NULL,
        profile_image VARCHAR(255) DEFAULT NULL,
        linkedin_url VARCHAR(500) DEFAULT NULL,
        display_order INT DEFAULT 0,
        status ENUM('Active','Former') DEFAULT 'Active',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    echo "✓ management_team table created\n";

    // =============================================
    // TABLE 6: MEMBERS (Registrations)
    // =============================================
    $pdo->exec("CREATE TABLE IF NOT EXISTS members (
        id INT AUTO_INCREMENT PRIMARY KEY,
        business_name VARCHAR(255) NOT NULL,
        contact_person VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL,
        phone VARCHAR(50) NOT NULL,
        sector VARCHAR(150) DEFAULT NULL,
        message TEXT DEFAULT NULL,
        status ENUM('Pending','Active','Suspended') DEFAULT 'Pending',
        registered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    echo "✓ members table created\n";

    // =============================================
    // TABLE 7: SERVICES
    // =============================================
    $pdo->exec("CREATE TABLE IF NOT EXISTS services (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        description TEXT,
        display_order INT DEFAULT 0,
        status ENUM('Active','Inactive') DEFAULT 'Active',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    echo "✓ services table created\n";

    // =============================================
    // SEED: site_settings with current homepage content
    // =============================================
    $settings = [
        // -- HERO SECTION --
        ['hero_badge', 'Nyeri County Chapter', 'hero', 'Hero Badge Text', 'text'],
        ['hero_title_line1', 'Empowering Business,', 'hero', 'Hero Title (Line 1)', 'text'],
        ['hero_title_highlight', 'Driving Growth', 'hero', 'Hero Title (Highlighted Text)', 'text'],
        ['hero_title_line3', 'in Nyeri County', 'hero', 'Hero Title (Line 3)', 'text'],
        ['hero_subtitle', 'The official voice of businesses in Nyeri — advocating for a thriving, inclusive economy through networking, policy reform, and enterprise development.', 'hero', 'Hero Subtitle', 'textarea'],

        // -- HERO STATS --
        ['stat_1_value', '15', 'hero_stats', 'Stat 1 Number', 'number'],
        ['stat_1_suffix', '+', 'hero_stats', 'Stat 1 Suffix', 'text'],
        ['stat_1_label', 'Years Active', 'hero_stats', 'Stat 1 Label', 'text'],
        ['stat_2_value', '47', 'hero_stats', 'Stat 2 Number', 'number'],
        ['stat_2_suffix', '', 'hero_stats', 'Stat 2 Suffix', 'text'],
        ['stat_2_label', 'Sectors', 'hero_stats', 'Stat 2 Label', 'text'],
        ['stat_3_prefix', 'Kes ', 'hero_stats', 'Stat 3 Prefix', 'text'],
        ['stat_3_value', '2', 'hero_stats', 'Stat 3 Number', 'number'],
        ['stat_3_suffix', 'B+', 'hero_stats', 'Stat 3 Suffix', 'text'],
        ['stat_3_label', 'Facilitated', 'hero_stats', 'Stat 3 Label', 'text'],

        // -- HERO GALLERY --
        ['gallery_image_1', '', 'hero_gallery', 'Gallery Image 1', 'image'],
        ['gallery_caption_1', 'Facilitating tea exports from Nyeri', 'hero_gallery', 'Gallery Caption 1', 'text'],
        ['gallery_image_2', '', 'hero_gallery', 'Gallery Image 2', 'image'],
        ['gallery_caption_2', 'Local entrepreneurship', 'hero_gallery', 'Gallery Caption 2', 'text'],
        ['gallery_image_3', '', 'hero_gallery', 'Gallery Image 3', 'image'],
        ['gallery_caption_3', 'Manufacturing & Jua Kali', 'hero_gallery', 'Gallery Caption 3', 'text'],

        // -- ABOUT SECTION (Homepage) --
        ['about_title', 'The Voice of Business in Nyeri County', 'about', 'About Section Title', 'text'],
        ['about_paragraph_1', 'The Kenya National Chamber of Commerce and Industry (KNCCI) Nyeri Chapter is the leading business membership organisation representing the interests of businesses operating in Nyeri County and its environs.', 'about', 'About Paragraph 1', 'textarea'],
        ['about_paragraph_2', 'We serve as the bridge between the private sector and government — advocating for policies that create an enabling environment for businesses to thrive and contribute to Nyeri\'s economic development.', 'about', 'About Paragraph 2', 'textarea'],
        ['about_established', 'Est. 1990s', 'about', 'About Established Badge', 'text'],
        ['about_established_sub', 'Serving Nyeri Business Community', 'about', 'About Established Sub-text', 'text'],

        // -- ABOUT PAGE (Our Story, Mission, Vision, Values) --
        ['story_title', 'Over Three Decades of Business Advocacy', 'about_page', 'Our Story Title', 'text'],
        ['story_paragraph_1', 'The Kenya National Chamber of Commerce and Industry (KNCCI) Nyeri Chapter was established to give the business community in Nyeri County a unified, powerful voice in matters of policy, trade, and economic development.', 'about_page', 'Our Story Paragraph 1', 'textarea'],
        ['story_paragraph_2', 'Since its founding, the chapter has grown from a small group of business leaders into a vibrant organisation representing hundreds of members across all sectors — from agriculture and manufacturing to trade, services, and the digital economy.', 'about_page', 'Our Story Paragraph 2', 'textarea'],
        ['story_paragraph_3', 'Today, KNCCI Nyeri Chapter is a key stakeholder in the county\'s economic development agenda, working closely with both the Nyeri County Government and national institutions to create a business environment where enterprises of all sizes can thrive.', 'about_page', 'Our Story Paragraph 3', 'textarea'],
        ['mission_text', 'To promote, support, and advocate for the interests of businesses in Nyeri County by providing relevant services, fostering partnerships, and engaging government for a conducive business environment.', 'about_page', 'Mission Statement', 'textarea'],
        ['vision_text', 'To be the leading, most trusted, and most impactful business membership organisation in Nyeri County — driving a prosperous, inclusive, and globally competitive local economy.', 'about_page', 'Vision Statement', 'textarea'],
        ['core_values', 'Integrity & Transparency|Inclusivity & Diversity|Innovation & Excellence|Accountability|Collaboration & Partnership', 'about_page', 'Core Values (pipe-separated)', 'textarea'],

        // -- CTA BANNER --
        ['cta_title', 'Ready to Grow Your Business?', 'cta', 'CTA Title', 'text'],
        ['cta_text', 'Join over 500 businesses in Nyeri County already benefiting from KNCCI membership. Get access to exclusive resources, events, and powerful advocacy.', 'cta', 'CTA Description', 'textarea'],

        // -- FOOTER / CONTACT --
        ['contact_phone', '+254 712 345 678', 'contact', 'Phone Number', 'text'],
        ['contact_email', 'nyeri@kenyachamber.or.ke', 'contact', 'Email Address', 'text'],
        ['contact_location', 'Nyeri Town, Nyeri County, Kenya', 'contact', 'Physical Address', 'text'],
        ['contact_hours', 'Mon – Fri: 8:00 AM – 5:00 PM', 'contact', 'Working Hours', 'text'],
        ['social_facebook', '#', 'social', 'Facebook URL', 'text'],
        ['social_twitter', '#', 'social', 'Twitter / X URL', 'text'],
        ['social_linkedin', '#', 'social', 'LinkedIn URL', 'text'],
        ['social_youtube', '#', 'social', 'YouTube URL', 'text'],
        ['footer_tagline', 'The official voice of business in Nyeri County. Advocating for growth, prosperity, and a thriving local economy.', 'contact', 'Footer Tagline', 'textarea'],
    ];

    $stmt = $pdo->prepare("INSERT IGNORE INTO site_settings (setting_key, setting_value, setting_group, setting_label, setting_type) VALUES (?, ?, ?, ?, ?)");
    foreach ($settings as $s) {
        $stmt->execute($s);
    }
    echo "✓ site_settings seeded with " . count($settings) . " entries\n";

    // =============================================
    // SEED: services with current homepage services
    // =============================================
    $services = [
        ['Policy Advocacy', 'Representing business interests at county and national government levels, pushing for favourable policies and regulations.', 1],
        ['Business Networking', 'Exclusive events and forums connecting Nyeri\'s business leaders, creating opportunities for collaboration and growth.', 2],
        ['Certificates & Documentation', 'Issuance of Certificates of Origin, business letters, and other trade facilitation documents recognised internationally.', 3],
        ['Training & Capacity Building', 'Workshops, seminars, and programmes designed to upskill business owners and their teams for a competitive edge.', 4],
        ['Business Matchmaking', 'Connecting local businesses with investors, suppliers, and trade partners regionally and internationally.', 5],
        ['Market Intelligence', 'Access to business intelligence, economic data, and market insights to help you make informed strategic decisions.', 6],
    ];

    $stmt = $pdo->prepare("INSERT IGNORE INTO services (title, description, display_order) VALUES (?, ?, ?)");
    foreach ($services as $s) {
        $stmt->execute($s);
    }
    echo "✓ services seeded with " . count($services) . " entries\n";

    echo "\n=== ALL TABLES CREATED AND SEEDED SUCCESSFULLY ===\n";

} catch(PDOException $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
?>

