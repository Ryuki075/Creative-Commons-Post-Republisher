(function( $ ) {
	'use strict';

	$(document).ready(function() {

		// When the open button is clicked, show the modal
		$("#cc-post-republisher-modal-button-open").click(function() {

			$("#cc-post-republisher-modal-container").show();

		});

		// When the close button is clicked, hide the modal
		$("#cc-post-republisher-modal-button-close").click(function() {

			$("#cc-post-republisher-modal-container").hide();

		});

	});

})( jQuery );
