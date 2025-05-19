<?php
session_start(); 
require '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['activity'])) {
    // Sécurisation des entrées
    $activite = $conn->real_escape_string($_POST['activity']);
    $date = $_POST['date'];
    $nom = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $participants = (int)$_POST['participants'];
    $demandes = $conn->real_escape_string($_POST['special_requests']);

    // Calcul du prix unitaire
    $prix_unitaire = 0;
    if ($activite === "Randonnée & Trekking") $prix_unitaire = 500;
    elseif ($activite === "Sports Nautiques") $prix_unitaire = 350;
    elseif ($activite === "Voyage Gastronomique") $prix_unitaire = 450;

    $total = $prix_unitaire * $participants;

    // Insertion SQL
    $sql = "INSERT INTO ReservationActivite 
            (activite, date, nom_complet, email, participants, demandes_speciales, total)
            VALUES ('$activite', '$date', '$nom', '$email', $participants, '$demandes', $total)";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['confirmation'] = [
            'activite' => $activite,
            'date' => $date,
            'nom' => $nom,
            'email' => $email,
            'participants' => $participants,
            'demandes' => $demandes,
            'total' => $total
        ];
        echo "Réservation enregistrée avec succès ✅";
    } else {
        echo "Erreur SQL : " . $conn->error;
    }

    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activités de Voyage</title>
    <!-- Preload -->
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" as="style">
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" as="style">
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" as="font" crossorigin>
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../Acceuil/style.css"> <!-- Lien vers le même CSS que l'accueil -->
    <style>
       

body {
    padding-top: 80px; 
    background: linear-gradient(#f1f3ff, #cbd4ff);
            min-height: 100vh;
}

/* Styles améliorés pour les boutons */
.btn-primary {
    background-color: #2696e9;
    border: none;
    padding: 12px 24px;
    font-weight: 600;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
    border-radius: 8px;
}

.btn-primary:hover {
    background-color: #1a6eb5;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

/* Modal de réservation amélioré */
.modal {
    display: none;
    position: fixed;
    z-index: 1050;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0,0,0,0.5);
    backdrop-filter: blur(5px);
}

.modal-content {
    background-color: #fff;
    margin: 5% auto;
    padding: 0;
    border-radius: 12px;
    box-shadow: 0 5px 30px rgba(0,0,0,0.3);
    width: 90%;
    max-width: 600px;
    overflow: hidden;
    animation: modalopen 0.4s;
}

@keyframes modalopen {
    from { opacity: 0; transform: translateY(-50px); }
    to { opacity: 1; transform: translateY(0); }
}

.modal-header {
    padding: 20px;
    background: linear-gradient(135deg, #2696e9, #1a6eb5);
    color: white;
}

.modal-header h2 {
    margin: 0;
    font-size: 1.8rem;
}

.modal-body {
    padding: 25px;
}

.close-modal {
    color: white;
    float: right;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s;
}

.close-modal:hover {
    transform: rotate(90deg);
    color: #f1f1f1;
}

/* Formulaires améliorés */
.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: #333;
}

.form-control, .form-select {
    width: 100%;
    padding: 12px 15px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 16px;
    transition: all 0.3s;
}

.form-control:focus, .form-select:focus {
    border-color: #2696e9;
    box-shadow: 0 0 0 0.25rem rgba(38, 150, 233, 0.25);
    outline: none;
}

/* Confirmation modal */
#confirmation-modal .modal-content {
    text-align: center;
}

#confirmation-modal .modal-header {
    background: linear-gradient(135deg, #4CAF50, #2E7D32);
}

#confirmation-modal .fa-check-circle {
    color: #4CAF50;
    font-size: 4rem;
    margin-bottom: 1rem;
}

/* Responsive */
@media (max-width: 768px) {
    .modal-content {
        margin: 10% auto;
        width: 95%;
    }
    
    body {
        padding-top: 70px;
    }
}

        header {
            text-align: center;
            padding: 2rem 0;
            background: rgba(255, 255, 255, 0.8);
            margin-bottom: 2rem;
        }
        
        header h1 {
            font-size: 2.5rem;
            color: #151a2e;
            margin-bottom: 1rem;
        }
        
        header p {
            font-size: 1.2rem;
            color: #555;
        }

        section {
            padding: 2rem 0;
            
        }
        
        h2 {
            font-size: 2rem;
            margin-bottom: 1.5rem;
            color: #333;
            text-align: center;
        }

        /* Carousel styles */
        #Carousel-slider {
            margin: 2rem auto;
            max-width: 1200px;
            padding: 0 15px;
        }

        .Carousel-slider {
            position: relative;
            height: 500px;
            overflow: hidden;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .slider-item {
            position: absolute;
            width: 100%;
            height: 100%;
            display: none;
            transition: opacity 0.5s ease;
        }

        .slider-item.active {
            display: block;
            opacity: 1;
        }

        .slider-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .carousel-nav {
            position: absolute;
            top: 50%;
            width: 100%;
            display: flex;
            justify-content: space-between;
            transform: translateY(-50%);
            z-index: 10;
            padding: 0 20px;
        }

        .carousel-btn {
            background: rgba(255, 255, 255, 0.3);
            color: white;
            border: none;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
            backdrop-filter: blur(5px);
        }

        .carousel-btn:hover {
            background: rgba(255, 255, 255, 0.5);
            transform: scale(1.1);
        }

        .indicators {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 10px;
            z-index: 10;
        }

        .indicator {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.5);
            cursor: pointer;
            transition: all 0.3s;
        }

        .indicator.active {
            background-color: white;
            transform: scale(1.2);
        }

        /* Hébergement styles */
        .hebergement-img {
            cursor: pointer;
            transition: transform 0.3s ease;
            border-radius: 8px;
            height: 150px;
            width: 100%;
            object-fit: cover;
            margin-bottom: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }

        .hebergement-img:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        /* Activity cards */
        #categories-activites {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 2rem;
            padding: 0 2rem;
        }

        .activity-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
            width: 300px;
            margin-bottom: 2rem;
        }

        .activity-card:hover {
            transform: translateY(-10px);
        }

        .activity-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .activity-card h3 {
            padding: 1rem 1rem 0;
            color: #151a2e;
        }

        .activity-card p {
            padding: 0 1rem 1rem;
            color: #555;
        }

        .activity-card a {
            display: inline-block;
            padding: 0.5rem 1rem;
            margin: 0 1rem 1rem;
            background: #2696e9;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s;
        }

        .activity-card a:hover {
            background: #1a6eb5;
        }

        /* Activity details */
        #randonnée-details, #plongée-details, #gastronomie-details {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            margin: 2rem auto;
            max-width: 800px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        #randonnée-details ul, #plongée-details ul, #gastronomie-details ul {
            margin: 1rem 0 2rem;
            padding-left: 2rem;
        }

        #randonnée-details li, #plongée-details li, #gastronomie-details li {
            margin-bottom: 0.5rem;
        }

        /* Testimonials */
        #temoignages {
            background: #f8f9fa;
            padding: 3rem 2rem;
        }

        .testimonial {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            margin: 1rem auto;
            max-width: 800px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }

        .testimonial p:first-child {
            font-style: italic;
            margin-bottom: 1rem;
        }

        .testimonial p:last-child {
            font-weight: bold;
            color: #2696e9;
        }

        /* Reservation section */
        #reserver {
            text-align: center;
            padding: 3rem 2rem;
            background: #151a2e;
            color: white;
        }

        #reserver p {
            margin-bottom: 2rem;
            font-size: 1.2rem;
        }

        #reserver button {
            padding: 1rem 2rem;
            background: #2696e9;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1.1rem;
            cursor: pointer;
            transition: background 0.3s;
        }

        #reserver button:hover {
            background: #1a6eb5;
        }

        /* Responsive styles */
        @media (max-width: 992px) {
            .Carousel-slider {
                height: 400px;
            }
        }

        @media (max-width: 768px) {
            header h1 {
                font-size: 2rem;
            }
            
            .hebergement-img {
                height: 120px;
            }
            
            .Carousel-slider {
                height: 300px;
            }
            
            .carousel-btn {
                width: 40px;
                height: 40px;
                font-size: 1.2rem;
            }
            
            .slider-caption {
                font-size: 1rem;
                padding: 10px;
            }
            
            #categories-activites {
                flex-direction: column;
                align-items: center;
            }
        }
        
        @media (max-width: 576px) {
            header h1 {
                font-size: 1.8rem;
            }
            
            .Carousel-slider {
                height: 250px;
            }
            
            .carousel-btn {
                width: 35px;
                height: 35px;
                font-size: 1rem;
            }
            h2{
                width: 200px;
                margin: 0 auto;
            }
        }
    </style>
</head>
<body>
    <main>
        <!-- Header -->
<nav class="navbar navbar-expand-lg fixed-top">
    <div class="container-fluid">
        <div class="d-flex align-items-center">
            <!-- Drapeau Palestine -->
            <div class="palestine-flag" aria-label="Drapeau de la Palestine">
                <svg viewBox="0 0 36 24" width="30" height="20" aria-hidden="true">
                    <rect y="0" width="36" height="8" fill="#000"/>
                    <rect y="8" width="36" height="8" fill="#fff"/>
                    <rect y="16" width="36" height="8" fill="#009736"/>
                    <polygon points="0,0 13,12 0,24" fill="#e4312b"/>
                </svg>
            </div>
            <a class="navbar-brand text-white me-2" href="../Acceuil/Acceuil.php">TRAVEL</a>
        </div>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link text-white" href="../Acceuil/Acceuil.php">Accueil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active text-white" href="./activite.php">Activités</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="../uploads/Destinations/destination.php">Destinations</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="../uploads/Destinations/reservation.php">Vol</a>
                </li>
                
                
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <span class="nav-link text-white">Bienvenue, <?= htmlspecialchars($_SESSION['username']) ?></span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="../login/logout.php">Déconnexion</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="../login/login.php">Connexion</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="../login/register.php">Inscription</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="../login/admin/admin_login.php">Admin</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
        
        <!--Carousel-->
        <section class="recherche-hebergement py-5">
            
            <div class="container text-center">
                <h1>Découvrez Nos Activités de Voyage</h1>
                <p>Des aventures inoubliables pour chaque type de voyageur</p>
                <h2>Découvrez </h2>
                <div class="row g-4 justify-content-center mt-4">
                    <div class="col-6 col-md-4 col-lg-2">
                        <div class="hebergement-item">
                            <img src="image/Snorklingjpeg.jpeg" alt="snorkling" class="img-fluid hebergement-img" data-carousel="0">
                            <h3>snorkling</h3>
                        </div>
                    </div>
                    <div class="col-6 col-md-4 col-lg-2">
                        <div class="hebergement-item">
                            <img src="image/kayak1jpeg.jpeg" alt="Randonnée" class="img-fluid hebergement-img" data-carousel="1">
                            <h3>Kayak</h3>
                        </div>
                    </div>
                    <div class="col-6 col-md-4 col-lg-2">
                        <div class="hebergement-item">
                            <img src="image/Camping1.jpeg" alt="camping" class="img-fluid hebergement-img" data-carousel="2">
                            <h3>camping</h3>
                        </div>
                    </div>
                    <div class="col-6 col-md-4 col-lg-2">
                        <div class="hebergement-item">
                            <img src="image/Parachute1.jpg" alt="Complexe Hôtelier" class="img-fluid hebergement-img" data-carousel="3">
                            <h3>Parachute</h3>
                        </div>
                    </div>
                    <div class="col-6 col-md-4 col-lg-2">
                        <div class="hebergement-item">
                            <img src="image/Monument.webp" alt="Monument" class="img-fluid hebergement-img" data-carousel="0">
                            <h3>Monument</h3>
                        </div>
                    </div>
                    <div class="col-6 col-md-4 col-lg-2">
                        <div class="hebergement-item">
                            <img src="image/Surfing1.jpeg" alt="Spa" class="img-fluid hebergement-img" data-carousel="4">
                            <h3>Surfing</h3>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <div id="Carousel-slider" class="container">
            <div class="Carousel-slider">
                <div class="slider-item active">
                    <img src="image/Snorklingjpeg.jpeg" alt="camping" class="img-fluid">
                    <div class="slider-caption">snorkling</div>
                </div>
                <div class="slider-item">
                    <img src="image/kayak1jpeg.jpeg" alt="Randonnée" class="img-fluid">
                    <div class="slider-caption">Kayak</div>
                </div>
                <div class="slider-item">
                    <img src="image/Camping1.jpeg" alt="Villa" class="img-fluid">
                    <div class="slider-caption">Villa premium</div>
                </div>
                <div class="slider-item">
                    <img src="image/Parachute1.jpg" alt="Complexe Hôtelier" class="img-fluid">
                    <div class="slider-caption">Rafting</div>
                </div>
                <div class="slider-item">
                    <img src="image/Monument.webp" alt="Spa" class="img-fluid">
                    <div class="slider-caption">Monuments</div>
                </div>
                <div class="slider-item">
                    <img src="image/Surfing1.jpeg" alt="Surfing" class="img-fluid">
                    <div class="slider-caption">Surfing</div>
                </div>
                
                <div class="carousel-nav">
                    <button class="carousel-btn prev-btn"><i class="fas fa-chevron-left"></i></button>
                    <button class="carousel-btn next-btn"><i class="fas fa-chevron-right"></i></button>
                </div>
                
                <div class="indicators">
                    <div class="indicator active" data-slide="0"></div>
                    <div class="indicator" data-slide="1"></div>
                    <div class="indicator" data-slide="2"></div>
                    <div class="indicator" data-slide="3"></div>
                    <div class="indicator" data-slide="4"></div>
                    <div class="indicator" data-slide="5"></div>
                    <div class="indicator" data-slide="6"></div>
                </div>
            </div>
        </div>

        <section id="intro-activites">
            <h2>Nos Activités</h2>
            <p>Choisissez parmi nos activités pour une expérience sur mesure. Que vous soyez passionné par la nature, la culture ou l'aventure, nous avons quelque chose pour vous !</p>
        </section>

        <section id="categories-activites">
          
            <div class="activity-card">
                <img src="image/Randonnée.jpg" alt="Randonnée">
                <h3>Randonnée & Trekking</h3>
                <p>Explorez des paysages magnifiques lors de randonnées dans des parcs nationaux.</p>
                <a href="#randonnée-details">En savoir plus</a>
            </div>
            <div class="activity-card">
                <img src="image/sportnautique.jpg" alt="Plongée sous-marine">
                <h3>Sports Nautiques</h3>
                <p>Plongez dans des eaux cristallines et découvrez la beauté sous-marine.</p>
                <a href="#plongée-details">En savoir plus</a>
            </div>
            <div class="activity-card">
                <img src="image/Italy.png" alt="Voyage gastronomique">
                <h3>Voyage Gastronomique</h3>
                <p>Partez à la découverte des saveurs locales à travers des cours de cuisine et des dégustations.</p>
                <a href="#gastronomie-details">En savoir plus</a>
            </div>
        </section>

        <section id="randonnée-details">
            <h2>Randonnée & Trekking</h2>
            <p>Explorez les montagnes et la nature sauvage avec nos circuits de trekking.</p>
            <ul>
                <li>Destination : Montagnes des Alpes</li>
                <li>Durée : 5 jours</li>
                <li>Niveau : Débutant à Expert</li>
                <li>Prix : 500€ par personne</li>
            </ul>
            <button class="reservation-btn" data-activity="Randonnée">Réserver maintenant</button>
        </section>

        <section id="plongée-details" style="display: none;">
            <h2>Sports Nautiques</h2>
            <p>Découvrez le monde sous-marin avec nos activités nautiques.</p>
            <ul>
                <li>Destination : Mer Rouge</li>
                <li>Durée : 3 jours</li>
                <li>Niveau : Débutant à Avancé</li>
                <li>Prix : 350€ par personne</li>
            </ul>
            <button class="reservation-btn" data-activity="Sports Nautiques">Réserver maintenant</button>
        </section>

        <section id="gastronomie-details" style="display: none;">
            <h2>Voyage Gastronomique</h2>
            <p>Découvrez les saveurs locales avec nos expériences culinaires.</p>
            <ul>
                <li>Destination : Toscane, Italie</li>
                <li>Durée : 4 jours</li>
                <li>Activités : Cours de cuisine, dégustations</li>
                <li>Prix : 450€ par personne</li>
            </ul>
            <button class="reservation-btn" data-activity="Voyage Gastronomique">Réserver maintenant</button>
        </section>

        <section id="temoignages">
            <h2>Ce que disent nos voyageurs</h2>
            <div class="testimonial">
                <p>"L'aventure en montagne était incroyable! Les paysages étaient à couper le souffle."</p>
                <p>- Marie, Randonnée dans les Alpes</p>
            </div>
            <div class="testimonial">
                <p>"Une expérience gastronomique unique. Chaque plat que nous avons appris à cuisiner était délicieux!"</p>
                <p>- Julien, Voyage gastronomique au Mexique</p>
            </div>
        </section>

        <section id="reserver">
            <h2>Réservez Votre Aventure</h2>
            <p>Choisissez votre activité et réservez dès aujourd'hui pour une expérience inoubliable.</p>
            <button id="open-reservation-modal">Réserver maintenant</button>
        </section>

        <!-- Reservation Modal -->
<div id="reservation-modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2><i class="fas fa-calendar-check me-2"></i>Réservation</h2>
            <span class="close-modal">&times;</span>
        </div>
        <div class="modal-body">
<form id="reservation-form" method="POST" action="activite.php">
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="activity">Activité:</label>
                        <select id="activity" name="activity" class="form-select" required>
                            <option value="">-- Sélectionnez une activité --</option>
                            <option value="Randonnée & Trekking">Randonnée & Trekking (500€)</option>
                            <option value="Sports Nautiques">Sports Nautiques (350€)</option>
                            <option value="Voyage Gastronomique">Voyage Gastronomique (450€)</option>
                        </select>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="date">Date:</label>
                        <input type="date" id="date" name="date" class="form-control" required>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="name">Nom complet:</label>
                        <input type="text" id="name" name="name" class="form-control" 
                               value="<?= isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : '' ?>" required>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" class="form-control" 
                               value="<?= isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : '' ?>" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="participants">Nombre de participants:</label>
                    <input type="number" id="participants" name="participants" min="1" max="10" 
                           class="form-control" value="1" required>
                </div>
                
                <div class="form-group">
                    <label for="special_requests">Demandes spéciales (optionnel):</label>
                    <textarea id="special_requests" name="special_requests" class="form-control" rows="3"></textarea>
                </div>
                
                <div class="price-summary p-3 mb-4 bg-light rounded">
                    <h5><i class="fas fa-receipt me-2"></i>Récapitulatif</h5>
                    <div class="d-flex justify-content-between">
                        <span>Prix unitaire:</span>
                        <span id="unit-price">0</span>€
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Participants:</span>
                        <span id="participants-count">1</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between fw-bold">
                        <span>Total:</span>
                        <span id="total-price">0</span>€
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary w-100 py-3">
                    <i class="fas fa-check-circle me-2"></i>Confirmer la réservation
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Confirmation Modal -->
<div id="confirmation-modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2><i class="fas fa-check-circle me-2"></i>Réservation confirmée!</h2>
            <span class="close-modal">&times;</span>
        </div>
        <div class="modal-body text-center">
            <i class="fas fa-check-circle fa-5x text-success mb-4"></i>
            <p id="confirmation-message" class="lead"></p>
            
            <div class="reservation-details p-3 mb-4 bg-light rounded text-start">
                <h5 class="mb-3"><i class="fas fa-info-circle me-2"></i>Détails de la réservation</h5>
                <div id="reservation-details-content"></div>
            </div>
            
            <button id="close-confirmation" class="btn btn-success">
                <i class="fas fa-thumbs-up me-2"></i>Parfait, merci!
            </button>
        </div>
    </div>
</div>

        <!-- Footer -->
<footer class="py-4">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-4 mb-md-0">
                <h3>TRAVEL</h3>
                <p>Votre agence de voyage préférée depuis 15 ans.</p>
                <div class="social-links">
                    <a href="#" aria-label="Facebook" style="text-decoration: none;"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" aria-label="Instagram" style="text-decoration: none;"><i class="fab fa-instagram"></i></a>
                    <a href="#" aria-label="What's App" style="text-decoration: none;"><i class="fab fa-whatsapp"></i></a>
                </div>
            </div>
            <div class="col-md-2 mb-4 mb-md-0">
                <h4>Liens</h4>
                <ul class="footer-links">
                    <li><a href="../Acceuil/Acceuil.php" style="text-decoration: none;">Accueil</a></li>
                    <li><a href="./activite.php" style="text-decoration: none;">Activités</a></li>
                    <li><a href="../uploads/Destinations/destination.php" style="text-decoration: none;">Destinations</a></li>
                </ul>
            </div>
            <div class="col-md-3 mb-4 mb-md-0">
                <h4>Contact</h4>
                <ul class="footer-contact">
                    <li><i class="fas fa-map-marker-alt"></i> 123 Rue du Voyage, Ariana</li>
                    <li><i class="fas fa-phone"></i> +216 23 45 67 89</li>
                    <li><i class="fas fa-envelope"></i> contact@travel.com</li>
                </ul>
            </div>
            
        </div>
        <hr class="my-4">
        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start">
                <p class="m-0">&copy; Travel Agency 2024-2025. Tous droits réservés.</p>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <a href="#top" style="text-decoration: none;" class="back-to-top"><i class="fas fa-arrow-up"></i> Retour en haut</a>
            </div>
        </div>
    </div>
</footer>
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" async></script>
<script src="../Acceuil/script.js" defer></script> <!-- Lien vers le même JS que l'accueil -->

    <script>
document.addEventListener("DOMContentLoaded", function() {
    // ==================== CAROUSEL FUNCTIONALITY ====================
    const sliderItems = document.querySelectorAll('.slider-item');
    const indicators = document.querySelectorAll('.indicator');
    const prevBtn = document.querySelector('.prev-btn');
    const nextBtn = document.querySelector('.next-btn');
    const hebergementImgs = document.querySelectorAll('.hebergement-img');
    
    let currentIndex = 0;
    let slideInterval;
    const slideDuration = 5000; // 5 seconds

    // Show specific slide
    function showSlide(index) {
        // Update current index
        currentIndex = index;
        
        // Hide all slides
        sliderItems.forEach(item => {
            item.classList.remove('active');
        });
        
        // Show current slide
        sliderItems[currentIndex].classList.add('active');
        
        // Update indicators
        indicators.forEach(indicator => {
            indicator.classList.remove('active');
        });
        indicators[currentIndex].classList.add('active');
    }

    // Next slide
    function nextSlide() {
        const nextIndex = (currentIndex + 1) % sliderItems.length;
        showSlide(nextIndex);
    }

    // Previous slide
    function prevSlide() {
        const prevIndex = (currentIndex - 1 + sliderItems.length) % sliderItems.length;
        showSlide(prevIndex);
    }

    // Auto-play
    function startAutoPlay() {
        slideInterval = setInterval(nextSlide, slideDuration);
    }

    function pauseAutoPlay() {
        clearInterval(slideInterval);
    }

    // Initialize carousel
    showSlide(0);
    startAutoPlay();

    // Event listeners
    prevBtn.addEventListener('click', () => {
        pauseAutoPlay();
        prevSlide();
        startAutoPlay();
    });

    nextBtn.addEventListener('click', () => {
        pauseAutoPlay();
        nextSlide();
        startAutoPlay();
    });

    // Indicators click
    indicators.forEach(indicator => {
        indicator.addEventListener('click', () => {
            const slideIndex = parseInt(indicator.dataset.slide);
            pauseAutoPlay();
            showSlide(slideIndex);
            startAutoPlay();
        });
    });

    // Pause on hover
    const carousel = document.querySelector('.Carousel-slider');
    carousel.addEventListener('mouseenter', pauseAutoPlay);
    carousel.addEventListener('mouseleave', startAutoPlay);

    // Hébergement images click
    hebergementImgs.forEach(img => {
        img.addEventListener('click', function() {
            const carouselIndex = parseInt(this.getAttribute('data-carousel'));
            pauseAutoPlay();
            showSlide(carouselIndex);
            startAutoPlay();
            
            // Scroll to carousel
            document.getElementById('Carousel-slider').scrollIntoView({
                behavior: 'smooth'
            });
        });
    });

    // ==================== ACTIVITY DETAILS NAVIGATION ====================
    document.querySelectorAll('#categories-activites a').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            
            // Hide all details sections
            document.querySelectorAll('#randonnée-details, #plongée-details, #gastronomie-details').forEach(section => {
                section.style.display = 'none';
            });
            
            // Show target section
            document.querySelector(targetId).style.display = 'block';
            
            // Scroll to target section
            document.querySelector(targetId).scrollIntoView({
                behavior: 'smooth'
            });
        });
    });

    // ==================== RESERVATION MODAL FUNCTIONALITY ====================
    const reservationModal = document.getElementById('reservation-modal');
    const confirmationModal = document.getElementById('confirmation-modal');
    const openModalBtn = document.getElementById('open-reservation-modal');
    const closeModalBtns = document.querySelectorAll('.close-modal');
    const closeConfirmationBtn = document.getElementById('close-confirmation');
    const reservationForm = document.getElementById('reservation-form');
    const reservationButtons = document.querySelectorAll('.reservation-btn');
    const activitySelect = document.getElementById('activity');
    const participantsInput = document.getElementById('participants');
    const unitPriceSpan = document.getElementById('unit-price');
    const participantsCountSpan = document.getElementById('participants-count');
    const totalPriceSpan = document.getElementById('total-price');

    // Calculate total price in real-time
    function calculateTotal() {
        const selectedOption = activitySelect.options[activitySelect.selectedIndex];
        let unitPrice = 0;
        
        // Extract price from selected option
        if (selectedOption) {
            const optionText = selectedOption.text;
            const priceMatch = optionText.match(/\((\d+)€\)/);
            if (priceMatch) {
                unitPrice = parseFloat(priceMatch[1]);
            }
        }
        
        const participants = parseInt(participantsInput.value) || 1;
        const total = unitPrice * participants;
        
        unitPriceSpan.textContent = unitPrice.toFixed(2);
        participantsCountSpan.textContent = participants;
        totalPriceSpan.textContent = total.toFixed(2);
    }

    // Event listeners for price calculation
    activitySelect.addEventListener('change', calculateTotal);
    participantsInput.addEventListener('input', calculateTotal);

    // Initialize price calculation
    calculateTotal();

    // Open modal function
    function openModal() {
        reservationModal.style.display = 'block';
        document.body.style.overflow = 'hidden'; // Prevent scrolling
    }

    // Close modal function
    function closeModal() {
        reservationModal.style.display = 'none';
        confirmationModal.style.display = 'none';
        document.body.style.overflow = 'auto'; // Restore scrolling
    }

    // Open from main reservation button
    openModalBtn.addEventListener('click', openModal);

    // Open from activity reservation buttons
    reservationButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const activity = this.getAttribute('data-activity');
            
            // Find matching option
            const options = Array.from(activitySelect.options);
            const option = options.find(opt => opt.text.includes(activity));
            
            if (option) {
                option.selected = true;
                calculateTotal();
            }
            
            openModal();
        });
    });

    // Close modals
    closeModalBtns.forEach(btn => {
        btn.addEventListener('click', closeModal);
    });

    closeConfirmationBtn.addEventListener('click', closeModal);

    // Close when clicking outside
    window.addEventListener('click', (e) => {
        if (e.target === reservationModal || e.target === confirmationModal) {
            closeModal();
        }
    });

    // Form submission
    reservationForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Get form values
        const formData = new FormData(this);
        const activity = activitySelect.options[activitySelect.selectedIndex].text;
        const name = formData.get('name');
        const email = formData.get('email');
        const date = formData.get('date');
        const participants = formData.get('participants');
        const unitPrice = parseFloat(unitPriceSpan.textContent);
        const totalPrice = parseFloat(totalPriceSpan.textContent);
        
        // Create confirmation message
        const confirmationMessage = `Merci ${name}, votre réservation a bien été enregistrée !`;
        
        const reservationDetails = `
            <p><strong>Activité:</strong> ${activity.split(' (')[0]}</p>
            <p><strong>Date:</strong> ${new Date(date).toLocaleDateString('fr-FR')}</p>
            <p><strong>Participants:</strong> ${participants}</p>
            <p><strong>Prix total:</strong> ${totalPrice.toFixed(2)}€</p>
            ${formData.get('special_requests') ? `<p><strong>Demandes:</strong> ${formData.get('special_requests')}</p>` : ''}
        `;
        
        // Show confirmation
        document.getElementById('confirmation-message').textContent = confirmationMessage;
        document.getElementById('reservation-details-content').innerHTML = reservationDetails;
        
        // In a real application, you would submit the form here
        // For this example, we'll just show the confirmation
        reservationModal.style.display = 'none';
        confirmationModal.style.display = 'block';
        
        // Optional: Reset form
        // this.reset();
        // calculateTotal();
    });

    // Set minimum date to today
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('date').min = today;
});
</script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    <?php if (isset($_SESSION['confirmation'])): ?>
        // Ouvre automatiquement le modal de confirmation
        document.getElementById('confirmation-modal').style.display = 'block';
        const data = <?= json_encode($_SESSION['confirmation']) ?>;
        document.getElementById('confirmation-message').textContent = "Merci " + data.nom + " ! Votre réservation est confirmée.";
        document.getElementById('reservation-details-content').innerHTML = `
            <p><strong>Activité:</strong> ${data.activite}</p>
            <p><strong>Date:</strong> ${data.date}</p>
            <p><strong>Email:</strong> ${data.email}</p>
            <p><strong>Participants:</strong> ${data.participants}</p>
            <p><strong>Demandes spéciales:</strong> ${data.demandes || 'Aucune'}</p>
            <p><strong>Total:</strong> ${data.total} €</p>
        `;
        <?php unset($_SESSION['confirmation']); ?>
    <?php endif; ?>

    // Ferme le modal confirmation
    document.getElementById('close-confirmation').addEventListener('click', function () {
        document.getElementById('confirmation-modal').style.display = 'none';
    });
});
</script>

</body>
</html>
