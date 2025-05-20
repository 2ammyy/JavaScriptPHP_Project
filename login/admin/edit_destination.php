<?php
session_start();
require '../../config/db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

// Vérifier l'ID de la destination
if (!isset($_GET['id'])) {
    header("Location: admin_destinations.php");
    exit;
}

$id = $_GET['id'];
$query = $pdo->prepare("SELECT * FROM destinations WHERE id = ?");
$query->execute([$id]);
$destination = $query->fetch();

if (!$destination) {
    header("Location: admin_destinations.php");
    exit;
}

$error = '';
$success = '';



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = (float)$_POST['price'];
    $currentImage = $destination['image'];
    
    // Validation des données
    if (empty($name) || empty($description) || $price <= 0) {
        $error = "Veuillez remplir tous les champs correctement";
    } else {
        // Gestion de l'upload d'image
        $imageName = $currentImage;
        $deleteCurrentImage = false;
        
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../../uploads/destinations/';
            $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
            $maxSize = 2 * 1024 * 1024; // 2MB
            
            $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($fileInfo, $_FILES['image']['tmp_name']);
            finfo_close($fileInfo);
            
            $fileExtension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
            
            if (!in_array($mimeType, $allowedTypes) || !in_array($fileExtension, $allowedExtensions)) {
                $error = "Type de fichier non autorisé. Seuls JPG, PNG et WebP sont acceptés.";
            } elseif ($_FILES['image']['size'] > $maxSize) {
                $error = "L'image ne doit pas dépasser 2MB.";
            } elseif (exif_imagetype($_FILES['image']['tmp_name']) === false) {
                $error = "Le fichier n'est pas une image valide.";
            } else {
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                // Supprimer l'ancienne image si elle existe
            if (!empty($currentImage) ){
                    $deleteCurrentImage = true;
                }
                
                $imageName = uniqid('dest_') . '.' . $fileExtension;
                $uploadPath = $uploadDir . $imageName;
                
                if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                    resizeImage($uploadPath, 800, 600);
                    
                    // Supprimer l'ancienne image après le succès du nouvel upload
                    if ($deleteCurrentImage && file_exists($uploadDir . $currentImage)) {
                        unlink($uploadDir . $currentImage);
                    }
                } else {
                    $error = "Erreur lors de l'enregistrement de la nouvelle image.";
                }
            }
        }
        
        if (empty($error)) {
            $query = $pdo->prepare("UPDATE destinations SET name = ?, description = ?, price = ?, image = ? WHERE id = ?");
            if ($query->execute([$name, $description, $price, $imageName, $id])) {
                $success = "Destination modifiée avec succès!";
                header("Refresh: 2; url=admin_destinations.php");
            } else {
                $error = "Erreur lors de la modification de la destination";
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
    <title>Modifier une Destination</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
</head>
<body>
    <div class="d-flex">
        
        <div class="sidebar p-3">
            
        </div>

        <!-- Main Content -->
        <div class="flex-grow-1 p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Modifier la Destination</h1>
                <a href="admin_destinations.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Retour
                </a>
            </div>

            <div class="card shadow-sm form-container">
                <div class="card-body">
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger"><?= $error ?></div>
                    <?php endif; ?>
                    
                    <?php if (!empty($success)): ?>
                        <div class="alert alert-success"><?= $success ?></div>
                    <?php else: ?>
                    <form method="POST" enctype="multipart/form-data" id="destinationForm">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nom de la destination</label>
                            <input type="text" class="form-control" id="name" name="name" 
                                   value="<?= htmlspecialchars($destination['name']) ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" 
                                      rows="5" required><?= htmlspecialchars($destination['description']) ?></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="price" class="form-label">Prix (€)</label>
                            <input type="number" class="form-control" id="price" name="price" 
                                   step="0.01" min="0" value="<?= htmlspecialchars($destination['price']) ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="image" class="form-label">Image</label>
                            
                            <?php if (!empty($destination['image'])): ?>
                            <div class="mb-2">
                                <p class="mb-1">Image actuelle:</p>
                                <img src="../../uploads/destinations/<?= htmlspecialchars($destination['image']) ?>" 
                                     class="img-thumbnail" style="max-width: 200px;">
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" id="deleteImage" name="deleteImage">
                                    <label class="form-check-label" for="deleteImage">
                                        Supprimer cette image
                                    </label>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <input type="file" class="form-control" id="image" name="image" 
                                   accept="image/jpeg, image/png, image/webp">
                            <small class="text-muted">Formats acceptés: JPG, PNG, WebP (max 2MB)</small>
                            <img id="imagePreview" class="img-thumbnail preview-image" src="#" alt="Aperçu de la nouvelle image">
                        </div>
                        
                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-save me-2"></i>Enregistrer les modifications
                        </button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Aperçu de la nouvelle image
        document.getElementById('image').addEventListener('change', function(e) {
            const preview = document.getElementById('imagePreview');
            const file = e.target.files[0];
            
            if (file) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    preview.style.display = 'block';
                    preview.src = e.target.result;
                }
                
                reader.readAsDataURL(file);
            } else {
                preview.style.display = 'none';
                preview.src = '#';
            }
        });
        
        // Gestion de la suppression d'image
        document.getElementById('deleteImage')?.addEventListener('change', function(e) {
            if (this.checked) {
                document.getElementById('image').required = true;
            } else {
                document.getElementById('image').required = false;
            }
        });
    </script>
</body>
</html>
