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
// WooCommerce helper functions
function amstheme_is_woocommerce_active() {
    return class_exists('WooCommerce');
}

function amstheme_get_woocommerce_url( $endpoint = 'shop', $fallback = '' ) {
    if ( function_exists( 'wc_get_page_permalink' ) ) {
        return wc_get_page_permalink( $endpoint );
    }

    return $fallback ? $fallback : home_url( '/' );
}

function amstheme_get_cart_url() {
    if ( function_exists( 'wc_get_cart_url' ) ) {
        return wc_get_cart_url();
    }

    return home_url( '/' );
}

function amstheme_get_cart_count() {
    if ( function_exists( 'WC' ) && WC()->cart ) {
        return WC()->cart->get_cart_contents_count();
    }

    return 0;
}

function amstheme_placeholder_img_src() {
    if ( function_exists( 'wc_placeholder_img_src' ) ) {
        return wc_placeholder_img_src();
    }

    return get_template_directory_uri() . '/assets/images/hero-placeholder.jpg';
}

function amstheme_get_auth_page_url() {
    $page = get_page_by_path( 'login-register' );
    if ( $page instanceof WP_Post ) {
        return get_permalink( $page );
    }

    if ( function_exists( 'wc_get_page_permalink' ) ) {
        return wc_get_page_permalink( 'myaccount' );
    }

    return wp_login_url( home_url() );
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
    if (amstheme_is_woocommerce_active() && (is_checkout() || is_cart())) {
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
// Redirect author pages to my-account
add_action( 'template_redirect', function() {
    if ( is_author() ) {
        $my_account_page = get_page_by_path( 'my-account' );
        if ( $my_account_page ) {
            wp_redirect( get_permalink( $my_account_page ), 301 );
            exit;
        }
    }
} );

// -----------------------------------------------------------------------------
// WooCommerce wrappers
function amstheme_wrapper_start() {
    echo '<div class="container">';
}
function amstheme_wrapper_end() {
    echo '</div>';
}
if ( amstheme_is_woocommerce_active() ) {
    add_action( 'woocommerce_before_main_content', 'amstheme_wrapper_start', 10 );
    add_action( 'woocommerce_after_main_content', 'amstheme_wrapper_end', 10 );                                         
}

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
if ( amstheme_is_woocommerce_active() ) {
    add_filter( 'woocommerce_form_field', 'amstheme_form_field_bootstrap', 10, 4 );
}
function amstheme_form_field_bootstrap($field, $key, $args, $value) {
    // Only apply custom styling to checkout and cart pages
    if (amstheme_is_woocommerce_active() && !is_checkout() && !is_cart()) {
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

/**
 * Login / Register form shortcode for the theme.
 */
function amstheme_login_register_shortcode( $atts ) {
    if ( is_user_logged_in() ) {
        return '<div class="ams-alert ams-alert-success">' . esc_html__( 'You are already logged in.', 'ams_souviner' ) . '</div>';
    }

    $errors = new WP_Error();
    $success = '';
    $show_register = false;

    if ( 'POST' === $_SERVER['REQUEST_METHOD'] && ! empty( $_POST['amstheme_auth_action'] ) ) {
        if ( empty( $_POST['amstheme_auth_nonce'] ) || ! wp_verify_nonce( wp_unslash( $_POST['amstheme_auth_nonce'] ), 'amstheme_auth' ) ) {
            $errors->add( 'nonce', __( 'Invalid form submission.', 'ams_souviner' ) );
        } else {
            $action = sanitize_text_field( wp_unslash( $_POST['amstheme_auth_action'] ) );

            if ( 'register' === $action ) {
                $show_register = true;
                $user_login = sanitize_user( wp_unslash( $_POST['user_login'] ) );
                $user_email = sanitize_email( wp_unslash( $_POST['user_email'] ) );

                if ( empty( $user_login ) || empty( $user_email ) ) {
                    $errors->add( 'required', __( 'Username and email are required.', 'ams_souviner' ) );
                } elseif ( ! validate_username( $user_login ) ) {
                    $errors->add( 'invalid_username', __( 'That username is not valid.', 'ams_souviner' ) );
                } elseif ( username_exists( $user_login ) ) {
                    $errors->add( 'username_exists', __( 'This username is already registered.', 'ams_souviner' ) );
                } elseif ( ! is_email( $user_email ) ) {
                    $errors->add( 'invalid_email', __( 'That email address is not valid.', 'ams_souviner' ) );
                } elseif ( email_exists( $user_email ) ) {
                    $errors->add( 'email_exists', __( 'This email address is already registered.', 'ams_souviner' ) );
                } elseif ( ! get_option( 'users_can_register' ) ) {
                    $errors->add( 'registration_closed', __( 'Registration is disabled on this site.', 'ams_souviner' ) );
                } else {
                    $password = wp_generate_password( 12, false );
                    $user_id = wp_create_user( $user_login, $password, $user_email );

                    if ( is_wp_error( $user_id ) ) {
                        $errors = $user_id;
                    } else {
                        wp_new_user_notification( $user_id, null, 'user' );
                        $success = esc_html__( 'Your account has been created. Check your email for login details.', 'ams_souviner' );
                    }
                }
            }
        }
    }

    $output = '<div class="ams-login-register">';

    if ( $errors->has_errors() ) {
        $output .= '<div class="ams-alert ams-alert-error">';
        foreach ( $errors->get_error_messages() as $message ) {
            $output .= '<p>' . esc_html( $message ) . '</p>';
        }
        $output .= '</div>';
    }

    if ( $success ) {
        $output .= '<div class="ams-alert ams-alert-success"><p>' . esc_html( $success ) . '</p></div>';
    }

    $output .= '<div class="ams-login-register-tabs">';
    $output .= '<button type="button" class="btn btn-default ams-tab-button' . ( $show_register ? '' : ' active' ) . '" data-target="ams-login-panel">' . esc_html__( 'Login', 'ams_souviner' ) . '</button>';
    $output .= '<button type="button" class="m-2 btn btn-primary ams-tab-button' . ( $show_register ? ' active' : '' ) . '" data-target="ams-register-panel">' . esc_html__( 'Register', 'ams_souviner' ) . '</button>';
    $output .= '</div>';

    ob_start();
    wp_login_form(
        array(
            'redirect'           => home_url(),
            'label_username'     => __( 'Username or Email', 'ams_souviner' ),
            'label_password'     => __( 'Password', 'ams_souviner' ),
            'label_remember'     => __( 'Remember Me', 'ams_souviner' ),
            'label_log_in'       => __( 'Login', 'ams_souviner' ),
            'id_username'        => 'ams_user_login',
            'id_password'        => 'ams_user_pass',
            'remember'           => true,
        )
    );
    $login_form = ob_get_clean();

    $output .= '<div id="ams-login-panel" class="ams-auth-panel' . ( $show_register ? '' : ' active' ) . '">';
    $output .= $login_form;
    $output .= '</div>';

    $output .= '<div id="ams-register-panel" class="ams-auth-panel' . ( $show_register ? ' active' : '' ) . '">';

    if ( get_option( 'users_can_register' ) ) {
        $output .= '<form method="post" class="ams-register-form">';
        $output .= '<label for="ams_register_user_login">' . esc_html__( 'Username', 'ams_souviner' ) . '</label>';
        $output .= '<input type="text" name="user_login" id="ams_register_user_login" class="form-control" required />';
        $output .= '<label for="ams_register_user_email">' . esc_html__( 'Email', 'ams_souviner' ) . '</label>';
        $output .= '<input type="email" name="user_email" id="ams_register_user_email" class="form-control" required />';
        $output .= '<input type="hidden" name="amstheme_auth_action" value="register" />';
        $output .= wp_nonce_field( 'amstheme_auth', 'amstheme_auth_nonce', true, false );
        $output .= '<button type="submit" class="button button-primary ams-register-submit">' . esc_html__( 'Create Account', 'ams_souviner' ) . '</button>';
        $output .= '</form>';
    } else {
        $output .= '<div class="ams-alert ams-alert-warning">' . esc_html__( 'Registration is currently disabled.', 'ams_souviner' ) . '</div>';
    }

    $output .= '</div>';
    $output .= '</div>';
    $output .= '<script>document.addEventListener("DOMContentLoaded", function(){const buttons=document.querySelectorAll(".ams-tab-button");buttons.forEach(button=>{button.addEventListener("click",()=>{const target=button.dataset.target;document.querySelectorAll(".ams-tab-button").forEach(el=>el.classList.remove("active"));document.querySelectorAll(".ams-auth-panel").forEach(el=>el.classList.remove("active"));button.classList.add("active");document.getElementById(target).classList.add("active");});});});</script>';

    return $output;
}
add_shortcode( 'amstheme_login_register', 'amstheme_login_register_shortcode' );

