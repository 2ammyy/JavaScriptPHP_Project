<?php
$showError = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
$servername = "localhost";
$username = "root";
$password = "";

try {
$bdd = new PDO("mysql:host=$servername;dbname=agence", $username, $password);
$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$email = $_POST['email'];
$plainPassword = $_POST['password'];

if (!empty($email) && !empty($plainPassword)) {
$check = $bdd->prepare("SELECT * FROM users WHERE email = :email");
$check->execute(['email' => $email]);

if ($check->rowCount() == 0) {
$showError = "L'adresse email n'existe pas";
} else {
$user = $check->fetch(PDO::FETCH_ASSOC);
// Vérification du mot de passe
if (password_verify($plainPassword, $user['password'])) {
// Connexion réussie, redirection vers une page protégée (par exemple, dashboard.php)
    header("Location: ../Acceuil/Acceuil.html");
    exit();

} else {
$showError = "Mot de passe incorrect";
}
}
} else {
$showError = "Tous les champs doivent être remplis.";
}
} catch (PDOException $e) {
echo "Erreur : " . $e->getMessage();
}
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Travel Agency</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #e6f2ff;
            background-image: url('https://images.unsplash.com/photo-1507525428034-b723cf961d3e?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        
        .overlay {
            background-color: rgba(0, 0, 0, 0.5);
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }
        
        .airplane-container {
            margin-bottom: 30px;
            width: 200px;
            height: 150px;
            position: relative;
        }
        
        .airplane {
            width: 150px;
            height: 80px;
            position: relative;
            margin: 0 auto;
            animation: float 3s ease-in-out infinite;
        }
        
        .airplane-body {
            width: 120px;
            height: 30px;
            background-color: #ff7e47;
            border-radius: 30px;
            position: relative;
        }
        
        .airplane-wing {
            width: 80px;
            height: 15px;
            background-color: #ff9e6d;
            position: absolute;
            top: 5px;
            left: 20px;
            transform: rotate(-3deg);
            border-radius: 5px;
        }
        
        .airplane-tail {
            width: 20px;
            height: 30px;
            background-color: #ff6b2c;
            position: absolute;
            top: -15px;
            left: 100px;
            transform: rotate(10deg);
            border-radius: 5px;
        }
        
        .airplane-window {
            width: 12px;
            height: 12px;
            background-color: #aad4ff;
            border-radius: 50%;
            position: absolute;
            top: 9px;
            border: 2px solid white;
        }
        
        .airplane-window:nth-child(1) { left: 20px; }
        .airplane-window:nth-child(2) { left: 40px; }
        .airplane-window:nth-child(3) { left: 60px; }
        .airplane-window:nth-child(4) { left: 80px; }
        
        .airplane-propeller {
            width: 30px;
            height: 5px;
            background-color: #333;
            position: absolute;
            top: 12px;
            left: -15px;
            border-radius: 5px;
            animation: spin 0.2s linear infinite;
        }
        
        .login-container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            width: 350px;
            background-color: rgba(255, 255, 255, 0.9);
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .cloud {
            position: absolute;
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 50%;
        }
        
        .agency-text {
            color: #ff7e47;
            font-weight: bold;
            font-size: 18px;
            margin-top: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="overlay"></div>
    
    <div class="airplane-container">
        <div class="airplane">
            <div class="airplane-body">
                <div class="airplane-wing"></div>
                <div class="airplane-tail"></div>
                <div class="airplane-window"></div>
                <div class="airplane-window"></div>
                <div class="airplane-window"></div>
                <div class="airplane-window"></div>
                <div class="airplane-propeller"></div>
            </div>
        </div>
        <div class="agency-text">Travel Agency</div>
    </div>
    
    <div class="container" style="display:flex;justify-content:center;" >
        <div class="login-container">
            <h2 class="form-title">Connexion</h2>
            
            <?php if ($showError): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($showError) ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Mot de passe</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Se connecter</button>
            </form>
            
            <div class="mt-3 text-center">
                <p>Pas encore de compte ? <a href="../login/Register.php">S'inscrire</a></p>
            </div>
            © 2025 Travel Agency|All rights reserved
        </div>
    </div>

    <script>
        const passwordInput = document.getElementById('password');
        const airplane = document.querySelector('.airplane');
        const airplaneBody = document.querySelector('.airplane-body');
        const propeller = document.querySelector('.airplane-propeller');
        
        // Animation de l'avion lors de la saisie
        passwordInput.addEventListener('focus', () => {
            airplane.style.animation = 'float 1s ease-in-out infinite';
            propeller.style.animation = 'spin 0.1s linear infinite';
        });
        
        passwordInput.addEventListener('blur', () => {
            if (passwordInput.value.length === 0) {
                airplane.style.animation = 'float 3s ease-in-out infinite';
                propeller.style.animation = 'spin 0.2s linear infinite';
            }
        });
        
        passwordInput.addEventListener('input', (e) => {
            const hasText = e.target.value.length > 0;
            
            if (hasText) {
                airplane.style.animation = 'float 0.5s ease-in-out infinite';
                propeller.style.animation = 'spin 0.05s linear infinite';
            } else {
                airplane.style.animation = 'float 3s ease-in-out infinite';
                propeller.style.animation = 'spin 0.2s linear infinite';
            }
        });
        
        // Nuages dynamiques
        function createClouds() {
            const airplaneContainer = document.querySelector('.airplane-container');
            
            for (let i = 0; i < 5; i++) {
                const cloud = document.createElement('div');
                cloud.className = 'cloud';
                
                const size = Math.random() * 30 + 20;
                const posX = Math.random() * 200;
                const posY = Math.random() * 100;
                const opacity = Math.random() * 0.5 + 0.3;
                
                cloud.style.width = `${size}px`;
                cloud.style.height = `${size/1.5}px`;
                cloud.style.left = `${posX}px`;
                cloud.style.top = `${posY}px`;
                cloud.style.opacity = opacity;
                
                airplaneContainer.appendChild(cloud);
            }
        }
        
        createClouds();
    </script>
</body>
</html>
