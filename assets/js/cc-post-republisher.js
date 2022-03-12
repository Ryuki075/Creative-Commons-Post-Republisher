document.addEventListener("DOMContentLoaded", function() {

	"use strict";

	// When the open button is clicked, show the modal
	document.addEventListener('click', function (event) {
		// If the clicked element doesn't have the right selector, bail
		if (!event.target.matches('#cc-post-republisher-modal-button-open')) return;
		// Don't follow the link
		event.preventDefault();
		// When the open button is clicked, show the modal
		document.querySelector('#cc-post-republisher-modal-container').style.display = 'block';
	}, false);

	// When the modal outer box is clicked (but not the modal text), hide the modal
	document.addEventListener('click', function (event) {
		// If the clicked element doesn't have the right selector, bail
		if (!event.target.matches('#cc-post-republisher-modal-container')) return;
		// Don't follow the link
		event.preventDefault();
		// When the open button is clicked, show the modal
		document.querySelector('#cc-post-republisher-modal-container').style.display = 'none';
	}, false);

	// When the close button is clicked, hide the modal
	document.addEventListener('click', function (event) {
		// If the clicked element doesn't have the right selector, bail
		if (!event.target.matches('#cc-post-republisher-modal-button-close')) return;
		// Don't follow the link
		event.preventDefault();
		// When the open button is clicked, show the modal
		document.querySelector('#cc-post-republisher-modal-container').style.display = 'none';
	}, false);

	// When the escape button is clicked, hide the modal
	document.onkeydown = function(evt) {
	    evt = evt || window.event;
	    var isEscape = false;
	    if ("key" in evt) {
	        isEscape = (evt.key === "Escape" || evt.key === "Esc");
	    } else {
	        isEscape = (evt.keyCode === 27);
	    }
	    if (isEscape) {
			document.querySelector('#cc-post-republisher-modal-container').style.display = 'none';
	    }
	};

})
