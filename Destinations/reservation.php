<?php
session_start();
require_once __DIR__.'/../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit();
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $destination_id = $_POST['destination_id'];
    $user_id = $_SESSION['user_id'];
    $date_depart = $_POST['date_depart'];
    $date_retour = $_POST['date_retour'];
    $nb_personnes = $_POST['nb_personnes'];
    $message = $_POST['message'] ?? '';

    try {
        $stmt = $pdo->prepare("INSERT INTO reservations (user_id, destination_id, date_depart, date_retour, nb_personnes, message) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $destination_id, $date_depart, $date_retour, $nb_personnes, $message]);
        
        $_SESSION['success'] = "Réservation confirmée!";
        header("Location: reservation.php");
        exit;
    } catch (PDOException $e) {
        $error = "Erreur : " . $e->getMessage();
    }
}

// Récupérer les destinations
$destinations = $pdo->query("SELECT id, name FROM destinations")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réservation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Formulaire de Réservation</h2>
        
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= $_SESSION['success'] ?></div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Destination</label>
                <select name="destination_id" class="form-control" required>
                    <?php foreach ($destinations as $d): ?>
                        <option value="<?= $d['id'] ?>"><?= htmlspecialchars($d['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Date de départ</label>
                    <input type="date" name="date_depart" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Date de retour</label>
                    <input type="date" name="date_retour" class="form-control" required>
                </div>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Nombre de personnes</label>
                <input type="number" name="nb_personnes" class="form-control" min="1" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Demandes spéciales</label>
                <textarea name="message" class="form-control" rows="3"></textarea>
            </div>
            
            <button type="submit" class="btn btn-primary">Confirmer</button>
        </form>
    </div>
</body>
</html>