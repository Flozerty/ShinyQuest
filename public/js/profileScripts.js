document.addEventListener('DOMContentLoaded', function () {

  // modal
  const body = document.querySelector('body'),
    editSection = document.querySelector("#edit-account");

  if (editSection) {

    const avatarButton = document.querySelector("#change-avatar-button"),
      avatarDialog = document.querySelector("#avatarDialog");

    avatarButton.addEventListener('click', () => {
      showDialog(avatarDialog)
      openCheck(avatarDialog)
    })

    // si on clique sur le backdrop de la modal, on veut fermer la modal
    function showDialog(dialog) {
      dialog.showModal();
      const dialogContent = dialog.querySelector('.dialog-content');
      const cancelButton = dialog.querySelector(".fa-circle-xmark");

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

    function openCheck(dialog) {
      if (dialog.open) {
        body.style.filter = "blur(3px)";
      } else {
        body.style.filter = "";
      }
    }
  }

  // avatar preview
  const avatarInput = document.querySelector('#avatar_avatar');
  const avatarImage = document.querySelector('#avatar-image');

  avatarInput.addEventListener('change', function () {
    const file = this.files[0];
    if (file) {
      const reader = new FileReader();

      // lecture du fichier & affichage
      reader.onload = function (e) {
        avatarImage.src = e.target.result;
      };

      reader.readAsDataURL(file);
    }
  });
})