document.addEventListener('DOMContentLoaded', function() {
    
    // Données des destinations
    const destinations = [
        {
            id: 1,
            name: "Paris",
            location: "France",
            description: "Explorez la ville de l'amour et des lumières avec ses monuments emblématiques.",
            image: "./image/pariss.jpg",
            price: "1500dt",
            region: "europe",
            type: "romantique",
            budget: "mid"
        },
        {
            id: 2,
            name: "Rome",
            location: "Italie",
            description: "Découvrez l'histoire ancienne et la cuisine italienne dans la ville éternelle.",
            image: "./image/rome.jpg",
            price: "1200dt",
            region: "europe",
            type: "culture",
            budget: "mid"
        },
        {
            id: 3,
            name: "Londres",
            location: "Royaume-Uni",
            description: "Plongez dans la modernité et l'histoire britannique dans cette ville cosmopolite.",
            image: "./image/london.jpg",
            price: "1800dt",
            region: "europe",
            type: "culture",
            budget: "high"
        },
        {
            id: 4,
            name: "Egypte",
            location: "Afrique du Nord",
            description: "Explorez les pyramides majestueuses et découvrez les mystères anciens.",
            image: "./image/egypt.jpg",
            price: "900dt",
            region: "afrique",
            type: "culture",
            budget: "low"
        },
        {
            id: 5,
            name: "Maroc",
            location: "Afrique du Nord",
            description: "Plongez dans un monde de couleurs, d'épices et de traditions millénaires.",
            image: "./image/maroc.jpg",
            price: "800dt",
            region: "afrique",
            type: "aventure",
            budget: "low"
        },
        {
            id: 6,
            name: "Istanbul",
            location: "Turquie",
            description: "Admirez les merveilles du Bosphore et laissez-vous séduire par les parfums des bazars.",
            image: "./image/istanbul.jpg",
            price: "1100dt",
            region: "asie",
            type: "culture",
            budget: "mid"
        }
    ];

    // Données des offres spéciales
    const specialOffers = [
        {
            id: 1,
            name: "Promotion à Paris",
            description: "5 nuits pour le prix de 3 ! Profitez de la ville lumière à prix réduit.",
            image: "./image/f1.jpg",
            oldPrice: "4500dt",
            newPrice: "2700dt",
            discount: "40%",
            link: "./offre_paris.html"
        },
        {
            id: 2,
            name: "Forfait Rome",
            description: "Weekend tout inclus à partir de 500dt. Vol + Hôtel + Visites guidées.",
            image: "./image/f2.jpg",
            oldPrice: "750dt",
            newPrice: "500dt",
            discount: "33%",
            link: "./off_rome.html"
        },
        {
            id: 3,
            name: "Escapade Londres",
            description: "Économisez 20% sur votre séjour. Offre valable jusqu'au 30/11/2024.",
            image: "./image/f3.jpg",
            oldPrice: "3600dt",
            newPrice: "2880dt",
            discount: "20%",
            link: "./off_lond.html"
        }
    ];

    // Données des témoignages avec évaluations
    const testimonialsData = [
        {
            quote: "Le voyage organisé par Travel Agency a dépassé toutes mes attentes. Tout était parfaitement planifié!",
            author: "Marie D.",
            trip: "Voyage à Paris",
            rating: 5,
            image: "./media/client1.jpg"
        },
        {
            quote: "Une expérience incroyable avec des guides locaux exceptionnels. Je recommande vivement!",
            author: "Sophie L.",
            trip: "Voyage à Rome",
            rating: 4,
            image: "./media/client2.jpg"
        },
        {
            quote: "Service client impeccable et itinéraire bien pensé. Nous reviendrons certainement.",
            author: "Jean P.",
            trip: "Voyage à Tokyo",
            rating: 5,
            image: "./media/client3.jpg"
        }
    ];

    // Références aux éléments DOM
    const destinationsGrid = document.getElementById('destinations-grid');
    const offersGrid = document.getElementById('offers-grid');
    const filterForm = document.getElementById('filter-form');
    const regionFilter = document.getElementById('region');
    const typeFilter = document.getElementById('type');
    const budgetFilter = document.getElementById('budget');
    const applyFiltersBtn = document.getElementById('apply-filters');
    const testimonialContainer = document.querySelector('.testimonials-slider');

    // Afficher toutes les destinations au chargement
    displayDestinations(destinations);
    displayOffers(specialOffers);
    initTestimonials();

    // Filtrer les destinations
    applyFiltersBtn.addEventListener('click', function() {
        const regionValue = regionFilter.value;
        const typeValue = typeFilter.value;
        const budgetValue = budgetFilter.value;

        const filteredDestinations = destinations.filter(destination => {
            return (regionValue === 'all' || destination.region === regionValue) &&
                   (typeValue === 'all' || destination.type === typeValue) &&
                   (budgetValue === 'all' || destination.budget === budgetValue);
        });

        displayDestinations(filteredDestinations);
    });

    // Fonction pour afficher les destinations
    function displayDestinations(destinationsToDisplay) {
        destinationsGrid.innerHTML = '';

        if (destinationsToDisplay.length === 0) {
            destinationsGrid.innerHTML = `
                <div class="col-12 text-center py-5">
                    <h4>Aucune destination ne correspond à vos critères de recherche.</h4>
                    <p>Essayez de modifier vos filtres.</p>
                </div>
            `;
            return;
        }

        destinationsToDisplay.forEach(destination => {
            const card = document.createElement('div');
            card.className = 'col-md-6 col-lg-4 col-xl-3 fade-in';
            card.innerHTML = `
                <div class="destination-card">
                    <img src="${destination.image}" alt="${destination.name}" >
                    <div class="card-body">
                        <h3>${destination.name}</h3>
                        <p class="location"><i class="fas fa-map-marker-alt"></i> ${destination.location}</p>
                        <p>${destination.description}</p>
                        <p class="price">À partir de ${destination.price}</p>
                        <button class="btn btn-primary">Voir détails</button>
                    </div>
                </div>
            `;
            destinationsGrid.appendChild(card);
        });

        // Animation des cartes
        animateCards();
    }

    // Fonction pour afficher les offres spéciales
    function displayOffers(offersToDisplay) {
        offersGrid.innerHTML = '';

        offersToDisplay.forEach(offer => {
            const card = document.createElement('div');
            card.className = 'col-md-6 col-lg-3 fade-in';
            card.innerHTML = `
                <div class="offer-card">
                    <img src="${offer.image}" alt="${offer.name}" >
                    <div class="card-body">
                        <h3>${offer.name}</h3>
                        <span class="discount">-${offer.discount}</span>
                        <p>${offer.description}</p>
                        <p>
                            <span class="price">${offer.oldPrice}</span>
                            <span class="new-price">${offer.newPrice}</span>
                        </p>
                        <a href="${offer.link}" class="btn btn-primary">Voir l'offre</a>
                    </div>
                </div>
            `;
            offersGrid.appendChild(card);
        });

        // Animation des cartes
        animateCards();
    }

    // Initialisation des témoignages avec étoiles
    function initTestimonials() {
        let currentTestimonial = 0;
        let testimonialInterval;

        // Fonction pour générer les étoiles
        function generateStars(rating) {
            let stars = '';
            for (let i = 1; i <= 5; i++) {
                stars += i <= rating 
                    ? '<i class="fas fa-star"></i>' 
                    : '<i class="far fa-star"></i>';
            }
            return `<div class="rating-stars">${stars}</div>`;
        }

        // Afficher un témoignage
        function showTestimonial(index) {
            const testimonial = testimonialsData[index];
            testimonialContainer.innerHTML = `
                <div class="testimonial active">
                    <blockquote>${testimonial.quote}</blockquote>
                    ${generateStars(testimonial.rating)}
                    <div class="client-info">
                        <img src="${testimonial.image}" alt="${testimonial.author}" >
                        <div>
                            <p><strong>${testimonial.author}</strong></p>
                            <p class="trip-info">${testimonial.trip}</p>
                        </div>
                    </div>
                </div>
                <div class="slider-controls">
                    <button class="prev-testimonial"><i class="fas fa-chevron-left"></i></button>
                    <button class="next-testimonial"><i class="fas fa-chevron-right"></i></button>
                </div>
            `;

            // Réattacher les événements
            document.querySelector('.prev-testimonial').addEventListener('click', prevTestimonial);
            document.querySelector('.next-testimonial').addEventListener('click', nextTestimonial);
        }

        // Témoignage précédent
        function prevTestimonial() {
            currentTestimonial = (currentTestimonial - 1 + testimonialsData.length) % testimonialsData.length;
            showTestimonial(currentTestimonial);
            resetInterval();
        }

        // Témoignage suivant
        function nextTestimonial() {
            currentTestimonial = (currentTestimonial + 1) % testimonialsData.length;
            showTestimonial(currentTestimonial);
            resetInterval();
        }

        // Réinitialiser l'intervalle
        function resetInterval() {
            clearInterval(testimonialInterval);
            testimonialInterval = setInterval(nextTestimonial, 1000);
        }

        // Pause au survol
        testimonialContainer.addEventListener('mouseenter', () => {
            clearInterval(testimonialInterval);
        });

        testimonialContainer.addEventListener('mouseleave', () => {
            resetInterval();
        });

        // Initialisation
        showTestimonial(currentTestimonial);
        testimonialInterval = setInterval(nextTestimonial, 1000);
    }

    // Animation des cartes
    function animateCards() {
        const cards = document.querySelectorAll('.fade-in');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('show');
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.1
        });

        cards.forEach(card => {
            observer.observe(card);
        });
    }

    // Navbar scroll effect
    const navbar = document.querySelector('.navbar');
    window.addEventListener('scroll', function() {
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });

    // Smooth scroll pour les ancres
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
            });
        });
    });

    // Optimisation vidéo pour mobile
    const promoVideo = document.getElementById('promo-video');
    if (window.innerWidth < 768) {
        promoVideo.setAttribute('playsinline', '');
        promoVideo.setAttribute('muted', '');
        promoVideo.setAttribute('autoplay', '');
        promoVideo.setAttribute('loop', '');
    }
      // Bouton "To the top" smooth scroll
  const topButton = document.querySelector('.back-to-top');
  if (topButton) {
    topButton.addEventListener('click', function(e) {
      e.preventDefault();
      window.scrollTo({
        top: 0,
        behavior: 'smooth'
      });
    });
  }

    // Lazy loading des images
    if ('loading' in HTMLImageElement.prototype) {
        const images = document.querySelectorAll('img[loading="lazy"]');
        images.forEach(img => {
            img.src = img.dataset.src;
        });
    } else {
        // Fallback pour les navigateurs qui ne supportent pas le lazy loading
        const script = document.createElement('script');
        script.src = 'https://cdnjs.cloudflare.com/ajax/libs/lazysizes/5.3.2/lazysizes.min.js';
        document.body.appendChild(script);
    }
});