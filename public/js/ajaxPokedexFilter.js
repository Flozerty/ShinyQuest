document.addEventListener('DOMContentLoaded', function () {
  const radios = document.querySelectorAll('input[name="generation"]');


  radios.forEach(radio => {
    radio.addEventListener('change', function () {
      const generationId = this.value,
        title = document.querySelector('h1').innerText,
        pokedexContent = document.getElementById('pokedex-content');

      pokedexContent.innerHTML = "";

      // récupération du pseudo User dans le h1
      const user = title.split('Shinydex de ')[1]
      console.log(user)

      // shinydex
      if (user) {
        if (generationId == "all") {
          fetch(`/${user}/shinydex/all`)
            .then(response => response.json())
            .then(data => {
              pokedexContent.innerHTML = data.html;
            })
        } else {
          fetch(`/${user}/shinydex/generation/${generationId}`)
            .then(response => response.json())
            .then(data => {
              pokedexContent.innerHTML = data.html;
            })
        }

        // pokedex
      } else {
        if (generationId == "all") {
          fetch(`/pokedex/all`)
            .then(response => response.json())
            .then(data => {
              pokedexContent.innerHTML = data.html;
            })
        } else {
          fetch(`/pokedex/generation/${generationId}`)
            .then(response => response.json())
            .then(data => {
              pokedexContent.innerHTML = data.html;
            })
        }
      }
    });
  })
})