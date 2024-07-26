document.addEventListener("click", event => {
	if (event.target.name == "ldMode")
		event.target.removeAttribute("id");
});