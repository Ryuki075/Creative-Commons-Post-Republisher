<?php
/**
 * @package Creative Commons Post Republisher
 */
class CC_Post_Republisher {

	private static $post;
	private $licenses;
	public $assets_url = CCPR_ASSET_DIR;

	public function __construct() {
		$this->assets_url = plugin_dir_url( __FILE__ ) . 'assets/';
		$this->licenses   = array(
			'cc-by'       => array(
				'license_type'        => 'cc-by',
				'license_image'       => 'cc-by.png',
				'license_name'        => __( 'Attribution', 'cc-post-republisher' ),
				'license_code'        => __( 'CC BY', 'cc-post-republisher' ),
				'license_description' => __( 'This license lets others distribute, remix, tweak, and build upon your work, even commercially, as long as they credit you for the original creation. This is the most accommodating of licenses offered. Recommended for maximum dissemination and use of licensed materials.', 'cc-post-republisher' ),
				'license_url'         => __( 'https://creativecommons.org/licenses/by/4.0', 'cc-post-republisher' ),
				'license_legal_url'   => __( 'https://creativecommons.org/licenses/by/4.0/legalcode', 'cc-post-republisher' ),
			),
			'cc-by-sa'    => array(
				'license_type'        => 'cc-by-sa',
				'license_image'       => 'cc-by-sa.png',
				'license_name'        => __( 'Attribution-ShareAlike', 'cc-post-republisher' ),
				'license_code'        => __( 'CC BY-SA', 'cc-post-republisher' ),
				'license_description' => __( 'This license lets others remix, tweak, and build upon your work even for commercial purposes, as long as they credit you and license their new creations under the identical terms. This license is often compared to “copyleft” free and open source software licenses. All new works based on yours will carry the same license, so any derivatives will also allow commercial use. This is the license used by Wikipedia, and is recommended for materials that would benefit from incorporating content from Wikipedia and similarly licensed projects.', 'cc-post-republisher' ),
				'license_url'         => __( 'https://creativecommons.org/licenses/by-sa/4.0', 'cc-post-republisher' ),
				'license_legal_url'   => __( 'https://creativecommons.org/licenses/by-sa/4.0/legalcode', 'cc-post-republisher' ),
			),
			'cc-by-nd'    => array(
				'license_type'        => 'cc-by-nd',
				'license_image'       => 'cc-by-nd.png',
				'license_name'        => __( 'Attribution-NoDerivs', 'cc-post-republisher' ),
				'license_code'        => __( 'CC BY-ND', 'cc-post-republisher' ),
				'license_description' => __( 'This license allows for redistribution, commercial and non-commercial, as long as it is passed along unchanged and in whole, with credit to you.', 'cc-post-republisher' ),
				'license_url'         => __( 'https://creativecommons.org/licenses/by-nd/4.0', 'cc-post-republisher' ),
				'license_legal_url'   => __( 'https://creativecommons.org/licenses/by-nd/4.0/legalcode', 'cc-post-republisher' ),
			),
			'cc-by-nc'    => array(
				'license_type'        => 'cc-by-nc',
				'license_image'       => 'cc-by-nc.png',
				'license_name'        => __( 'Attribution-NonCommercial', 'cc-post-republisher' ),
				'license_code'        => __( 'CC BY-NC', 'cc-post-republisher' ),
				'license_description' => __( 'This license lets others remix, tweak, and build upon your work non-commercially, and although their new works must also acknowledge you and be non-commercial, they don’t have to license their derivative works on the same terms.', 'cc-post-republisher' ),
				'license_url'         => __( 'https://creativecommons.org/licenses/by-nc/4.0', 'cc-post-republisher' ),
				'license_legal_url'   => __( 'https://creativecommons.org/licenses/by-nc/4.0/legalcode', 'cc-post-republisher' ),
			),
			'cc-by-nc-sa' => array(
				'license_type'        => 'cc-by-nc-sa',
				'license_image'       => 'cc-by-nc-sa.png',
				'license_name'        => __( 'Attribution-NonCommercial-ShareAlike', 'cc-post-republisher' ),
				'license_code'        => __( 'CC BY-NC-SA', 'cc-post-republisher' ),
				'license_description' => __( 'This license lets others remix, tweak, and build upon your work non-commercially, as long as they credit you and license their new creations under the identical terms.', 'cc-post-republisher' ),
				'license_url'         => __( 'https://creativecommons.org/licenses/by-nc-sa/4.0', 'cc-post-republisher' ),
				'license_legal_url'   => __( 'https://creativecommons.org/licenses/by-nc-sa/4.0/legalcode', 'cc-post-republisher' ),
			),
			'cc-by-nc-nd' => array(
				'license_type'        => 'cc-by-nc-nd',
				'license_image'       => 'cc-by-nc-nd.png',
				'license_name'        => __( 'Attribution-NonCommercial-NoDerivs', 'cc-post-republisher' ),
				'license_code'        => __( 'CC BY-NC-ND', 'cc-post-republisher' ),
				'license_description' => __( 'This license is the most restrictive of our six main licenses, only allowing others to download your works and share them with others as long as they credit you, but they can’t change them in any way or use them commercially.', 'cc-post-republisher' ),
				'license_url'         => __( 'https://creativecommons.org/licenses/by-nc-nd/4.0', 'cc-post-republisher' ),
				'license_legal_url'   => __( 'https://creativecommons.org/licenses/by-nc-nd/4.0/legalcode', 'cc-post-republisher' ),
			),
			'cc0'         => array(
				'license_type'        => 'cc0',
				'license_image'       => 'cc0.png',
				'license_name'        => __( 'No Rights Reserved', 'cc-post-republisher' ),
				'license_code'        => __( 'CC0 1.0 Universal (CC0 1.0) Public Domain Dedication', 'cc-post-republisher' ),
				'license_description' => __( 'The person who associated a work with this deed has dedicated the work to the public domain by waiving all of his or her rights to the work worldwide under copyright law, including all related and neighboring rights, to the extent allowed by law. You can copy, modify, distribute and perform the work, even for commercial purposes, all without asking permission.', 'cc-post-republisher' ),
				'license_url'         => __( 'https://creativecommons.org/publicdomain/zero/1.0/', 'cc-post-republisher' ),
				'license_legal_url'   => __( 'https://creativecommons.org/publicdomain/zero/1.0/legalcode', 'cc-post-republisher' ),
			),
			'pdm'         => array(
				'license_type'        => 'pdm',
				'license_image'       => 'pdm.png',
				'license_name'        => __( 'Public Domain Mark "No Known Copyright"', 'cc-post-republisher' ),
				'license_code'        => __( 'Public Domain Mark', 'cc-post-republisher' ),
				'license_description' => __( 'The Public Domain Mark is recommended for works that are free of known copyright around the world. These will typically be very old works.  It is not recommended for use with works that are in the public domain in some jurisdictions if they also known to be restricted by copyright in others.', 'cc-post-republisher' ),
				'license_url'         => __( 'https://creativecommons.org/share-your-work/public-domain/pdm', 'cc-post-republisher' ),
				'license_legal_url'   => __( 'https://creativecommons.org/share-your-work/public-domain/pdm', 'cc-post-republisher' ),
			),
		);

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

		// Fallback for ClassicPress or environments without FSE
		if ( ! $this->is_gutenberg_active() ) {
			add_filter( 'the_content', array( $this, 'cc_post_republisher_add_to_content' ) );
		}
	}

	/**
	 * Check if Gutenberg is active.
	 * Must be used not earlier than plugins_loaded action fired.
	 *
	 * https://gist.github.com/mihdan/8ba1a70d8598460421177c7d31202908
	 *
	 * @return bool
	 */
	private function is_gutenberg_active() {
		$gutenberg    = false;
		$block_editor = false;

		if ( has_filter( 'replace_editor', 'gutenberg_init' ) ) {
			// Gutenberg is installed and activated.
			$gutenberg = true;
		}

		if ( version_compare( $GLOBALS['wp_version'], '5.0-beta', '>' ) ) {
			// Block editor.
			$block_editor = true;
		}

		if ( ! $gutenberg && ! $block_editor ) {
			return false;
		}

		include_once ABSPATH . 'wp-admin/includes/plugin.php';

		if ( ! is_plugin_active( 'classic-editor/classic-editor.php' ) ) {
			return true;
		}

		$use_block_editor = ( get_option( 'classic-editor-replace' ) === 'no-replace' );

		return $use_block_editor;
	}

	/**
	 * Localizes the frontend script with necessary post data
	 */
	public function enqueue_scripts() {
		global $post;

		// Localize the modal script with new data
		wp_localize_script(
			'cc-post-republisher-modal',
			'modalSettings',
			array(
				'root'           => esc_url_raw( rest_url() ),
				'nonce'          => wp_create_nonce( 'wp_rest' ),
				'postID'         => $post->ID,
				'postType'       => $post->post_type,
				'licenseContent' => $this->get_post_republish_license( isset( $post->ID ) ? $post->ID : 0 ),
				'licenseImage'   => $this->get_license_image( isset( $post->ID ) ? $post->ID : 0 ),
				'termsContent'   => $this->get_post_republish_terms(),
			)
		);
	}

	/**
	 * Localizes the dashboard script with necessary post data
	 */
	public function admin_enqueue_scripts() {
		global $post;
		$options        = get_option( 'cc_post_republisher_settings' );
		$active_license = isset( $options['license_type'] ) ? $options['license_type'] : 'cc-by';

		// Localize the block script with new data
		wp_localize_script(
			'cc-post-republisher-block',
			'blockSettings',
			array(
				'activeLicense'      => $active_license,
				'activeLicenseImage' => $this->get_license_image( isset( $post->ID ) ? $post->ID : 0 ),
			)
		);
	}

	/**
	 * Gets the license of the post that we're going to republish
	 */
	public function get_license_type( $post_id ) {
		// Get the license from the post meta if set for this specific post
		$license_type = get_post_meta( $post_id, 'creative_commons_post_republisher_license-type', true );

		if ( $license_type && 'default' !== $license_type ) {
			return $license_type;
		}

		// Fall back to the global setting
		$options = get_option( 'cc_post_republisher_settings' );
		return isset( $options['license_type'] ) ? $options['license_type'] : 'cc-by';
	}

	/**
	 * Gets the license image of the post that we're going to republish
	 */
	public function get_license_image( $post_id ) {
		$license_type = $this->get_license_type( $post_id );

		if ( isset( $this->licenses[ $license_type ] ) ) {
			return $this->assets_url . 'img/' . $this->licenses[ $license_type ]['license_image'];
		}

		// Fall back to a default image if needed
		return $this->assets_url . 'img/cc-by.png';
	}


	/**
	 * Gets the license of the post that we're going to republish
	 */
	public function get_post_republish_license( $post_id ) {

		// Get the license for this post
		$license_type = $this->get_license_type( $post_id );

		if ( isset( $this->licenses[ $license_type ] ) ) {
			$license       = $this->licenses[ $license_type ];
			$license_image = "<img src='{$this->assets_url}img/{$license['license_image']}' alt='{$license['license_name']}' />";
			$license_type  = "<div id='cc-post-republisher-license'><h3>License</h3><a href='{$license['license_url']}' target='_blank'>{$license_image}{$license['license_name']}</a></div>";

			return $license_type;
		}

		if ( 'cc0' === $license_type || 'pdm' === $license_type ) {

			$license_url   = $this->licenses[ $license_type ]['license_url'];
			$license_name  = $this->licenses[ $license_type ]['license_name'];
			$license_img   = $this->licenses[ $license_type ]['license_image'];
			$license_image = "<img src='{$this->assets_url}img/{$license_img}' alt='{$license_name}' />";

			$license_type = "<div id='cc-post-republisher-license'><p><strong>License</strong></p><a href='{$license_url}' target='_blank'>{$license_image}{$license_name}</a></div>";

			return $license_type;

		}

		if ( 'no-cc-license' !== $license_type ) {

			$license_url   = $this->licenses[ $license_type ]['license_url'];
			$license_name  = $this->licenses[ $license_type ]['license_name'];
			$license_img   = $this->licenses[ $license_type ]['license_image'];
			$license_image = "<img src='{$this->assets_url}img/{$license_img}' alt='Creative Commons License {$license_name}' />";

			$license_type = "<div id='cc-post-republisher-license'><p><strong>License</strong></p><a href='{$license_url}' target='_blank'>{$license_image}Creative Commons {$license_name}</a></div>";

			return $license_type;

		}

		return '';
	}

	/**
	 * Gets the terms of the post that we're going to republish
	 */
	public function get_post_republish_terms() {

		$ccpr_options = get_option( 'cc_post_republisher_settings' );

		if ( '' !== $ccpr_options['termstext'] ) {
			return wpautop( $ccpr_options['termstext'] );
		}
	}


	/**
	 * If the classic editor is active, fallback to placing button after the_content()
	 */
	public function cc_post_republisher_add_to_content( $content ) {
		if ( is_singular() && in_the_loop() && is_main_query() ) {

			// Set default values
			$button_text          = isset( $attributes['buttonText'] ) ? $attributes['buttonText'] : 'Republish';
			$active_license       = get_option( 'cc_post_republisher_settings' )['license_type'];
			$active_license_image = $this->get_license_image( get_the_ID() );

			// Render the button with the license image
			ob_start();
			?>
			<div>
				<button id="cc-post-republisher-modal-button-open">
					<img src="<?php echo esc_url( $active_license_image ); ?>" alt="License Image" style="width: 88px; margin-right: 5px;" />
					<span><?php echo esc_html( $button_text ); ?></span>
				</button>
				<div id="cc-post-republisher-modal-container">
					<div id="cc-post-republisher-modal"></div>
				</div>
			</div>
			<?php
			$button_html = ob_get_clean();
			$content    .= $button_html;
		}
		return $content;
	}
}
