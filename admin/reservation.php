<?php
session_start();
require_once '../../config/db.php';

// Vérification si l'administrateur est connecté
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Récupération de toutes les réservations
$stmt = $pdo->query("SELECT r.*, u.username 
                     FROM reservation r 
                     JOIN users u ON r.user_id = u.id 
                     ORDER BY r.date_reservation DESC");

// Vérifier si la requête a retourné des résultats
$reservations = $stmt->fetchAll();

if ($reservations === false) {
    $error_message = "Aucune réservation trouvée.";
} else {
    // Code pour afficher les réservations
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau des Réservations</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        h1 {
            text-align: center;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <h1>Liste des Réservations</h1>

    <?php if (isset($error_message)): ?>
        <p><?= htmlspecialchars($error_message) ?></p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Utilisateur</th>
                    <th>Type</th>
                    <th>Départ</th>
                    <th>Destination</th>
                    <th>Date Aller</th>
                    <th>Date Retour</th>
                    <th>Adultes</th>
                    <th>Ados</th>
                    <th>Bébés</th>
                    <th>Classe</th>
                    <th>Date Réservation</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reservations as $res): ?>
                    <tr>
                        <td><?= htmlspecialchars($res['username']) ?></td>
                        <td><?= htmlspecialchars($res['type_voyage']) ?></td>
                        <td><?= htmlspecialchars($res['depart']) ?></td>
                        <td><?= htmlspecialchars($res['destination']) ?></td>
                        <td><?= htmlspecialchars($res['date_aller']) ?></td>
                        <td><?= htmlspecialchars($res['date_retour']) ?: '—' ?></td>
                        <td><?= (int)$res['adulte'] ?></td>
                        <td><?= (int)$res['ado'] ?></td>
                        <td><?= (int)$res['bebe'] ?></td>
                        <td><?= htmlspecialchars($res['classe']) ?></td>
                        <td><?= $res['date_reservation'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>
