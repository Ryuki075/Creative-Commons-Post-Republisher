<?php
/**
 * @package Creative Commons Post Republisher
 */
class CC_Post_Republisher_Admin {

	public $plugin_name = CCPR_PLUGIN_NAME;
	public $version     = CCPR_VERSION;
	public $assets_url  = CCPR_ASSET_DIR;

	public function __construct() {

		add_action( 'admin_menu', array( $this, 'setup_plugin_options_menu' ), 9 );
		add_action( 'admin_init', array( $this, 'initialize_general_settings' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'cc_post_republisher_scripts' ) );
	}

	/**
	 * Loads plugin scripts and styles
	 */
	public function cc_post_republisher_scripts() {
		wp_enqueue_style( 'cc-post-republisher-admin-css', $this->assets_url . 'css/cc-post-republisher-admin.css', array(), CCPR_VERSION );
		wp_enqueue_script(
			'cc-post-republisher-modal',
			$this->assets_url . 'license-block/modal.js',
			array( 'jquery' ),
			CCPR_VERSION,
			true
		);

		wp_localize_script(
			'cc-post-republisher-modal',
			'modalSettings',
			array(
				'root'  => esc_url_raw( rest_url() ),
				'nonce' => wp_create_nonce( 'wp_rest' ),
			)
		);
	}

	/**
	 * Creates main settings menu page, as well as submenu page
	 */
	public function setup_plugin_options_menu() {

		add_submenu_page(
			'options-general.php',
			__( 'Creative Commons Post Republisher Settings', 'cc-post-republisher' ),
			__( 'Creative Commons', 'cc-post-republisher' ),
			'manage_options',
			'cc_post_republisher_settings',
			array( $this, 'render_settings_page_content' )
		);
	}

	/**
	 * Provide default values for the general settings
	 *
	 * @return array
	 */
	public static function default_general_settings() {

		// Get the site admin email to put into the default terms text
		$admin_email = get_option( 'admin_email' );
		$site_name   = get_bloginfo( 'name' );

		$defaults = array(
			'termstext'    => sprintf(
				'<strong>REPUBLISHING TERMS</strong>
				<p>You may republish this article online or in print under the selected Creative Commons license. You must provide attribution to the article when republishing. An ideal attribution to this article includes: Title, Author, Source, License. For more information on providing attribution, see: <a href="https://wiki.creativecommons.org/wiki/Recommended_practices_for_attribution" target="_blank">https://wiki.creativecommons.org/wiki/Recommended_practices_for_attribution</a></p>
				<p>If you have any questions, please email <a href="mailto:%s">%s</a></p>',
				esc_attr( $admin_email ),
				esc_attr( $admin_email )
			),
			'license_type' => 'cc-by',
			'button_text'  => 'Republish',
		);

		update_option( 'cc_post_republisher_settings', $defaults );
	}

	/**
	 * Renders a simple page to display for the theme menu defined above.
	 */
	public function render_settings_page_content() {
		?>
		<!-- Create a header in the default WordPress 'wrap' container -->
		<div class="wrap">

			<h2><?php esc_attr_e( 'Creative Commons Post Republisher Settings', 'cc-post-republisher' ); ?></h2>
			<?php
			// Display any settings errors registered to the settings_error hook
			settings_errors();

			?>

			<form method="post" action="options.php">
				<?php

				settings_fields( 'cc_post_republisher_general_settings' );
				do_settings_sections( 'cc_post_republisher_general_settings' );

				submit_button();

				?>
			</form>

		</div><!-- /.wrap -->
		<?php
	}

	/**
	 * Initializes the general settings by registering the Sections, Fields, and Settings.
	 *
	 * This function is registered with the 'admin_init' hook.
	 */
	public function initialize_general_settings() {

		// Control title of republish
		// Set Terms text
		// Choose CC license to use
		// Choose whether to display at end of post or not
		add_settings_section(
			'general_settings_section',
			__( 'General Settings', 'cc-post-republisher' ),
			'',
			'cc_post_republisher_general_settings'
		);

		add_settings_field(
			'termstext',
			__( 'Terms Text', 'cc-post-republisher' ),
			array( $this, 'wp_editor_input_callback' ),
			'cc_post_republisher_general_settings',
			'general_settings_section',
			array(
				'label_for'    => 'termstext',
				'option_group' => 'cc_post_republisher_settings',
				'option_id'    => 'termstext',
			)
		);

		add_settings_field(
			'license_type',
			__( 'Creative Commons License Type', 'cc-post-republisher' ),
			array( $this, 'license_input_callback' ),
			'cc_post_republisher_general_settings',
			'general_settings_section',
			array(
				'label_for'          => 'license_type',
				'option_group'       => 'cc_post_republisher_settings',
				'option_id'          => 'license_type',
				'option_description' => __( 'Select the default license that you want to apply to your post content. This can be changed on individual posts.', 'cc-post-republisher' ),
			)
		);

		// add_settings_field(
		//  'button_text',
		//  __( 'Button Text', 'cc-post-republisher' ),
		//  array( $this, 'text_input_callback' ),
		//  'cc_post_republisher_general_settings',
		//  'general_settings_section',
		//  array(
		//      'label_for'    => 'button_text',
		//      'option_group' => 'cc_post_republisher_settings',
		//      'option_id'    => 'button_text',
		//  )
		// );

		register_setting(
			'cc_post_republisher_general_settings',
			'cc_post_republisher_settings'
		);
	}

	public function wp_editor_input_callback( $wp_editor_input ) {

		// Get existing option from database
		$option_group = $wp_editor_input['option_group'];
		$option_id    = $wp_editor_input['option_id'];
		$option_name  = "{$option_group}[{$option_id}]";
		$options      = get_option( $option_group );
		$content      = isset( $options[ $option_id ] ) ? $options[ $option_id ] : '';

		// Get arguments from setting
		$settings = array(
			'quicktags'     => array( 'buttons' => 'strong,em,del,ul,ol,li,close' ),
			'textarea_name' => $option_name,
		);
		// Render the output
		wp_editor( $content, $option_id, $settings );
	}

	public function license_input_callback( $license_input ) {

		// Get arguments from setting
		$option_group       = $license_input['option_group'];
		$option_id          = $license_input['option_id'];
		$option_name        = "{$option_group}[{$option_id}]";
		$option_description = $license_input['option_description'];

		// Get existing option from database
		$options      = get_option( $option_group );
		$option_value = isset( $options[ $option_id ] ) ? $options[ $option_id ] : '';

		// Render the output
		echo '<h4>' . esc_html( $option_description ) . '</h4>';
		?>
		<div class="cc-post-republisher-licenses">
			<div class="license-details">
				<div class="license-radio-button">
					<input type="radio" name="<?php echo esc_attr( $option_name ); ?>" value="cc-by" <?php checked( $option_value, 'cc-by' ); ?>>
				</div>
				<div class="license-meta">
					<img src="<?php echo esc_url( $this->assets_url . 'img/cc-by.png' ); ?>" alt="<?php __( 'Creative Commons License Attribution CC BY', 'cc-post-republisher' ); ?>" />
					<h3 class="license-name"><?php esc_html_e( 'Attribution', 'cc-post-republisher' ); ?></h3>
					<h3 class="license-code"><?php esc_html_e( 'CC BY', 'cc-post-republisher' ); ?></h3>
				</div>
				<div class="license-description">
					<p><?php esc_html_e( 'This license lets others distribute, remix, tweak, and build upon your work, even commercially, as long as they credit you for the original creation. This is the most accommodating of licenses offered. Recommended for maximum dissemination and use of licensed materials.', 'cc-post-republisher' ); ?></p>
					<p><a href="<?php echo esc_url( __( 'https://creativecommons.org/licenses/by/4.0', 'cc-post-republisher' ) ); ?>" target="_blank"><?php esc_html_e( 'View License Deed', 'cc-post-republisher' ); ?></a> | <a href="<?php echo esc_url( __( 'https://creativecommons.org/licenses/by/4.0/legalcode', 'cc-post-republisher' ) ); ?>" target="_blank"><?php esc_html_e( 'View Legal Code', 'cc-post-republisher' ); ?></a></p>
				</div>
			</div>
			<div class="license-details">
				<div class="license-radio-button">
					<input type="radio" name="<?php echo esc_attr( $option_name ); ?>" value="cc-by-sa" <?php checked( $option_value, 'cc-by-sa' ); ?>>
				</div>
				<div class="license-meta">
					<img src="<?php echo esc_url( $this->assets_url . 'img/cc-by-sa.png' ); ?>" alt="Creative Commons License Attribution-ShareAlike CC BY-SA" />
					<h3 class="license-name"><?php esc_html_e( 'Attribution-ShareAlike', 'cc-post-republisher' ); ?></h3>
					<h3 class="license-code"><?php esc_html_e( 'CC BY-SA', 'cc-post-republisher' ); ?></h3>
				</div>
				<div class="license-description">
					<p><?php esc_html_e( 'This license lets others remix, tweak, and build upon your work even for commercial purposes, as long as they credit you and license their new creations under the identical terms. This license is often compared to “copyleft” free and open source software licenses. All new works based on yours will carry the same license, so any derivatives will also allow commercial use. This is the license used by Wikipedia, and is recommended for materials that would benefit from incorporating content from Wikipedia and similarly licensed projects.', 'cc-post-republisher' ); ?></p>
					<p><a href="<?php echo esc_url( __( 'https://creativecommons.org/licenses/by-sa/4.0', 'cc-post-republisher' ) ); ?>" target="_blank"><?php esc_html_e( 'View License Deed', 'cc-post-republisher' ); ?></a> | <a href="<?php echo esc_url( __( 'https://creativecommons.org/licenses/by-sa/4.0/legalcode', 'cc-post-republisher' ) ); ?>" target="_blank"><?php esc_html_e( 'View Legal Code', 'cc-post-republisher' ); ?></a></p>
				</div>
			</div>
			<div class="license-details">
				<div class="license-radio-button">
					<input type="radio" name="<?php echo esc_attr( $option_name ); ?>" value="cc-by-nd" <?php checked( $option_value, 'cc-by-nd' ); ?>>
				</div>
				<div class="license-meta">
					<img src="<?php echo esc_url( $this->assets_url . 'img/cc-by-nd.png' ); ?>" alt="Creative Commons License Attribution-NoDerivs CC BY-ND" />
					<h3 class="license-name"><?php esc_html_e( 'Attribution-NoDerivs', 'cc-post-republisher' ); ?></h3>
					<h3 class="license-code"><?php esc_html_e( 'CC BY-ND', 'cc-post-republisher' ); ?></h3>
				</div>
				<div class="license-description">
					<p><?php esc_html_e( 'This license allows for redistribution, commercial and non-commercial, as long as it is passed along unchanged and in whole, with credit to you.', 'cc-post-republisher' ); ?></p>
					<p><a href="<?php echo esc_url( __( 'https://creativecommons.org/licenses/by-nd/4.0', 'cc-post-republisher' ) ); ?>" target="_blank"><?php esc_html_e( 'View License Deed', 'cc-post-republisher' ); ?></a> | <a href="<?php echo esc_url( __( 'https://creativecommons.org/licenses/by-nd/4.0/legalcode', 'cc-post-republisher' ) ); ?>" target="_blank"><?php esc_html_e( 'View Legal Code', 'cc-post-republisher' ); ?></a></p>
				</div>
			</div>
			<div class="license-details">
				<div class="license-radio-button">
					<input type="radio" name="<?php echo esc_attr( $option_name ); ?>" value="cc-by-nc" <?php checked( $option_value, 'cc-by-nc' ); ?>>
				</div>
				<div class="license-meta">
					<img src="<?php echo esc_url( $this->assets_url . 'img/cc-by-nc.png' ); ?>" alt="Creative Commons License Attribution-NonCommercial CC BY-NC" />
					<h3 class="license-name"><?php esc_html_e( 'Attribution-NonCommercial', 'cc-post-republisher' ); ?></h3>
					<h3 class="license-code"><?php esc_html_e( 'CC BY-NC', 'cc-post-republisher' ); ?></h3>
				</div>
				<div class="license-description">
					<p><?php esc_html_e( 'This license lets others remix, tweak, and build upon your work non-commercially, and although their new works must also acknowledge you and be non-commercial, they don’t have to license their derivative works on the same terms.', 'cc-post-republisher' ); ?></p>
					<p><a href="<?php echo esc_url( __( 'https://creativecommons.org/licenses/by-nc/4.0', 'cc-post-republisher' ) ); ?>" target="_blank"><?php esc_html_e( 'View License Deed', 'cc-post-republisher' ); ?></a> | <a href="<?php echo esc_url( __( 'https://creativecommons.org/licenses/by-nc/4.0/legalcode', 'cc-post-republisher' ) ); ?>" target="_blank"><?php esc_html_e( 'View Legal Code', 'cc-post-republisher' ); ?></a></p>
				</div>
			</div>
			<div class="license-details">
				<div class="license-radio-button">
					<input type="radio" name="<?php echo esc_attr( $option_name ); ?>" value="cc-by-nc-sa" <?php checked( $option_value, 'cc-by-nc-sa' ); ?>>
				</div>
				<div class="license-meta">
					<img src="<?php echo esc_url( $this->assets_url . 'img/cc-by-nc-sa.png' ); ?>" alt="Creative Commons License Attribution-NonCommercial-ShareAlike CC BY-NC-SA" />
					<h3 class="license-name"><?php esc_html_e( 'Attribution-NonCommercial-ShareAlike', 'cc-post-republisher' ); ?></h3>
					<h3 class="license-code"><?php esc_html_e( 'CC BY-NC-SA', 'cc-post-republisher' ); ?></h3>
				</div>
				<div class="license-description">
					<p><?php esc_html_e( 'This license lets others remix, tweak, and build upon your work non-commercially, as long as they credit you and license their new creations under the identical terms.', 'cc-post-republisher' ); ?></p>
					<p><a href="<?php echo esc_url( __( 'https://creativecommons.org/licenses/by-nc-sa/4.0', 'cc-post-republisher' ) ); ?>" target="_blank"><?php esc_html_e( 'View License Deed', 'cc-post-republisher' ); ?></a> | <a href="<?php echo esc_url( __( 'https://creativecommons.org/licenses/by-nc-sa/4.0/legalcode', 'cc-post-republisher' ) ); ?>" target="_blank"><?php esc_html_e( 'View Legal Code', 'cc-post-republisher' ); ?></a></p>
				</div>
			</div>
			<div class="license-details">
				<div class="license-radio-button">
					<input type="radio" name="<?php echo esc_attr( $option_name ); ?>" value="cc-by-nc-nd" <?php checked( $option_value, 'cc-by-nc-nd' ); ?>>
				</div>
				<div class="license-meta">
					<img src="<?php echo esc_url( $this->assets_url . 'img/cc-by-nc-nd.png' ); ?>" alt="Creative Commons License Attribution-NonCommercial-NoDerivs CC BY-NC-ND" />
					<h3 class="license-name"><?php esc_html_e( 'Attribution-NonCommercial-NoDerivs', 'cc-post-republisher' ); ?></h3>
					<h3 class="license-code"><?php esc_html_e( 'CC BY-NC-ND', 'cc-post-republisher' ); ?></h3>
				</div>
				<div class="license-description">
					<p><?php esc_html_e( 'This license is the most restrictive of our six main licenses, only allowing others to download your works and share them with others as long as they credit you, but they can’t change them in any way or use them commercially.', 'cc-post-republisher' ); ?></p>
					<p><a href="<?php echo esc_url( __( 'https://creativecommons.org/licenses/by-nc-nd/4.0', 'cc-post-republisher' ) ); ?>" target="_blank"><?php esc_html_e( 'View License Deed', 'cc-post-republisher' ); ?></a> | <a href="<?php echo esc_url( __( 'https://creativecommons.org/licenses/by-nc-nd/4.0/legalcode', 'cc-post-republisher' ) ); ?>" target="_blank"><?php esc_html_e( 'View Legal Code', 'cc-post-republisher' ); ?></a></p>
				</div>
			</div>
			<div class="license-details">
				<div class="license-radio-button">
					<input type="radio" name="<?php echo esc_attr( $option_name ); ?>" value="cc0" <?php checked( $option_value, 'cc0' ); ?>>
				</div>
				<div class="license-meta">
					<img src="<?php echo esc_url( $this->assets_url . 'img/cc0.png' ); ?>" alt="No Rights Reserved" />
					<h3 class="license-name"><?php esc_html_e( 'No Rights Reserved', 'cc-post-republisher' ); ?></h3>
					<h3 class="license-code"><?php esc_html_e( 'CC0', 'cc-post-republisher' ); ?></h3>
				</div>
				<div class="license-description">
					<p><?php esc_html_e( 'The person who associated a work with this deed has dedicated the work to the public domain by waiving all of his or her rights to the work worldwide under copyright law, including all related and neighboring rights, to the extent allowed by law. You can copy, modify, distribute and perform the work, even for commercial purposes, all without asking permission.', 'cc-post-republisher' ); ?></p>
					<p><a href="<?php echo esc_url( __( 'https://creativecommons.org/publicdomain/zero/1.0', 'cc-post-republisher' ) ); ?>" target="_blank"><?php esc_html_e( 'View License Deed', 'cc-post-republisher' ); ?></a> | <a href="<?php echo esc_url( __( 'https://creativecommons.org/publicdomain/zero/1.0/legalcode', 'cc-post-republisher' ) ); ?>" target="_blank"><?php esc_html_e( 'View Legal Code', 'cc-post-republisher' ); ?></a></p>
				</div>
			</div>
			<div class="license-details">
				<div class="license-radio-button">
					<input type="radio" name="<?php echo esc_attr( $option_name ); ?>" value="pdm" <?php checked( $option_value, 'pdm' ); ?>>
				</div>
				<div class="license-meta">
					<img src="<?php echo esc_url( $this->assets_url . 'img/pdm.png' ); ?>" alt="Public Domain Mark" />
					<h3 class="license-name"><?php esc_html_e( 'Public Domain Mark', 'cc-post-republisher' ); ?></h3>
					<h3 class="license-code"><?php esc_html_e( 'PDM', 'cc-post-republisher' ); ?></h3>
				</div>
				<div class="license-description">
					<p><?php esc_html_e( 'The Public Domain Mark is recommended for works that are free of known copyright around the world. These will typically be very old works.  It is not recommended for use with works that are in the public domain in some jurisdictions if they also known to be restricted by copyright in others.', 'cc-post-republisher' ); ?></p>
					<p><a href="<?php echo esc_url( __( 'https://creativecommons.org/share-your-work/public-domain/pdm', 'cc-post-republisher' ) ); ?>" target="_blank"><?php esc_html_e( 'View License Deed', 'cc-post-republisher' ); ?></a></p>
				</div>
			</div>
			<div class="license-details">
				<div class="license-radio-button">
					<input type="radio" name="<?php echo esc_attr( $option_name ); ?>" value="no-cc-license" <?php checked( $option_value, 'no-cc-license' ); ?>>
				</div>
				<div class="license-meta">
					<h3 class="license-code" style="margin-top: 0;"><?php esc_html_e( 'Not Creative Commons Licensed', 'cc-post-republisher' ); ?></h3>
				</div>
				<div class="license-description">
					<p><?php esc_html_e( 'Defaults to non-licensed, allowing you to individually set licensing on posts.', 'cc-post-republisher' ); ?></p>
				</div>
			</div>
		</div>
		<?php
	}

	public function text_input_callback( $args ) {
		$option_group = $args['option_group'];
		$option_id    = $args['option_id'];
		$option_name  = "{$option_group}[{$option_id}]";
		$options      = get_option( $option_group );
		$value        = isset( $options[ $option_id ] ) ? $options[ $option_id ] : '';

		echo '<input type="text" id="' . esc_attr( $option_id ) . '" name="' . esc_attr( $option_name ) . '" value="' . esc_attr( $value ) . '" />';
	}

	// Validate inputs
	public function validate_inputs( $input ) {
		// Create our array for storing the validated options
		$output = array();
		// Loop through each of the incoming options
		foreach ( $input as $key => $value ) {
			// Check to see if the current option has a value. If so, process it.
			if ( isset( $input[ $key ] ) ) {
				// Strip all HTML and PHP tags and properly handle quoted strings
				$output[ $key ] = wp_strip_all_tags( stripslashes( $input[ $key ] ) );
			}
		} // end foreach
		// Return the array processing any additional functions filtered by this action
		return apply_filters( 'validate_inputs', $output, $input );
	}
}
new CC_Post_Republisher_Admin();
