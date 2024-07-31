
const footer = document.querySelector('footer'),
	mainWrapper = document.querySelector('#main-wrapper');

function adjustMainHeight() {
	mainWrapper.style.minHeight = `calc(100vh - ${footer.offsetHeight}px)`
	// console.log(mainWrapper.style.minHeight)
}

adjustMainHeight();
