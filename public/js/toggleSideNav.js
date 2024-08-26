const sideNavContainer = document.querySelector('#main-side-nav'),
  toggleBtn = document.querySelector('#toggle-nav-btn'),
  mainContainer = document.querySelector('main'),
  footer = document.querySelector('footer')


let toggleNav = false;

toggleBtn.addEventListener('click', () => {
  toggleNav = !toggleNav;
  verifyToggle();
})

toggleBtn.addEventListener('mouseover', () => {
  if (toggleNav) {
    toggleBtn.style.transform = "translateX(20%) rotate(180deg)";
  } else {
    toggleBtn.style.transform = "translateX(80%)";
  }
})

toggleBtn.addEventListener('mouseout', () => {
  if (toggleNav) {
    toggleBtn.style.transform = "translateX(40%) rotate(180deg)";
  } else {
    toggleBtn.style.transform = "translateX(60%)";
  }
})

function verifyToggle() {
  if (toggleNav) {
    sideNavContainer.classList.add('toggle-nav');
    sideNavContainer.classList.remove('hide-nav');
    mainContainer.classList.add("main-blur")
    footer.classList.add("main-blur")

  } else {
    sideNavContainer.classList.add('hide-nav');
    sideNavContainer.classList.remove('toggle-nav');
    mainContainer.classList.remove("main-blur")
    footer.classList.remove("main-blur")
  }
}

document.addEventListener('click', (event) => {
  if (!sideNavContainer.contains(event.target) && toggleNav) {
    toggleNav = !toggleNav;
    toggleBtn.style.transform = "translateX(60%)";
    verifyToggle();
  }
})