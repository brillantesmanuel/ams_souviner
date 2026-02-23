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

    // Bootstrap Icons
    wp_enqueue_style(
        'bootstrap-icons',
        'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css',
        [],
        '1.11.0'
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

    // Header JavaScript (Global)
    wp_enqueue_script(
        'header-js',
        get_template_directory_uri() . '/assets/js/header.js',
        ['jquery'],
        $theme_version,
        true
    );

    // Front page responsive styles
    if (is_front_page()) {
        wp_enqueue_style(
            'front-page-responsive',
            get_template_directory_uri() . '/assets/css/front-page-responsive.css',
            ['main-style'],
            $theme_version
        );

        wp_enqueue_script(
            'front-page-js',
            get_template_directory_uri() . '/assets/js/front-page.js',
            [],
            $theme_version,
            true
        );
    }

    // Checkout JavaScript
    if (is_checkout() || is_cart()) {
        wp_enqueue_script(
            'checkout-js',
            get_template_directory_uri() . '/assets/js/checkout.js',
            [],
            $theme_version,
            true
        );
    }
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

// -----------------------------------------------------------------------------
// Custom WooCommerce Form Field Styling
add_filter('woocommerce_form_field', 'amstheme_form_field_bootstrap', 10, 4);
function amstheme_form_field_bootstrap($field, $key, $args, $value) {
    // Only apply custom styling to checkout and cart pages
    if (!is_checkout() && !is_cart()) {
        return $field;
    }

    // Extract field variables
    $type = isset($args['type']) ? $args['type'] : 'text';
    $required = isset($args['required']) && $args['required'] ? true : false;
    $class = isset($args['class']) ? $args['class'] : [];
    $label = isset($args['label']) ? $args['label'] : '';
    $placeholder = isset($args['placeholder']) ? $args['placeholder'] : '';

    // Start output
    $field = '<div class="woocommerce-form-row form-group">';

    if ('checkbox' === $type) {
        $field .= '<div class="form-check">';
        $field .= '<input type="checkbox" class="form-check-input" id="' . esc_attr($key) . '" name="' . esc_attr($key) . '" value="1" ' . ($value ? 'checked' : '') . ' />';
        if ($label) {
            $field .= '<label class="form-check-label" for="' . esc_attr($key) . '">' . wp_kses_post($label);
            if ($required) {
                $field .= ' <span class="text-danger">*</span>';
            }
            $field .= '</label>';
        }
        $field .= '</div>';
    } elseif ('textarea' === $type) {
        if ($label) {
            $field .= '<label for="' . esc_attr($key) . '" class="form-label">' . wp_kses_post($label);
            if ($required) {
                $field .= ' <span class="text-danger">*</span>';
            }
            $field .= '</label>';
        }
        $field .= '<textarea id="' . esc_attr($key) . '" name="' . esc_attr($key) . '" class="form-control" rows="3"' . ($placeholder ? ' placeholder="' . esc_attr($placeholder) . '"' : '') . '>' . wp_kses_post($value) . '</textarea>';
    } elseif ('select' === $type) {
        if ($label) {
            $field .= '<label for="' . esc_attr($key) . '" class="form-label">' . wp_kses_post($label);
            if ($required) {
                $field .= ' <span class="text-danger">*</span>';
            }
            $field .= '</label>';
        }
        $field .= '<select id="' . esc_attr($key) . '" name="' . esc_attr($key) . '" class="form-control"' . ($required ? ' required' : '') . '>';
        if (isset($args['options'])) {
            foreach ($args['options'] as $option_key => $option_label) {
                $selected = $option_key === $value ? ' selected' : '';
                $field .= '<option value="' . esc_attr($option_key) . '"' . $selected . '>' . wp_kses_post($option_label) . '</option>';
            }
        }
        $field .= '</select>';
    } else {
        if ($label) {
            $field .= '<label for="' . esc_attr($key) . '" class="form-label">' . wp_kses_post($label);
            if ($required) {
                $field .= ' <span class="text-danger">*</span>';
            }
            $field .= '</label>';
        }
        if ('hidden' === $type) {
            $field .= '<input type="' . esc_attr($type) . '" id="' . esc_attr($key) . '" name="' . esc_attr($key) . '" value="' . esc_attr($value) . '" />';
        } else {
            $input_type = in_array($type, ['email', 'tel', 'number', 'date', 'time']) ? $type : 'text';
            $field .= '<input type="' . esc_attr($input_type) . '" class="form-control" id="' . esc_attr($key) . '" name="' . esc_attr($key) . '" value="' . esc_attr($value) . '"' . ($placeholder ? ' placeholder="' . esc_attr($placeholder) . '"' : '') . ($required ? ' required' : '') . ' />';
        }
    }

    $field .= '</div>';

    return $field;
}

