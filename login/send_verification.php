<?php
require_once 'email.php';

// Récupérer le nom d'utilisateur depuis l'URL
$username = isset($_GET['username']) ? $_GET['username'] : '';

// Connexion à la base de données pour récupérer l'email de l'utilisateur
$servername = "localhost";
$db_username = "root";
$password = "";
$dbname = "agence";

try {
    $bdd = new PDO("mysql:host=$servername;dbname=$dbname", $db_username, $password);
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Sélectionner l'utilisateur par son nom d'utilisateur
    $stmt = $bdd->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);

    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Récupérer l'email et générer un code OTP
        $email = $user['email'];
        $otpCode = rand(100000, 999999);  // Génération d'un code OTP aléatoire

        // Mettre à jour le code OTP dans la base de données
        $updateStmt = $bdd->prepare("UPDATE users SET verification_code = :otpCode WHERE username = :username");
        $updateStmt->execute([
            'otpCode' => $otpCode,
            'username' => $username
        ]);

        // Envoi de l'email de vérification
        sendVerificationEmail($email, $username, $otpCode);


    }
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>

<!-- Message de confirmation -->
<p>Un email de vérification a été envoyé à l'adresse fournie.</p>
