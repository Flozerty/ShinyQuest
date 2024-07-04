document.addEventListener('DOMContentLoaded', function () {

  const body = document.querySelector('body');
  const cards = document.querySelectorAll('.pokemon-card');

  const dialog = document.querySelector('dialog');
  const dialogContent = dialog.querySelector('#dialog-content');
  const cancelButton = dialog.querySelector(".cancel");

  const IdCaptureInputHidden = dialog.querySelector("#IdCapture")


  
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
})