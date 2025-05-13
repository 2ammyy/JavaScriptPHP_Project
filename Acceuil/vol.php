<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $query->execute([$email]);
    $user = $query->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = [
            'id' => $user['id'],
            'email' => $user['email'],
            
        ];
        header("Location: Acceuil/vol.php"); // Ou n'importe quelle autre page
        exit;
    } else {
        $error = "Identifiants incorrects.";
    }
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réservation de Vol</title>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <style>
       /* pas faite encore/*
    </style>
</head>
<body>
<div class="reservation-container">
    <h1>Réserver un vol</h1>

    <form id="reservationForm" method="POST" action="../login/admin/traiter_reservation.php">
        <div class="form-group">
            <label for="flightType">Type de vol</label>
            <select id="flightType" name="type_voyage" required>
                <option value="aller-retour">Aller-retour</option>
                <option value="aller-simple">Aller simple</option>
            </select>
        </div>

        <div class="row">
            <div class="col">
                <div class="form-group">
                    <label for="departure">Départ</label>
                    <input type="text" id="departure" name="depart" required>
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <label for="destination">Destination</label>
                    <input type="text" id="destination" name="destination" required>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <div class="form-group">
                    <label for="departureDatetime">Date et heure de départ</label>
                    <input type="datetime-local" id="departureDatetime" name="date_aller" required>
                </div>
            </div>
            <div class="col" id="returnDatetimeCol">
                <div class="form-group">
                    <label for="returnDatetime">Date et heure de retour</label>
                    <input type="datetime-local" id="returnDatetime" name="date_retour">
                </div>
            </div>
        </div>

        <div class="form-group">
            <label>Passagers</label>
            <div class="passenger-control">
                <span class="material-symbols-outlined">person</span>
                <span>Adultes (12+)</span>
                <button type="button" onclick="updatePassenger('adult', -1)">-</button>
                <span id="adultCountDisplay">1</span>
                <button type="button" onclick="updatePassenger('adult', 1)">+</button>
            </div>

            <div class="passenger-control">
                <span class="material-symbols-outlined">child_care</span>
                <span>Enfants (2-11)</span>
                <button type="button" onclick="updatePassenger('child', -1)">-</button>
                <span id="childCountDisplay">0</span>
                <button type="button" onclick="updatePassenger('child', 1)">+</button>
            </div>

            <div class="passenger-control">
                <span class="material-symbols-outlined">baby_changing_station</span>
                <span>Bébés (&lt;2)</span>
                <button type="button" onclick="updatePassenger('infant', -1)">-</button>
                <span id="infantCountDisplay">0</span>
                <button type="button" onclick="updatePassenger('infant', 1)">+</button>
            </div>
        </div>

        <!-- Champs cachés pour envoyer au PHP -->
        <input type="hidden" id="adultCount" name="adulte" value="1">
        <input type="hidden" id="childCount" name="ado" value="0">
        <input type="hidden" id="infantCount" name="bebe" value="0">

        <div class="form-group">
            <label for="cabinClass">Classe</label>
            <select id="cabinClass" name="classe" required>
                <option value="economy">Économique</option>
                <option value="premium">Premium Économique</option>
                <option value="business">Affaires</option>
                <option value="first">Première</option>
            </select>
        </div>

        <button type="submit" class="submit-btn">
            <span class="material-symbols-outlined">confirmation_number</span>
            Confirmer la réservation
        </button>
    </form>
</div>

<script>
    // Initialisation des dates
    document.addEventListener('DOMContentLoaded', function() {
        const now = new Date();
        const later = new Date();
        later.setHours(now.getHours() + 1);

        document.getElementById('departureDatetime').value = formatDateTime(now);
        document.getElementById('returnDatetime').value = formatDateTime(later);

        document.getElementById('flightType').addEventListener('change', function() {
            const returnCol = document.getElementById('returnDatetimeCol');
            returnCol.style.display = this.value === 'aller-simple' ? 'none' : 'block';
        });
    });

    function formatDateTime(date) {
        const y = date.getFullYear();
        const m = String(date.getMonth() + 1).padStart(2, '0');
        const d = String(date.getDate()).padStart(2, '0');
        const h = String(date.getHours()).padStart(2, '0');
        const min = String(date.getMinutes()).padStart(2, '0');
        return ${y}-${m}-${d}T${h}:${min};
    }

    function updatePassenger(type, change) {
        const displayId = ${type}CountDisplay;
        const hiddenId = type === 'adult' ? 'adultCount' :
                         type === 'child' ? 'childCount' : 'infantCount';

        const displayEl = document.getElementById(displayId);
        let count = parseInt(displayEl.textContent);
        count += change;
        if (count < 0 || (type === 'adult' && count === 0)) return;

        displayEl.textContent = count;
        document.getElementById(hiddenId).value = count;
    }
</script>
</body>
</html>
