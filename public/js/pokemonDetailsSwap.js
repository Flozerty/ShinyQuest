document.addEventListener('DOMContentLoaded', function () {
  const previousButton = document.querySelector('#previous');
  const nextButton = document.querySelector('#next');

  previousButton.addEventListener('click', function (e) {
    const pokemonId = parseInt(previousButton.getAttribute('data-id'));
    changePokemon(pokemonId);
  });

  nextButton.addEventListener('click', function (e) {
    const pokemonId = parseInt(nextButton.getAttribute('data-id'));
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

    console.log(dataReceived);

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
    const typesSection = document.querySelector('#types');
    typesSection.innerHTML = "";
    dataReceived.data.types.forEach(type => {
      img = document.createElement('img');
      img.src = type.img;
      img.alt = "type " + type.name;
      typesSection.appendChild(img);
    })

    // Update formes
    const varietiesSection = document.querySelector('#varieties');
    varietiesSection.innerHTML = '';

    // Vérifie s'il y a plusieurs variétés
    if (dataReceived.data.pokemonVarieties.length > 1) {
      dataReceived.data.pokemonVarieties.forEach(pokemonV => {
        if (pokemonV.pkmnStats.id !== dataReceived.pokemon.pkmnStats.id) {

          // <a><figure><img>*2<figcaption><///////>

          const link = document.createElement('a');
          link.href = `/pokemon/${pokemonV.pkmnStats.id}`;

          const figure = document.createElement('figure');
          figure.className = 'non-active-pkmn';

          const imgNormal = document.createElement('img');
          imgNormal.className = 'sprite-normal';
          imgNormal.src = pokemonV.pkmnStats.sprites.other['official-artwork'].front_default;
          imgNormal.alt = pokemonV.pkmnStats.name;
          figure.appendChild(imgNormal);

          const imgShiny = document.createElement('img');
          imgShiny.className = 'sprite-shiny';
          imgShiny.src = pokemonV.pkmnStats.sprites.other['official-artwork'].front_shiny;
          imgShiny.alt = `${pokemonV.pkmnStats.name} shiny`;
          imgShiny.loading = 'lazy';
          figure.appendChild(imgShiny);

          const figcaption = document.createElement('figcaption');
          figcaption.textContent = pokemonV.pkmnStats.name;
          figure.appendChild(figcaption);

          link.appendChild(figure);
          varietiesSection.appendChild(link);
        }
      });
    }
    varietiesTitle = document.querySelector('#varieties-title');
    // update variétés visible ou non
    if (varietiesSection.children.length > 0) {
      varietiesSection.classList.remove('hidden')
      varietiesTitle.classList.remove('hidden')
    } else {
      varietiesSection.classList.add('hidden')
      varietiesTitle.classList.add('hidden')
    }

    // update chaine d'évolution



    // update stats



    // update "other-details"
    document.querySelector('#height-number').innerText = dataReceived.pokemon.pkmnStats.height
    document.querySelector('#weight-number').innerText = dataReceived.pokemon.pkmnStats.weight

    const abilitiesContainer = document.querySelector('#abilities')
    abilitiesContainer.innerHTML = "";
    dataReceived.data.abilities.forEach(ability => {

      ability.names.forEach(lang => {
        if (lang.language.name == "fr") {
          const li = document.createElement('li');
          li.innerHTML = `${lang.name} <span class="english-name">(${ability.name})</span>`;
          abilitiesContainer.appendChild(li);
        }
      });
    });

    // update infos de shasse
    const textContainer = document.querySelector('#text-wrapper');
    textContainer.innerHTML = "";

    if (dataReceived.captures.length > 0) {
      // infosCapture
      const pCapture = document.createElement('p');
      pCapture.innerHTML = `${dataReceived.data.name} a été capturé <strong>${dataReceived.captures.length}</strong> fois par la communauté, dont
            <strong>${dataReceived.capturesByLieu[0].nb_captures}</strong> fois sur <strong>${dataReceived.capturesByLieu[0].jeu}</strong>!`

      textContainer.appendChild(pCapture)

      // bestSpot
      const pBestSpot = document.createElement('p');
      pBestSpot.innerHTML = "Le meilleur spot : "

      if (dataReceived.bestSpots.length > 0 && dataReceived.bestSpots[0] != null) {
        // si 1er spot pas null
        pBestSpot.innerHTML += `<strong>${dataReceived.bestSpots[0].lieu}</strong>, capturé <strong>${dataReceived.bestSpots[0].nb_captures}</strong> fois, avec une
            moyenne de <strong>${Math.round(dataReceived.bestSpots[0].moyenne_rencontres)}</strong> rencontres.`
      } else if (dataReceived.bestSpots.length > 1) {
        // si 1er null mais qu'il y a un 2e spot
        pBestSpot.innerHTML += `<strong>${dataReceived.bestSpots[1].lieu}</strong>, capturé <strong>${dataReceived.bestSpots[0].nb_captures}</strong> fois, avec une
            moyenne de <strong>${Math.round(dataReceived.bestSpots[0].moyenne_rencontres)}</strong> rencontres.`
      } else {
        // si seulement null
        pBestSpot.innerHTML += "aucun lieu enregistré dans ce jeu."
      }
      textContainer.appendChild(pBestSpot)

    } else {
      // pas de capture
      pNoCapture = document.createElement('p');
      pNoCapture.innerHTML = `${dataReceived.data.name} n'a encore jamais été capturé par la communauté!`
      textContainer.appendChild(pNoCapture)
    }
  }
});
