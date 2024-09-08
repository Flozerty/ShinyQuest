document.addEventListener('DOMContentLoaded', function () {
  const radios = document.querySelectorAll('input[name="generation"]');

  radios.forEach(radio => {
    radio.addEventListener('change', function () {
      const generationId = this.value;
      const pokedexContent = document.getElementById('pokedex-content');
      pokedexContent.innerHTML = ""

      fetch(`/api/generation/${generationId}`)
        .then(response => response.json())
        .then(data => {
          pokedexContent.innerHTML = data.html;  // Insérer directement le HTML
        })
        .catch(error => console.error('Erreur lors de la récupération des pokémons:', error));
    });
  })
})