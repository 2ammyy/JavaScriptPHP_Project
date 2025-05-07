<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Travel Agency - Découvrez des voyages fantastiques à travers le monde avec nos offres exclusives">
  <title>Accueil - Travel Agency</title>
  <!-- Preload des ressources critiques -->
  <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" as="style">
  <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css" as="style">
  <link rel="preload" href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" as="font" crossorigin>
  <link rel="preload" href="./3.mp4" as="video" type="video/mp4">
  
  <!-- CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
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
        <a class="navbar-brand text-white me-2" href="../Acceuil/Acceuil.html">TRAVEL</a>
      </div>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      
      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav">
    <li class="nav-item">
        <a class="nav-link text-white" href="./Acceuil.php" aria-current="page">Accueil</a>
    </li>
    <li class="nav-item">
        <a class="nav-link text-white" href="../Offres/activite.php">Activités</a>
    </li>
    <li class="nav-item">
        <a class="nav-link active text-white" href="../Destinations/destination.php">Destinations</a>
    </li>
    
    <?php session_start(); ?>
    <?php if (isset($_SESSION['user_id'])): ?>
        <!-- Affiché quand l'utilisateur est connecté -->
        <li class="nav-item">
            <span class="nav-link text-white">Bienvenue, <?= htmlspecialchars($_SESSION['username']) ?></span>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white" href="../login/logout.php">Déconnexion</a>
        </li>
    <?php else: ?>
        <!-- Affiché quand l'utilisateur est déconnecté -->
        <li class="nav-item">
            <a class="nav-link text-white" href="../login/login.php">Connexion</a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white" href="../login/register.php">Inscription</a>
        </li>
    <?php endif; ?>
</ul>
      </div>
    </div>
    
  </nav>

  <!-- Section principale -->
  <section class="home position-relative vh-100 d-flex flex-column align-items-center justify-content-center text-center">
    <!-- Vidéo de fond -->
    <video autoplay muted loop playsinline class="position-absolute w-100 h-100" aria-hidden="true">
      <source src="./3.mp4" type="video/mp4">
      Votre navigateur ne supporte pas les vidéos HTML5.
    </video>

    <!-- Contenu au-dessus de la vidéo -->
    <div class="content px-3 fade-in slide-up">
      <h1 style="color: white;">Voyages.<br><span style="color:rgb(255, 255, 255) ;">Fantastiques</span></h1>
      <p class="mx-auto" style="max-width: 800px;color:white">
        Bienvenue chez TRAVEL, votre partenaire privilégié pour découvrir des destinations exceptionnelles à travers le monde. 
        Que vous soyez à la recherche d'aventures palpitantes, de moments de détente sur des plages paradisiaques, ou de découvertes culturelles enrichissantes, 
        nous avons le voyage parfait pour vous. 
      </p>
      <div class="floating-btn-container">
        <a href="../Destinations/destination.php" class="floating-btn btn-booking">
          Booking Now
          <span class="pulse-ring" aria-hidden="true"></span>
        </a>
      </div>
      <div class="media-icons d-flex justify-content-center gap-4">
        <a href="https://www.facebook.com/" class="facebook-icon" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
        <a href="https://www.instagram.com/" class="instagram-icon" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
        <a href="https://wa.me/" class="whatsapp-icon" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
      </div>
    </div>
  </section>

  <!-- Section Parallaxe -->
  <section class="parallax-1" aria-label="Découvrez le monde">
    <div class="parallax-inner">
      <h1>Découvrez le Monde</h1>
      <p class="text-white">Des destinations uniques pour des expériences inoubliables</p>
    </div>
  </section>

  <section class="parallax-content">
    <div class="container">
      <h2>Nos Destinations Phares</h2>
      <p>
        Explorez nos destinations les plus populaires à travers le monde. Chaque lieu a été soigneusement sélectionné pour vous offrir une expérience de voyage exceptionnelle.
      </p>
      <div class="row mt-4">
        <div class="col-md-4 mb-4">
          <div class="destination-card">
            <div class="destination-icon">
              <i class="fas fa-umbrella-beach"></i>
            </div>
            <h3>Plages Paradisiaques</h3>
            <p>Détendez-vous sur les plus belles plages du monde</p>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="destination-card">
            <div class="destination-icon">
              <i class="fas fa-mountain"></i>
            </div>
            <h3>Aventure Montagne</h3>
            <p>Randonnées et paysages à couper le souffle</p>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="destination-card">
            <div class="destination-icon">
              <i class="fas fa-city"></i>
            </div>
            <h3>Villes Vibrantes</h3>
            <p>Découvrez la culture des grandes métropoles</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="parallax-2" aria-label="Aventures inoubliables">
    <div class="parallax-inner">
      <h1>Aventures Inoubliables</h1>
      <p class="text-white">Créez des souvenirs qui dureront toute une vie</p>
    </div>
  </section>

  <section class="parallax-content bg-light">
    <div class="container">
      <h2>Pourquoi Choisir Travel?</h2>
      <div class="row">
        <div class="col-md-6">
          <div class="feature-box">
            <i class="fas fa-award"></i>
            <h3>15 ans d'expérience</h3>
            <p>Notre expertise au service de vos voyages</p>
          </div>
        </div>
        <div class="col-md-6">
          <div class="feature-box">
            <i class="fas fa-hand-holding-heart"></i>
            <h3>Service personnalisé</h3>
            <p>Des voyages adaptés à vos envies</p>
          </div>
        </div>
        <div class="col-md-6">
          <div class="feature-box">
            <i class="fas fa-shield-alt"></i>
            <h3>Sécurité garantie</h3>
            <p>Votre tranquillité d'esprit est notre priorité</p>
          </div>
        </div>
        <div class="col-md-6">
          <div class="feature-box">
            <i class="fas fa-percentage"></i>
            <h3>Meilleurs prix</h3>
            <p>Garantie du meilleur rapport qualité-prix</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="parallax-3" aria-label="Prêt à partir">
    <div class="parallax-inner">
      <h1>Prêt à Partir?</h1>
      <p class="text-white">Contactez-nous dès aujourd'hui pour planifier votre prochaine aventure</p>
    </div>
  </section>

  <!--Section équipe -->
  <section class="equipes py-5 bg-white">
    <div class="container">
      <h2 class="text-center mb-5">Notre Équipe</h2>
      <div class="row g-4 justify-content-center">
        <div class="col-md-6 col-lg-3">
          <div class="equipe fade-in">
            <div class="team-img-container">
              <img src="./t2.jpg" alt="Sara Lu, Agent de réservation" class="img-fluid" >
            </div>
            <h3>Sara Lu</h3>
            <p class="position">Agent De Réservation</p>
            <p class="description">Pour bien confirmer votre réservation de voyage.</p>
            <a href="mailto:WebMaster@gmail.com" class="btn btn-outline-primary">Contact</a>
          </div>
        </div>
        
        <div class="col-md-6 col-lg-3">
          <div class="equipe fade-in">
            <div class="team-img-container">
              <img src="./t111.jpg" alt="Lugan Lee, Conseiller en voyages" class="img-fluid" >
            </div>
            <h3>Lugan Lee</h3>
            <p class="position">Conseiller En Voyages</p>
            <p class="description">Pour avoir des idées et des choix de votre voyage.</p>
            <a href="mailto:WebMaster@gmail.com" class="btn btn-outline-primary">Contact</a>
          </div>
        </div>
        
        <div class="col-md-6 col-lg-3">
          <div class="equipe fade-in">
            <div class="team-img-container">
              <img src="./t33.jpg" alt="Muzan Sam, Responsable du service clientèle" class="img-fluid" >
            </div>
            <h3>Muzan Sam</h3>
            <p class="position">Responsable Du Service Clientèle</p>
            <p class="description">Plus d'autres informations à propos de nos voyages</p>
            <a href="mailto:WebMaster@gmail.com" class="btn btn-outline-primary">Contact</a>
          </div>
        </div>
        
        <div class="col-md-6 col-lg-3">
          <div class="equipe fade-in">
            <div class="team-img-container">
              <img src="./t4.jpg" alt="Suryeon Shim, Spécialiste marketing" class="img-fluid" >
            </div>
            <h3>Suryeon Shim</h3>
            <p class="position">Spécialiste Marketing</p>
            <p class="description">Pour profiter des opportunités uniques et spéciales.</p>
            <a href="mailto:WebMaster@gmail.com" class="btn btn-outline-primary">Contact</a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Newsletter -->
  <section class="newsletter py-5 bg-dark text-white">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-8 text-center">
          <h2 class="mb-4">Abonnez-vous à notre newsletter</h2>
          <p class="mb-4">Recevez nos meilleures offres et actualités directement dans votre boîte mail</p>
          <form class="newsletter-form">
            <div class="input-group">
              <input type="email" class="form-control" placeholder="Votre email" required>
              <button class="btn btn-primary" type="submit">S'abonner</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="py-4">
    <div class="container">
      <div class="row">
        <div class="col-md-4 mb-4 mb-md-0">
          <h3>TRAVEL</h3>
          <p>Votre agence de voyage préférée depuis 15 ans.</p>
          <div class="social-links">
            <a href="#" aria-label="Facebook"style="text-decoration: none;"><i class="fab fa-facebook-f"></i></a>
            <a href="#" aria-label="Instagram"style="text-decoration: none;"><i class="fab fa-instagram"></i></a>
            <a href="#" aria-label="What's App" style="text-decoration: none;"><i class="fab fa-whatsapp"></i></a>
          </div>
        </div>
        <div class="col-md-2 mb-4 mb-md-0">
          <h4>Liens</h4>
          <ul class="footer-links">
            <li><a href="./Acceuil.html">Accueil</a></li>
            <li><a href="../Offres/activite.html">Activités</a></li>
            <li><a href="../Destinations/destination.html">Destinations</a></li>
            
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
        <div class="col-md-3">
          <h4>Newsletter</h4>
          <p>Abonnez-vous pour ne rien manquer</p>
          <form class="footer-newsletter">
            <input type="email" placeholder="Votre email" required>
            <button type="submit"><i class="fas fa-paper-plane"></i></button>
          </form>
        </div>
      </div>
      <hr class="my-4">
      <div class="row align-items-center">
        <div class="col-md-6 text-center text-md-start">
          <p class="m-0">&copy; Travel Agency 2024-2025. Tous droits réservés.</p>
        </div>
        <div class="col-md-6 text-center text-md-end">
          <a href="#top" class="back-to-top"><i class="fas fa-arrow-up"></i> Retour en haut</a>
        </div>
      </div>
    </div>
  </footer>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" async></script>
  <script src="script.js" defer></script>
</body>
</html>
