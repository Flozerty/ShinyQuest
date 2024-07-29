const main = document.querySelector("#main-wrapper");
const profileNav = document.querySelector('#profile-nav');
const userContainer = document.querySelector('#header-profile');
const caret = document.querySelector("#profile-caret");
const header = document.querySelector("header");

show = false;
displayers = [caret, userContainer];

// montrer / cacher la profile-nav 
displayers.forEach(element => {
  element.addEventListener('click', () => {
    if (show) {
      hideProfileNav()
    } else {
      showProfileNav()
    }
  })
  element.addEventListener('mouseenter', () => {
    if (!show) {
      showProfileNav()
    }
  });
});

// retire la nav quand on clique ailleurs
main.addEventListener("click", () => {
  if (show) {
    hideProfileNav()
  }
})

// retire la nav quand on clique sur le header (sauf sur les displayers)
header.addEventListener("click", (event) => {
  if (show && !caret.contains(event.target) && !userContainer.contains(event.target)) {
    hideProfileNav()
  }
})

function showProfileNav() {
  caret.style.transform = "rotate(0deg)";
  profileNav.style.transform = "translate(0, 100%)";
  show = true;
}

function hideProfileNav() {
  caret.style.transform = "rotate(-90deg)";
  profileNav.style.transform = "translate(150%, 50%) rotate(45deg)";
  show = false;
}