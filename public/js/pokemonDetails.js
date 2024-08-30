document.addEventListener('DOMContentLoaded', () => {

    // Stats
    const statsComp = document.querySelectorAll('.stat-completion');

    function checkVisibility() {
        statsComp.forEach((element) => {
            const rect = element.getBoundingClientRect();
            const windowHeight = window.innerHeight || document.documentElement.clientHeight;

            // Si l'image est visible
            if (rect.top <= windowHeight) {
                displayStat(element);
                // window.removeEventListener('scroll', checkVisibility)
            }
        })
    }

    function displayStat(comp) {
        const stat = comp.getAttribute('title').split('/')[0];
        const percentage = (stat / 220) * 100;
        const completionBar = comp.querySelector('.completion-bar');

        setTimeout(() => {
            completionBar.style.width = percentage + '%';
        }, 500);
    }

    window.addEventListener('scroll', checkVisibility);
    checkVisibility();


    // Formes des pokemons
    const formsDiv = document.querySelector('#varieties'),
        evolutionDiv = document.querySelector('#evolution');

    [formsDiv, evolutionDiv].forEach(div => {
        if (div) {
            if (div.offsetHeight > 500) {
                div.style.maxHeight = "500px";
                div.style.overflowY = "scroll";
            } else {
                div.style.maxHeight = "none";
                div.style.overflowY = "hidden";
            }
        }
    });
});
