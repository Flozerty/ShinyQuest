document.addEventListener('DOMContentLoaded', function () {
  const incrementButtons = document.querySelectorAll('.increment');
  const decrementButtons = document.querySelectorAll('.decrement');
  const increment10Buttons = document.querySelectorAll('.increment10');
  const decrement10Buttons = document.querySelectorAll('.decrement10');
  const compteurInputs = document.querySelectorAll('.compteur-input');

  function updateCounter(url, captureId) {
    fetch(url)
      .then(response => response.json())
      .then(data => {
        verifyToRemoveLoading()
        const compteurElement = document.getElementById('compteur-' + captureId);
        if (compteurElement) {
          compteurElement.value = data.nbRencontres;
        }
      })
  }

  // increment
  incrementButtons.forEach(button => {
    button.addEventListener('click', function () {
      const captureId = this.getAttribute('data-id');
      verifyToStartLoading()
      updateCounter(`/shasse/${captureId}/increment`, captureId);
    });
  });
  // decrement
  decrementButtons.forEach(button => {
    button.addEventListener('click', function () {
      const captureId = this.getAttribute('data-id');
      verifyToStartLoading()
      updateCounter(`/shasse/${captureId}/decrement`, captureId);
    });
  });
  // increment 10
  increment10Buttons.forEach(button => {
    button.addEventListener('click', function () {
      const captureId = this.getAttribute('data-id');
      verifyToStartLoading()
      updateCounter(`/shasse/${captureId}/increment10`, captureId);
    });
  });
  // decrement 10
  decrement10Buttons.forEach(button => {
    button.addEventListener('click', function () {
      const captureId = this.getAttribute('data-id');
      verifyToStartLoading()
      updateCounter(`/shasse/${captureId}/decrement10`, captureId);
    });
  });

  // compteur input change
  compteurInputs.forEach(input => {
    input.addEventListener('change', function () {
      const captureId = this.id.split('-')[1];
      const newValue = this.value;

      const url = `/shasse/${captureId}/updateCounter/${newValue}`;

      fetch(url, { method: 'POST' })
        .then(response => response.json())
        .then(data => {
          verifyToRemoveLoading()

          const compteurElement = document.getElementById('compteur-' + captureId);
          if (compteurElement) {
            compteurElement.value = data.nbRencontres;
          }
        })
    });
  });

  function verifyToRemoveLoading() {

  }

  function verifyToStartLoading() {

  }
});