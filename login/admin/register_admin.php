<?php
session_start();
require '../../config/db.php'; 

// Vérification de l'authentification de l'administrateur

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
        $insert_query = $pdo->prepare("INSERT INTO admins (email, password) VALUES (?, ?)");
        $insert_query->execute([$email, $hashed_password]);
        $success = "L'administrateur a été ajouté avec succès.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription Administrateur</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3a0ca3;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --success-color: #4cc9f0;
            --error-color: #f72585;
            --border-radius: 8px;
            --box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: var(--dark-color);
            line-height: 1.6;
        }

        .container {
            width: 100%;
            max-width: 500px;
            padding: 2rem;
        }

        .card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 2.5rem;
            text-align: center;
        }

        h2 {
            color: var(--secondary-color);
            margin-bottom: 1.5rem;
            font-size: 1.8rem;
            position: relative;
            padding-bottom: 0.5rem;
        }

        h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: var(--primary-color);
            border-radius: 3px;
        }

        .form-group {
            margin-bottom: 1.5rem;
            text-align: left;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--dark-color);
        }

        .input-field {
            position: relative;
        }

        .input-field i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #adb5bd;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 40px;
            border: 2px solid #e9ecef;
            border-radius: var(--border-radius);
            font-size: 1rem;
            transition: var(--transition);
        }

        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
        }

        .btn {
            display: inline-block;
            background: var(--primary-color);
            color: white;
            padding: 0.75rem 2rem;
            border: none;
            border-radius: var(--border-radius);
            cursor: pointer;
            font-size: 1rem;
            font-weight: 500;
            width: 100%;
            transition: var(--transition);
            margin-top: 1rem;
        }

        .btn:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
        }

        .back-link {
            display: inline-block;
            margin-top: 1.5rem;
            color: var(--secondary-color);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
        }

        .back-link:hover {
            color: var(--primary-color);
            text-decoration: underline;
        }

        .alert {
            padding: 0.75rem 1.25rem;
            border-radius: var(--border-radius);
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
        }

        .alert-success {
            background-color: rgba(76, 201, 240, 0.2);
            color: #006d77;
            border-left: 4px solid var(--success-color);
        }

        .alert-error {
            background-color: rgba(247, 37, 133, 0.2);
            color: #9d0208;
            border-left: 4px solid var(--error-color);
        }

        @media (max-width: 576px) {
            .card {
                padding: 1.5rem;
            }

            h2 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <h2><i class="fas fa-user-shield"></i> Inscription Administrateur</h2>

            <!-- Affichage des messages -->
            <?php if ($success): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> <?php echo $success; ?>
                </div>
            <?php endif; ?>
            <?php if ($error): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <!-- Formulaire d'inscription -->
            <form method="POST" action="register_admin.php">
                <div class="form-group">
                    <label for="email">Email</label>
                    <div class="input-field">
                        <i class="fas fa-envelope"></i>
                        <input type="email" id="email" name="email" placeholder="admin@exemple.com" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <div class="input-field">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password" placeholder="••••••••" required>
                    </div>
                </div>

                <button type="submit" class="btn">
                    <i class="fas fa-user-plus"></i> Créer le compte
                </button>
            </form>

            <a href="admin_dashboard.php" class="back-link">
                <i class="fas fa-arrow-left"></i> Retour au tableau de bord
            </a>
        </div>
    </div>
</body>
</html>
