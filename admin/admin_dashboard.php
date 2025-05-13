<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
</head>
<body>
    <h2>Bienvenue, <?php echo htmlspecialchars($_SESSION['email']); ?></h2>
    <a href="admin_logout.php">Se déconnecter</a>
    
    <h3>Gestion des utilisateurs</h3>
    <a href="admin_users.php">Voir et gérer les utilisateurs</a>
    <h3>Nouveau Administrateur</h3>
    <a href="register_admin.php">Ajouter un nouvel administrateur</a>
    <h3>reservation</h3>
    <a href="reservation.php">reservation/a>
</body>
</html>
