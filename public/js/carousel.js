document.addEventListener('DOMContentLoaded', function () {
  const carousel = document.querySelectorAll('.carousel');

  if (carousel) {

    carousel.forEach(container => {
      const nextButton = container.querySelector('.arrow-right'),
        previousButton = container.querySelector('.arrow-left'),
        cardsContainer = container.querySelector('.cards-container');

      let translateValue = 0

      // Valeur de translateX en fonction de la width du 1er enfant (card)
      const widthValue = cardsContainer.children[0].offsetWidth + 10,
        carouselWidth = widthValue * cardsContainer.children.length,
        containerWidth = cardsContainer.offsetWidth;

      // affichage des fleches si besoin
      if (carouselWidth - containerWidth > 0) {
        nextButton.style.display = 'block'
        previousButton.style.display = 'block'
      }

      // défilement à gauche
      previousButton.addEventListener("click", () => {
        if (translateValue < 0) {
          translateValue += widthValue
          cardsContainer.style.transform = `translateX(${translateValue}px)`
        }
      })

      // défilement à droite
      nextButton.addEventListener("click", () => {
        // console.log(cardsContainer.offsetWidth - ((cardsContainer.children.length) * 200))
        if (translateValue > (containerWidth - carouselWidth)) {
          translateValue -= widthValue
          cardsContainer.style.transform = `translateX(${translateValue}px)`
        }
      });
    });
  }
})