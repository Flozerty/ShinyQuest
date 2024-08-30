document.addEventListener('DOMContentLoaded', function () {
    const messagerieContainers = document.querySelectorAll('.messagerie-header-link');
    if (messagerieContainers[0]) {
        // chercher les nouveaux messages
        fetch('/newMessages')
            .then(response => response.json())
            .then(data => {
                if (data && data > 0) {
                    messagerieContainers.forEach(container => {

                        const newMessages = document.createElement('span');
                        newMessages.classList.add('new-messages-header');
                        newMessages.innerHTML = data;
                        container.appendChild(newMessages);
                    });
                } else {
                    console.log('pas de nouveaux messages')
                }
            })
            .catch(error => console.error('Erreur lors du chargement des nouveaux messages :', error));
    }
});