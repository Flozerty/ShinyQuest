document.addEventListener('DOMContentLoaded', function () {

    const colorSelection = document.querySelectorAll('.l_d_mode-main input, .l_d_mode input');

    colorSelection.forEach(choice => {
        choice.addEventListener('change', function () {
            if (this.id === 'pokeball-toggle' || this.id === 'pokeball-toggle2') {
                setColors('--darkred', '--lightred');
            } else if (this.id === 'superball-toggle' || this.id === 'superball-toggle2') {
                setColors('--darkblue', '--lightblue');
            } else if (this.id === 'hyperball-toggle' || this.id === 'hyperball-toggle2') {
                setColors('--darkred', '--lightblue');
            }
        });
    });

    function setColors(primaryColor, secondaryColor) {
        document.documentElement.style.setProperty('--primary-color', `var(${primaryColor})`);
        document.documentElement.style.setProperty('--secondary-color', `var(${secondaryColor})`);
    }
});