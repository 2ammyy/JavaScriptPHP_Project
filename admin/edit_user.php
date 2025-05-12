<?php
session_start();
require '../../config/db.php';

// Sécurité : redirection si non connecté en tant qu'admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

// Vérification que l'ID est passé dans l'URL
if (isset($_GET['id'])) {
    $userId = $_GET['id'];

    // Récupérer les données actuelles de l'utilisateur
    $query = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $query->execute([$userId]);
    $user = $query->fetch();

    // Si l'utilisateur existe
    if (!$user) {
        echo "Utilisateur non trouvé.";
        exit;
    }
} else {
    // Si aucun ID n'est passé dans l'URL
    echo "Erreur : aucun utilisateur sélectionné pour modification.";
    exit;
}

// Traitement du formulaire de modification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];

    // Mettre à jour les informations dans la base de données
    $query = $pdo->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
    $query->execute([$username, $email, $userId]);

    // Redirection après la modification
    header("Location: admin_users.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Modifier Utilisateur</title>
</head>
<body>
    <h2>Modifier l'utilisateur</h2>
    <form method="POST" action="edit_user.php?id=<?= $user['id']; ?>">
        <label>Nom d'utilisateur :</label>
        <input type="text" name="username" value="<?= htmlspecialchars($user['username']); ?>" required><br><br>

        <label>Email :</label>
        <input type="email" name="email" value="<?= htmlspecialchars($user['email']); ?>" required><br><br>

        <button type="submit">Sauvegarder</button>
    </form>
</body>
</html>
