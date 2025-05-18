<?php
session_start();
require '../../config/db.php';

// Sécurité : redirection si non connecté en tant qu'admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

// Récupérer tous les utilisateurs
$query = $pdo->query("SELECT * FROM users");
$users = $query->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gestion des utilisateurs</title>
    <style>
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        th { background-color: #eee; }
        a { margin-right: 10px; }
    </style>
</head>
<body>
    <h2>Liste des utilisateurs</h2>
    <a href="admin_dashboard.php">← Retour au tableau de bord</a>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom d'utilisateur</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
            <tr>
                <td><?= $user['id']; ?></td>
                <td><?= htmlspecialchars($user['username']); ?></td>
                <td><?= htmlspecialchars($user['email']); ?></td>
                <td>
                    <a href="edit_user.php?id=<?= $user['id']; ?>">Éditer</a> |
                    <a href="delete_user.php?id=<?= $user['id']; ?>" onclick="return confirm('Supprimer cet utilisateur ?')">Supprimer</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
