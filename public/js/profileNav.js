const main = document.querySelector("#main-wrapper");
const profileNav = document.querySelector('#profile-nav');
const userContainer = document.querySelector('#header-profile');
const caret = document.querySelector("#profile-caret");

show = false;

displayers = [caret, userContainer];

displayers.forEach(element => {

  element.addEventListener('click', () => {
    if (show) {
      caret.style.transform = "rotate(-90deg)";
      profileNav.style.transform = "translate(150%, 50%) rotate(45deg)";
    } else {
      caret.style.transform = "rotate(0deg)";
      profileNav.style.transform = "translate(0, 100%)";
    }
    show = !show;
  })
});

main.addEventListener("click", () => {
  if (show) {
    caret.style.transform = "rotate(-90deg)";
    profileNav.style.transform = "translate(150%, 50%) rotate(45deg)";
    show = !show;
  }
})
