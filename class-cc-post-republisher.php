<?php
/**
 * The core post republisher class.
 *
 * @package Creative Commons Post Republisher
 */
class CC_Post_Republisher {

	/**
	 * The licenses in use
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array    $licenses    The licenses available for use.
	 */
	private $licenses;

	/**
	 * Class construct method. Sets up licenses.
	 */
	public function __construct() {

		$this->plugin_name = 'cc-post-republisher';
		$this->version     = '1.0.0';
		$this->assets_url  = plugin_dir_url( __FILE__ ) . 'assets/';

		$this->load_republish_on_single();

		add_action( 'admin_enqueue_scripts', array( $this, 'cc_post_republisher_wp_admin_scripts' ) );

		$this->licenses = array(
			'cc-by'         => array(
				'license_type'        => 'cc-by',
				'license_image'       => 'cc-by.png',
				'license_name'        => 'Attribution',
				'license_code'        => 'CC BY',
				'license_description' => 'This license lets others distribute, remix, tweak, and build upon your work, even commercially, as long as they credit you for the original creation. This is the most accommodating of licenses offered. Recommended for maximum dissemination and use of licensed materials.',
				'license_url'         => 'https://creativecommons.org/licenses/by/4.0',
				'license_legal_url'   => 'https://creativecommons.org/licenses/by/4.0/legalcode',
			),
			'cc-by-sa'      => array(
				'license_type'        => 'cc-by-sa',
				'license_image'       => 'cc-by-sa.png',
				'license_name'        => 'Attribution-ShareAlike',
				'license_code'        => 'CC BY-SA',
				'license_description' => 'This license lets others remix, tweak, and build upon your work even for commercial purposes, as long as they credit you and license their new creations under the identical terms. This license is often compared to “copyleft” free and open source software licenses. All new works based on yours will carry the same license, so any derivatives will also allow commercial use. This is the license used by Wikipedia, and is recommended for materials that would benefit from incorporating content from Wikipedia and similarly licensed projects.',
				'license_url'         => 'https://creativecommons.org/licenses/by-sa/4.0',
				'license_legal_url'   => 'https://creativecommons.org/licenses/by-sa/4.0/legalcode',
			),
			'cc-by-nd'      => array(
				'license_type'        => 'cc-by-nd',
				'license_image'       => 'cc-by-nd.png',
				'license_name'        => 'Attribution-NoDerivs',
				'license_code'        => 'CC BY-ND',
				'license_description' => 'This license allows for redistribution, commercial and non-commercial, as long as it is passed along unchanged and in whole, with credit to you.',
				'license_url'         => 'https://creativecommons.org/licenses/by-nd/4.0',
				'license_legal_url'   => 'https://creativecommons.org/licenses/by-nd/4.0/legalcode',
			),
			'cc-by-nc'      => array(
				'license_type'        => 'cc-by-nc',
				'license_image'       => 'cc-by-nc.png',
				'license_name'        => 'Attribution-NonCommercial',
				'license_code'        => 'CC BY-NC',
				'license_description' => 'This license lets others remix, tweak, and build upon your work non-commercially, and although their new works must also acknowledge you and be non-commercial, they don’t have to license their derivative works on the same terms.',
				'license_url'         => 'https://creativecommons.org/licenses/by-nc/4.0',
				'license_legal_url'   => 'https://creativecommons.org/licenses/by-nc/4.0/legalcode',
			),
			'cc-by-nc-sa'   => array(
				'license_type'        => 'cc-by-nc-sa',
				'license_image'       => 'cc-by-nc-sa.png',
				'license_name'        => 'Attribution-NonCommercial-ShareAlike',
				'license_code'        => 'CC BY-NC-SA',
				'license_description' => 'This license lets others remix, tweak, and build upon your work non-commercially, as long as they credit you and license their new creations under the identical terms.',
				'license_url'         => 'https://creativecommons.org/licenses/by-nc-sa/4.0',
				'license_legal_url'   => 'https://creativecommons.org/licenses/by-nc-sa/4.0/legalcode',
			),
			'cc-by-nc-nd'   => array(
				'license_type'        => 'cc-by-nc-nd',
				'license_image'       => 'cc-by-nc-nd.png',
				'license_name'        => 'Attribution-NonCommercial-NoDerivs',
				'license_code'        => 'CC BY-NC-ND',
				'license_description' => 'This license is the most restrictive of our six main licenses, only allowing others to download your works and share them with others as long as they credit you, but they can’t change them in any way or use them commercially.',
				'license_url'         => 'https://creativecommons.org/licenses/by-nc-nd/4.0',
				'license_legal_url'   => 'https://creativecommons.org/licenses/by-nc-nd/4.0/legalcode',
			),
			'no-cc-license' => array(
				'license_type'        => 'no-cc-license',
				'license_name'        => 'No Creative Commons License',
				'license_description' => 'Select No Creative Commons License if you want to default to no license and will select licenses on individual posts.',
			),
		);

	}

	/**
	 * Loads republish box only on single posts
	 */
	public function load_republish_on_single() {

			add_action( 'wp_footer', array( $this, 'render_republish_box' ), 99 );
			add_action( 'wp_enqueue_scripts', array( $this, 'cc_post_republisher_scripts' ) );
			add_filter( 'the_content', array( $this, 'cc_post_republisher_open_modal' ) );

			add_filter( 'body_class', array( $this, 'cc_post_republisher_body_class' ), 20 );

	}

	/**
	 * Loads plugin scripts and styles
	 */
	public function cc_post_republisher_scripts() {

		if ( is_single() ) {

			wp_enqueue_style( 'cc-post-republisher-css', $this->assets_url . 'css/cc-post-republisher.css', array(), '1.0.0' );
			wp_enqueue_script( 'cc-post-republisher-js', $this->assets_url . 'js/cc-post-republisher.js', array(), '1.0.0', true );

		}

	}

	/**
	 * Loads plugin admin scripts and styles.
	 *
	 * @param string $hook page that this displays on.
	 */
	public function cc_post_republisher_wp_admin_scripts( $hook ) {

		// Load only on ?page=cc_post_republisher_settings.
		if ( 'settings_page_cc_post_republisher_settings' !== $hook ) {
				return;
		}

		wp_enqueue_script( 'cc-post-republisher-admin-js', $this->assets_url . 'js/cc-post-republisher-admin.js', array(), '1.0.0', true );

	}

	/**
	 * Loads plugin scripts and styles.
	 *
	 * @param array $classes Array of existing classes on the post.
	 */
	public function cc_post_republisher_body_class( $classes ) {

		if ( is_single() ) {

			$classes[] = 'cc-post-republisher';

		}

		return $classes;

	}

	/**
	 * Loads button to open modal.
	 *
	 * @param array $content The content of the post.
	 */
	public function cc_post_republisher_open_modal( $content ) {

		if ( is_single() ) {
			// Get the license for this post.
			$post_license = $this->get_license();

			// If this has a CC license attributed, display open modal.
			if ( 'no-cc-license' !== $post_license ) {

				$license_name  = $this->licenses[ $post_license ]['license_name'];
				$license_img   = $this->licenses[ $post_license ]['license_image'];
				$license_image = "<img src='{$this->assets_url}img/{$license_img}' alt='Creative Commons License {$license_name}' />";

				$content .= "<a id='cc-post-republisher-modal-button-open'>{$license_image}Republish</a>";

			}
		}

		return $content;

	}

	/**
	 * Gets the title of the post that we're going to republish
	 */
	public function get_post_republish_title() {

		return get_the_title();

	}

	/**
	 * Gets the content of the post that we're going to republish
	 */
	public function get_post_republish_content() {

		$content = get_the_content();

		return '<textarea id="cc-post-republisher-content-textarea" onfocus="this.select();" readonly>' . htmlspecialchars( $content ) . '</textarea>';

	}

	/**
	 * Gets the license of the post that we're going to republish
	 */
	public function get_license() {

		// Get the license settings to pull the global default.
		$ccpr_options = get_option( 'cc_post_republisher_settings' );

		// Get the license selected for this specific post.
		$post_license = get_post_meta( get_the_id(), 'creative_commons_post_republisher_license-type' );

		// If we've set to use the default post license (or none is set for this post), use the default.
		if ( empty( $post_license ) || 'default' === $post_license[0] ) {
			$post_license = $ccpr_options['license_type'];
		} else {
			$post_license = $post_license[0];
		}

		return $post_license;

	}

	/**
	 * Gets the license of the post that we're going to republish
	 */
	public function get_post_republish_license() {

		// Get the license for this post.
		$post_license = $this->get_license();

		if ( 'no-cc-license' !== $post_license ) {

			$license_url   = $this->licenses[ $post_license ]['license_url'];
			$license_name  = $this->licenses[ $post_license ]['license_name'];
			$license_img   = $this->licenses[ $post_license ]['license_image'];
			$license_image = "<img src='{$this->assets_url}img/{$license_img}' alt='Creative Commons License {$license_name}' />";

			$license_type = "<div id='cc-post-republisher-license'><h3>License</h3><a href='{$license_url}' target='_blank'>{$license_image}Creative Commons {$license_name}</a></div>";

			return $license_type;

		}

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
	 * Renders the republish box on the frontend.
	 */
	public function render_republish_box() {

		global $post;

		if ( is_single() ) {

			echo '<div id="cc-post-republisher-modal-container">';

				echo '<div id="cc-post-republisher-modal">';

					echo '<span id="cc-post-republisher-modal-button-close">&times;</span>';

					echo $this->get_post_republish_terms();

					echo $this->get_post_republish_license();

					echo '<div id="cc-post-republisher-post-content">';

						echo $this->get_post_republish_title();

						echo $this->get_post_republish_content();

					echo '</div>';

				echo '</div>';

			echo '</div>';

		}

	}

}
new CC_Post_Republisher();
