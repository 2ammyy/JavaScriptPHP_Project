<?php
require_once __DIR__ . '/../../config/db.php';

if (isset($_POST['id'])) {
    $id = (int)$_POST['id'];
    $stmt = $pdo->prepare("DELETE FROM reservation WHERE id = ?");
    $stmt->execute([$id]);
}

header("Location: reservation.php");
exit;
