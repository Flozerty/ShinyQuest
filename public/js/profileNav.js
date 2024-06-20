const main = document.querySelector("#main-wrapper");
const profileNav = document.querySelector('#profile-nav');
const userContainer = document.querySelector('#header-profile');
const caret = document.querySelector("#profile-caret")

show = false;

userContainer.addEventListener('click', () => {
  if (show) {
    caret.style.transform = "rotate(0deg)";
    profileNav.style.transform = "translateY(-100%)";
  } else {
    caret.style.transform = "rotate(-90deg)";
    profileNav.style.transform = "translateY(100%)";
  }
  show = !show;
})

main.addEventListener("click", () => {
  if (show) {
    caret.style.transform = "rotate(0deg)";
    profileNav.style.transform = "translateY(-100%)";
    show = !show;
  }
})
