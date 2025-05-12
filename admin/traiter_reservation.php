<?php
session_start();
require_once __DIR__ . '/../../config/db.php';

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit();
}

$userId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérifie que tous les champs obligatoires sont présents
    if (
        isset($_POST['type_voyage'], $_POST['depart'], $_POST['destination'], $_POST['date_aller'],
              $_POST['adulte'], $_POST['ado'], $_POST['bebe'], $_POST['classe'])
    ) {
        // Nettoie les données
        $type_voyage = htmlspecialchars($_POST['type_voyage']);
        $depart = htmlspecialchars($_POST['depart']);
        $destination = htmlspecialchars($_POST['destination']);
        $date_aller = htmlspecialchars($_POST['date_aller']);
        $date_retour = !empty($_POST['date_retour']) ? htmlspecialchars($_POST['date_retour']) : null;
        $adulte = (int)$_POST['adulte'];
        $ado = (int)$_POST['ado'];
        $bebe = (int)$_POST['bebe'];
        $classe = htmlspecialchars($_POST['classe']);

        // Prépare la requête d'insertion
        $stmt = $pdo->prepare("INSERT INTO reservation (
            user_id, type_voyage, depart, destination, date_aller, date_retour,
            adulte, ado, bebe, classe, date_reservation
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");

        // Exécute la requête avec les bonnes valeurs
        if ($stmt->execute([
            $userId, $type_voyage, $depart, $destination, $date_aller, $date_retour,
            $adulte, $ado, $bebe, $classe
        ])) {
            $success = "Réservation réussie !";
        } else {
            $error = "Erreur lors de l'enregistrement de la réservation.";
        }
    } else {
        $error = "Veuillez remplir tous les champs obligatoires.";
    }
}
?>

<!-- Affichage des messages -->
<?php if (isset($error)): ?>
    <p class="error"><?= htmlspecialchars($error) ?></p>
<?php elseif (isset($success)): ?>
    <p class="success"><?= htmlspecialchars($success) ?></p>
<?php endif; ?>
