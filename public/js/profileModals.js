document.addEventListener('DOMContentLoaded', function () {

  const body = document.querySelector('body');

  const editSection = document.querySelector("#edit-account")

  if (editSection) {

    const avatarButton = document.querySelector("#change-avatar-button")
    const pseudoButton = document.querySelector("#change-pseudo-button")

    const avatarDialog = document.querySelector("#avatarDialog")
    const pseudoDialog = document.querySelector("#pseudoDialog")


    avatarButton.addEventListener('click', () => {
      showDialog(avatarDialog)
      openCheck(avatarDialog)
    })

    pseudoButton.addEventListener('click', () => {
      showDialog(pseudoDialog)
      openCheck(pseudoDialog)
    })

    // si on clique sur le backdrop de la modal, on veut fermer la modal
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
  }
})