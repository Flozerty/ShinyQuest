document.addEventListener('DOMContentLoaded', function () {

    const colorSelection = document.querySelectorAll('.l_d_mode-main input, .l_d_mode input');

    colorSelection.forEach(choice => {
        choice.addEventListener('change', function () {
            syncSelection(this.id);
            changeColorsBasedOnSelection(this.id);
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

    function changeColorsBasedOnSelection(selectedId) {
        if (selectedId === 'pokeball-toggle' || selectedId === 'pokeball-toggle2') {
            setColors('--darkred', '--lightred', '--light-background', '--black');
        } else if (selectedId === 'superball-toggle' || selectedId === 'superball-toggle2') {
            setColors('--darkblue', '--lightblue', '--light-background', '--black');
        } else if (selectedId === 'hyperball-toggle' || selectedId === 'hyperball-toggle2') {
            setColors('--yellow', '--white', '--background-dark-main', '--white');
        }
    }

    function setColors(primaryColor, secondaryColor, backgroundColor, fontMainColor) {
        document.documentElement.style.setProperty('--primary-color', `var(${primaryColor})`);
        document.documentElement.style.setProperty('--secondary-color', `var(${secondaryColor})`);
        document.documentElement.style.setProperty('--background-color', `var(${backgroundColor})`);
        document.documentElement.style.setProperty('--font-main-color', `var(${fontMainColor})`);
    }
});