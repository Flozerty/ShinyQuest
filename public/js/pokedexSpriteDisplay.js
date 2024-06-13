document.addEventListener('DOMContentLoaded', function () {

  const button = document.getElementById('shiny-mode');
  const shinySprites = document.querySelectorAll('.sprite-shiny');
  const normalSprites = document.querySelectorAll('.sprite-normal');

  let shinyMode = false;
  button.addEventListener('click', () => {
    shinyMode = !shinyMode;
    verifyDisplayMode();
  })


  function verifyDisplayMode() {
    if (shinyMode) {
      shinySprites.forEach(sprite => {
        sprite.style.display = "block"
      });
      normalSprites.forEach(sprite => {
        sprite.style.display = "none"
      });
    } else {
      normalSprites.forEach(sprite => {
        sprite.style.display = "block"
      });
      shinySprites.forEach(sprite => {
        sprite.style.display = "none"
      });
    }
  }

  verifyDisplayMode()
})