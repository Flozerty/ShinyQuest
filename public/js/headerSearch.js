$(document).ready(function () {

    const searchInput = document.getElementById('search-input');
    const searchResults = document.getElementById('search-results');

    searchInput.addEventListener('input', () => {
        const query = searchInput.value;

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
        console.log(data)

        searchResults.innerHTML = '';

        if (data.pokemons.length > 0) {
            const pokemonList = document.createElement('ul');
            pokemonList.innerHTML = '<li class="search-title">Pokémons</li>';

            data.pokemons.forEach(pokemon => {
                const li = document.createElement('li');
                li.classList.add('search-result')
                li.innerHTML = pokemon.pokedex_id + " - " + pokemon.name;
                pokemonList.appendChild(li);
            });
            searchResults.appendChild(pokemonList);
        }

        if (data.users.length > 0) {
            const userList = document.createElement('ul');
            userList.innerHTML = '<li class="search-title">Dresseurs</li>';

            data.users.forEach(user => {
                console.log(user)
                console.log(user.pseudo)
                const li = document.createElement('li');
                li.classList.add('search-result')
                li.textContent = user.pseudo;
                userList.appendChild(li);
            });
            searchResults.appendChild(userList);
        }
    }
});