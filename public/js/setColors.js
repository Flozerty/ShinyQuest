document.addEventListener('DOMContentLoaded', function () {
  const colorSelection = document.querySelectorAll('.l_d_mode-main input, .l_d_mode input');

  colorSelection.forEach(choice => {
    choice.addEventListener('change', function () {
      syncSelection(choice.id);
      changeSelectedColor(choice.id);
      saveSelection(choice.id)
      changeSelectedBall();
    })
  });
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
    document.getElementById('pokeball-toggle2').checked = true;

  } else if (selectedId === 'superball-toggle' || selectedId === 'superball-toggle2') {
    document.getElementById('superball-toggle').checked = true;
    document.getElementById('superball-toggle2').checked = true;

  } else if (selectedId === 'hyperball-toggle' || selectedId === 'hyperball-toggle2') {
    document.getElementById('hyperball-toggle').checked = true;
    document.getElementById('hyperball-toggle2').checked = true;
  } else {
    document.getElementById('superball-toggle').checked = true;
    document.getElementById('superball-toggle2').checked = true;
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
}