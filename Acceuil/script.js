
document.addEventListener('DOMContentLoaded', function() {
    // Animation d'apparition du contenu principal
    const homeContent = document.querySelector('.home .content');
    setTimeout(() => {
      homeContent.classList.add('show');
    }, 300);
  
    // Effet de parallaxe pour la vidéo de fond
    const videoBg = document.querySelector('.home video');
    window.addEventListener('scroll', function() {
      const scrollPosition = window.pageYOffset;
      videoBg.style.transform = `translateY(${scrollPosition * 0.5}px)`;
    });
  
    // Animation des cartes d'équipe au survol
    const teamCards = document.querySelectorAll('.equipe');
    teamCards.forEach(card => {
      card.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-10px)';
        this.querySelector('img').style.transform = 'scale(1.05)';
      });
      
      card.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0)';
        this.querySelector('img').style.transform = 'scale(1)';
      });
    });
  
    // Effet de changement de couleur pour la navbar au scroll
    const navbar = document.querySelector('.navbar');
    window.addEventListener('scroll', function() {
      if (window.scrollY > 50)  {
        navbar.classList.add('scrolled');
      } else {
        navbar.classList.remove('scrolled');
      }
    });
  
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
    const topButton = document.querySelector('footer a');
    topButton.addEventListener('click', function(e) {
      e.preventDefault();
      window.scrollTo({
        top: 0,
        behavior: 'smooth'
      });
    });
  
    // Animation des éléments au défilement
    const fadeElements = document.querySelectorAll('.fade-in');
    
    const appearOnScroll = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('show');
          appearOnScroll.unobserve(entry.target);
        }
      });
    }, {
      threshold: 0.1
    });
  
    fadeElements.forEach(element => {
      appearOnScroll.observe(element);
    });
  
    // Effet de typewriter pour le titre principal
    const title = document.querySelector('.home h1');
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
  });

 //Animation pour le botton Booking Now
// Effet de profondeur au survol
const floatBtn = document.querySelector('.floating-btn');

floatBtn.addEventListener('mousemove', (e) => {
  const x = e.pageX - floatBtn.getBoundingClientRect().left;
  const y = e.pageY - floatBtn.getBoundingClientRect().top;
  
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

// Animation simple au survol (optionnel)
const flag = document.querySelector('.palestine-flag');
if (flag) {
  flag.addEventListener('mouseenter', () => {
    flag.style.animation = 'wave 1s ease-in-out infinite';
  });
  flag.addEventListener('mouseleave', () => {
    flag.style.animation = 'wave 3s ease-in-out infinite';
  });
}
