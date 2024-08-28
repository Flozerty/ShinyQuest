document.addEventListener('DOMContentLoaded', function () {
    const messagerieContainers = document.querySelectorAll('.messagerie-header-link');
    // chercher les nouveaux messages
    fetch('/newMessages')
        .then(response => response.json())
        .then(data => {
            if (data && data > 0) {
                messagerieContainers.forEach(container => {

                    let newMessages = document.createElement('span');
                    newMessages.classList.add('new-messages-header');
                    newMessages.innerHTML = data;
                    container.appendChild(newMessages);
                });
            }
        })
        .catch(error => console.error('Erreur lors du chargement des nouveaux messages :', error));
});