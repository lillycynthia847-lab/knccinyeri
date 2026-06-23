<?php
require_once 'includes/config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Fetch image path to delete it from server
    $stmt = $pdo->prepare("SELECT cover_image FROM news WHERE id = ?");
    $stmt->execute([$id]);
    $article = $stmt->fetch();
    
    if ($article && $article['cover_image']) {
        $imagePath = '../' . $article['cover_image'];
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
    }
    
    // Delete record
    $stmt = $pdo->prepare("DELETE FROM news WHERE id = ?");
    $stmt->execute([$id]);
}

header("Location: news.php");
exit;
?>
