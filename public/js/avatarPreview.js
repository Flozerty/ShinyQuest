document.addEventListener('DOMContentLoaded', function () {
  const avatarInput = document.querySelector('#avatar_avatar');
  const avatarImage = document.querySelector('#avatar-image');

  avatarInput.addEventListener('change', function () {
    const file = this.files[0];
    if (file) {
      const reader = new FileReader();

      // lecture du fichier & affichage
      reader.onload = function (e) {
        avatarImage.src = e.target.result;
      };

      reader.readAsDataURL(file);
    }
  });
});