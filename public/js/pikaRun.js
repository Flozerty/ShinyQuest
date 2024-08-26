document.addEventListener('DOMContentLoaded', function () {
  const pikaRun = document.getElementById('pika-run');
  const inputs = document.querySelectorAll('input');

  function checkVisibility() {
    const rect = pikaRun.getBoundingClientRect();
    const windowHeight = window.innerHeight || document.documentElement.clientHeight;

    // Si l'image est visible
    if (rect.top <= windowHeight) {
      pikaRun.classList.add('run-animation');
    } else {
      // Si l'image sort, on réinitialise
      pikaRun.classList.remove('run-animation');
      pikaRun.style.left = "0";
    }
  }

  window.addEventListener('scroll', checkVisibility);

  inputs.forEach(input => {
    input.addEventListener('input', () => {
      checkVisibility();
    })
  })

  checkVisibility();
  // intervalle de vérification pour les autres cas possibles
  setInterval(checkVisibility, 500)
});