<?php
/**
 * Admin settings for plugin
 *
 * @package Creative Commons Post Republisher
 */
class CC_Post_Republisher_Admin {

	/**
	 * Constructs admin.
	 */
	public function __construct() {

		$this->plugin_name = 'cc-post-republisher';
		$this->version     = '1.0.0';
		$this->assets_url  = plugin_dir_url( __FILE__ ) . 'assets/';

		add_action( 'admin_menu', array( $this, 'setup_plugin_options_menu' ), 9 );
		add_action( 'admin_init', array( $this, 'initialize_general_settings' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'cc_post_republisher_scripts' ) );

	}

	/**
	 * Loads plugin scripts and styles
	 */
	public function cc_post_republisher_scripts() {

		wp_enqueue_style( 'cc-post-republisher-admin-css', $this->assets_url . 'css/cc-post-republisher-admin.css', array(), '1.0.0' );

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
	 */
	public static function default_general_settings() {

		// Get the site admin email to put into the default terms text.
		$admin_email = get_option( 'admin_email' );
		$site_title  = get_bloginfo( 'name' );

		$defaults = array(
			'termstext'    => "<strong>REPUBLISHING TERMS</strong><p>You may republish this article online or in print under our Creative Commons license. You may not edit or shorten the text, you must attribute the article to {$site_title} and you must include the author’s name in your republication.</p><p>If you have any questions, please email <a href='mailto:{$admin_email}'>{$admin_email}</a></p>",
			'license_type' => 'cc-by',
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

			<h2><?php esc_html_e( 'Creative Commons Post Republisher Settings', 'cc-post-republisher' ); ?></h2>
			<?php
			// Display any settings errors registered to the settings_error hook.
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

		// Control title of republish.
		// Set Terms text.
		// Choose CC license to use.
		// Choose whether to display at end of post or not.
		add_settings_section(
			'general_settings_section',                      // ID used to identify this section and with which to register options.
			__( 'General Settings', 'cc-post-republisher' ), // Title to be displayed on the administration page.
			'',                                              // Callback used to render the description of the section.
			'cc_post_republisher_general_settings'           // Page on which to add this section of options.
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
				'option_description' => 'Select the license that you want to apply to your post content.',
			)
		);

		register_setting(
			'cc_post_republisher_general_settings',
			'cc_post_republisher_settings'
		);

	}

	/**
	 * Input Callbacks
	 *
	 * @param array $text_input The callback for the textbox.
	 */
	public function text_input_callback( $text_input ) {

		// Get arguments from setting.
		$option_group = $text_input['option_group'];
		$option_id    = $text_input['option_id'];
		$option_name  = "{$option_group}[{$option_id}]";

		// Get existing option from database.
		$options      = get_option( $option_group );
		$option_value = isset( $options[ $option_id ] ) ? $options[ $option_id ] : '0';

		// Render the output.
		echo "<input type='text' id='{$option_id}' name='{$option_name}' value='{$option_value}' />";

	}

	/**
	 * Checkbox Callbacks
	 *
	 * @param array $checkbox_input The callback for the checkbox.
	 */
	public function checkbox_input_callback( $checkbox_input ) {

		// Get arguments from setting.
		$option_group       = $checkbox_input['option_group'];
		$option_id          = $checkbox_input['option_id'];
		$option_name        = "{$option_group}[{$option_id}]";
		$option_description = $checkbox_input['option_description'];

		// Get existing option from database.
		$options      = get_option( $option_group );
		$option_value = isset( $options[ $option_id ] ) ? $options[ $option_id ] : '';

		// Render the output.
		$input  = '';
		$input .= "<input type='checkbox' id='{$option_id}' name='{$option_name}' value='1' " . checked( $option_value, 1, false ) . ' />';
		$input .= "<label for='{$option_id}'>{$option_description}</label>";

		echo $input;

	}

	/**
	 * Editor Callbacks
	 *
	 * @param array $wp_editor_input The callback for the editor.
	 */
	public function wp_editor_input_callback( $wp_editor_input ) {

		// Get existing option from database.
		$option_group = $wp_editor_input['option_group'];
		$option_id    = $wp_editor_input['option_id'];
		$option_name  = "{$option_group}[{$option_id}]";
		$options      = get_option( $option_group );
		$content      = isset( $options[ $option_id ] ) ? $options[ $option_id ] : '';

		// Get arguments from setting.
		$settings = array(
			'quicktags'     => array( 'buttons' => 'strong,em,del,ul,ol,li,close' ),
			'textarea_name' => $option_name,
		);
		// Render the output.
		wp_editor( $content, $option_id, $settings );

	}

	/**
	 * Editor Callbacks
	 *
	 * @param array $license_input The callback for the license input fields.
	 */
	public function license_input_callback( $license_input ) {

		// Get arguments from setting.
		$option_group       = $license_input['option_group'];
		$option_id          = $license_input['option_id'];
		$option_name        = "{$option_group}[{$option_id}]";
		$option_description = $license_input['option_description'];

		// Get existing option from database.
		$options      = get_option( $option_group );
		$option_value = isset( $options[ $option_id ] ) ? $options[ $option_id ] : '';

		// Render the output.
		echo "<h4>{$option_description}</h4>";
		?>
		<div class="cc-post-republisher-licenses">
			<div class="license-details">
				<div class="license-radio-button">
					<input type="radio" name="<?php echo esc_html( $option_name ); ?>" value="cc-by" <?php checked( $option_value, 'cc-by' ); ?>>
				</div>
				<div class="license-meta">
					<img src="<?php echo esc_url( $this->assets_url ) . 'img/cc-by.png'; ?>" alt="Creative Commons License Attribution CC BY" />
					<h3 class="license-name">Attribution</h3>
					<h3 class="license-code">CC BY</h3>
				</div>
				<div class="license-description">
					<p>This license lets others distribute, remix, tweak, and build upon your work, even commercially, as long as they credit you for the original creation. This is the most accommodating of licenses offered. Recommended for maximum dissemination and use of licensed materials.</p>
					<p><a href="https://creativecommons.org/licenses/by/4.0" target="_blank">View License Deed</a> | <a href="https://creativecommons.org/licenses/by/4.0/legalcode" target="_blank">View Legal Code</a></p>
				</div>
			</div>
			<div class="license-details">
				<div class="license-radio-button">
					<input type="radio" name="<?php echo esc_html( $option_name ); ?>" value="cc-by-sa" <?php checked( $option_value, 'cc-by-sa' ); ?>>
				</div>
				<div class="license-meta">
					<img src="<?php echo esc_url( $this->assets_url ) . 'img/cc-by-sa.png'; ?>" alt="Creative Commons License Attribution-ShareAlike CC BY-SA" />
					<h3 class="license-name">Attribution-ShareAlike</h3>
					<h3 class="license-code">CC BY-SA</h3>
				</div>
				<div class="license-description">
					<p>This license lets others remix, tweak, and build upon your work even for commercial purposes, as long as they credit you and license their new creations under the identical terms. This license is often compared to “copyleft” free and open source software licenses. All new works based on yours will carry the same license, so any derivatives will also allow commercial use. This is the license used by Wikipedia, and is recommended for materials that would benefit from incorporating content from Wikipedia and similarly licensed projects.</p>
					<p><a href="https://creativecommons.org/licenses/by-sa/4.0" target="_blank">View License Deed</a> | <a href="https://creativecommons.org/licenses/by-sa/4.0/legalcode" target="_blank">View Legal Code</a></p>
				</div>
			</div>
			<div class="license-details">
				<div class="license-radio-button">
					<input type="radio" name="<?php echo esc_html( $option_name ); ?>" value="cc-by-nd" <?php checked( $option_value, 'cc-by-nd' ); ?>>
				</div>
				<div class="license-meta">
					<img src="<?php echo esc_url( $this->assets_url ) . 'img/cc-by-nd.png'; ?>" alt="Creative Commons License Attribution-NoDerivs CC BY-ND" />
					<h3 class="license-name">Attribution-NoDerivs</h3>
					<h3 class="license-code">CC BY-ND</h3>
				</div>
				<div class="license-description">
					<p>This license allows for redistribution, commercial and non-commercial, as long as it is passed along unchanged and in whole, with credit to you.</p>
					<p><a href="https://creativecommons.org/licenses/by-nd/4.0" target="_blank">View License Deed</a> | <a href="https://creativecommons.org/licenses/by-nd/4.0/legalcode" target="_blank">View Legal Code</a></p>
				</div>
			</div>
			<div class="license-details">
				<div class="license-radio-button">
					<input type="radio" name="<?php echo esc_html( $option_name ); ?>" value="cc-by-nc" <?php checked( $option_value, 'cc-by-nc' ); ?>>
				</div>
				<div class="license-meta">
					<img src="<?php echo esc_url( $this->assets_url ) . 'img/cc-by-nc.png'; ?>" alt="Creative Commons License Attribution-NonCommercial CC BY-NC" />
					<h3 class="license-name">Attribution-NonCommercial</h3>
					<h3 class="license-code">CC BY-NC</h3>
				</div>
				<div class="license-description">
					<p>This license lets others remix, tweak, and build upon your work non-commercially, and although their new works must also acknowledge you and be non-commercial, they don’t have to license their derivative works on the same terms.</p>
					<p><a href="https://creativecommons.org/licenses/by-nc/4.0" target="_blank">View License Deed</a> | <a href="https://creativecommons.org/licenses/by-nc/4.0/legalcode" target="_blank">View Legal Code</a></p>
				</div>
			</div>
			<div class="license-details">
				<div class="license-radio-button">
					<input type="radio" name="<?php echo esc_html( $option_name ); ?>" value="cc-by-nc-sa" <?php checked( $option_value, 'cc-by-nc-sa' ); ?>>
				</div>
				<div class="license-meta">
					<img src="<?php echo esc_url( $this->assets_url ) . 'img/cc-by-nc-sa.png'; ?>" alt="Creative Commons License Attribution-NonCommercial-ShareAlike CC BY-NC-SA" />
					<h3 class="license-name">Attribution-NonCommercial-ShareAlike</h3>
					<h3 class="license-code">CC BY-NC-SA</h3>
				</div>
				<div class="license-description">
					<p>This license lets others remix, tweak, and build upon your work non-commercially, as long as they credit you and license their new creations under the identical terms.</p>
					<p><a href="https://creativecommons.org/licenses/by-nc-sa/4.0" target="_blank">View License Deed</a> | <a href="https://creativecommons.org/licenses/by-nc-sa/4.0/legalcode" target="_blank">View Legal Code</a></p>
				</div>
			</div>
			<div class="license-details">
				<div class="license-radio-button">
					<input type="radio" name="<?php echo esc_html( $option_name ); ?>" value="cc-by-nc-nd" <?php checked( $option_value, 'cc-by-nc-nd' ); ?>>
				</div>
				<div class="license-meta">
					<img src="<?php echo esc_url( $this->assets_url ) . 'img/cc-by-nc-nd.png'; ?>" alt="Creative Commons License Attribution-NonCommercial-NoDerivs CC BY-NC-ND" />
					<h3 class="license-name">Attribution-NonCommercial-NoDerivs</h3>
					<h3 class="license-code">CC BY-NC-ND</h3>
				</div>
				<div class="license-description">
					<p>This license is the most restrictive of our six main licenses, only allowing others to download your works and share them with others as long as they credit you, but they can’t change them in any way or use them commercially.</p>
					<p><a href="https://creativecommons.org/licenses/by-nc-nd/4.0" target="_blank">View License Deed</a> | <a href="https://creativecommons.org/licenses/by-nc-nd/4.0/legalcode" target="_blank">View Legal Code</a></p>
				</div>
			</div>
			<div class="license-details">
				<div class="license-radio-button">
					<input type="radio" name="<?php echo esc_html( $option_name ); ?>" value="no-cc-license" <?php checked( $option_value, 'no-cc-license' ); ?>>
				</div>
				<div class="license-meta">
					<img src="<?php echo esc_url( $this->assets_url ) . 'img/no-cc-license.png'; ?>" alt="Creative Commons License Attribution-NonCommercial-NoDerivs CC BY-NC-ND" />
					<h3 class="license-name">No Creative Commons License</h3>
				</div>
				<div class="license-description">
					<p>Select No Creative Commons License if you want to default to no license and will select licenses on individual posts.</p>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Validate inputs
	 *
	 * @param array $input The input field.
	 */
	public function validate_inputs( $input ) {
		// Create our array for storing the validated options.
		$output = array();
		// Loop through each of the incoming options.
		foreach ( $input as $key => $value ) {
			// Check to see if the current option has a value. If so, process it.
			if ( isset( $input[ $key ] ) ) {

				// Strip all HTML and PHP tags and properly handle quoted strings.
				$output[ $key ] = strip_tags( stripslashes( $input[ $key ] ) );

			}
		}
		// Return the array processing any additional functions filtered by this action.
		return apply_filters( 'validate_inputs', $output, $input );
	}


}
new CC_Post_Republisher_Admin();
