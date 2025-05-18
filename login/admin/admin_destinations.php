<?php
session_start();
require '../../config/db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

function resizeImage($filePath, $maxWidth, $maxHeight) {
    // ... (conservez votre fonction existante)
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = (float)$_POST['price'];
    
    // 1. Définition du chemin ABSOLU du dossier d'upload
    $baseDir = __DIR__ . '/../../uploads/Destinations/';
    $uploadDir = str_replace('/', DIRECTORY_SEPARATOR, $baseDir);
    
    // 2. Vérification/Création du dossier
    if (!file_exists($uploadDir)) {
        if (!mkdir($uploadDir, 0755, true)) {
            $error = "Impossible de créer le dossier de destination: " . $uploadDir;
            error_log($error);
        }
    }
    
    // 3. Vérification des permissions
    if (!is_writable($uploadDir)) {
        $error = "Le dossier n'est pas accessible en écriture: " . $uploadDir;
        error_log($error);
    }
    
    if (empty($error)) {
        // Gestion de l'upload d'image
        $imageName = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
            $maxSize = 2 * 1024 * 1024; // 2MB
            
            // Vérification du type MIME
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $_FILES['image']['tmp_name']);
            finfo_close($finfo);
            
            // Vérification de l'extension
            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $allowedExt = ['jpg', 'jpeg', 'png', 'webp'];
            
            if (!in_array($mimeType, $allowedTypes) || !in_array($ext, $allowedExt)) {
                $error = "Seuls les fichiers JPG, PNG et WebP sont autorisés";
            } elseif ($_FILES['image']['size'] > $maxSize) {
                $error = "Le fichier ne doit pas dépasser 2MB";
            } else {
                // Génération d'un nom de fichier unique
                $imageName = uniqid('dest_') . '.' . $ext;
                $uploadPath = $uploadDir . $imageName;
                
                // Déplacement du fichier
                if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                    // Redimensionnement si nécessaire
                    resizeImage($uploadPath, 800, 600);
                } else {
                    $error = "Erreur lors de l'enregistrement du fichier";
                    error_log("Erreur move_uploaded_file: " . print_r(error_get_last(), true));
                }
            }
        }
        
        // Insertion en base de données
        if (empty($error)) {
            $query = $pdo->prepare("INSERT INTO destinations (name, description, price, image) VALUES (?, ?, ?, ?)");
            if ($query->execute([$name, $description, $price, $imageName])) {
                $success = "Destination ajoutée avec succès!";
                header("Refresh: 2; url=admin_destinations.php");
            } else {
                $error = "Erreur lors de l'ajout en base de données";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une Destination</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Utilisez le même style que admin_destinations.php */
        .form-container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
        }
        
        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .form-group textarea {
            min-height: 100px;
        }
        
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }
        
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="container">
         <aside class="sidebar">
            <div class="sidebar-header">
                <h3>Admin Dashboard</h3>
                <p>Bienvenue, <?php echo htmlspecialchars($_SESSION['email']); ?></p>
            </div>
            
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="#" class="nav-link active">
                        <i class="fas fa-tachometer-alt"></i> Tableau de bord
                    </a>
                </li>
                <li class="nav-item">
                    <a href="admin_users.php" class="nav-link">
                        <i class="fas fa-users"></i> Gestion des utilisateurs
                    </a>
                </li>
                <li class="nav-item">
                    <a href="register_admin.php" class="nav-link">
                        <i class="fas fa-user-plus"></i> Nouvel administrateur
                    </a>
                </li>
            </ul>
        </aside>
        
        <main class="main-content">
            <div class="header">
                <h1>Ajouter une Destination</h1>
                <a href="admin_destinations.php" class="btn btn-primary">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
            </div>
            
            <div class="form-container">
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>
                
                <?php if (!empty($success)): ?>
                    <div class="alert alert-success"><?= $success ?></div>
                <?php else: ?>
                <form method="POST" action="" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="name">Nom de la destination</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" required></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="price">Prix (€)</label>
                        <input type="number" id="price" name="price" step="0.01" min="0" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="image">Image</label>
                        <input type="file" id="image" name="image" accept="image/*">
                    </div>
                    
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Enregistrer
                    </button>
                </form>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
</html>