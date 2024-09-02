////////////////////////// Nouveaux Messages //////////////////////////
const messagerieContainers = document.querySelectorAll('.messagerie-header-link');
if (messagerieContainers[0]) {
  // chercher les nouveaux messages
  fetch('/newMessages')
    .then(response => response.json())
    .then(data => {
      if (data && data > 0) {
        messagerieContainers.forEach(container => {

          const newMessages = document.createElement('span');
          newMessages.classList.add('new-messages-header');
          newMessages.innerHTML = data;
          container.appendChild(newMessages);
        });
      } else {
        console.log('pas de nouveaux messages')
      }
    })
    .catch(error => console.error('Erreur lors du chargement des nouveaux messages :', error));
}

/////////////////////// Initialistaion couleurs ///////////////////////
function changeSelectedBall() {
  const imgBall = document.querySelectorAll('.capture-ball-img')
  let selectedBall = localStorage.getItem('colorSelection')

  imgBall.forEach(img => {

    if (selectedBall === 'pokeball-toggle' || selectedBall === 'pokeball-toggle2') {
      img.src = "https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/items/poke-ball.png"

    } else if (selectedBall === 'superball-toggle' || selectedBall === 'superball-toggle2') {
      img.src = "https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/items/great-ball.png"

    } else if (selectedBall === 'hyperball-toggle' || selectedBall === 'hyperball-toggle2') {
      img.src = "https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/items/ultra-ball.png"
    } else {
      img.src = "https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/items/great-ball.png"
    }
  });
}

changeSelectedBall();

/////////////////////////////// PikaRun ///////////////////////////////
const pikaRun = document.getElementById('pika-run');

if (pikaRun) {
  const inputs = document.querySelectorAll('input');

  function checkVisibility() {
    const rect = pikaRun.getBoundingClientRect();
    const windowHeight = window.innerHeight || document.documentElement.clientHeight;

    // image est visible ?
    if (rect.top <= windowHeight) {
      pikaRun.classList.add('run-animation');
    } else {
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
  setInterval(checkVisibility, 100)
}