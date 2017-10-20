(function( $ ) {
	'use strict';

	$(document).ready(function() {

		// When the open button is clicked, show the modal
		$("#cc-post-republisher-modal-button-open").click(function() {

			$("#cc-post-republisher-modal-container").show();

		});

		// When the modal outer box is clicked (but not the modal text), hide the modal
		$("#cc-post-republisher-modal-container").on('click', function(e) {
			if (e.target !== this)
				return;
				$("#cc-post-republisher-modal-container").hide();

		});

		// When the escape button is clicked, hide the modal
		$(document).keydown(function(e) {
			if (e.keyCode == 27) {
				$("#cc-post-republisher-modal-container").hide();
			}

		});

		// When the close button is clicked, hide the modal
		$("#cc-post-republisher-modal-button-close").click(function() {

			$("#cc-post-republisher-modal-container").hide();

		});

	});

})( jQuery );
