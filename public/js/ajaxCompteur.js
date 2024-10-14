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

    // maj, utiliser obj = {
    //   "clé1": "valeur1",
    //   "clé2": "valeur2"
    // };

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
      if (parseInt(compteurInput.value) + value >= 0) {
        compteurInput.value = parseInt(compteurInput.value) + value;
      } else {
        compteurInput.value = 0;
      }
      verifyToUpdate();
    }

    // compteur input change
    compteurInput.addEventListener('change', () => {
      if (parseInt(compteurInput.value) <= 0) {
        compteurInput.value = 0;
      }
      verifyToUpdate()
    })

    function verifyToUpdate() {
      if (timeout) {
        clearTimeout(timeout);
      }
      // timeout before update
      timeout = setTimeout(() => {
        updateCounter()
      }, 500);

      // si pas de spinner alors on en créait un
      spinner = formCompteur.querySelector('.input-loading');
      if (!spinner) {
        const i = document.createElement('i')
        i.className = "fa-brands fa-cloudscale loading input-loading";
        formCompteur.appendChild(i)
      }
    }

    function updateCounter() {
      const newValue = compteurInput.value;

      fetch(`/shasse/${captureId}/updateCounter/${newValue}`)
        .then(response => response.json())
        .then(data => {
          formCompteur.querySelector('.input-loading').remove()
          // compteurInput.value = data.nbRencontres;
          console.log("compteur updated : " + data.nbRencontres)
        })
    }
  });
});