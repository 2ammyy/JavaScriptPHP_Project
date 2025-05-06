// script.js amélioré

document.addEventListener('DOMContentLoaded', function() {
  // Animation d'apparition du contenu principal
  const homeContent = document.querySelector('.home .content');
  if (homeContent) {
    setTimeout(() => {
      homeContent.classList.add('show');
    }, 300);
  }

  // Effet de parallaxe pour la vidéo de fond
  const videoBg = document.querySelector('.home video');
  if (videoBg) {
    window.addEventListener('scroll', function() {
      const scrollPosition = window.pageYOffset;
      videoBg.style.transform = `translateY(${scrollPosition * 0.5}px)`;
    });
  }

  // Animation des cartes d'équipe au survol
  const teamCards = document.querySelectorAll('.equipe');
  teamCards.forEach(card => {
    card.addEventListener('mouseenter', function() {
      this.style.transform = 'translateY(-10px)';
      const img = this.querySelector('img');
      if (img) img.style.transform = 'scale(1.05)';
    });
    
    card.addEventListener('mouseleave', function() {
      this.style.transform = 'translateY(0)';
      const img = this.querySelector('img');
      if (img) img.style.transform = 'scale(1)';
    });
  });

  // Effet de changement de couleur pour la navbar au scroll
  const navbar = document.querySelector('.navbar');
  if (navbar) {
    window.addEventListener('scroll', function() {
      if (window.scrollY > 50) {
        navbar.classList.add('scrolled');
      } else {
        navbar.classList.remove('scrolled');
      }
    });
  }

  // Animation des icônes de médias sociaux
  const socialIcons = document.querySelectorAll('.media-icons a');
  socialIcons.forEach(icon => {
    icon.addEventListener('mouseenter', function() {
      this.style.color = '#27C7D4';
    });
    icon.addEventListener('mouseleave', function() {
      this.style.color = 'white';
    });
  });

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

  // Animation des éléments au défilement
  const fadeElements = document.querySelectorAll('.fade-in');
  if (fadeElements.length > 0) {
    const appearOnScroll = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('show');
          appearOnScroll.unobserve(entry.target);
        }
      });
    }, {
      threshold: 0.1,
      rootMargin: '0px 0px -100px 0px'
    });

    fadeElements.forEach(element => {
      appearOnScroll.observe(element);
    });
  }

  // Effet de typewriter pour le titre principal (seulement sur desktop)
  if (window.innerWidth > 768) {
    const title = document.querySelector('.home h1');
    if (title) {
      const originalText = title.textContent;
      title.textContent = '';
      
      let i = 0;
      const typeWriter = setInterval(() => {
        if (i < originalText.length) {
          title.textContent += originalText.charAt(i);
          i++;
        } else {
          clearInterval(typeWriter);
        }
      }, 100);
    }
  }

  // Animation pour le bouton Booking Now
  const floatBtn = document.querySelector('.floating-btn');
  if (floatBtn) {
    floatBtn.addEventListener('mousemove', (e) => {
      const rect = floatBtn.getBoundingClientRect();
      const x = e.clientX - rect.left;
      const y = e.clientY - rect.top;
      
      floatBtn.style.setProperty('--x', `${x}px`);
      floatBtn.style.setProperty('--y', `${y}px`);
      
      floatBtn.style.boxShadow = `
        0 4px 20px rgba(66, 133, 244, 0.3),
        ${x - 50}px ${y - 10}px 30px rgba(66, 133, 244, 0.15)
      `;
    });

    floatBtn.addEventListener('mouseleave', () => {
      floatBtn.style.boxShadow = '0 4px 20px rgba(66, 133, 244, 0.3)';
    });
  }

  // Animation du drapeau Palestine
  const flag = document.querySelector('.palestine-flag');
  if (flag) {
    flag.addEventListener('mouseenter', () => {
      flag.style.animation = 'wave 0.8s ease-in-out infinite';
    });
    flag.addEventListener('mouseleave', () => {
      flag.style.animation = 'wave 3s ease-in-out infinite';
    });
  }

  // Optimisation des vidéos pour mobile
  const video = document.querySelector('.home video');
  if (video && window.innerWidth < 768) {
    video.setAttribute('playsinline', '');
    video.setAttribute('muted', '');
    video.setAttribute('autoplay', '');
    video.setAttribute('loop', '');
  }
});

// Optimisation du chargement des images
document.addEventListener('DOMContentLoaded', function() {
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