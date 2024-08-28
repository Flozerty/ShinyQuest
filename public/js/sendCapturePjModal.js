document.addEventListener('DOMContentLoaded', function () {

  const
    body = document.querySelector('body'),
    cardLinks = document.querySelectorAll(".fa-share-from-square"),
    sendCaptureDialog = document.querySelector("#sendCaptureDialog"),
    inputId = document.querySelector('#id-capture')

  if (cardLinks) {
    cardLinks.forEach(link => {

      link.addEventListener('click', () => {
        console.log(link.id)
        updateSelectedId(link.id);
        showDialog(sendCaptureDialog)
        openCheck(sendCaptureDialog)
      })
    });
  }

  const form = document.querySelector('#contactForm'),
    select = document.querySelector('#ami');

  select.addEventListener('change', (event) => {
    form.action = "/messagerie/" + event.target.value
  })

  ////////////// FUNCTIONS //////////////

  function showDialog(dialog) {
    dialog.showModal();
    const dialogContent = dialog.querySelector('.dialog-content');
    const cancelButton = dialog.querySelector(".cancelBtn");

    cancelButton.addEventListener("click", () => {

      if (dialog.open) {
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
  }

  // check si le dialog est ouvert
  function openCheck(dialog) {
    if (dialog.open) {
      body.style.filter = "blur(3px)";
    } else {
      body.style.filter = "";
    }
  }

  // met a jour le hidden input contenant l'id de la capture
  function updateSelectedId(id) {
    inputId.value = id;
  }
})