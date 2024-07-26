document.addEventListener('DOMContentLoaded', function () {
  let shinyMode = false;

  const shinySprites = document.querySelectorAll('.sprite-shiny');
  const normalSprites = document.querySelectorAll('.sprite-normal');
  const button = document.getElementById('shiny-mode');

  if (button) { // On est dans le pokedex
    button.addEventListener('click', () => {
      shinyMode = !shinyMode;
      verifyDisplayMode();
    })

  } else { // Pour le Shinydex
    shinyMode = true;
  }

  // Affichage
  function verifyDisplayMode() {
    if (shinyMode) {
      shinySprites.forEach(sprite => {
        sprite.style.display = "block"
      });
      normalSprites.forEach(sprite => {
        sprite.style.display = "none"
      });
    } else {
      shinySprites.forEach(sprite => {
        sprite.style.display = "none"
      });
      normalSprites.forEach(sprite => {
        sprite.style.display = "block"
      });
    }
  }
  verifyDisplayMode()
})