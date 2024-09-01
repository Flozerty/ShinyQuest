document.addEventListener('DOMContentLoaded', function () {

  const cards = document.querySelectorAll('.pokemon-card')

  cards.forEach(card => {
    const incrementButton = card.querySelector('.increment');
    const decrementButton = card.querySelector('.decrement');
    const increment10Button = card.querySelector('.increment10');
    const decrement10Button = card.querySelector('.decrement10');
    const compteurInput = card.querySelector('.compteur-input');
    const captureId = card.getAttribute('data-id');

    // increment
    incrementButton.addEventListener('click', function () {
      verifyToStartLoading()
      compteurInput.value = parseInt(compteurInput.value) + 1
      updateCounter(`increment`);
    });
    // decrement
    decrementButton.addEventListener('click', function () {
      verifyToStartLoading()
      compteurInput.value = parseInt(compteurInput.value) - 1
      updateCounter(`decrement`);
    });
    // increment 10
    increment10Button.addEventListener('click', function () {
      verifyToStartLoading()
      compteurInput.value = parseInt(compteurInput.value) + 10
      updateCounter(`increment10`);
    });
    // decrement 10
    decrement10Button.addEventListener('click', function () {
      verifyToStartLoading()
      compteurInput.value = parseInt(compteurInput.value) - 10
      updateCounter(`decrement10`);
    });

    function updateCounter() {
      const newValue = compteurInput.value;

      fetch(`/shasse/${captureId}/updateCounter/${newValue}`)
        .then(response => response.json())
        .then(data => {
          console.log(data.nbRencontres)
          verifyToRemoveLoading()
          // console.log(data.nbRencontres)
        })
    }

    // compteur input change
    compteurInput.addEventListener('change', function () {
      updateCounter()
    })
  });

  function verifyToRemoveLoading() {

  }

  function verifyToStartLoading() {

  }
});