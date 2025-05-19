<?php
session_start();
require '../../config/db.php';

// Vérification de l'authentification admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

// Récupération des destinations
$query = $pdo->query("SELECT * FROM destinations ORDER BY created_at DESC");
$destinations = $query->fetchAll();

// Traitement de la suppression
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    
    // Récupérer l'image associée pour la supprimer
    $imgQuery = $pdo->prepare("SELECT image FROM destinations WHERE id = ?");
    $imgQuery->execute([$id]);
    $image = $imgQuery->fetchColumn();
    
    if ($image && file_exists("../../uploads/destinations/" . $image)) {
        unlink("../../uploads/destinations/" . $image);
    }
    
    // Supprimer la destination
    $deleteQuery = $pdo->prepare("DELETE FROM destinations WHERE id = ?");
    $deleteQuery->execute([$id]);
    header("Location: admin_destinations.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Destinations</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .sidebar {
            width: 250px;
            background-color: #2c3e50;
            min-height: 100vh;
        }
        .sidebar .nav-link {
            color: #bdc3c7;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background-color: rgba(255,255,255,0.1);
            color: white;
        }
        .destination-img {
            width: 100px;
            height: 60px;
            object-fit: cover;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar p-3">
            <h4 class="text-white mb-4">Admin Dashboard</h4>
            <p class="text-muted small">Bienvenue, <?= htmlspecialchars($_SESSION['email']) ?></p>
            <hr class="bg-secondary">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="admin_dashboard.php" class="nav-link">
                        <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="admin_users.php" class="nav-link">
                        <i class="fas fa-users me-2"></i> Utilisateurs
                    </a>
                </li>
                <li class="nav-item">
                    <a href="admin_destinations.php" class="nav-link active">
                        <i class="fas fa-map-marked-alt me-2"></i> Destinations
                    </a>
                </li>
                <li class="nav-item">
                    <a href="reservation.php" class="nav-link">
                        <i class="fas fa-calendar-check me-2"></i> Réservations
                    </a>
                </li>
                <li class="nav-item mt-3">
                    <a href="admin_logout.php" class="nav-link text-danger">
                        <i class="fas fa-sign-out-alt me-2"></i> Déconnexion
                    </a>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="flex-grow-1 p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Gestion des Destinations</h1>
                <a href="add_destination.php" class="btn btn-success">
                    <i class="fas fa-plus me-2"></i>Ajouter une destination
                </a>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Image</th>
                                    <th>Nom</th>
                                    <th>Description</th>
                                    <th>Prix</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($destinations as $destination): ?>
                                <tr>
                                    <td><?= $destination['id'] ?></td>
                                    <td>
                                        <?php if (!empty($destination['image'])): ?>
                                            <img src="../../uploads/destinations/<?= htmlspecialchars($destination['image']) ?>" 
                                                 class="destination-img" 
                                                 alt="<?= htmlspecialchars($destination['name']) ?>">
                                        <?php else: ?>
                                            <span class="text-muted">Aucune image</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= htmlspecialchars($destination['name']) ?></td>
                                    <td><?= substr(htmlspecialchars($destination['description']), 0, 50) ?>...</td>
                                    <td><?= number_format($destination['price'], 2) ?> €</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="edit_destination.php?id=<?= $destination['id'] ?>" 
                                               class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="admin_destinations.php?delete=<?= $destination['id'] ?>" 
                                               class="btn btn-sm btn-danger" 
                                               onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette destination ?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>