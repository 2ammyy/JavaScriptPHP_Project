
<?php
require '../../config/db.php';
// Récupérer les réservations
$stmt = $pdo->query("SELECT r.*, u.username FROM reservation r JOIN users u ON r.user_id = u.id ORDER BY r.date_reservation DESC");
$reservations = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord - Réservations</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary: #4361ee;
            --secondary: #3f37c9;
            --success: #4cc9f0;
            --warning: #f8961e;
            --danger: #f94144;
            --light: #f8f9fa;
            --dark: #212529;
            --gray: #6c757d;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f5f7fa;
            color: #333;
            line-height: 1.6;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .header h1 {
            color: var(--primary);
            font-weight: 700;
        }
        
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        }
        
        .stat-card h3 {
            color: var(--gray);
            font-size: 14px;
            margin-bottom: 10px;
            text-transform: uppercase;
        }
        
        .stat-card p {
            font-size: 24px;
            font-weight: 700;
            color: var(--dark);
        }
        
        .table-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        thead {
            background-color: var(--primary);
            color: white;
        }
        
        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }
        
        th {
            font-weight: 500;
            text-transform: uppercase;
            font-size: 14px;
        }
        
        tbody tr:hover {
            background-color: rgba(67, 97, 238, 0.05);
        }
        
        .status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .status-confirmed {
            background-color: #d4edda;
            color: #155724;
        }
        
        .status-cancelled {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            text-decoration: none;
            margin-right: 5px;
        }
        
        .btn i {
            margin-right: 5px;
        }
        
        .btn-traiter {
            background-color: var(--success);
            color: white;
        }
        
        .btn-edit {
            background-color: var(--warning);
            color: white;
        }
        
        .btn-delete {
            background-color: var(--danger);
            color: white;
        }
        
        .btn:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }
        
        .action-buttons {
            display: flex;
            gap: 5px;
        }
        
        .no-reservations {
            text-align: center;
            padding: 40px;
            color: var(--gray);
        }
        
        @media (max-width: 768px) {
            .stats-container {
                grid-template-columns: 1fr;
            }
            
            table {
                display: block;
                overflow-x: auto;
            }
        }
        .btn-dashboard {
    background-color: var(--primary);
    color: white;
    padding: 8px 15px;
    margin-right: 15px;
    text-decoration: none;
    border-radius: 6px;
    font-size: 14px;
    transition: all 0.3s ease;
}

.btn-dashboard:hover {
    background-color: var(--secondary);
    transform: translateY(-2px);
}
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-calendar-alt"></i> Gestion des Réservations</h1>
           
            <div>
                <span class="status status-confirmed">Connecté</span>
            </div>
             <a href="admin_dashboard.php" class="btn btn-dashboard">
            <i class="fas fa-arrow-left"></i> Retour au dashboard
        </a>
            
        </div>
        
        <div class="stats-container">
            <div class="stat-card">
                <h3>Total Réservations</h3>
                <p><?= count($reservations) ?></p>
            </div>
            <div class="stat-card">
                <h3>Aller Simple</h3>
                 <p><?= count(array_filter($reservations, fn($r) => strtolower($r['type_voyage']) === 'aller simple')) ?></p>
            </div>
            <div class="stat-card">
                <h3>Aller-Retour</h3>
                 <p><?= count(array_filter($reservations, fn($r) => strtolower($r['type_voyage']) === 'aller-retour')) ?></p>
            </div>
            <div class="stat-card">
                <h3>En attente</h3>
                <p><p><?= count(array_filter($reservations, fn($r) => strtolower($r['status']) === 'en attente')) ?></p></p>
            </div>
        </div>
        
        <div class="table-container">
            <?php if (empty($reservations)): ?>
                <div class="no-reservations">
                    <i class="fas fa-calendar-times fa-3x" style="margin-bottom: 15px;"></i>
                    <p>Aucune réservation trouvée</p>
                </div>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Utilisateur</th>
                            <th>Type</th>
                            <th>Itinéraire</th>
                            <th>Dates</th>
                            <th>Passagers</th>
                            <th>Classe</th>
                            <th>Date Résa</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reservations as $res): ?>
                            <tr>
                                <td>
                                    <strong><?= htmlspecialchars($res['username']) ?></strong>
                                </td>
                                <td>
                                    <span class="status <?= $res['type_voyage'] === 'Aller simple' ? 'status-pending' : 'status-confirmed' ?>">
                                        <?= htmlspecialchars($res['type_voyage']) ?>
                                    </span>
                                </td>
                                <td>
                                    <div><strong><?= htmlspecialchars($res['depart']) ?></strong></div>
                                    <div><i class="fas fa-arrow-right" style="font-size: 10px; margin: 5px 0;"></i></div>
                                    <div><strong><?= htmlspecialchars($res['destination']) ?></strong></div>
                                </td>
                                <td>
                                    <div><strong>Aller:</strong> <?= htmlspecialchars($res['date_aller']) ?></div>
                                    <?php if ($res['date_retour']): ?>
                                        <div><strong>Retour:</strong> <?= htmlspecialchars($res['date_retour']) ?></div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div><i class="fas fa-user"></i> <?= (int)$res['adulte'] ?> Adultes</div>
                                    <div><i class="fas fa-user-graduate"></i> <?= (int)$res['ado'] ?> Ados</div>
                                    <div><i class="fas fa-baby"></i> <?= (int)$res['bebe'] ?> Bébés</div>
                                </td>
                                <td><?= htmlspecialchars($res['classe']) ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($res['date_reservation'])) ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <form method="POST" action="traiter_reservation.php" style="display:inline;">
                                            <input type="hidden" name="id" value="<?= $res['id'] ?>">
                                            <button type="submit" class="btn btn-traiter" title="Traiter">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        <form method="GET" action="modifier_reservation.php" style="display:inline;">
                                            <input type="hidden" name="id" value="<?= $res['id'] ?>">
                                            <button type="submit" class="btn btn-edit" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </form>
                                        <form method="POST" action="supprimer_reservation.php" style="display:inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette réservation ?');">
                                            <input type="hidden" name="id" value="<?= $res['id'] ?>">
                                            <button type="submit" class="btn btn-delete" title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>