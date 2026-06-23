<?php
require_once 'includes/config.php';
if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT cover_image FROM events WHERE id = ?"); $stmt->execute([$_GET['id']]); $e = $stmt->fetch();
    if ($e && $e['cover_image'] && file_exists('../' . $e['cover_image'])) unlink('../' . $e['cover_image']);
    $stmt = $pdo->prepare("DELETE FROM events WHERE id = ?"); $stmt->execute([$_GET['id']]);
}
header("Location: events.php"); exit;
?>
