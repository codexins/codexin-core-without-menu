<?php
/**
 * Template Loader
 *
 * @since 1.0
 */

// Do not allow directly accessing this file.
defined( 'ABSPATH' ) OR die( esc_html__( 'This script cannot be accessed directly.', 'codexin' ) );

/**
 * Locate template.
 *
 * @since 1.0
 *
 * @param 		string 	$template	Template to load.
 * @return 		string 				Path to the template file.
 */
add_filter('template_include', 'codexin_testimonial_template');

function codexin_testimonial_template( $template ) {
	if ( is_post_type_archive( 'testimonial' ) ) {
		$theme_files = array( 'archive-testimonial.php', 'templates/archive-testimonial.php' );
		$exists_in_theme = locate_template( $theme_files, false );
		if ( $exists_in_theme != '' ) {
			return $exists_in_theme;
		} else {
			return CODEXIN_CORE_ROOT_DIR . 'templates/archive-testimonial.php';
		}
	}

	if ( is_singular( 'testimonial' ) ) {
		$theme_files = array( 'single-testimonial.php', 'templates/single-testimonial.php' );
		$exists_in_theme = locate_template( $theme_files, false );
		if ( $exists_in_theme != '' ) {
			return $exists_in_theme;
		} else {
			return CODEXIN_CORE_ROOT_DIR . 'templates/single-testimonial.php';
		}
	}
	return $template;
}
?>