document.addEventListener('DOMContentLoaded', function () {
  const radios = document.querySelectorAll('input[name="generation"]'),
    isShinyDex = document.querySelector('#dex-completion') ? true : false;

  radios.forEach(radio => {
    radio.addEventListener('change', function () {
      const generationId = this.value;
      const pokedexContent = document.getElementById('pokedex-content');
      pokedexContent.innerHTML = "";

      // shinydex
      if (isShinyDex) {
        user = "Flozerty";

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