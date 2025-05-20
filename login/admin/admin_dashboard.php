<?php
session_start();
require '../../config/db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

// Récupération des statistiques
try {
    $totalUsers = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
} catch (PDOException $e) {
    $totalUsers = 0;
}

try {
    $totalDestinations = $pdo->query("SELECT COUNT(*) FROM destinations")->fetchColumn();
} catch (PDOException $e) {
    error_log("Erreur destinations: " . $e->getMessage());
    $totalDestinations = 0; 
}

try {
    $totalReservations = $pdo->query("SELECT COUNT(*) FROM reservation")->fetchColumn();
} catch (PDOException $e) {
    error_log("Table reservations non trouvée: " . $e->getMessage());
    $totalReservations = 0;
}

// Données pour le graphique (dernières réservations par jour)
$reservationsByDay = [];
try {
    $query = $pdo->query("
        SELECT DATE(date_reservation) AS day, 
               COUNT(*) AS count 
        FROM reservation 
        WHERE date_reservation >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
        GROUP BY day 
        ORDER BY day
    ");
    $reservationsByDay = $query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Erreur récupération réservations par jour: " . $e->getMessage());
}

// Préparation des données pour Chart.js
$days = [];
$reservationsData = [];
foreach ($reservationsByDay as $item) {
    $days[] = date("d M", strtotime($item['day']));
    $reservationsData[] = $item['count'];
}

// Calcul du maximum des données pour le graphique
$maxReservations = !empty($reservationsData) ? max($reservationsData) : 0;
$suggestedMax = $maxReservations + 2; 
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2c3e50;
            --accent-color: #e74c3c;
            --light-color: #ecf0f1;
            --dark-color: #34495e;
            --success-color: #2ecc71;
            --chart-color-1: #3498db;
            --chart-color-2: #2ecc71;
            --chart-color-3: #e74c3c;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f5f5f5;
            color: #333;
        }
        
        .container {
            display: flex;
            min-height: 100vh;
        }
        
        .sidebar {
            width: 250px;
            background-color: var(--secondary-color);
            color: white;
            padding: 20px 0;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }
        
        .sidebar-header {
            padding: 0 20px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar-header h3 {
            color: var(--light-color);
            margin-bottom: 5px;
        }
        
        .sidebar-header p {
            color: #bdc3c7;
            font-size: 0.9em;
        }
        
        .nav-menu {
            margin-top: 20px;
        }
        
        .nav-item {
            list-style: none;
        }
        
        .nav-link {
            display: block;
            padding: 12px 20px;
            color: #bdc3c7;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .nav-link:hover, .nav-link.active {
            background-color: rgba(255,255,255,0.1);
            color: white;
            border-left: 4px solid var(--primary-color);
        }
        
        .nav-link i {
            margin-right: 10px;
        }
        
        .main-content {
            flex: 1;
            padding: 30px;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #ddd;
        }
        
        .header h1 {
            color: var(--secondary-color);
        }
        
        .logout-btn {
            background-color: var(--accent-color);
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            font-size: 0.9em;
            transition: background-color 0.3s;
        }
        
        .logout-btn:hover {
            background-color: #c0392b;
        }
        
        .dashboard-cards {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .card {
            background-color: white;
            border-radius: 8px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .card h3 {
            color: var(--secondary-color);
            margin-bottom: 15px;
            font-size: 1.2em;
            display: flex;
            align-items: center;
        }
        
        .card h3 i {
            margin-right: 10px;
        }
        
        .card-value {
            font-size: 2.2em;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .card-btn {
            display: inline-block;
            background-color: var(--primary-color);
            color: white;
            padding: 8px 15px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 0.9em;
            transition: background-color 0.3s;
            margin-top: 15px;
        }
        
        .card-btn:hover {
            background-color: #2980b9;
        }
        
        .chart-container {
            background-color: white;
            border-radius: 8px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-top: 30px;
        }
        
        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .chart-header h3 {
            color: var(--secondary-color);
            font-size: 1.2em;
        }
        
        .chart-wrapper {
            position: relative;
            height: 400px;
            width: 100%;
        }
        
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }
            
            .sidebar {
                width: 100%;
                height: auto;
            }
            
            .dashboard-cards {
                grid-template-columns: 1fr;
            }
            
            .chart-wrapper {
                height: 300px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Sidebar Navigation -->
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
        
        <!-- Main Content -->
        <main class="main-content">
            <div class="header">
                <h1>Tableau de bord</h1>
                <a href="admin_logout.php" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Se déconnecter
                </a>
            </div>
            
            <div class="dashboard-cards">
                <div class="card">
                    <h3><i class="fas fa-users"></i> Utilisateurs</h3>
                    <div class="card-value"><?= $totalUsers ?></div>
                    <a href="admin_users.php" class="card-btn">Gérer les utilisateurs</a>
                </div>
                
                <div class="card">
                    <h3><i class="fas fa-map-marked-alt"></i> Destinations</h3>
                    <div class="card-value"><?= $totalDestinations ?></div>
                    <a href="admin_destinations.php" class="card-btn">Gérer les destinations</a>
                </div>
                
                <div class="card">
                    <h3><i class="fas fa-calendar-check"></i> Réservations</h3>
                    <div class="card-value"><?= $totalReservations ?></div>
                    <a href="reservation.php" class="card-btn">Gérer les réservations</a>
                </div>
            </div>
            
            <!-- Graphique des réservations en courbe -->
            <div class="chart-container">
                <div class="chart-header">
                    <h3><i class="fas fa-chart-line"></i> Évolution des réservations (30 derniers jours)</h3>
                </div>
                <div class="chart-wrapper">
                    <canvas id="reservationsChart"></canvas>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Configuration du graphique en courbe par jour
        const ctx = document.getElementById('reservationsChart').getContext('2d');
        const reservationsChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?= json_encode($days) ?>,
                datasets: [{
                    label: 'Nombre de réservations',
                    data: <?= json_encode($reservationsData) ?>,
                    backgroundColor: 'rgba(52, 152, 219, 0.2)',
                    borderColor: 'rgba(52, 152, 219, 1)',
                    borderWidth: 2,
                    tension: 0.4,
                    pointBackgroundColor: 'rgba(52, 152, 219, 1)',
                    pointRadius: 3,
                    pointHoverRadius: 5,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        },
                        suggestedMax: <?= $suggestedMax ?>,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            padding: 20
                        }
                    },
                    tooltip: {
                        callbacks: {
                            title: function(context) {
                                return 'Jour: ' + context[0].label;
                            },
                            label: function(context) {
                                return context.parsed.y + ' réservation(s)';
                            }
                        },
                        displayColors: false
                    }
                },
                elements: {
                    line: {
                        cubicInterpolationMode: 'monotone'
                    }
                }
            }
        });
    </script>
</body>
</html>
