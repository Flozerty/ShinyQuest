document.addEventListener('DOMContentLoaded', function () {

  const body = document.querySelector('body');

  const delAmiBtn = document.querySelectorAll(".delete-ami-button"),
    delAmiDialog = document.querySelector("#delete-ami-modal");

  delAmiBtn.forEach(button => {
    const id = button.id.split('-')[1];

    button.addEventListener('click', () => {
      // {{ path('delete_ami', {'id': data.id}) }}
      showDialog(delAmiDialog, id)
      openCheck(delAmiDialog)
    })
  })

  // si on clique sur le backdrop de la modal, on veut fermer la modal
  function showDialog(dialog, id) {
    dialog.showModal();
    const dialogContent = dialog.querySelector('.dialog-content'),
      cancelButton = dialog.querySelector(".cancelBtn"),
      validateLink = dialog.querySelector(".delete-link");

    validateLink.href = `/amis/${id}/delete`

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
})