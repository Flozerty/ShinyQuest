document.addEventListener('DOMContentLoaded', function () {
  const previousButton = document.querySelector('#previous'),
    nextButton = document.querySelector('#next'),
    body = document.querySelector('body');

  // previous pokemon
  previousButton.addEventListener('click', () => {
    const pokemonId = parseInt(previousButton.getAttribute('data-id'));
    if (pokemonId > 0) {
      slideRight();
      changePokemon(pokemonId);
    }
  });

  // next pokemon
  nextButton.addEventListener('click', () => {
    const pokemonId = parseInt(nextButton.getAttribute('data-id'));
    slideLeft();
    changePokemon(pokemonId);
  });

  // request
  function changePokemon(pokemonId) {
    if (pokemonId > 0) {
      showLoading();
      previousButton.disabled = true;
      nextButton.disabled = true;

      fetch(`/api/pokemonDetailsChange/${pokemonId}`)
        .then(response => response.json())
        .then(data => {
          updatePokemonDetails(data);
          hideLoading();
          recoverSlide();
          previousButton.disabled = false;
          nextButton.disabled = false;
        })
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
    const evolutionSection = document.querySelector('#evolution');
    evolutionSection.innerHTML = ''; // Vide le contenu actuel

    dataReceived.data.evolutionChain.forEach(pokemon => {
      const evolutionElement = renderEvolutionChain(pokemon, dataReceived.currentPokemonId);
      evolutionSection.appendChild(evolutionElement);
    });

    // update stats
    const statsContainer = document.querySelector('#stats-container');
    statsContainer.innerHTML = '';

    dataReceived.data.stats.forEach(stat => {
      const li = document.createElement('li');

      // recherche du nom français
      let statName = '';
      stat.details_stat.names.forEach(lang => {
        if (lang.language.name === "fr") {
          statName = lang.name;
        }
      });

      li.className = 'stat';
      li.innerHTML = `<span class="stat-name">${statName} : </span>
        <span class="stat-amount">${stat.base_stat}</span>
        <div class="stat-completion" title="${stat.base_stat}/220">
          <div class="completion-bar" style="width: ${(stat.base_stat / 220) * 100}%;"></div>
        </div>`;
      statsContainer.appendChild(li);
    });

    // update "other-details"
    document.querySelector('#height-number').innerText = dataReceived.pokemon.pkmnStats.height / 10;
    document.querySelector('#weight-number').innerText = dataReceived.pokemon.pkmnStats.weight / 10;
    document.querySelector('#capture-rate-number').innerText = dataReceived.pokemon.pkmnSpec.capture_rate;


    // update "ablities"
    const abilitiesContainer = document.querySelector('#abilities')
    abilitiesContainer.innerHTML = "";
    dataReceived.data.abilities.forEach(ability => {
      const li = document.createElement('li');

      // nom
      let strong = document.createElement('strong');
      ability.names.forEach(lang => {
        if (lang.language.name == "fr") {
          strong.innerHTML = `${lang.name}<span class="english-name">(${ability.name})</span>`;
        }
      });
      li.appendChild(strong);

      // description
      let description = document.createElement('span');
      ability.flavor_text_entries.forEach(text => {
        if (text.language.name == "fr") {
          description.innerHTML = " : " + text.flavor_text;
        }
      });
      li.appendChild(description);

      abilitiesContainer.appendChild(li);
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

  ////////////////////////////////////////////////////////////////////////
  //////////// Fonction de création de la chaine d'évolution /////////////
  ////////////////////////////////////////////////////////////////////////
  function renderEvolutionChain(pokemon, currentPokemonId) {

    let pokemonName = '';
    pokemon.pokemon.pkmnSpec.names.forEach(lang => {
      if (lang.language.name === 'fr') {
        pokemonName = lang.name;
      }
    });

    const evolutionContainer = document.createElement('div');
    evolutionContainer.className = 'evolution-details';

    // Créer le contenu du pokemon d'origine de l'évolution
    const pokemonContainer = document.createElement('a');
    pokemonContainer.href = `/pokemon/${pokemon.pokemon.pkmnStats.id}`;
    pokemonContainer.innerHTML =
      `<figure class="${pokemon.pokemon.pkmnStats.id === currentPokemonId ? 'active-pkmn' : 'non-active-pkmn'}">
          <img class="sprite-normal" src="${pokemon.pokemon.pkmnStats.sprites.other['official-artwork'].front_default}" alt="${pokemon.pokemon.pkmnSpec.name}">
          <img class="sprite-shiny" src="${pokemon.pokemon.pkmnStats.sprites.other['official-artwork'].front_shiny}" alt="${pokemon.pokemon.pkmnSpec.name} shiny" loading="lazy">
          <figcaption>
            <p>${pokemonName}</p>
          </figcaption>
        </figure>`;
    evolutionContainer.appendChild(pokemonContainer);

    // séparateur si le Pokémon a des enfants (évolutions)
    if (pokemon.children && pokemon.children.length > 0) {
      const separator = document.createElement('i');
      separator.className = 'fa-solid fa-down-long separator';
      evolutionContainer.appendChild(separator);
    }

    // Si le Pokémon a des évolutions, on boucle pour les ajouter
    if (pokemon.children && pokemon.children.length > 0) {
      const evolutionChildrenDiv = document.createElement('div');
      evolutionChildrenDiv.className = 'evolution-children';

      pokemon.children.forEach(child => {
        evolutionChildrenDiv.appendChild(renderEvolutionChain(child, currentPokemonId));
      });

      evolutionContainer.appendChild(evolutionChildrenDiv);
    }
    return evolutionContainer;
  }

  /////////////////////////////////////////////////////////////////
  //////////////////////// SWIPE & LOADING ////////////////////////
  /////////////////////////////////////////////////////////////////
  function showLoading() {
    const nav = document.querySelector('#navigation'),
      spinner = document.createElement('i');
    spinner.className = "fa-brands fa-cloudscale loading";
    nav.appendChild(spinner)

    body.classList.add("wait")
  }

  function hideLoading() {
    const spinners = document.querySelectorAll('.loading')
    spinners.forEach(spinner => {
      spinner.remove()

      body.classList.remove("wait")
    })
  }

  function slideLeft() {
    const figMain = document.querySelector('#main-active-pkmn');
    figMain.classList.add('slideLeft');
    setTimeout(() => {
      figMain.classList.remove('slideLeft');
      figMain.classList.add('slideRight');
    }, 500);
  }

  function slideRight() {
    const figMain = document.querySelector('#main-active-pkmn');
    figMain.classList.add('slideRight');
    setTimeout(() => {
      figMain.classList.remove('slideRight');
      figMain.classList.add('slideLeft');
    }, 500);
  }

  function recoverSlide() {
    const figMain = document.querySelector('#main-active-pkmn');
    setTimeout(() => {
      figMain.classList.remove('slideRight');
      figMain.classList.remove('slideLeft');
    }, 200);
  }
});