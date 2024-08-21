document.addEventListener('DOMContentLoaded', function () {
    const pokemonsContainer = document.getElementById('pokemonId');
    const gamesContainer = document.getElementById('shasse_start_jeu');

    fetch('/api/pokemons')
        .then(response => response.json())
        .then(data => {
            data.forEach(pokemon => {
                const element = document.createElement('option');
                // <option value="{{ pokemon.pokedex_id }}">{{ pokemon.pokedex_id }} - {{ pokemon.name }}</option>
                element.value = pokemon.pokedex_id;
                element.innerHTML = pokemon.pokedex_id + " - " + pokemon.name;
                pokemonsContainer.appendChild(element);
            });
        })
        .catch(error => console.error('Erreur lors du chargement des pokemons:', error));

    fetch('/api/games')
        .then(response => response.json())
        .then(data => {
            data.forEach(game => {
                const element = document.createElement('option');
                element.value = game;
                element.innerHTML = game;
                gamesContainer.appendChild(element);
            });
        })
        .catch(error => console.error('Erreur lors du chargement des jeux:', error));
});
