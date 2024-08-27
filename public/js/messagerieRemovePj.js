document.addEventListener('DOMContentLoaded', function () {
  const inputContainer = document.querySelector('#pjContainer')

  if (inputContainer) {
    const delBtn = inputContainer.querySelector('.fa-trash-can')

    delBtn.addEventListener('click', () => {
      inputContainer.remove()
    })
  }
})