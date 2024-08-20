<?php
/**
 * Plugin Name:       WP Center On Mobile
 * Description:       Add a Center on Mobile class toggle to group blocks
 * Requires at least: 6.1
 * Requires PHP:      7.0
 * Version:           1.0.0
 * Author:            Andrea Roenning
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       wp-center-on-mobile
 *
 */

/*
 * Similar Approach to Enable Column Direction: https://github.com/ndiego/enable-column-direction 
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Enqueue Editor scripts and styles
 */
function wp_center_on_mobile_enqueue_block_editor_assets() {
	$plugin_path = untrailingslashit( plugin_dir_path( __FILE__ ) );
	$plugin_url  = untrailingslashit( plugin_dir_url( __FILE__ ) );
	$asset_file  = include untrailingslashit( plugin_dir_path( __FILE__ ) ) . '/build/index.asset.php';

	wp_enqueue_script(
		'center-on-mobile-editor-scripts',
		$plugin_url . '/build/index.js',
		$asset_file['dependencies'],
		$asset_file['version']
	);

}
add_action( 'enqueue_block_editor_assets', 'wp_center_on_mobile_enqueue_block_editor_assets' );

/**
 * Enqueue block styles - Applies to both front end and editor
 */
function wp_center_on_mobile_block_styles() {

	$asset_file  = include untrailingslashit( plugin_dir_path( __FILE__ ) ) . '/build/style.asset.php';

	wp_enqueue_block_style (
		'core/group',
			array(
				'handle' => 'enable-center-on-mobile',
				'src'    => plugin_dir_url( __FILE__ ) . 'build/style.css',
				'ver'    => $asset_file['version'],
				'path'   => plugin_dir_path( __FILE__ ) . 'build/style.css',
			)
	);
}
add_action( 'after_setup_theme', 'wp_center_on_mobile_block_styles' );

/*
 * Check for the attribute and change the front end code with HTML Tag Processor
 */
function wp_center_on_mobile_render_block_group( $block_content, $block ) {
	$centered_on_mobile = isset( $block['attrs']['isCenteredOnMobile'] ) ? $block['attrs']['isCenteredOnMobile'] : false;
	
	if ( ! $centered_on_mobile ) {
		return $block_content;
	}

	$p = new WP_HTML_Tag_Processor( $block_content );
	if ( $p->next_tag() ) {
		$p->add_class( 'is-centered-on-mobile' );
	}
	$block_content = $p->get_updated_html();

return $block_content;
}
add_filter( 'render_block_core/group', 'wp_center_on_mobile_render_block_group', 10, 2 );
