<?php
/**
 * @package Creative Commons Post Republisher
 */
class CC_Post_Republisher_Meta_Box {

	private $screens = array(
		'post',
	);

	private $fields = array(
		array(
			'id' 		=> 'license-type',
			'label' 	=> 'License Type',
			'type' 		=> 'radio',
			'default'	=> 'default',
			'options' 	=> array(
				'default'		=> 'Default License',
				'cc-by' 		=> '<strong>CC BY</strong> - Attribution',
				'cc-by-sa' 		=> '<strong>CC BY-SA</strong> - Attribution-ShareAlike',
				'cc-by-nd' 		=> '<strong>CC BY-ND</strong> - Attribution-NoDerivs',
				'cc-by-nc' 		=> '<strong>CC BY-NC</strong> - Attribution-NonCommercial',
				'cc-by-nc-sa' 	=> '<strong>CC BY-NC-SA</strong> - Attribution-NonCommercial-ShareAlike',
				'cc-by-nc-nd' 	=> '<strong>CC BY-NC-ND</strong> - Attribution-NonCommercial-NoDerivs',
				'no-cc-license'	=> 'Not Creative Commons Licensed'
			),
		),
	);

	/**
	 * Class construct method. Adds actions to their respective WordPress hooks.
	 */
	public function __construct() {

		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_post' ) );

	}

	/**
	 * Hooks into WordPress' add_meta_boxes function.
	 * Goes through screens (post types) and adds the meta box.
	 */
	public function add_meta_boxes() {

		foreach ( $this->screens as $screen ) {

			add_meta_box(
				'creative-commons-post-republisher',
				__( 'Creative Commons', 'cc-post-republisher' ),
				array( $this, 'add_meta_box_callback' ),
				$screen,
				'side',
				'default'
			);

		}

	}

	/**
	 * Generates the HTML for the meta box
	 *
	 * @param object $post WordPress post object
	 */
	public function add_meta_box_callback( $post ) {

		wp_nonce_field( 'creative_commons_post_republisher_data', 'creative_commons_post_republisher_nonce' );

		echo 'Assign a license to this post. If no license is selected, the post will have the default license that is set in the <a href="' . admin_url() . 'options-general.php?page=cc_post_republisher_settings">Creative Commons Post Republisher Settings</a>.';

		$this->generate_fields( $post );

	}

	/**
	 * Generates the field's HTML for the meta box.
	 */
	public function generate_fields( $post ) {

		$output = '';

		foreach ( $this->fields as $field ) {

			$label = '<label for="' . $field['id'] . '">' . $field['label'] . '</label>';
			$db_value = get_post_meta( $post->ID, 'creative_commons_post_republisher_' . $field['id'], true );
			if ( empty( $db_value ) )
				$db_value = $field['default'];
			$input = '<fieldset>';
			$input .= '<legend class="screen-reader-text">' . $field['label'] . '</legend>';
			$i = 0;
			foreach ( $field['options'] as $key => $value ) {
				$field_value = !is_numeric( $key ) ? $key : $value;
				$input .= sprintf(
					'<label><input %s id="%s" name="%s" type="radio" value="%s"> %s</label>%s',
					$db_value === $field_value ? 'checked' : '',
					$field['id'],
					$field['id'],
					$field_value,
					$value,
					$i < count( $field['options'] ) - 1 ? '<br>' : ''
				);
				$i++;
			}
			$input .= '</fieldset>';

			$output .= '<p>' . $input . '</p>';

		}

		echo $output;

	}

	/**
	 * Hooks into WordPress' save_post function
	 */
	public function save_post( $post_id ) {

		if ( ! isset( $_POST['creative_commons_post_republisher_nonce'] ) )
			return $post_id;

		$nonce = $_POST['creative_commons_post_republisher_nonce'];
		if ( !wp_verify_nonce( $nonce, 'creative_commons_post_republisher_data' ) )
			return $post_id;

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return $post_id;

		foreach ( $this->fields as $field ) {

			if ( isset( $_POST[ $field['id'] ] ) ) {

				update_post_meta( $post_id, 'creative_commons_post_republisher_' . $field['id'], $_POST[ $field['id'] ] );

			}

		}

	}

}
new CC_Post_Republisher_Meta_Box;