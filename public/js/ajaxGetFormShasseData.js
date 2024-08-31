document.addEventListener('DOMContentLoaded', function () {
  const pokemonsContainer = document.getElementById('pokemonId'),
    gamesContainer = document.getElementById('jeu'),
    ballSelect = document.getElementById('ball');

  // loading containers
  const compteurLoading = "",
    jeuLoading = document.querySelector('.loading.jeu-loading'),
    nomPokemonLoading = document.querySelector('.loading.nom-pokemon-loading'),
    ballLoading = document.querySelector('.loading.ball-loading');

  /////////////////// récupération des pokemons ///////////////////
  fetch('/api/pokemons')
    .then(response => response.json())
    .then(data => {
      nomPokemonLoading.remove();

      data.forEach(pokemon => {
        const element = document.createElement('option');
        // <option value="{{ pokemon.pokedex_id }}">{{ pokemon.pokedex_id }} - {{ pokemon.name }}</option>
        element.value = pokemon.pokedex_id;
        element.innerHTML = pokemon.pokedex_id + " - " + pokemon.name;
        pokemonsContainer.appendChild(element);
      });
    })

  ///////////////////// récupération des jeux /////////////////////
  fetch('/api/games')
    .then(response => response.json())
    .then(data => {
      jeuLoading.remove();

      data.forEach(game => {
        const element = document.createElement('option');
        element.value = game;
        element.innerHTML = game;
        gamesContainer.appendChild(element);
      });
    })

  ///////////////////// récupération des balls /////////////////////
  fetch('/api/balls')
    .then(response => response.json())
    .then(data => {
      ballLoading.remove();

      data.forEach(group => {
        if (group && group.ballsData) {
          const optgroup = document.createElement('optgroup');
          optgroup.label = group.categoryName;

          group.ballsData.forEach(ball => {
            // gérer la condition si null
            if (ball && ball.spriteName && ball.name) {
              const option = document.createElement('option');
              option.value = ball.spriteName;
              option.textContent = ball.name;
              optgroup.appendChild(option);
            }
          });

          ballSelect.appendChild(optgroup);
        }
      });
    })
});
