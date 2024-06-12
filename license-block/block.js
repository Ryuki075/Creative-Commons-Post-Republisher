(function (blocks, element, editor, components) {
	var el = element.createElement;
	var RichText = editor.RichText;

	blocks.registerBlockType('cc/post-republisher', {
		title: 'Creative Commons Post Republisher',
		icon: 'admin-network',
		category: 'common',
		attributes: {
			buttonText: { type: 'string', default: 'Republish' }
		},
		edit: function (props) {
			var attributes = props.attributes;
			var setAttributes = props.setAttributes;
			var activeLicense = blockSettings.activeLicense;
			var activeLicenseImage = blockSettings.activeLicenseImage;

			var onChangeButtonText = function (newText) {
				setAttributes({ buttonText: newText });
			};

			return el('div', { className: 'components-placeholder' },
				el('div', { className: 'cc-description' }, 'The Post Republisher will display the globally set license by default, unless a license is chosen for a specific post/page.'),
				el('div', { className: 'cc-global-license components-placeholder__label' },
					el('img', { src: activeLicenseImage, alt: 'License Image', style: { width: '88px', marginRight: '5px' } }),
					'Global License: ' + activeLicense
				),
				el('div', { className: 'cc-republish-button' },
					el('button', { id: 'cc-post-republisher-modal-button-open', className: 'editable-button' },
						el('img', { src: activeLicenseImage, alt: 'License Image', style: { width: '88px', marginRight: '5px' } }),
						el(RichText, {
							tagName: 'span',
							value: attributes.buttonText,
							onChange: onChangeButtonText,
							placeholder: 'Enter button text...'
						})
					)
				)
			);
		},
		save: function (props) {
			var attributes = props.attributes;
			var activeLicenseImage = blockSettings.activeLicenseImage;

			return el('div', {},
				el('button', {
					id: 'cc-post-republisher-modal-button-open',
				},
					el('img', { src: activeLicenseImage, alt: 'License Image', style: { width: '88px', marginRight: '5px' } }),
					el(RichText.Content, {
						tagName: 'span',
						value: attributes.buttonText
					})
				),
				el('div', {
					id: 'cc-post-republisher-modal-container'
				},
					el('div', {
						id: 'cc-post-republisher-modal'
					})
				)
			);
		}
	});
})(
	window.wp.blocks,
	window.wp.element,
	window.wp.editor,
	window.wp.components
);
