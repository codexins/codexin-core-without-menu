<?php
/*
Plugin Name: Codexin Core
Plugin URI: http://themeitems.com
Description: A plugin to carry out all the core functionalities for Codexin theme
Version: 1.0
Author: Themeitems
Author URI: http://themeitems.com
License: GPL2
Text Domain: codexin
*/

// Declaring Global Constants for plugin paths and URL
define( 'CODEXIN_CORE_VERSION', '1.0' );

define( 'CODEXIN_CORE_INC_DIR', plugin_dir_path( __FILE__ ) . 'inc' );
define( 'CODEXIN_CORE_ASSET_DIR', plugin_dir_url( __FILE__ ) . 'assets' );
define( 'CODEXIN_CORE_JS_DIR', CODEXIN_CORE_ASSET_DIR . '/js/shortcode-js' );
define( 'CODEXIN_CORE_SC_DIR', plugin_dir_path( __FILE__ ) . 'inc/shortcodes' );
define( 'CODEXIN_CORE_WDGT_DIR', plugin_dir_path( __FILE__ ) . 'inc/widgets' );

if( ! class_exists( 'Codexin_Core' ) ) {
	/**
	 * Master class for the Codexin Core Plugin
	 * 
	 * @since v1.0
	 */
	class Codexin_Core {
		/**
		 * Call all loading functions.
		 * 
		 * @since v1.0
		 */
		public function __construct() {
			// Loading the core files
			$this -> codexin_includes();

			// Enquequeing styles and scripts
			$this -> codexin_enqueque();

			// Enquequeing admin styles and scripts
			$this -> codexin_admin_enqueque();

			// Register actions using add_actions() custom function.
			$this -> codexin_add_actions();
		}

		/**
		 * Loading the core files
		 * 
		 * @since v1.0
		 */
		public function codexin_includes() {
			// Registering Custom Posts
			require_once CODEXIN_CORE_INC_DIR . '/custompost.php';

			// Adding metaboxes
			require_once CODEXIN_CORE_INC_DIR . '/metaboxes.php';

			// Registering and Integrating the Shortcodes in King Composer 
			// require_once CODEXIN_CORE_INC_DIR . '/shortcode_loader.php';

			// Adding Helper File
			require_once CODEXIN_CORE_INC_DIR . '/helpers.php';

			// Including post like system
			require_once CODEXIN_CORE_INC_DIR . '/post_like.php';

			// Initalizing custom widgets
			require_once CODEXIN_CORE_WDGT_DIR . '/address-box-widget.php';
			require_once CODEXIN_CORE_WDGT_DIR . '/instagram-widget.php';
			require_once CODEXIN_CORE_WDGT_DIR . '/popular-posts-widget.php';
			require_once CODEXIN_CORE_WDGT_DIR . '/recent-posts-widget.php';
			require_once CODEXIN_CORE_WDGT_DIR . '/social-share-widget.php';
		}

		/**
		 * Enqueques styles and scripts
		 * 
		 * @since v1.0
		 */
		public function codexin_enqueque() {
			add_action( 'wp_enqueue_scripts', array( $this, 'codexin_styles' ), 99 );
			add_action( 'wp_enqueue_scripts', array( $this, 'codexin_script' ), 99 );
		}

		public function codexin_styles() {
			wp_enqueue_style( 'swiper', CODEXIN_CORE_ASSET_DIR . '/css/swiper.min.css', false, '4.4.2','all' );
			wp_enqueue_style( 'photoswipe', CODEXIN_CORE_ASSET_DIR . '/css/photoswipe.min.css', false, '4.1.2','all' );
		}

		public function codexin_script() {
			wp_enqueue_script( 'swiper', CODEXIN_CORE_ASSET_DIR . '/js/swiper.min.js', array( 'jquery' ), 4.4, true );
			wp_enqueue_script( 'photoswipe', CODEXIN_CORE_ASSET_DIR . '/js/photoswipe.js', array( 'jquery' ), 4.1, true );
			wp_enqueue_script( 'codexin-post-like-script', CODEXIN_CORE_ASSET_DIR . '/js/codexin-post-like.js', array( 'jquery' ), '0.5', true );
			wp_localize_script( 'codexin-post-like-script', 'postLikes', array(
				'ajaxurl' 	=> admin_url( 'admin-ajax.php' ),
				'like' 		=> esc_html__( 'Like', 'codexin' ),
				'unlike' 	=> esc_html__( 'Unlike', 'codexin' )
			) );
		}

		/**
		 * Enqueques admin styles and scripts
		 * 
		 * @since v1.0
		 */
		public function codexin_admin_enqueque() {
			add_action( 'admin_enqueue_scripts', array( $this, 'codexin_admin_styles' ), 99 );
		}

		public function codexin_admin_styles() {
			wp_enqueue_style( 'codexin-admin-stylesheet', CODEXIN_CORE_ASSET_DIR . '/css/admin/admin-metabox-styles.css', false, '1.0','all' );
		}

		/**
		 * Add actions and filters in the plugin.
		 * 
		 * @uses add_action()
		 * @uses add_filter()
		 * @since v1.0
		 */
		public function codexin_add_actions() {
			/**
			 * Load plugin text domain.
			 *
			 * @since 1.0.0
			 */
			add_action( 'init', 'codexin_core_load_textdomain' );
			function codexin_core_load_textdomain() {
				load_plugin_textdomain( 'codexin-core', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
			}

			/**
			 * Adding custom image sizes.
			 *
			 * @since 1.0.0
			 */
			add_action( 'init', 'codexin_add_image_sizes' );
			function codexin_add_image_sizes() {
				add_image_size( 'codexin-core-rect-one', 600, 400, true );
				add_image_size( 'codexin-core-rect-two', 570, 464, true );
				add_image_size( 'codexin-core-square-one', 220, 220, true );
				add_image_size( 'codexin-core-square-two', 500, 500, true );
			}

			/**
			 * Initialization of photoswipe
			 *
			 * @since 	v1.0
			 */
			add_action( 'wp_footer', 'codexin_photoswipe_init' );
			function codexin_photoswipe_init() {
				$result = '';
				ob_start();
				?>
		        <!-- Initializing Photoswipe -->
		        <div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
		            <div class="pswp__bg"></div>
		            <div class="pswp__scroll-wrap">
		                <div class="pswp__container">
		                    <div class="pswp__item"></div>
		                    <div class="pswp__item"></div>
		                    <div class="pswp__item"></div>
		                </div>
		                <div class="pswp__ui pswp__ui--hidden">
		                    <div class="pswp__top-bar">
		                        <div class="pswp__counter"></div>
		                        <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>
		                        <button class="pswp__button pswp__button--share" title="Share"></button>
		                        <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>
		                        <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>
		                        <div class="pswp__preloader">
		                            <div class="pswp__preloader__icn">
		                                <div class="pswp__preloader__cut">
		                                    <div class="pswp__preloader__donut"></div>
		                                </div>
		                            </div>
		                        </div>
		                    </div>
		                    <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
		                        <div class="pswp__share-tooltip"></div>
		                    </div>
		                    <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)">
		                    </button>
		                    <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)">
		                    </button>
		                    <div class="pswp__caption">
		                        <div class="pswp__caption__center"></div>
		                    </div>
		                </div>
		            </div>
		        </div>
		        <!-- end of Photoswipe -->
				<?php
				$result .= ob_get_clean();
				printf( '%s', $result );
			}
		}
	}
}


// Instantiating the Master Class
$codexin_core = new Codexin_Core();



// add_filter('template_include', 'testimonial_archive_template');

// function testimonial_archive_template( $template ) {
// 	if ( is_post_type_archive('testimonial') ) {
// 		$theme_files = array('archive-testimonial.php', 'templates/archive-testimonial.php');
// 		$exists_in_theme = locate_template( $theme_files, false );
// 		if ( $exists_in_theme != '' ) {
// 			return $exists_in_theme;
// 		} else {
// 			return plugin_dir_path(__FILE__) . 'templates/archive-testimonial.php';
// 		}
// 	}
// 	return $template;
// }

// add_filter( 'single_template', 'testimonial_single_template', 10, 1 );
// function testimonial_single_template( $single_template ) {
// 	if ( is_singular('testimonial') ) {
// 		$theme_files = array('single-testimonial.php', 'templates/single-testimonial.php');
// 		$exists_in_theme = locate_template( $theme_files, false );
// 		if ( $exists_in_theme != '' ) {
// 			return $exists_in_theme;
// 		} else {
// 			return plugin_dir_path(__FILE__) . 'templates/single-testimonial.php';
// 		}
// 	}
// 	return $template;
// }

?>
