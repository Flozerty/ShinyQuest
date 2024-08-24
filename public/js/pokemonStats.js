document.addEventListener('DOMContentLoaded', () => {
    const statsComp = document.querySelectorAll('.stat-completion')

    statsComp.forEach((element) => {
        const stat = element.getAttribute('title').split('/')[0];
        const percentage = (stat / 220) * 100;
        const completionBar = element.querySelector('.completion-bar');

        setTimeout(() => {
            completionBar.style.width = percentage + '%';
        }, 1000);
    });
});