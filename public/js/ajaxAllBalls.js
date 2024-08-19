document.addEventListener('DOMContentLoaded', function() {
    const ballSelect = document.getElementById('ball');

    // Charger les balls
    fetch('/api/balls')
        .then(response => response.json())
        .then(data => {
            data.forEach(group => {
                if (group && group.ballsData) {
                    const optgroup = document.createElement('optgroup');
                    optgroup.label = group.categoryName;

                    group.ballsData.forEach(ball => {
                        // gérer la condition si null
                        if (ball && ball.spriteName && ball.name) {
                            const option = document.createElement('option');
                            option.value = ball.spriteName;
                            option.textContent = ball.name;
                            optgroup.appendChild(option);
                        }
                    });

                    ballSelect.appendChild(optgroup);
                }
            });
        })
        .catch(error => console.error('Erreur lors du chargement des balls:', error));
});