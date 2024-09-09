document.addEventListener('DOMContentLoaded', function () {

  //////////////////// modal capture ////////////////////
  const body = document.querySelector('body'),
    cards = document.querySelectorAll('.pokemon-card'),
    dialog = document.querySelector('dialog'),
    dialogContent = dialog.querySelector('.dialog-content'),
    cancelButton = dialog.querySelector(".cancelBtn"),
    IdCaptureInputHidden = dialog.querySelector("#IdCapture")

  cards.forEach(card => {
    const buttonFind = card.querySelector(".shasse-trouve");

    buttonFind.addEventListener('click', () => {
      // changer la value du hidden Input de la modal (idCapture)
      const captureId = card.querySelector(".hidden").textContent;
      IdCaptureInputHidden.value = captureId;

      // afficher le modal
      dialog.showModal();
      openCheck(dialog)
    })
  })

  cancelButton.addEventListener("click", () => {

    if (dialog?.open) {
      dialog.close();
      openCheck(dialog);
    }
  });

  // si on clique sur le backdrop de la modal, on veut fermer la modal
  document.addEventListener('click', (event) => {
    if (!dialogContent.contains(event.target) && dialog.contains(event.target)) {
      dialog.close();
      openCheck(dialog);
    }
  });

  // check si le dialog est ouvert
  function openCheck(dialog) {
    if (dialog.open) {
      body.style.filter = "blur(3px)";
    } else {
      body.style.filter = "";
    }
  }

  /////////////// Other shasse toggle links ///////////////
  const linksContainer = document.querySelectorAll('.menu-burger-shasse')

  linksContainer.forEach(container => {
    const displayButton = container.querySelector('.toggle-menu'),
      linksDiv = container.querySelector('ul');

    let toggle = false;

    displayButton.addEventListener('click', () => {
      toggle = !toggle;
      verifyToggle();
    })

    document.addEventListener('click', (event) => {
      if (toggle && !linksDiv.contains(event.target) && !displayButton.contains(event.target)) {
        toggle = !toggle;
        verifyToggle();
      }
    })

    verifyToggle()

    function verifyToggle() {
      if (toggle) {
        linksDiv.classList.remove('hide')
      } else {
        linksDiv.classList.add('hide')
      }
    }
  })

  ////////////////// catch pokemon song //////////////////
  const flashMessages = document.querySelectorAll('.flash-message');
  if (flashMessages.length) {

    const audio = new Audio('/sfx/catch-pokemon.mp3');
    audio.volume = 0.2;
    audio.play();
  }
});