document.addEventListener('DOMContentLoaded', function () {
  const img = document.querySelector('#main-active-pkmn');
  let audio;

  img.addEventListener('click', () => {
    if (!(audio && !audio.paused)) {
      const pokemonId = parseInt(document.querySelector('#previous').getAttribute('data-id')) + 1;
      const url = `https://raw.githubusercontent.com/PokeAPI/cries/main/cries/pokemon/latest/${pokemonId}.ogg`;

      audio = new Audio(url);
      audio.play();
    }
  });
});