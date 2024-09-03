
document.addEventListener('DOMContentLoaded', () => {
    const audio = document.querySelector('#music');
    audio.volume = 0.3;

    let isPlaying = localStorage.getItem('isMusicPlaying') === 'true';
    const currentTime = localStorage.getItem('audioCurrentTime');

    if (isPlaying) {
        audio.currentTime = currentTime ? parseFloat(currentTime) : 0;

        // Demander une interaction utilisateur pour démarrer la musique
        audio.addEventListener('canplaythrough', () => {
            audio.play().catch(e => {
                console.log("Erreur de lecture automatique : ", e);
                isPlaying = false;
                // si la musique n'a pas encore démarré

                const playOnClick = () => {
                    audio.play()
                    // Une fois que la musique a démarré, retirez cet écouteur
                    document.removeEventListener('click', playOnClick);
                };

                document.addEventListener('click', playOnClick);
            })
        })
    };

    // Écouter les événements play/pause pour stocker l'état
    audio.onplay = () => {
        localStorage.setItem('isMusicPlaying', 'true');
    };

    audio.onpause = () => {
        localStorage.setItem('isMusicPlaying', 'false');
    };

    // Sauvegarder le temps actuel à chaque seconde
    audio.ontimeupdate = () => {
        localStorage.setItem('audioCurrentTime', audio.currentTime);
    };

    if (currentTime) {
        audio.currentTime = parseFloat(currentTime);
    }

    document.body.appendChild(audio);

    const profileNavSection = document.querySelector('#profile-nav');

    const playBtn = document.createElement('button');
    playBtn.className = "music-button"

    playBtn.innerHTML = isPlaying ? '<i class="fa-solid fa-pause"></i>' : '<i class="fa-solid fa-play"></i>';
    playBtn.addEventListener('click', () => {
        if (audio.paused) {
            audio.play();
            playBtn.innerHTML = '<i class="fa-solid fa-pause"></i>';
        } else {
            audio.pause();
            playBtn.innerHTML = '<i class="fa-solid fa-play"></i>';
        }
    })

    profileNavSection.appendChild(playBtn);
});