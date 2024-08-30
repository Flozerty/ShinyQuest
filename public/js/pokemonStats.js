document.addEventListener('DOMContentLoaded', () => {
    const statsComp = document.querySelectorAll('.stat-completion'),
        statsContainter = document.querySelector('#stats-container')

    function checkVisibility() {
        const rect = statsContainter.getBoundingClientRect();
        const windowHeight = window.innerHeight || document.documentElement.clientHeight;

        // Si l'image est visible
        if (rect.top <= windowHeight) {
            displayStats();
            window.removeEventListener('scroll', checkVisibility)
            console.log('test');
        }
    }

    function displayStats() {
        statsComp.forEach((element) => {
            const stat = element.getAttribute('title').split('/')[0];
            const percentage = (stat / 220) * 100;
            const completionBar = element.querySelector('.completion-bar');

            setTimeout(() => {
                completionBar.style.width = percentage + '%';
            }, 500);
        });
    }

    window.addEventListener('scroll', checkVisibility);
    checkVisibility();
});