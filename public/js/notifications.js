let notifDiv = document.querySelectorAll('.flash-message'),
  timeouts = [];

affichageElements()
// affichage des elements
function affichageElements() {
  let i = 0;

  notifDiv.forEach(element => {
    const xmark = element.querySelector('i');

    // addEventListener du X 
    xmark.addEventListener('click', () => {
      element.parentNode.removeChild(element);

      redimensionner();
    })

    // 50 px min & i*70px
    element.style.bottom = 50 + (70 * i) + "px";

    // timeout on fait disparaitre, puis on supprime l'element
    let timeout = setTimeout(() => {

      element.classList.add('disappear')
      setTimeout(() => {
        element.parentNode.removeChild(element);
      }, 1500)

    }, 1500 * ((notifDiv.length - i) + 1));

    // -> dans le tableau des timeouts
    timeouts.push(timeout)

    i++;
  })
}

// reset de l'affichage
function redimensionner() {
  timeouts.forEach(timeout => {
    clearTimeout(timeout);
  });
  timeouts = [];

  notifDiv = document.querySelectorAll('.notification');
  console.log(notifDiv)
  affichageElements()
}