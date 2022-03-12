<?php
/**
 * @package Creative Commons Post Republisher
 */
class CC_Post_Republisher {

	private static $post;

	private $licenses;

	// Get options from DB
	// Get content and title of post
	// Clean content and title of post
	public function __construct() {

		$this->plugin_name = 'cc-post-republisher';
		$this->version     = '1.4.0';
		$this->assets_url  = plugin_dir_url( __FILE__ ) . 'assets/';

		$this->load_republish_on_single();

		$this->licenses = array(
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
			'cc0' => array(
				'license_type'        => 'cc0',
				'license_image'       => 'cc0.png',
				'license_name'        => __( 'No Rights Reserved', 'cc-post-republisher' ),
				'license_code'        => __( 'CC0 1.0 Universal (CC0 1.0) Public Domain Dedication', 'cc-post-republisher' ),
				'license_description' => __( 'The person who associated a work with this deed has dedicated the work to the public domain by waiving all of his or her rights to the work worldwide under copyright law, including all related and neighboring rights, to the extent allowed by law. You can copy, modify, distribute and perform the work, even for commercial purposes, all without asking permission.', 'cc-post-republisher' ),
				'license_url'         => __( 'https://creativecommons.org/publicdomain/zero/1.0/', 'cc-post-republisher' ),
				'license_legal_url'   => __( 'https://creativecommons.org/publicdomain/zero/1.0/legalcode', 'cc-post-republisher' ),
			),
			'pdm' => array(
				'license_type'        => 'pdm',
				'license_image'       => 'pdm.png',
				'license_name'        => __( 'Public Domain Mark "No Known Copyright"', 'cc-post-republisher' ),
				'license_code'        => __( 'Public Domain Mark', 'cc-post-republisher' ),
				'license_description' => __( 'The Public Domain Mark is recommended for works that are free of known copyright around the world. These will typically be very old works.  It is not recommended for use with works that are in the public domain in some jurisdictions if they also known to be restricted by copyright in others.', 'cc-post-republisher' ),
				'license_url'         => __( 'https://creativecommons.org/share-your-work/public-domain/pdm', 'cc-post-republisher' ),
				'license_legal_url'   => __( 'https://creativecommons.org/share-your-work/public-domain/pdm', 'cc-post-republisher' ),
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

			wp_enqueue_style( 'cc-post-republisher-css', $this->assets_url . 'css/cc-post-republisher.css', array(), '1.4.0' );
			wp_enqueue_script( 'cc-post-republisher-js', $this->assets_url . 'js/cc-post-republisher.js', array(), '1.4.0', true );

		}

	}

	/**
	 * Loads plugin scripts and styles
	 */
	public function cc_post_republisher_body_class( $classes ) {

		if ( is_single() ) {

			$classes[] = 'cc-post-republisher';

		}

		return $classes;

	}

	/**
	 * Loads button to open modal
	 */
	public function cc_post_republisher_open_modal( $content ) {

		if ( is_single() ) {

			// Get the license for this post
			$post_license = $this->get_license();

			// If this has a CC license attributed, display open modal
			if ( 'no-cc-license' !== $post_license ) {

				$license_name  = $this->licenses[ $post_license ]['license_name'];
				$license_text  = __( 'Creative Commons License', 'cc-post-republisher' );
				$license_img   = $this->licenses[ $post_license ]['license_image'];
				$license_image = "<img src='{$this->assets_url}img/{$license_img}' alt='{$license_text} {$license_name}' />";

				$content .= '<button id="cc-post-republisher-modal-button-open">' . $license_image . __( 'Republish', 'cc-post-republisher' ) . '</button>';

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

		// Get the license settings to pull the global default
		$ccpr_options = get_option( 'cc_post_republisher_settings' );

		// Get the license selected for this specific post
		$post_license = get_post_meta( get_the_id(), 'creative_commons_post_republisher_license-type' );

		// If we've set to use the default post license (or none is set for this post), use the default
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

		// Get the license for this post
		$post_license = $this->get_license();

		if ( 'cc0' === $post_license || 'pdm' === $post_license ) {

			$license_url   = $this->licenses[ $post_license ]['license_url'];
			$license_name  = $this->licenses[ $post_license ]['license_name'];
			$license_img   = $this->licenses[ $post_license ]['license_image'];
			$license_image = "<img src='{$this->assets_url}img/{$license_img}' alt='{$license_name}' />";

			$license_type = "<div id='cc-post-republisher-license'><h3>License</h3><a href='{$license_url}' target='_blank'>{$license_image}{$license_name}</a></div>";

			return $license_type;

		}

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

	public function render_republish_box() {

		global $post;

		if ( is_single() ) {

			echo '<div id="cc-post-republisher-modal-container">';

				echo '<div id="cc-post-republisher-modal">';

					echo '<button id="cc-post-republisher-modal-button-close">&times;</button>';

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
