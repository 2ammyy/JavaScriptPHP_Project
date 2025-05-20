<?php
session_start();
require '../../config/db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

// Vérification que l'ID est passé dans l'URL
if (isset($_GET['id'])) {
    $userId = $_GET['id'];

    // Suppression de l'utilisateur par ID
    $query = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $query->execute([$userId]);

    // Redirection vers la page de gestion des utilisateurs
    header("Location: admin_users.php");
    exit;
} else {
    // Si aucun ID n'est passé dans l'URL
    echo "Erreur : aucun utilisateur sélectionné pour suppression.";
}
?>
