<?php
session_start();
require '../../config/db.php'; // Connexion à la base de données

// Vérification de l'authentification de l'administrateur
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

$error = "";
$success = "";

// Si le formulaire est soumis (méthode POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $hashed_password = password_hash($password, PASSWORD_BCRYPT); // Hachage du mot de passe

    // Vérifier si l'email est déjà utilisé
    $query = $pdo->prepare("SELECT * FROM admins WHERE email = ?");
    $query->execute([$email]);
    $admin_exists = $query->fetch();

    if ($admin_exists) {
        $error = "Cet email est déjà utilisé.";
    } else {
        // Insérer le nouvel administrateur dans la base de données
        $insert_query = $pdo->prepare("INSERT INTO admins (email, password, role) VALUES (?, ?, ?)");
        $insert_query->execute([$email, $hashed_password, 'admin']);
        $success = "L'administrateur a été ajouté avec succès.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Inscription Administrateur</title>
</head>
<body>
    <h2>Inscription d'un nouvel administrateur</h2>

    <!-- Affichage du message de succès ou d'erreur -->
    <?php if ($success): ?>
        <p style="color: green;"><?php echo $success; ?></p>
    <?php endif; ?>
    <?php if ($error): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <!-- Formulaire d'inscription -->
    <form method="POST" action="register_admin.php">
        <label>Email:</label>
        <input type="email" name="email" required><br>
        <label>Mot de passe:</label>
        <input type="password" name="password" required><br>
        <button type="submit">S'inscrire</button>
    </form>
    
    <a href="admin_dashboard.php">Retour au tableau de bord</a>
</body>
</html>
