document.addEventListener('DOMContentLoaded', function () {
  const body = document.querySelector('body');

  // delete ami
  const delAmiBtn = document.querySelectorAll(".delete-ami-button"),
    delAmiDialog = document.querySelector("#delete-ami-modal");

  if (delAmiBtn) {
    delAmiBtn.forEach(button => {
      const id = button.id.split('-')[1];

      button.addEventListener('click', () => {
        showDialog(delAmiDialog, "amis", id)
        openCheck(delAmiDialog)
      })
    })
  }

  // delete capture
  const delCaptureBtn = document.querySelectorAll(".delete-capture-button"),
    delCaptureDialog = document.querySelector("#delete-capture-modal");

  if (delCaptureBtn) {
    delCaptureBtn.forEach(button => {
      const id = button.id.split('-')[1];

      button.addEventListener('click', () => {
        showDialog(delCaptureDialog, "capture", id)
        openCheck(delCaptureDialog)
      })
    })
  }

  // delete shasse
  const delShasseBtn = document.querySelectorAll(".delete-shasse-button"),
    delShasseDialog = document.querySelector("#delete-shasse-modal");

  if (delShasseBtn) {
    delShasseBtn.forEach(button => {
      const id = button.id.split('-')[1];

      button.addEventListener('click', () => {
        showDialog(delShasseDialog, "shasse", id)
        openCheck(delShasseDialog)
      })
    })
  }

  // si on clique sur le backdrop de la modal, on veut fermer la modal
  function showDialog(dialog, type, id) {
    dialog.showModal();
    const dialogContent = dialog.querySelector('.dialog-content'),
      cancelButton = dialog.querySelector(".cancelBtn");

    const validateLink = dialog.querySelector(".delete-link");
    validateLink.href = `/${type}/${id}/delete`

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