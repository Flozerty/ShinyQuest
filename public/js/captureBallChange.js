function changeSelectedBall() {
  const imgBall = document.querySelectorAll('.capture-ball-img')
  let selectedBall = localStorage.getItem('colorSelection')

  imgBall.forEach(img => {

    if (selectedBall === 'pokeball-toggle' || selectedBall === 'pokeball-toggle2') {
      img.src = "https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/items/poke-ball.png"

    } else if (selectedBall === 'superball-toggle' || selectedBall === 'superball-toggle2') {
      img.src = "https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/items/great-ball.png"

    } else if (selectedBall === 'hyperball-toggle' || selectedBall === 'hyperball-toggle2') {
      img.src = "https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/items/ultra-ball.png"
    } else {
      img.src = "https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/items/great-ball.png"
    }
  });
}

changeSelectedBall();