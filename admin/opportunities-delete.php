<?php
require_once 'includes/config.php';
if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT document FROM opportunities WHERE id = ?"); $stmt->execute([$_GET['id']]); $opp = $stmt->fetch();
    if ($opp && $opp['document'] && file_exists('../' . $opp['document'])) unlink('../' . $opp['document']);
    $stmt = $pdo->prepare("DELETE FROM opportunities WHERE id = ?"); $stmt->execute([$_GET['id']]);
}
header("Location: opportunities.php"); exit;
?>
