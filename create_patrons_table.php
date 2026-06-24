<?php
$host = '127.0.0.1';
$user = 'root';
$pass = '';
$db   = 'kncci_db';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $pdo->exec("CREATE TABLE IF NOT EXISTS patrons (
        id INT AUTO_INCREMENT PRIMARY KEY,
        company_name VARCHAR(255) NOT NULL,
        logo_url VARCHAR(255) DEFAULT NULL,
        display_order INT DEFAULT 0,
        status ENUM('Active','Inactive') DEFAULT 'Active',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    echo "patrons table created successfully.";
} catch(PDOException $e) {
    echo "ERROR: " . $e->getMessage();
}
?>

