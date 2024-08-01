document.addEventListener('DOMContentLoaded', function () {

	const formsDiv = document.querySelector('#varieties'),
		evolutionDiv = document.querySelector('#evolution');

	[formsDiv, evolutionDiv].forEach(div => {
		if (div.offsetHeight > 500) {
			div.style.maxHeight = "500px";
			div.style.overflowY = "scroll";
		} else {
			div.style.maxHeight = "none";
			div.style.overflowY = "hidden";
		}
	});
})