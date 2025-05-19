<?php
require_once __DIR__ . '/../../config/db.php';

// Vérifie si l'ID est présent
if (!isset($_GET['id'])) {
    die("ID de réservation manquant.");
}

$id = (int)$_GET['id'];

// Récupère la réservation actuelle
$stmt = $pdo->prepare("SELECT * FROM reservation WHERE id = ?");
$stmt->execute([$id]);
$reservation = $stmt->fetch();

if (!$reservation) {
    die("Réservation introuvable.");
}

// Traitement de la modification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type_voyage = htmlspecialchars($_POST['type_voyage']);
    $depart = htmlspecialchars($_POST['depart']);
    $destination = htmlspecialchars($_POST['destination']);
    $date_aller = $_POST['date_aller'];
    $date_retour = !empty($_POST['date_retour']) ? $_POST['date_retour'] : null;
    $adulte = (int)$_POST['adulte'];
    $ado = (int)$_POST['ado'];
    $bebe = (int)$_POST['bebe'];
    $classe = htmlspecialchars($_POST['classe']);

    $stmt = $pdo->prepare("UPDATE reservation SET 
        type_voyage = ?, depart = ?, destination = ?, date_aller = ?, date_retour = ?, 
        adulte = ?, ado = ?, bebe = ?, classe = ?
        WHERE id = ?");

    $stmt->execute([
        $type_voyage, $depart, $destination, $date_aller, $date_retour,
        $adulte, $ado, $bebe, $classe, $id
    ]);

    header("Location: reservation.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Réservation</title>
    <style>
        body { font-family: Arial; padding: 20px; background-color: #f8f9fa; }
        form { max-width: 600px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px #ccc; }
        label { display: block; margin-top: 10px; }
        input, select { width: 100%; padding: 8px; margin-top: 5px; border-radius: 4px; border: 1px solid #ccc; }
        button { margin-top: 20px; padding: 10px 15px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; }
        h2 { text-align: center; color: #007bff; }
    </style>
</head>
<body>

<h2>Modifier la Réservation</h2>

<form method="POST">
    <label>Type de voyage :</label>
    <select name="type_voyage">
        <option value="aller simple" <?= $reservation['type_voyage'] === 'aller simple' ? 'selected' : '' ?>>Aller simple</option>
        <option value="aller-retour" <?= $reservation['type_voyage'] === 'aller-retour' ? 'selected' : '' ?>>Aller-retour</option>
    </select>

    <label>Départ :</label>
    <input type="text" name="depart" value="<?= htmlspecialchars($reservation['depart']) ?>" required>

    <label>Destination :</label>
    <input type="text" name="destination" value="<?= htmlspecialchars($reservation['destination']) ?>" required>

    <label>Date aller :</label>
    <input type="date" name="date_aller" value="<?= $reservation['date_aller'] ?>" required>

    <label>Date retour :</label>
    <input type="date" name="date_retour" value="<?= $reservation['date_retour'] ?>">

    <label>Nombre d'adultes :</label>
    <input type="number" name="adulte" value="<?= $reservation['adulte'] ?>" required>

    <label>Nombre d'adolescents :</label>
    <input type="number" name="ado" value="<?= $reservation['ado'] ?>" required>

    <label>Nombre de bébés :</label>
    <input type="number" name="bebe" value="<?= $reservation['bebe'] ?>" required>

    <label>Classe :</label>
    <select name="classe">
        <option value="économique" <?= $reservation['classe'] === 'économique' ? 'selected' : '' ?>>Économique</option>
        <option value="business" <?= $reservation['classe'] === 'business' ? 'selected' : '' ?>>Business</option>
        <option value="première" <?= $reservation['classe'] === 'première' ? 'selected' : '' ?>>Première</option>
    </select>

    <button type="submit">Enregistrer les modifications</button>
</form>

</body>
</html>
