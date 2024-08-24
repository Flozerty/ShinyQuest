document.addEventListener('DOMContentLoaded', function () {

    // initailisation aux paramètres enregistrés
    loadSavedSelection();

    const colorSelection = document.querySelectorAll('.l_d_mode-main input, .l_d_mode input');

    colorSelection.forEach(choice => {
        choice.addEventListener('change', function () {
            syncSelection(this.id);
            changeSelectedColor(this.id);
            saveSelection(this.id)
        });
    });

    function syncSelection(selectedId) {
        // On s'assure que les 2 sélecteurs se mettent à jour
        if (selectedId === 'pokeball-toggle' || selectedId === 'pokeball-toggle2') {
            document.getElementById('pokeball-toggle').checked = true;
            document.getElementById('pokeball-toggle2').checked = true;

        } else if (selectedId === 'superball-toggle' || selectedId === 'superball-toggle2') {
            document.getElementById('superball-toggle').checked = true;
            document.getElementById('superball-toggle2').checked = true;

        } else if (selectedId === 'hyperball-toggle' || selectedId === 'hyperball-toggle2') {
            document.getElementById('hyperball-toggle').checked = true;
            document.getElementById('hyperball-toggle2').checked = true;
        }
    }

    function changeSelectedColor(selectedId) {
        if (selectedId === 'pokeball-toggle' || selectedId === 'pokeball-toggle2') {
            setColors(
                '--darkred',
                '--lightred',
                '--background-light-main',
                '--light-secondary',
                '--black',
                '--white',
            );
        } else if (selectedId === 'superball-toggle' || selectedId === 'superball-toggle2') {
            setColors(
                '--darkblue',
                '--lightblue',
                '--background-light-main',
                '--light-secondary',
                '--black',
                '--white',
            );
        } else if (selectedId === 'hyperball-toggle' || selectedId === 'hyperball-toggle2') {
            setColors(
                '--yellow',
                '--dark-secondary',
                '--background-dark-main',
                '--dark-secondary',
                '--white',
                '--dark-card-color',
            );
        }
    }

    // fonction qui mettra a jour les coulurs dans :root
    function setColors(
        primaryColor,
        secondaryColor,
        backgroundColor,
        backgroundSecondaryColor,
        fontMainColor,
        cardColor,
    ) {
        document.documentElement.style.setProperty('--primary-color', `var(${primaryColor})`);
        document.documentElement.style.setProperty('--secondary-color', `var(${secondaryColor})`);
        document.documentElement.style.setProperty('--background-color', `var(${backgroundColor})`);
        document.documentElement.style.setProperty('--background-secondary', `var(${backgroundSecondaryColor})`);
        document.documentElement.style.setProperty('--font-main-color', `var(${fontMainColor})`);
        document.documentElement.style.setProperty('--cardColor', `var(${cardColor})`);
    }

    // sauvegarde de la sélection dans le Local Storage
    function saveSelection(selectedId) {
        localStorage.setItem('colorSelection', selectedId);
    }

    function loadSavedSelection() {
        const savedId = localStorage.getItem('colorSelection');
        if (savedId) {
            const input = document.getElementById(savedId);
            input.checked = true;
            changeSelectedColor(savedId);
        }
    }
});