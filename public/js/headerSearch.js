// Ajax recherche
const searchInput = document.querySelectorAll('.search-input'),
  searchResults = document.querySelector('#search-results');
let lastQuery;
let timeout;

searchInput.forEach(inputElement => {
  inputElement.addEventListener('input', () => {
    const query = inputElement.value.toLowerCase();
    // si on a déjà un timeout actif, on le supprime
    clearTimeout(timeout);

    // conditions de réalisation de la requete
    if (query.length <= 1) {
      searchResults.innerHTML = '';
      lastQuery = "";
      return;
    }

    // Si query.length > 2 && user ne tape plus au clavier depuis .5 seconde && query différente de la dernière envoyée
    timeout = setTimeout(() => {
      if (query !== lastQuery) {
        // console.log('requête envoyée pour : ' + query)
        fetch(`/search?q=${query}`)
          .then(response => response.json())
          .then(data => {
            displayResults(data);
            lastQuery = query;
          })
          .catch(error => {
            console.error('Erreur en récupérant les résultats:', error);
          });
      }
    }, 300);
  });


  // fonction de retour des résultats
  function displayResults(data) {
    searchResults.innerHTML = '';

    if (data.pokemons.length > 0) {
      // affichage des pokémons trouvés
      const pkmnList = document.createElement('ul');
      pkmnList.innerHTML = '<li class="search-title">Pokémons</li>';

      data.pokemons.forEach(pokemon => {
        const li = document.createElement('li');
        li.classList.add('search-result');
        li.innerHTML = `<a href='/pokemon/${pokemon.pokedex_id}'>${pokemon.pokedex_id} - ${pokemon.name}</a>`;
        pkmnList.appendChild(li);
      });
      searchResults.appendChild(pkmnList);
    }

    if (data.users.length > 0) {
      // affichage des users trouvés
      const userList = document.createElement('ul');
      userList.innerHTML = '<li class="search-title">Dresseurs</li>';

      data.users.forEach(user => {
        const li = document.createElement('li');
        li.classList.add('search-result');
        li.innerHTML = `<a href='/profile/${user.pseudo}'>${user.pseudo}</a>`;
        userList.appendChild(li);
      });
      searchResults.appendChild(userList);
    }

    // Si on n'a trouvé aucun résultat
    if (data.users.length == 0 && data.pokemons.length == 0) {
      searchResults.innerHTML = "<p>Aucun résultat trouvé</p>";
    }

    // dans tous les cas, on met une croix pour supprimer la recherche
    closeBtn = document.createElement('span');
    closeBtn.innerHTML = "<i class='fa-solid fa-xmark'></i>";
    searchResults.appendChild(closeBtn);

    closeBtn.addEventListener('click', () => {
      searchResults.innerHTML = "";
      inputElement.value = "";
      lastQuery = "";
      clearTimeout(timeout);
    })
  }
})