<?php
session_start();
require '../../config/db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

function resizeImage($filePath, $maxWidth, $maxHeight) {
    // Vérifier si le fichier existe
    if (!file_exists($filePath)) {
        error_log("Fichier introuvable: " . $filePath);
        return false;
    }

    // Obtenir les informations de l'image
    $imageInfo = getimagesize($filePath);
    if ($imageInfo === false) {
        error_log("Impossible de lire les informations de l'image: " . $filePath);
        return false;
    }

    $originalWidth = $imageInfo[0];
    $originalHeight = $imageInfo[1];
    $mimeType = $imageInfo['mime'];

    // Calculer les nouvelles dimensions en conservant le ratio
    $ratio = $originalWidth / $originalHeight;
    
    if ($maxWidth / $maxHeight > $ratio) {
        $newWidth = $maxHeight * $ratio;
        $newHeight = $maxHeight;
    } else {
        $newWidth = $maxWidth;
        $newHeight = $maxWidth / $ratio;
    }

    // Créer une nouvelle image à partir du fichier original
    switch ($mimeType) {
        case 'image/jpeg':
            $sourceImage = imagecreatefromjpeg($filePath);
            break;
        case 'image/png':
            $sourceImage = imagecreatefrompng($filePath);
            break;
        case 'image/webp':
            $sourceImage = imagecreatefromwebp($filePath);
            break;
        default:
            error_log("Type d'image non supporté: " . $mimeType);
            return false;
    }

    if ($sourceImage === false) {
        error_log("Impossible de créer l'image à partir du fichier: " . $filePath);
        return false;
    }

    // Créer une nouvelle image vide avec les nouvelles dimensions
    $resizedImage = imagecreatetruecolor($newWidth, $newHeight);

    // Conserver la transparence pour les PNG
    if ($mimeType == 'image/png' || $mimeType == 'image/webp') {
        imagealphablending($resizedImage, false);
        imagesavealpha($resizedImage, true);
        $transparent = imagecolorallocatealpha($resizedImage, 255, 255, 255, 127);
        imagefilledrectangle($resizedImage, 0, 0, $newWidth, $newHeight, $transparent);
    }

    // Redimensionner l'image
    imagecopyresampled(
        $resizedImage, $sourceImage,
        0, 0, 0, 0,
        $newWidth, $newHeight,
        $originalWidth, $originalHeight
    );

    // Sauvegarder l'image redimensionnée (écrase le fichier original)
    $result = false;
    switch ($mimeType) {
        case 'image/jpeg':
            $result = imagejpeg($resizedImage, $filePath, 85); // Qualité à 85%
            break;
        case 'image/png':
            $result = imagepng($resizedImage, $filePath, 6); // Compression level 6
            break;
        case 'image/webp':
            $result = imagewebp($resizedImage, $filePath, 85); // Qualité à 85%
            break;
    }

    // Libérer la mémoire
    imagedestroy($sourceImage);
    imagedestroy($resizedImage);

    if (!$result) {
        error_log("Erreur lors de l'enregistrement de l'image redimensionnée: " . $filePath);
        return false;
    }

    return true;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = (float)$_POST['price'];
    
    // 1. Définition du chemin ABSOLU du dossier d'upload
    $baseDir = __DIR__ . '/../../uploads/destinations/';
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
            <!-- Même sidebar que admin_destinations.php -->
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