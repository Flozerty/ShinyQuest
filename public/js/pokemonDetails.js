document.addEventListener('DOMContentLoaded', () => {

    // Stats

    function checkVisibility() {
        // on vérifie les stats en cas de changement (ajax)
        const statsComp = document.querySelectorAll('.stat-completion');

        statsComp.forEach((element) => {
            const rect = element.getBoundingClientRect();
            const windowHeight = window.innerHeight || document.documentElement.clientHeight;

            // Si l'image est visible
            if (rect.top <= windowHeight) {
                displayStat(element);
            } else {
                const completionBar = element.querySelector('.completion-bar');
                completionBar.style.width = 0;
                completionBar.style.transition = "0s";
            }
        })
    }

    function displayStat(comp) {
        const stat = comp.getAttribute('title').split('/')[0];
        const percentage = (stat / 220) * 100;
        const completionBar = comp.querySelector('.completion-bar');
        completionBar.style.transition = "1s";

        setTimeout(() => {
            completionBar.style.width = percentage + '%';
        }, 150);
    }

    window.addEventListener('scroll', checkVisibility);
    checkVisibility();

    // cri pokemon
    const img = document.querySelector('#main-active-pkmn');
    const songBtn = document.querySelector('#pokemon-song-play');
    let audio;

    [img, songBtn].forEach(container => {
        container.addEventListener('click', () => {
            if (!(audio && !audio.paused)) {
                const pokemonId = parseInt(document.querySelector('#previous').getAttribute('data-id')) + 1;
                const url = `https://raw.githubusercontent.com/PokeAPI/cries/main/cries/pokemon/latest/${pokemonId}.ogg`;

                audio = new Audio(url);
                audio.volume = 0.2;
                audio.play();
            }
        });
    })
});