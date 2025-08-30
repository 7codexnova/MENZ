// Kleine interacties + feedback parsing
(function(){
  const year = document.getElementById('year');
  if (year) year.textContent = new Date().getFullYear();

  const navToggle = document.querySelector('.nav-toggle');
  const nav = document.querySelector('.main-nav');
  if (navToggle && nav) {
    navToggle.addEventListener('click', () => {
      const open = nav.getAttribute('data-open') === 'true';
      nav.setAttribute('data-open', String(!open));
      navToggle.setAttribute('aria-expanded', String(!open));
    });
  }

  // Toon feedback als we ?sent=1 of ?error=1 in de URL hebben
  const params = new URLSearchParams(location.search);
  const feedback = document.getElementById('form-feedback');
  if (feedback) {
    if (params.get('sent') === '1') {
      feedback.textContent = 'Bedankt! Uw bericht is verzonden. We nemen zo snel mogelijk contact op.';
      feedback.style.color = '#2b7a0b';
    } else if (params.get('error') === '1') {
      feedback.textContent = 'Er ging iets mis met verzenden. Probeer het later opnieuw of bel ons.';
      feedback.style.color = '#b42318';
    }
  }
})();
