jQuery(document).ready(function ($) {
	$(document).on('click', '#cc-post-republisher-modal-button-open', function () {
		var postId = wpApiSettings.postID;
		var postType = wpApiSettings.postType;

		// Ensure postId is valid before making the AJAX request
		if (postId) {
			// Set the REST URL based on the post type (must be pluralized for posts and pages)
			if (postType === 'post') {
				postType = 'posts';
			} else if (postType === 'page') {
				postType = 'pages';
			}
			var postUrl = wpApiSettings.root + 'wp/v2/' + postType + '/' + postId;

			// AJAX request to get content
			$.ajax({
				url: postUrl,
				method: 'GET',
				beforeSend: function (xhr) {
					xhr.setRequestHeader('X-WP-Nonce', wpApiSettings.nonce);
				},
				success: function (response) {
					var modal = $('#cc-post-republisher-modal');
					var container = modal.closest('#cc-post-republisher-modal-container');

					if (modal.length) {
						var licenseContent = wpApiSettings.licenseContent;
						var termsContent = wpApiSettings.termsContent;

						var content = '<button id="cc-post-republisher-modal-button-close">&times;</button>';
						content += termsContent;
						content += licenseContent;
						content += '<div id="cc-post-republisher-post-content">';
						content += '<textarea><h1>' + response.title.rendered + '</h1>\n\n' + response.content.rendered + '</textarea>';
						content += '</div>';

						modal.html(content);
						container.css('display', 'block');
					} else {
						console.error('Modal element not found');
					}
				},
				error: function (error) {
					console.error('AJAX error:', error);
				}
			});
		} else {
			console.error('Post ID is not valid.');
		}
	});

	$(document).on('click', '#cc-post-republisher-modal-button-close', function () {
		$('#cc-post-republisher-modal-container').css('display', 'none');
	});

	// Close modal if clicking outside the modal
	$(document).on('click', '#cc-post-republisher-modal-container', function (e) {
		if ($(e.target).is('#cc-post-republisher-modal-container')) {
			$(this).css('display', 'none');
		}
	});

	// Close modal if pressing the escape key
	$(document).on('keydown', function (e) {
		if (e.key === "Escape") {
			$('#cc-post-republisher-modal-container').css('display', 'none');
		}
	});
});
