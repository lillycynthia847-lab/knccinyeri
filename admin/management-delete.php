<?php
require_once 'includes/config.php';
if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT profile_image FROM management_team WHERE id = ?"); $stmt->execute([$_GET['id']]); $member = $stmt->fetch();
    if ($member && $member['profile_image'] && file_exists('../' . $member['profile_image'])) unlink('../' . $member['profile_image']);
    $stmt = $pdo->prepare("DELETE FROM management_team WHERE id = ?"); $stmt->execute([$_GET['id']]);
}
header("Location: management.php"); exit;
?>
