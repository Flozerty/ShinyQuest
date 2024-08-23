document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.querySelector('#searchPokemons');
    const pokedexContent = document.querySelector('#pokedex-content');
    const pokemonCards = pokedexContent.querySelectorAll('.pokemon');

    searchInput.addEventListener('input', function () {
        const searchText = searchInput.value.toLowerCase();

        pokemonCards.forEach(card => {
            const pokemonName = card.querySelector('.pokemon-name').textContent.toLowerCase();

            if (pokemonName.includes(searchText)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        })
    });
});