document.addEventListener('DOMContentLoaded', function () {

  const cards = document.querySelectorAll('.pokemon-card')

  cards.forEach(card => {
    const incrementButton = card.querySelector('.increment'),
      decrementButton = card.querySelector('.decrement'),
      increment10Button = card.querySelector('.increment10'),
      decrement10Button = card.querySelector('.decrement10');

    const formCompteur = card.querySelector('.form-like-compteur'),
      compteurInput = card.querySelector('.compteur-input');

    const captureId = card.getAttribute('data-id');
    let timeout;

    incrementButton.addEventListener('click', () => {
      changeCounter(1)
    });
    decrementButton.addEventListener('click', () => {
      changeCounter(-1)
    });
    increment10Button.addEventListener('click', () => {
      changeCounter(10)
    });
    decrement10Button.addEventListener('click', () => {
      changeCounter(-10)
    });

    // change la valeur de l'input
    function changeCounter(value) {
      compteurInput.value = parseInt(compteurInput.value) + value;
      verifyToUpdate();
    }

    // compteur input change
    compteurInput.addEventListener('change', () => {
      verifyToUpdate()
    })

    function updateCounter() {
      const newValue = compteurInput.value;

      fetch(`/shasse/${captureId}/updateCounter/${newValue}`)
        .then(response => response.json())
        .then(data => {
          formCompteur.querySelector('.input-loading').remove()
          console.log(data.nbRencontres)
        })
    }

    function verifyToUpdate() {
      if (timeout) {
        clearTimeout(timeout);
        spinner = formCompteur.querySelector('.input-loading');
        spinner ? spinner.remove() : null;
      }

      const i = document.createElement('i')
      i.className = "fa-brands fa-cloudscale loading input-loading";
      formCompteur.appendChild(i)

      timeout = setTimeout(() => {
        updateCounter()
      }, 500);
    }
  });
});