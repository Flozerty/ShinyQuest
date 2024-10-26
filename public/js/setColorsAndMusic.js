document.addEventListener('DOMContentLoaded', function () {
  // couleurs
  const colorSelection = document.querySelectorAll('.l_d_mode-main input, .l_d_mode input');

  colorSelection.forEach(choice => {
    choice.addEventListener('change', function () {
      syncSelection(choice.id);
      changeSelectedColor(choice.id);
      saveSelection(choice.id)
      changeSelectedBall();
    })
  });

  // musique
  const audio = document.querySelector('#music');
  audio.volume = 0.2;

  let isPlaying = localStorage.getItem('isMusicPlaying') === 'true';
  const currentTime = localStorage.getItem('audioCurrentTime');

  if (isPlaying) {
    audio.currentTime = currentTime ? parseFloat(currentTime) : 0;

    // Demander une interaction utilisateur pour démarrer la musique
    audio.addEventListener('canplaythrough', () => {
      audio.play().catch(e => {
        console.log("Erreur de lecture automatique : ", e);
        isPlaying = false;
        // si la musique n'a pas encore démarré

        const playOnClick = () => {
          audio.play()
          // Une fois que la musique a démarré on enlève l'écouteur
          document.removeEventListener('click', playOnClick);
        };

        document.addEventListener('click', playOnClick);
      })
    })
  };

  // Écouter les événements play/pause pour stocker l'état
  audio.onplay = () => {
    localStorage.setItem('isMusicPlaying', 'true');
  };
  audio.onpause = () => {
    localStorage.setItem('isMusicPlaying', 'false');
  };

  // Sauvegarder le temps actuel à chaque seconde
  audio.ontimeupdate = () => {
    localStorage.setItem('audioCurrentTime', audio.currentTime);
  };

  if (currentTime) {
    audio.currentTime = parseFloat(currentTime);
  }

  document.body.appendChild(audio);

  const profileNavSection = document.querySelector('#profile-nav');

  const playBtn = document.createElement('button');
  playBtn.className = "music-button"
  playBtn.ariaLabel = "jouer la musique"

  playBtn.innerHTML = isPlaying ? '<i class="fa-solid fa-pause"></i>' : '<i class="fa-solid fa-play"></i>';
  playBtn.addEventListener('click', () => {
    if (audio.paused) {
      audio.play();
      playBtn.innerHTML = '<i class="fa-solid fa-pause"></i>';
      playBtn.ariaLabel = "arrêter la musique"
    } else {
      audio.pause();
      playBtn.innerHTML = '<i class="fa-solid fa-play"></i>';
      playBtn.ariaLabel = "jouer la musique"
    }
  })

  profileNavSection.appendChild(playBtn);
});

loadSavedSelection();

///////////////////////////////////////////////////////////////////////
///////////////////////////// FONCTIONS ///////////////////////////////
///////////////////////////////////////////////////////////////////////

function loadSavedSelection() {
  let savedId = localStorage.getItem('colorSelection');
  if (!savedId) {
    savedId = "superball-toggle";
  }
  changeSelectedColor(savedId);

  // on va changer l'input sélectionné dès le chargement de la page terminé
  document.addEventListener('DOMContentLoaded', function () {
    syncSelection(savedId);
  })
}

// synchronisation des deux sélecteurs de ball/palette
function syncSelection(selectedId) {
  if (selectedId === 'pokeball-toggle' || selectedId === 'pokeball-toggle2') {
    document.getElementById('pokeball-toggle').checked = true;
    if (document.getElementById('pokeball-toggle2')) {
      document.getElementById('pokeball-toggle2').checked = true;
    }

  } else if (selectedId === 'superball-toggle' || selectedId === 'superball-toggle2') {
    document.getElementById('superball-toggle').checked = true;
    if (document.getElementById('superball-toggle2')) {
      document.getElementById('superball-toggle2').checked = true;
    }
  } else if (selectedId === 'hyperball-toggle' || selectedId === 'hyperball-toggle2') {
    document.getElementById('hyperball-toggle').checked = true;
    if (document.getElementById('hyperball-toggle2')) {
      document.getElementById('hyperball-toggle2').checked = true;
    }
  } else {
    document.getElementById('superball-toggle').checked = true;
    if (document.getElementById('superball-toggle2')) {
      document.getElementById('superball-toggle2').checked = true;
    }
  }
}

// sauvegarde de la sélection dans le Local Storage
function saveSelection(selectedId) {
  localStorage.setItem('colorSelection', selectedId);
}

// attribue les couleurs liées à l'input sélectionné
function changeSelectedColor(selectedId) {
  if (selectedId === 'pokeball-toggle' || selectedId === 'pokeball-toggle2') {
    setColors(
      '--darkred',
      '--lightred',
      '--background-light-main',
      '--light-secondary',
      '--black',
      '--white',
      '--light-secondary-full',
      '--darkblue',
      '--darkred',
      '--dark-card-color',
    );
  } else if (selectedId === 'superball-toggle' || selectedId === 'superball-toggle2') {
    setColors(
      '--darkblue',
      '--lightblue',
      '--background-light-main',
      '--light-secondary',
      '--black',
      '--white',
      '--light-secondary-full',
      '--darkblue',
      '--darkred',
      '--dark-card-color',
    );
  } else if (selectedId === 'hyperball-toggle' || selectedId === 'hyperball-toggle2') {
    setColors(
      '--yellow',
      '--dark-secondary',
      '--background-dark-main',
      '--dark-secondary',
      '--white',
      '--dark-card-color',
      '--dark-secondary-full',
      '--bluemedium',
      '--redmedium',
      '--darker',
    );
  } else {
    console.log("problème dans l'attribution des couleurs")
  }
}

// fonction qui met a jour les coulurs dans :root
function setColors(
  primaryColor,
  secondaryColor,
  backgroundColor,
  backgroundSecondary,
  fontMainColor,
  cardColor,
  cardSecondaryColor,
  blueMain,
  redMain,
  HoverMain,
) {
  document.documentElement.style.setProperty('--primary-color', `var(${primaryColor})`);
  document.documentElement.style.setProperty('--secondary-color', `var(${secondaryColor})`);
  document.documentElement.style.setProperty('--background-color', `var(${backgroundColor})`);
  document.documentElement.style.setProperty('--background-secondary', `var(${backgroundSecondary})`);
  document.documentElement.style.setProperty('--font-main-color', `var(${fontMainColor})`);
  document.documentElement.style.setProperty('--cardColor', `var(${cardColor})`);
  document.documentElement.style.setProperty('--cardSecondaryColor', `var(${cardSecondaryColor})`);
  document.documentElement.style.setProperty('--blue-main', `var(${blueMain})`);
  document.documentElement.style.setProperty('--red-main', `var(${redMain})`);
  document.documentElement.style.setProperty('--hover-main-color', `var(${HoverMain})`);
}