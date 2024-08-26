const fullHeader = document.querySelector('header');
const sideNav = document.querySelector('#main-side-nav');
const toggleNavBtn = document.querySelector('#toggle-nav-btn');

let lastScrollY = window.scrollY;

window.addEventListener('scroll', () => {
  if (window.scrollY > lastScrollY) {
    fullHeader.style.transform = 'translateY(-100%)';
    sideNav.style.top = '0';
    sideNav.style.paddingTop = '80px';
    toggleNavBtn.style.top = 'calc(10% + 80px)';
  } else {
    fullHeader.style.transform = 'translateY(0)';
    sideNav.style.top = '80px';
    sideNav.style.paddingTop = '0';
    toggleNavBtn.style.top = '10%';
  }
  lastScrollY = window.scrollY;
})