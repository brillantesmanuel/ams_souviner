<?php
/**
 * AMSTheme functions.php
 * Modernized for Composer Bootstrap, WooCommerce, TGMPA
 */

// Load Composer autoloader
require_once __DIR__ . '/vendor/autoload.php';

// -----------------------------------------------------------------------------
// Load all PHP files from /inc automatically
foreach (glob(__DIR__ . '/inc/*.php') as $file) {
    require_once $file;
}


// -----------------------------------------------------------------------------
// Enqueue Styles & Scripts
function amstheme_enqueue_assets() {
    $theme_version = wp_get_theme()->get('Version');

    // Bootstrap CSS & JS from Composer
    wp_enqueue_style(
        'bootstrap-css',
        get_template_directory_uri() . '/vendor/twbs/bootstrap/dist/css/bootstrap.min.css',
        [],
        '5.3.3'
    );

    wp_enqueue_script(
        'bootstrap-js',
        get_template_directory_uri() . '/vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js',
        ['jquery'],
        '5.3.3',
        true
    );

    // Main theme stylesheet
    wp_enqueue_style(
        'main-style',
        get_stylesheet_uri(),
        [],
        $theme_version
    );
}
add_action('wp_enqueue_scripts', 'amstheme_enqueue_assets');

// -----------------------------------------------------------------------------
// WooCommerce wrappers
function amstheme_wrapper_start() {
    echo '<div class="container">';
}
function amstheme_wrapper_end() {
    echo '</div>';
}
add_action('woocommerce_before_main_content', 'amstheme_wrapper_start', 10);
add_action('woocommerce_after_main_content', 'amstheme_wrapper_end', 10);

// -----------------------------------------------------------------------------
// TGMPA Plugin Activation
add_action('tgmpa_register', 'amstheme_register_required_plugins');
function amstheme_register_required_plugins() {
    $plugins = [
        [
            'name'     => 'WooCommerce',
            'slug'     => 'woocommerce',
            'required' => true,
        ],
    ];

    $config = [
        'id'           => 'amstheme',
        'menu'         => 'tgmpa-install-plugins',
        'parent_slug'  => 'themes.php',
        'capability'   => 'edit_theme_options',
        'has_notices'  => true,
        'dismissable'  => false,
        'is_automatic' => true,
    ];

    tgmpa($plugins, $config);
}

// -----------------------------------------------------------------------------
// Admin notice if WooCommerce not installed
add_action('admin_notices', function () {
    if (!class_exists('WooCommerce')) {
        echo '<div class="notice notice-error"><p>';
        echo esc_html__('This theme requires WooCommerce to function properly.', 'amstheme');
        echo '</p></div>';
    }
});
