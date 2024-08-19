document.addEventListener('DOMContentLoaded', function() {
    const formContainer = document.getElementById('new-shasse');
    fetch('/shasse/new')
        .then(response => response.json())
        .then(data => {
            if (data.html) {
                formContainer.innerHTML = data.html;
            } else {
                console.error('Erreur lors du chargement du formulaire.');
            }
        })
        .catch(error => console.error('Erreur lors du chargement du formulaire:', error));
});