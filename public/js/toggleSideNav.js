const sideNavContainer = document.querySelector('#main-side-nav'),
  toggleBtn = document.querySelector('#toggle-nav-btn'),
  mainContainer = document.querySelector('#main-wrapper')

let toggle = false;

toggleBtn.addEventListener('click', () => {
  toggle = !toggle;
  verifyToggle();
})

function verifyToggle() {

  if (toggle) {
    toggleBtn.style.transform = "translateX(60%) rotate(180deg)";
    sideNavContainer.classList.add('toggle-nav');
    sideNavContainer.classList.remove('hide-nav');

  } else {
    toggleBtn.style.transform = "translateX(60%)";
    sideNavContainer.classList.add('hide-nav');
    sideNavContainer.classList.remove('toggle-nav');
  }
}