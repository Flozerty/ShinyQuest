document.addEventListener('DOMContentLoaded', function () {
  const inputContainer = document.querySelector('#pjContainer')

  // remove PJ
  if (inputContainer) {
    const delBtn = inputContainer.querySelector('.fa-trash-can')
    delBtn.addEventListener('click', () => {
      inputContainer.remove()
    })
  }

  // initailisation de la page : scroll to bot
  scrollToBot();

  function scrollToBot() {
    const footer = document.querySelector('footer');
    if (footer) {
      const footerTop = footer.getBoundingClientRect().top + window.scrollY; //+ window.scrollY pour quand on refresh la page dans la conv

      window.scrollTo({
        top: footerTop - window.innerHeight
      });
    }
  }
});