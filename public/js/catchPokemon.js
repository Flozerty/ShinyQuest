document.addEventListener('DOMContentLoaded', function () {
    const flashMessages = document.querySelectorAll('.flash-message');
    if (flashMessages.length) {
        
        const audio = new Audio('/sfx/catch-pokemon.mp3');
        audio.play();
    }
});