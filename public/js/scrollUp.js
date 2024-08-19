const scrollUp = () => {
  const scrollUp = document.querySelector('.scroller');

  (this.scrollY <= 500) ? scrollUp.classList.add('hide-scroll') : scrollUp.classList.remove('hide-scroll');
}

window.addEventListener('scroll', scrollUp);