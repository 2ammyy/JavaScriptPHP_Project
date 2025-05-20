
<?php 
session_start();

// Inclusion du fichier de configuration de la base de données
require '../../config/db.php';
// Initialisation des variables
$error = '';
$success = '';
$form_submitted = false;

// Vérification de la connexion de l'utilisateur
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login/login.php");
    exit();
}

// Traitement du formulaire si soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $form_submitted = true;
    
    // Nettoyage et validation des données
    $type_voyage = htmlspecialchars(trim($_POST['type_voyage'] ?? ''));
    $depart = htmlspecialchars(trim($_POST['depart'] ?? ''));
    $destination = htmlspecialchars(trim($_POST['destination'] ?? ''));
    $date_aller = $_POST['date_aller'] ?? '';
    $date_retour = ($type_voyage === 'aller-retour') ? ($_POST['date_retour'] ?? null) : null;
    $adulte = filter_var($_POST['adulte'] ?? 1, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    $ado = filter_var($_POST['ado'] ?? 0, FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]);
    $bebe = filter_var($_POST['bebe'] ?? 0, FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]);
    $classe = htmlspecialchars(trim($_POST['classe'] ?? ''));

    // Validation des données
    if (empty($type_voyage)) {
        $error = "Veuillez sélectionner un type de voyage";
    } elseif (empty($depart) || strlen($depart) > 100) {
        $error = "La ville de départ est invalide";
    } elseif (empty($destination) || strlen($destination) > 100) {
        $error = "La destination est invalide";
    } elseif (empty($date_aller) || strtotime($date_aller) < strtotime('today')) {
        $error = "La date d'aller est invalide";
    } elseif ($type_voyage === 'aller-retour' && (!empty($date_retour) && strtotime($date_retour) < strtotime($date_aller))) {
        $error = "La date de retour doit être après la date d'aller";
    } elseif ($adulte === false) {
        $error = "Nombre d'adultes invalide";
    } elseif ($ado === false) {
        $error = "Nombre d'adolescents invalide";
    } elseif ($bebe === false) {
        $error = "Nombre de bébés invalide";
    } elseif (empty($classe)) {
        $error = "Veuillez sélectionner une classe";
    } else {
        try {
            // Préparation de la requête SQL
            $sql = "INSERT INTO reservation (
                user_id, type_voyage, depart, destination,
                date_aller, date_retour, adulte, ado, bebe, classe,
                date_reservation
            ) VALUES (
                :user_id, :type_voyage, :depart, :destination,
                :date_aller, :date_retour, :adulte, :ado, :bebe, :classe,
                :date_reservation
            )";

            $stmt = $pdo->prepare($sql);

            // Paramètres de la requête
            $params = [
                ':user_id' => $_SESSION['user_id'],
                ':type_voyage' => $type_voyage,
                ':depart' => $depart,
                ':destination' => $destination,
                ':date_aller' => $date_aller,
                ':date_retour' => $date_retour,
                ':adulte' => $adulte,
                ':ado' => $ado,
                ':bebe' => $bebe,
                ':classe' => $classe,
                ':date_reservation' => date('Y-m-d H:i:s')
            ];

            // Exécution de la requête
            $stmt->execute($params);
            
            // Message de succès
            $success = "Réservation enregistrée avec succès!";
            
        } catch (PDOException $e) {
            $error = "Erreur de réservation: Veuillez réessayer";
            error_log("Erreur réservation: " . $e->getMessage());
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réservation de Voyage</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&family=Playfair+Display:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2980b9;
            --accent-color: #e74c3c;
            --light-color: #ecf0f1;
            --dark-color: #2c3e50;
            --success-color: #27ae60;
            --error-color: #e74c3c;
            --border-radius: 8px;
            --box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f5f7fa;
            color: var(--dark-color);
            line-height: 1.6;
            padding: 20px;
        }
        
        .container {
            max-width: 800px;
            margin: 30px auto;
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 25px;
            text-align: center;
            font-family: 'Playfair Display', serif;
        }
        
        .header h1 {
            font-size: 2.2rem;
            margin-bottom: 10px;
        }
        
        .form-container {
            padding: 30px;
        }
        
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: var(--border-radius);
            font-weight: 500;
            display: none; /* Caché par défaut */
        }
        
        .error {
            background-color: #fdecea;
            color: var(--error-color);
            border-left: 4px solid var(--error-color);
        }
        
        .success {
            background-color: #e8f5e9;
            color: var(--success-color);
            border-left: 4px solid var(--success-color);
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-row {
            display: flex;
            gap: 20px;
        }
        
        .form-col {
            flex: 1;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--dark-color);
        }
        
        .required:after {
            content: " *";
            color: var(--accent-color);
        }
        
        input, select {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: var(--border-radius);
            font-size: 16px;
            transition: border 0.3s;
        }
        
        input:focus, select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.2);
        }
        
        input[type="date"] {
            appearance: none;
        }
        
        .passengers {
            display: flex;
            gap: 15px;
            margin-top: 15px;
        }
        
        .passenger-type {
            flex: 1;
            background: var(--light-color);
            padding: 15px;
            border-radius: var(--border-radius);
        }
        
        button {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 14px 25px;
            font-size: 16px;
            border-radius: var(--border-radius);
            cursor: pointer;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: background 0.3s;
            width: 100%;
            margin-top: 10px;
        }
        
        button:hover {
            background: var(--secondary-color);
        }
        
        @media (max-width: 768px) {
            .form-row, .passengers {
                flex-direction: column;
                gap: 15px;
            }
            
            .header h1 {
                font-size: 1.8rem;
            }
            
            .form-container {
                padding: 20px;
            }
        }

        .date-error {
            color: var(--error-color);
            font-size: 0.8rem;
            margin-top: 5px;
            display: none;
        }
    </style>
</head>
<body>
   <div class="container">
        <div class="header">
            <h1>Réservation de Voyage</h1>
            <p>Planifiez votre prochaine aventure en toute simplicité</p>
        </div>
        
        <div class="form-container">
            <?php if ($form_submitted && !empty($error)): ?>
                <div class="alert error"><?= htmlspecialchars($error) ?></div>
            <?php elseif ($form_submitted && !empty($success)): ?>
                <div class="alert success" id="success-message"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <form method="POST" id="reservation-form">
                <div class="form-group">
                    <label for="type_voyage" class="required">Type de voyage</label>
                    <select name="type_voyage" id="type_voyage" required>
                        <option value="">-- Sélectionnez --</option>
                        <option value="aller simple" <?= isset($_POST['type_voyage']) && $_POST['type_voyage'] === 'aller simple' ? 'selected' : '' ?>>Aller simple</option>
                        <option value="aller-retour" <?= isset($_POST['type_voyage']) && $_POST['type_voyage'] === 'aller-retour' ? 'selected' : '' ?>>Aller-retour</option>
                    </select>
                </div>

                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label for="depart" class="required">Ville de départ</label>
                            <input type="text" name="depart" id="depart" value="<?= isset($_POST['depart']) ? htmlspecialchars($_POST['depart']) : '' ?>" required>
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label for="destination" class="required">Destination</label>
                            <input type="text" name="destination" id="destination" value="<?= isset($_POST['destination']) ? htmlspecialchars($_POST['destination']) : '' ?>" required>
                        </div>
                    </div>
                </div>

                 <div class="form-row">
            <div class="form-col">
                <div class="form-group">
                    <label for="date_aller" class="required">Date d'aller</label>
                    <input type="date" name="date_aller" id="date_aller" value="<?= isset($_POST['date_aller']) ? htmlspecialchars($_POST['date_aller']) : '' ?>" required min="<?= date('Y-m-d') ?>">
                </div>
            </div>
            <div class="form-col">
                <div class="form-group" id="retour-group" style="display: none;">
                    <label for="date_retour" class="required">Date de retour</label>
                    <input type="date" name="date_retour" id="date_retour" value="<?= isset($_POST['date_retour']) ? htmlspecialchars($_POST['date_retour']) : '' ?>" min="<?= date('Y-m-d') ?>">
                    <div class="date-error" id="date-retour-error">La date de retour doit être après la date d'aller</div>
                </div>
            </div>
        </div>

                <div class="form-group">
                    <label>Passagers</label>
                    <div class="passengers">
                        <div class="passenger-type">
                            <label for="adulte" class="required">Adultes</label>
                            <input type="number" name="adulte" id="adulte" min="1" value="<?= isset($_POST['adulte']) ? htmlspecialchars($_POST['adulte']) : '1' ?>" required>
                        </div>
                        <div class="passenger-type">
                            <label for="ado">Adolescents</label>
                            <input type="number" name="ado" id="ado" min="0" value="<?= isset($_POST['ado']) ? htmlspecialchars($_POST['ado']) : '0' ?>">
                        </div>
                        <div class="passenger-type">
                            <label for="bebe">Bébés</label>
                            <input type="number" name="bebe" id="bebe" min="0" value="<?= isset($_POST['bebe']) ? htmlspecialchars($_POST['bebe']) : '0' ?>">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="classe" class="required">Classe</label>
                    <select name="classe" id="classe" required>
                        <option value="">-- Sélectionnez --</option>
                        <option value="economique" <?= isset($_POST['classe']) && $_POST['classe'] === 'economique' ? 'selected' : '' ?>>Économique</option>
                        <option value="affaire" <?= isset($_POST['classe']) && $_POST['classe'] === 'affaire' ? 'selected' : '' ?>>Affaire</option>
                        <option value="premiere" <?= isset($_POST['classe']) && $_POST['classe'] === 'premiere' ? 'selected' : '' ?>>Première</option>
                    </select>
                </div>

                <button type="submit">Réserver maintenant</button>
            </form>
        </div>
    </div>

     <script>
         // Validation en temps réel de la date de retour
        document.getElementById('date_retour')?.addEventListener('change', function() {
            const dateAller = document.getElementById('date_aller').value;
            const dateRetour = this.value;
            const errorElement = document.getElementById('date-retour-error');
            
            if (dateAller && dateRetour) {
                if (new Date(dateRetour) <= new Date(dateAller)) {
                    this.setCustomValidity("La date de retour doit être après la date d'aller");
                    errorElement.style.display = 'block';
                    this.style.borderColor = 'var(--error-color)';
                } else {
                    this.setCustomValidity("");
                    errorElement.style.display = 'none';
                    this.style.borderColor = '';
                }
            }
        });

        // Validation aussi lors du changement de date d'aller
        document.getElementById('date_aller')?.addEventListener('change', function() {
            const dateRetourInput = document.getElementById('date_retour');
            if (dateRetourInput && dateRetourInput.value) {
                dateRetourInput.dispatchEvent(new Event('change'));
            }
        });
        // Affiche/masque le champ date de retour selon le type de voyage
        document.getElementById('type_voyage').addEventListener('change', function() {
            const retourGroup = document.getElementById('retour-group');
            if (this.value === 'aller-retour') {
                retourGroup.style.display = 'block';
                document.getElementById('date_retour').setAttribute('required', '');
            } else {
                retourGroup.style.display = 'none';
                document.getElementById('date_retour').removeAttribute('required');
            }
        });

        // Gestion du message de succès
        document.addEventListener('DOMContentLoaded', function() {
            const typeVoyage = document.getElementById('type_voyage');
            if (typeVoyage.value === 'aller-retour') {
                document.getElementById('retour-group').style.display = 'block';
            }
            
            const successMessage = document.getElementById('success-message');
            if (successMessage) {
                // Afficher le message de succès
                successMessage.style.display = 'block';
                
                // Après 2 secondes, masquer le message et réinitialiser le formulaire
                setTimeout(function() {
                    successMessage.style.display = 'none';
                    document.getElementById('reservation-form').reset();
                }, 2000);
            }
        });
    </script>

    <div style="text-align: center; margin-top: 20px;">
    <a href="./destination.php" style="
        display: inline-block;
        padding: 12px 24px;
        background-color: #2980b9;
        color: white;
        text-decoration: none;
        border-radius: 8px;
        font-weight: bold;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        transition: background 0.3s ease;
    " onmouseover="this.style.backgroundColor='#1c5980'" onmouseout="this.style.backgroundColor='#2980b9'">
        Retour à la page principale
    </a>
</div>

</body>
</html>
