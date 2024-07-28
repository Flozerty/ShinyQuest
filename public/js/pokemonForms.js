document.addEventListener('DOMContentLoaded', function () {
	$formsDiv = document.querySelector('#varieties')
	if ($formsDiv.offsetHeight >= 500) {
		$formsDiv.style.maxHeight = "500px";
		$formsDiv.style.overflowY = "scroll";
	} else {
		$formsDiv.style.maxHeight = "none";
		$formsDiv.style.overflowY = "hidden";

	}
})