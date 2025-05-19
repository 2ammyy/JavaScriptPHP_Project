<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Découvrez nos destinations populaires et offres spéciales pour votre prochain voyage">
    <title>Destinations - Travel Agency</title>
    <!-- Preload -->
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" as="style">
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" as="style">
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" as="font" crossorigin>
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="./destination.css">
</head>
<body>
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
                <a class="navbar-brand text-white me-2" href="../../Acceuil/Acceuil.php">TRAVEL</a>
            </div>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                      <a class="nav-link text-white" href="../../Acceuil/Acceuil.php" aria-current="page">Accueil</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link text-white" href="../../Offres/activite.php">Activités</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link active text-white" href="./destination.php">Destinations</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link  text-white" href="./reservation.php">Vol</a>
                    </li>
                    
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item">
                            <span class="nav-link text-white">Bienvenue, <?= htmlspecialchars($_SESSION['username'] ?? '') ?></span>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="../../login/logout.php">Déconnexion</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="../../login/login.php">Connexion</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="../../login/register.php">Inscription</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container mt-5">
        <section id="destinations">
            <!-- Section Filtres -->
            <section id="filters" class="mb-5 fade-in">
                <h2 class="text-center mb-4">Trouvez Votre Destination Parfaite</h2>
                <form id="filter-form" class="row g-3 justify-content-center">
                    <div class="col-md-4 col-lg-3">
                        <label for="region" class="form-label">Région :</label>
                        <select id="region" name="region" class="form-select">
                            <option value="all">Toutes les régions</option>
                            <option value="europe">Europe</option>
                            <option value="asie">Asie</option>
                            <option value="afrique">Afrique</option>
                            <option value="amerique">Amériques</option>
                            <option value="oceanie">Océanie</option>
                        </select>
                    </div>
                    
                    <div class="col-md-4 col-lg-3">
                        <label for="type" class="form-label">Type de voyage :</label>
                        <select id="type" name="type" class="form-select">
                            <option value="all">Tous types</option>
                            <option value="plage">Plage</option>
                            <option value="montagne">Montagne</option>
                            <option value="culture">Culture</option>
                            <option value="aventure">Aventure</option>
                            <option value="romantique">Romantique</option>
                        </select>
                    </div>
                    
                    <div class="col-md-4 col-lg-3">
                        <label for="budget" class="form-label">Budget :</label>
                        <select id="budget" name="budget" class="form-select">
                            <option value="all">Tous budgets</option>
                            <option value="low">Économique (moins de 1000dt)</option>
                            <option value="mid">Confort (1000dt - 5000dt)</option>
                            <option value="high">Luxe (plus de 5000dt)</option>
                        </select>
                    </div>
                    
                    <div class="col-md-4 col-lg-2 d-flex align-items-end">
                        <button type="button" id="apply-filters" class="btn btn-primary w-100">
                            <i class="fas fa-filter me-2"></i>Filtrer
                        </button>
                    </div>
                </form>
            </section>
            
            <!-- Section Vidéo Promotionnelle -->
            <section id="video-section" class="fade-in">
                <div class="video-container">
                    <video id="promo-video" autoplay muted loop playsinline>
                        <source src="./1.mp4" type="video/mp4">
                        Votre navigateur ne prend pas en charge la balise vidéo.
                    </video>
                    <div class="video-overlay">
                        <h2>Explorez le Monde</h2>
                        <p>Des expériences uniques vous attendent</p>
                    </div>
                </div>
            </section>
            
            <!-- Section Destinations Populaires -->
            <section id="popular-destinations" class="fade-in">
                <h2 class="section-title">Destinations Populaires</h2>
                <div class="row g-4" id="destinations-grid">
                    <!-- Les cartes seront générées par JavaScript -->
                </div>
            </section>

            <!-- Section Offres Spéciales -->
            <section id="special-offers" class="fade-in">
                <h2 class="section-title">Offres Spéciales</h2>
                <div class="row g-4" id="offers-grid">
                </div>
            </section>

            <!-- Section Offre Londres -->
<section id="london-offer" class="offer-section py-5">
    <div class="container mt-4">
        <h2 class="text-start">
            Hilton London Angel Islington 
            <span class="hotel-rating" aria-label="4 étoiles">
                <i class="fas fa-star star"></i>
                <i class="fas fa-star star"></i>
                <i class="fas fa-star star"></i>
                <i class="fas fa-star star"></i>
                <i class="far fa-star star"></i>
            </span>
        </h2>
        
        <div class="slider-container">
            <div class="slider">
                <div class="slide active">
                    <img src="image/l1.webp" alt="Hilton London Room">
                </div>
                <div class="slide">
                    <img src="image/l2.webp" alt="Hilton London Lobby">
                </div>
                <div class="slide">
                    <img src="image/l3.webp" alt="Hilton London Restaurant">
                </div>
                <div class="slide">
                    <img src="image/l4.webp" alt="Hilton London Bathroom">
                </div>
                <div class="slide">
                    <img src="image/l5.webp" alt="Hilton London View">
                </div>
                <div class="slide">
                    <img src="image/l6.webp" alt="Hilton London Facilities">
                </div>
                <div class="slide">
                    <img src="image/l7.webp" alt="Hilton London Exterior">
                </div>
            </div>
            
           <!-- Contrôles spécifiques à Paris -->
            <button class="slider-btn prev"><i class="fas fa-chevron-left"></i></button>
            <button class="slider-btn next"><i class="fas fa-chevron-right"></i></button>
            
            <div class="slider-nav">
                <div class="slider-indicator active"></div>
                <div class="slider-indicator"></div>
                <div class="slider-indicator"></div>
                <div class="slider-indicator"></div>
                <div class="slider-indicator"></div>
                <div class="slider-indicator"></div>
                <div class="slider-indicator"></div>
            </div>
        </div>

        <div class="hotel-description">
            <p>
                Chambre agréable, literie confortable. Excellent petit-déjeuner varié salé et sucré. Il ne manquait rien. 
                Très bonne situation à proximité de nombreux transports en commun. 
                Quartier agréable avec de nombreux restaurants et commerces. Très bon accueil.
            </p>
        </div>
    </div>
</section>

<!-- Section Offre Rome -->
<section id="rome-offer" class="offer-section py-5 bg-light">
    <div class="container mt-4">
        <h2 class="text-start">
            Best Western Hotel Le Montparnasse 
            <span class="hotel-rating" aria-label="4 étoiles">
                <i class="fas fa-star star"></i>
                <i class="fas fa-star star"></i>
                <i class="fas fa-star star"></i>
                <i class="fas fa-star star"></i>
                <i class="far fa-star star"></i>
            </span>
        </h2>
        
        <div class="slider-container">
            <div class="slider">
                <div class="slide active">
                    <img src="image/r1.jpg" alt="Chambre de l'hôtel">
                </div>
                <div class="slide">
                    <img src="image/r2.jpg" alt="Salle de bain de l'hôtel">
                </div>
                <div class="slide">
                    <img src="image/r3.jpg" alt="Restaurant de l'hôtel">
                </div>
                <div class="slide">
                    <img src="image/r4.jpg" alt="Vue depuis l'hôtel">
                </div>
            </div>
            
            <!-- Contrôles spécifiques à Paris -->
            <button class="slider-btn prev"><i class="fas fa-chevron-left"></i></button>
            <button class="slider-btn next"><i class="fas fa-chevron-right"></i></button>
            
            <div class="slider-nav">
                <div class="slider-indicator active"></div>
                <div class="slider-indicator"></div>
                <div class="slider-indicator"></div>
                <div class="slider-indicator"></div>
            </div>
        </div>

        <div class="hotel-description">
            <p>
                Idéalement situé dans le quartier Spagna de Rome, le Ripetta Luxury Del Corso se trouve à 100 mètres de la Piazza del Popolo, à 750 mètres de la Via Condotti et à 400 mètres de la Villa Borghese. Il se trouve à environ 1,4 km de la fontaine de Trevi, à 750 mètres de la place d'Espagne et à 700 mètres de la place d'Espagne. L'établissement assure des services d'étage et de concierge.
            </p>
            <p>
                Les chambres sont équipées de la climatisation, d'une télévision par satellite à écran plat, d'une bouilloire, d'un bidet, d'un sèche-cheveux et d'un bureau. Leur salle de bains privative est pourvue d'une douche et d'articles de toilette gratuits. Toutes les chambres du Ripetta Luxury Del Corso comprennent un coin salon.
            </p>
            <p>
                Un petit-déjeuner continental est servi tous les matins sur place.
            </p>
            <p>
                La Via Margutta se trouve à 350 mètres du Ripetta Luxury Del Corso. L'aéroport de Rome-Ciampino, le plus proche, est implanté à 17,6 km.
            </p>
            <p>
                Les couples apprécient particulièrement l'emplacement de cet établissement. Ils lui donnent la note de 9,8 pour un séjour à deux.
            </p>
        </div>
    </div>
</section>

<!-- Section Offre Paris -->
<section id="paris-offer" class="offer-section py-5">
    <div class="container mt-4">
        <h2 class="text-start">
            Ripetta Luxury Del Corso 
            <span class="hotel-rating" aria-label="5 étoiles">
                <i class="fas fa-star star"></i>
                <i class="fas fa-star star"></i>
                <i class="fas fa-star star"></i>
                <i class="fas fa-star star"></i>
                <i class="fas fa-star star"></i>
            </span>
        </h2>
        
        <div class="slider-container">
            <div class="slider">
                <div class="slide active">
                    <img src="image/p1.jpg" alt="Chambre luxueuse">
                </div>
                <div class="slide">
                    <img src="image/p2.jpg" alt="Salle de bain haut de gamme">
                </div>
                <div class="slide">
                    <img src="image/p3.webp" alt="Vue sur Paris">
                </div>
                <div class="slide">
                    <img src="image/p4.webp" alt="Restaurant gastronomique">
                </div>
                <div class="slide">
                    <img src="image/p6.jpg" alt="Piscine intérieure">
                </div>
                <div class="slide">
                    <img src="image/p8.webp" alt="Spa de luxe">
                </div>
            </div>
            
           <!-- Contrôles spécifiques à Paris -->
            <button class="slider-btn prev"><i class="fas fa-chevron-left"></i></button>
            <button class="slider-btn next"><i class="fas fa-chevron-right"></i></button>
            
            <div class="slider-nav">
                <div class="slider-indicator active"></div>
                <div class="slider-indicator"></div>
                <div class="slider-indicator"></div>
                <div class="slider-indicator"></div>
                <div class="slider-indicator"></div>
                <div class="slider-indicator"></div>
            </div>
        </div>

        <div class="hotel-description">
            <p>
                Le Best Western Hotel Le Montparnasse vous accueille à Paris, à 3 minutes à pied de la gare SNCF/station de métro Montparnasse et à 10 minutes de marche du jardin du Luxembourg.
            </p>
            <p>
                Toutes les chambres de l'hôtel sont dotées de la climatisation individuelle et d'une grande télévision par satellite à écran plat. Une connexion Wi-Fi est disponible gratuitement dans tout l'établissement.
            </p>
            <p>
                Vous séjournerez à 20 minutes à pied de Saint-Germain-des-Prés, du Quartier latin et de la célèbre cathédrale Notre-Dame. Le Best Western Hotel Le Montparnasse se trouve à proximité du musée du Louvre.
            </p>
            <p>
                Les couples apprécient particulièrement l'emplacement de cet établissement. Ils lui donnent la note de 9,1 pour un séjour à deux.
            </p>
        </div>
    </div>
</section>

            <!-- Section Témoignages améliorée -->
            <section id="testimonials" class="fade-in">
                <h2 class="section-title">Ce Que Nos Clients Disent</h2>
                <div class="testimonials-container">
                    <div class="testimonials-slider">
                        <!-- Les témoignages seront générés dynamiquement par JavaScript -->
                    </div>
                </div>
            </section>

            <!-- Nouvelle Section Réservation -->
            <section id="reservation" class="fade-in py-5 bg-light">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-8 text-center">
                            <h2 class="section-title">Prêt à réserver votre voyage ?</h2>
                            <p class="mb-5">Remplissez notre formulaire de réservation et notre équipe vous contactera dans les plus brefs délais.</p>
                            <a href="./reservation.php" class="btn btn-primary btn-lg">Faire une réservation</a>                        </div>
                    </div>
                </div>
            </section>
        </section>
    </main>

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
                        <li><a href="../Offres/activite.php" style="text-decoration: none;">Activités</a></li>
                        <li><a href="../Destinations/destination.php" style="text-decoration: none;">Destinations</a></li>
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

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="./destination.js" defer></script>
    
</body>
</html>
