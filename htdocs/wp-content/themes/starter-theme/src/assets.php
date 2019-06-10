<?php
/**
 * This file controls all assets in our theme.
 *
 * 'st_' in the function names stands for Starter Theme.
 */

// Define the theme version. Bump the version up to update client-side caches.
define( 'THEME_VERSION', '0.0.0' );

/**
 * Enqueue styles and scripts.
 */
function st_theme_scripts() {
    wp_enqueue_style( 'theme-css', get_template_directory_uri() . '/assets/dist/main.css', [], THEME_VERSION, 'all' );

    wp_enqueue_script( 'theme-js', get_template_directory_uri() . '/assets/dist/main.js', [ 'jquery' ], THEME_VERSION, true );
}
add_action( 'wp_enqueue_scripts', 'st_theme_scripts' );
