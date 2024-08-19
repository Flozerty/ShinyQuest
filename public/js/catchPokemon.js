document.addEventListener('DOMContentLoaded', function () {
    const button = document.querySelector('.fa-circle-check');
    const audio = new Audio('/sfx/catch-pokemon.mp3');

    button.addEventListener('click', function () {
        audio.play();
    });
});