document.addEventListener('DOMContentLoaded', function () {
    const img = document.querySelector('#main-active-pkmn')

    img.addEventListener('click', () => {
        const pokemonId = parseInt(document.querySelector('#previous').getAttribute('data-id')) + 1
        url = `https://raw.githubusercontent.com/PokeAPI/cries/main/cries/pokemon/latest/${pokemonId}.ogg`
        const audio = new Audio(url);
        audio.play();
    })
});
