document.addEventListener('DOMContentLoaded', function () {
    const previousButton = document.querySelector('#previous');
    const nextButton = document.querySelector('#next');

    previousButton.addEventListener('click', function (e) {
        const pokemonId = parseInt(previousButton.getAttribute('data-id'), 10);
        changePokemon(pokemonId);
    });

    nextButton.addEventListener('click', function (e) {
        const pokemonId = parseInt(nextButton.getAttribute('data-id'), 10);
        changePokemon(pokemonId);
    });

    function changePokemon(pokemonId) {
        if (pokemonId > 0) {
            fetch(`/api/pokemonDetailsChange/${pokemonId}`)
                .then(response => response.json())
                .then(data => {
                    updatePokemonDetails(data);
                })
                .catch(error => console.error('Erreur pendant la récupération:', error));
        }
    }

    // Update la page avec le nouveau Pokémon
    function updatePokemonDetails(dataReceived) {

        // updates basics
        document.querySelector('h1').textContent = `${dataReceived.data.name} - #${dataReceived.pokemon.pkmnStats.id.toString().padStart(3, '0')}`;
        document.getElementById('main-active-pkmn').dataset.id = dataReceived.currentPokemonId;

        // update boutons
        previousButton.setAttribute('data-id', dataReceived.currentPokemonId - 1);
        nextButton.setAttribute('data-id', dataReceived.currentPokemonId + 1);

        // update img principale
        document.querySelector('#main-active-pkmn .sprite-normal').src = dataReceived.pokemon.pkmnStats.sprites.other['official-artwork'].front_default;
        document.querySelector('#main-active-pkmn .sprite-shiny').src = dataReceived.pokemon.pkmnStats.sprites.other['official-artwork'].front_shiny;

        // update description
        let description = '';
        dataReceived.pokemon.pkmnSpec.flavor_text_entries.forEach(entry => {
            if (entry.language.name === "fr") {
                description = entry.flavor_text;
            }
        });
        document.querySelector('#pokemon-description').textContent = description;

        // update types

        // update formes
        // Update formes (varieties)
        const varietiesSection = document.getElementById('varieties');
        varietiesSection.innerHTML = ''; // On vide le contenu actuel

        // Vérifie s'il y a plusieurs variétés
        if (dataReceived.data.pokemonVarieties.length > 1) {
            dataReceived.data.pokemonVarieties.forEach(pokemonV => {
                if (pokemonV.pkmnStats.id !== dataReceived.pokemon.pkmnStats.id) {
                    // Crée un élément de lien pour chaque variété
                    const link = document.createElement('a');
                    link.href = `/pokemon/${pokemonV.pkmnStats.id}`; // Met à jour l'URL en conséquence

                    // Crée un élément figure pour la variété
                    const figure = document.createElement('figure');
                    figure.className = 'non-active-pkmn';

                    // Image normale
                    const imgNormal = document.createElement('img');
                    imgNormal.className = 'sprite-normal';
                    imgNormal.src = pokemonV.pkmnStats.sprites.other['official-artwork'].front_default;
                    imgNormal.alt = pokemonV.pkmnStats.name;
                    figure.appendChild(imgNormal);

                    // Image shiny
                    const imgShiny = document.createElement('img');
                    imgShiny.className = 'sprite-shiny';
                    imgShiny.src = pokemonV.pkmnStats.sprites.other['official-artwork'].front_shiny;
                    imgShiny.alt = `${pokemonV.pkmnStats.name} shiny`;
                    imgShiny.loading = 'lazy';
                    figure.appendChild(imgShiny);

                    // Nom du Pokémon
                    const figcaption = document.createElement('figcaption');
                    figcaption.textContent = pokemonV.pkmnStats.name;
                    figure.appendChild(figcaption);

                    link.appendChild(figure);
                    varietiesSection.appendChild(link);
                }
            });
        }

        // update chaine d'évolution



        // update stats

        // update "other-details"

        // update infos de shasse

    }
});
