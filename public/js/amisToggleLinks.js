document.addEventListener('DOMContentLoaded', (event) => {

  const amisLinksContainer = document.querySelectorAll('.menu-burger-ami')

  amisLinksContainer.forEach(container => {
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
})