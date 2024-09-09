document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.querySelector('#searchPokemons');

    searchInput.addEventListener('input', function () {
        const pokemonCards = document.querySelectorAll('.pokemon'),
            searchText = searchInput.value.toLowerCase();

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