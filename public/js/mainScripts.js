const
  pikaRun = document.getElementById('pika-run'),
  lucarioContainer = document.querySelector("#pokemon-dance"),
  main = document.querySelector("#main-wrapper"),
  mainContainer = document.querySelector('main'),
  header = document.querySelector("header"),
  messagerieContainers = document.querySelectorAll('.messagerie-header-link'),
  profileNav = document.querySelector('#profile-nav'),
  userContainer = document.querySelector('#header-profile'),
  caret = document.querySelector("#profile-caret"),
  sideNav = document.querySelector('#main-side-nav'),
  toggleNavBtn = document.querySelector('#toggle-nav-btn'),
  scrollUp = document.querySelector('.scroller'),
  footer = document.querySelector('footer');

///////////////////////////////////////////////////////////////////////
////////////////////////// Nouveaux Messages //////////////////////////
///////////////////////////////////////////////////////////////////////
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
}

///////////////////////////////////////////////////////////////////////
/////////////////////// Initialistaion couleurs ///////////////////////
///////////////////////////////////////////////////////////////////////
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

///////////////////////////////////////////////////////////////////////
/////////////////////////////// PikaRun ///////////////////////////////
///////////////////////////////////////////////////////////////////////
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

///////////////////////////////////////////////////////////////////////
///////////////////////////// lucario dance ///////////////////////////
///////////////////////////////////////////////////////////////////////
let inactivityTimeout;
let animationInterval;

function startAnimation() {
  animationInterval = setInterval(() => {
    lucarioContainer.classList.toggle("show");
  }, 2000);
}

function stopAnimation() {
  clearInterval(animationInterval);
  lucarioContainer.classList.remove("show");
}

function resetTimeout() {
  clearTimeout(inactivityTimeout);
  stopAnimation();
  inactivityTimeout = setTimeout(startAnimation, 600000);
}

document.addEventListener("click", resetTimeout);
document.addEventListener("mousemove", resetTimeout);
document.addEventListener("mousedown", resetTimeout);
document.addEventListener("keypress", resetTimeout);
document.addEventListener("touchstart", resetTimeout);
resetTimeout();

///////////////////////////////////////////////////////////////////////
////////////////////////// header profile nav /////////////////////////
///////////////////////////////////////////////////////////////////////
if (caret) {
  show = false;
  displayers = [caret, userContainer];

  // montrer / cacher la profile-nav 
  displayers.forEach(element => {
    if (element) {
      element.addEventListener('click', () => {
        if (show) {
          hideProfileNav()
        } else {
          showProfileNav()
        }
      })
    }
  });

  // retire la nav quand on clique ailleurs
  document.addEventListener("click", (event) => {
    if (
      show && !profileNav.contains(event.target) &&
      !caret.contains(event.target) &&
      (userContainer ? !userContainer.contains(event.target) : true)
    ) {
      hideProfileNav()
    }
  })

  function showProfileNav() {
    caret.style.transform = "rotate(0deg)";
    profileNav.style.transform = "translate(0, 100%)";
    show = true;
  }

  function hideProfileNav() {
    caret.style.transform = "rotate(-90deg)";
    profileNav.style.transform = "translate(170%, 50%) rotate(45deg)";
    show = false;
  }
}

///////////////////////////////////////////////////////////////////////
//////////////////////////// scroll listener //////////////////////////
///////////////////////////////////////////////////////////////////////
let lastScrollY;

window.addEventListener('scroll', () => {
  if (this.scrollY <= 300) {
    scrollUp.classList.add('hide-scroll')
  } else {
    scrollUp.classList.remove('hide-scroll')

    // hide/revele header si > 300
    if (window.scrollY > lastScrollY) {
      header.style.transform = 'translateY(-100%)';
      sideNav.style.top = '0';
      sideNav.style.paddingTop = '80px';
      toggleNavBtn.style.top = 'calc(10% + 80px)';
    } else {
      header.style.transform = 'translateY(0)';
      sideNav.style.top = '80px';
      sideNav.style.paddingTop = '0';
      toggleNavBtn.style.top = '10%';
    }
    lastScrollY = window.scrollY;
  }
})

///////////////////////////////////////////////////////////////////////
//////////////////////////// toggle sideNav ///////////////////////////
///////////////////////////////////////////////////////////////////////
let toggleNav = false;

toggleNavBtn.addEventListener('click', () => {
  toggleNav = !toggleNav;
  verifyToggle();
})

toggleNavBtn.addEventListener('mouseover', () => {
  if (toggleNav) {
    toggleNavBtn.style.transform = "translateX(20%) rotate(180deg)";
  } else {
    toggleNavBtn.style.transform = "translateX(80%)";
  }
})

toggleNavBtn.addEventListener('mouseout', () => {
  if (toggleNav) {
    toggleNavBtn.style.transform = "translateX(40%) rotate(180deg)";
  } else {
    toggleNavBtn.style.transform = "translateX(60%)";
  }
})

function verifyToggle() {
  if (toggleNav) {
    sideNav.classList.add('toggle-nav');
    sideNav.classList.remove('hide-nav');
    mainContainer.classList.add("main-blur")
    footer.classList.add("main-blur")
    toggleNavBtn.style.transform = "translateX(40%) rotate(180deg)";

  } else {
    sideNav.classList.add('hide-nav');
    sideNav.classList.remove('toggle-nav');
    mainContainer.classList.remove("main-blur")
    footer.classList.remove("main-blur")
    toggleNavBtn.style.transform = "translateX(60%)";
  }
}

document.addEventListener('click', (event) => {
  if (!sideNav.contains(event.target) && toggleNav) {
    toggleNav = !toggleNav;
    toggleNavBtn.style.transform = "translateX(60%)";
    verifyToggle();
  }
})