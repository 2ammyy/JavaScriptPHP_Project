<?php
session_start();
$pdo = new PDO("mysql:host=localhost;dbname=agence", "root", "");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$message = "";
$step = "email"; // étapes: email → otp → reset

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Étape 1 : Envoi du code
    if (isset($_POST["send_otp"])) {
        $email = $_POST["email"];

        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->rowCount() > 0) {
            $otp = rand(100000, 999999);
            $_SESSION["reset_email"] = $email;

            $update = $pdo->prepare("UPDATE users SET verification_code = ?, reset_requested_at = NOW() WHERE email = ?");
            $update->execute([$otp, $email]);

            // Tu peux remplacer cette ligne par ton envoi réel via email.php
            // sendVerificationEmail($email, "Utilisateur", $otp);
            $message = "📩 Code envoyé : $otp (simulation)"; // pour tests
            $step = "otp";
        } else {
            $message = "❌ Email introuvable.";
        }

    // Étape 2 : Vérification du code
    } elseif (isset($_POST["verify_otp"])) {
        $otp = $_POST["otp"];
        $email = $_SESSION["reset_email"] ?? "";

        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND verification_code = ?");
        $stmt->execute([$email, $otp]);

        if ($stmt->rowCount() > 0) {
            $step = "reset";
        } else {
            $message = "❌ Code incorrect.";
            $step = "otp";
        }

    // Étape 3 : Réinitialiser le mot de passe
    } elseif (isset($_POST["reset_password"])) {
        $new_password = $_POST["new_password"];
        $email = $_SESSION["reset_email"] ?? "";

        $hashed = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET password = ?, verification_code = NULL WHERE email = ?");
        $stmt->execute([$hashed, $email]);

        session_destroy();
        $message = "✅ Mot de passe réinitialisé. Vous pouvez maintenant vous connecter.";
        $step = "done";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mot de passe oublié</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            display: flex; justify-content: center; align-items: center;
            height: 100vh;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            width: 320px;
        }
        input {
            width: 100%; padding: 10px;
            margin-bottom: 10px;
        }
        button {
            padding: 10px; width: 100%;
            background: #5a5eff; color: white;
            border: none; border-radius: 5px;
            cursor: pointer;
        }
        .message { margin-top: 10px; font-weight: bold; color: #333; }
    </style>
</head>
<body>
<div class="container">
    <h2>🔑 Mot de passe oublié</h2>
    <form method="post">
        <?php if ($step === "email"): ?>
            <input type="email" name="email" placeholder="Votre email" required>
            <button type="submit" name="send_otp">Envoyer le code</button>

        <?php elseif ($step === "otp"): ?>
            <input type="text" name="otp" placeholder="Code reçu par email" required>
            <button type="submit" name="verify_otp">Vérifier</button>

        <?php elseif ($step === "reset"): ?>
            <input type="password" name="new_password" placeholder="Nouveau mot de passe" required>
            <button type="submit" name="reset_password">Réinitialiser</button>

        <?php elseif ($step === "done"): ?>
            <p class="message">✅ Mot de passe réinitialisé !</p>
            <button onclick="window.location.href='login.php'">Se connecter</button>
        <?php endif; ?>
    </form>

    <?php if (!empty($message)): ?>
        <p class="message"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>
</div>
</body>
</html>
