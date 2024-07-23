$(document).ready(function () {

    const searchInput = document.getElementById('search-input');
    const searchResults = document.getElementById('search-results');

    searchInput.addEventListener('input', () => {
        const query = searchInput.value;

        // conditions de réalisation de la requete
        if (query.length > 2) {

            fetch(`/search?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    displayResults(data);
                });
        } else {
            searchResults.innerHTML = '';
        }
    });

    function displayResults(data) {
        // console.log(data)
        searchResults.innerHTML = '';

        if (data.pokemons.length > 0) {
            const pkmnList = document.createElement('ul');
            pkmnList.innerHTML = '<li class="search-title">Pokémons</li>';

            data.pokemons.forEach(pokemon => {
                const li = document.createElement('li');
                li.classList.add('search-result')
                li.innerHTML = "<a href='/pokemon/" + pokemon.pokedex_id + "'>" + pokemon.pokedex_id + " - " + pokemon.name + "</a>";
                pkmnList.appendChild(li);
            });
            searchResults.appendChild(pkmnList);
        }

        if (data.users.length > 0) {
            const userList = document.createElement('ul');
            userList.innerHTML = '<li class="search-title">Dresseurs</li>';

            data.users.forEach(user => {
                const li = document.createElement('li');
                li.classList.add('search-result')
                li.innerHTML = "<a href='/users/" + user.id + "'>" + user.pseudo + "</a>";
                userList.appendChild(li);
            });
            searchResults.appendChild(userList);
        }

        if (data.users.length == 0 && data.pokemons.length == 0) {
            searchResults.innerHTML = "<p>Aucun résultat trouvé</p>";
        }
    }
});