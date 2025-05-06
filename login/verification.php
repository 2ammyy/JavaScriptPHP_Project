<?php
$codeSent = false;
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['send_code'])) {
        $email = $_POST['email'];

        // Générer un code aléatoire
        $otp = rand(100000, 999999);

        // Connexion DB
        try {
            $bdd = new PDO("mysql:host=localhost;dbname=agence", "root", "");
            $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Mettre à jour ou insérer le code dans la base
            $stmt = $bdd->prepare("UPDATE users SET verification_code = :otp WHERE email = :email");
            $stmt->execute(['otp' => $otp, 'email' => $email]);

            if ($stmt->rowCount() > 0) {
                require 'email.php';
                sendVerificationEmail($email, 'Utilisateur', $otp);
                $codeSent = true;
                $message = "📩 Code envoyé à votre email.";
            } else {
                $message = "❌ Email non trouvé dans la base de données.";
            }
        } catch (PDOException $e) {
            $message = "Erreur DB : " . $e->getMessage();
        }

    } elseif (isset($_POST['verify_code'])) {
        $email = $_POST['email'];
        $otp_code = $_POST['otp_code'];

        try {
            $bdd = new PDO("mysql:host=localhost;dbname=agence", "root", "");
            $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $bdd->prepare("SELECT * FROM users WHERE email = :email AND verification_code = :otp");
            $stmt->execute(['email' => $email, 'otp' => $otp_code]);

            if ($stmt->rowCount() > 0) {
                $message = "✅ Vérification réussie !";
            } else {
                $message = "❌ Code invalide.";
                $codeSent = true;
            }
        } catch (PDOException $e) {
            $message = "Erreur DB : " . $e->getMessage();
        }
        if ($stmt->rowCount() > 0) {
          
            header("Location: /JavaScriptPHP_Project/Acceuil/Acceuil.html");
            exit();
        }

    }


}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Vérification Email</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #5a5eff, #9d9dff);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .verification-container {
            background-color: white;
            padding: 40px 30px;
            border-radius: 15px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
            width: 350px;
            text-align: center;
        }

        h2 {
            margin-bottom: 25px;
            color: #5a5eff;
        }

        input[type="email"],
        input[type="text"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            transition: border 0.3s ease;
        }

        input[type="email"]:focus,
        input[type="text"]:focus {
            border-color: #5a5eff;
            outline: none;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #5a5eff;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        button:hover {
            background-color: #3d3dee;
        }

        .message {
            margin-top: 15px;
            font-weight: bold;
            color: #333;
        }
    </style>
</head>
<body>
<div class="verification-container">
    <h2>Vérification de l'email</h2>
    <form method="POST">
        <input type="email" name="email" placeholder="Votre email" required value="<?php echo isset($email) ? htmlspecialchars($email) : '' ?>">

        <?php if ($codeSent): ?>
            <input type="text" name="otp_code" placeholder="Code de vérification" required>
            <button type="submit" name="verify_code">Vérifier</button>
        <?php else: ?>
            <button type="submit" name="send_code">Envoyer le code</button>
        <?php endif; ?>

        <?php if (!empty($message)): ?>
            <div class="message"><?= $message ?></div>

            <?php if ($message === "✅ Vérification réussie !"): ?>
                <button onclick="window.location.href='verifier_code.php'" style="margin-top: 15px; padding: 10px 20px; background-color: #6A5ACD; color: white; border: none; border-radius: 8px; cursor: pointer;">
                        style="margin-top: 15px; padding: 10px 20px; background-color: #6A5ACD; color: white; border: none; border-radius: 8px; cursor: pointer;">
                    Continuer
                </button>
            <?php endif; ?>
        <?php endif; ?>
    </form>
</div>
</body>
</html>
