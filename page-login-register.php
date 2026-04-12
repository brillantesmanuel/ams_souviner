<?php
/**
 * Template Name: Login / Register
 * Template Post Type: page
 */

// Redirect logged-in users to account page
if ( is_user_logged_in() ) {
    $account_page = get_page_by_path( 'my-account' );
    if ( $account_page ) {
        wp_safe_remote_get( get_permalink( $account_page ) );
        wp_redirect( get_permalink( $account_page ) );
        exit;
    }
}

get_header();
?>

<div class="container py-5">
    <div class="row">
        <!-- Login Column -->
        <div class="col-lg-6 mb-4 mb-lg-0">
            <div class="ams-auth-card">
                <h2 class="ams-auth-title">Login</h2>
                <?php 
                if ( amstheme_is_woocommerce_active() ) {
                    echo do_shortcode( '[woocommerce_my_account]' );
                } else {
                    ?>
                    <form method="post" action="<?php echo esc_url( wp_login_url() ); ?>">
                        <div class="mb-3">
                            <label for="ams_user_login" class="form-label"><?php esc_html_e( 'Username or Email', 'ams_souviner' ); ?></label>
                            <input type="text" name="log" id="ams_user_login" class="form-control" required />
                        </div>

                        <div class="mb-3">
                            <label for="ams_user_pass" class="form-label"><?php esc_html_e( 'Password', 'ams_souviner' ); ?></label>
                            <input type="password" name="pwd" id="ams_user_pass" class="form-control" required />
                        </div>

                        <div class="form-check mb-3">
                            <input type="checkbox" name="rememberme" id="ams_rememberme" class="form-check-input" />
                            <label class="form-check-label" for="ams_rememberme">
                                <?php esc_html_e( 'Remember Me', 'ams_souviner' ); ?>
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <?php esc_html_e( 'Login', 'ams_souviner' ); ?>
                        </button>
                    </form>
                    <?php
                }
                ?>
            </div>
        </div>

        <!-- Registration Column -->
        <div class="col-lg-6">
            <div class="ams-auth-card">
                <h2 class="ams-auth-title">Register</h2>
                <?php
                if ( get_option( 'users_can_register' ) ) {
                    $registration_errors = new WP_Error();
                    $success_message = '';

                    if ( 'POST' === $_SERVER['REQUEST_METHOD'] && ! empty( $_POST['amstheme_register_submit'] ) ) {
                        if ( empty( $_POST['amstheme_register_nonce'] ) || ! wp_verify_nonce( wp_unslash( $_POST['amstheme_register_nonce'] ), 'amstheme_register' ) ) {
                            $registration_errors->add( 'nonce', __( 'Invalid form submission.', 'ams_souviner' ) );
                        } else {
                            $user_email = sanitize_email( wp_unslash( $_POST['user_email'] ?? '' ) );

                            if ( empty( $user_email ) ) {
                                $registration_errors->add( 'required', __( 'Email is required.', 'ams_souviner' ) );
                            } elseif ( ! is_email( $user_email ) ) {
                                $registration_errors->add( 'invalid_email', __( 'That email address is not valid.', 'ams_souviner' ) );
                            } elseif ( email_exists( $user_email ) ) {
                                $registration_errors->add( 'email_exists', __( 'This email address is already registered.', 'ams_souviner' ) );
                            } else {
                                // Auto-generate username from email
                                $user_login = strtolower( explode( '@', $user_email )[0] );
                                $user_login = sanitize_user( $user_login );

                                // If username already exists, append random number
                                if ( username_exists( $user_login ) ) {
                                    $user_login = $user_login . rand( 1000, 9999 );
                                }

                                $password = wp_generate_password( 12, false );
                                $user_id = wp_create_user( $user_login, $password, $user_email );

                                if ( is_wp_error( $user_id ) ) {
                                    $registration_errors = $user_id;
                                } else {
                                    wp_new_user_notification( $user_id, null, 'user' );
                                    $success_message = __( 'Your account has been created. Check your email for login details.', 'ams_souviner' );
                                }
                            }
                        }
                    }

                    if ( $registration_errors->has_errors() ) {
                        ?>
                        <div class="ams-alert ams-alert-error">
                            <?php
                            foreach ( $registration_errors->get_error_messages() as $message ) {
                                echo '<p>' . esc_html( $message ) . '</p>';
                            }
                            ?>
                        </div>
                        <?php
                    }

                    if ( $success_message ) {
                        ?>
                        <div class="ams-alert ams-alert-success">
                            <p><?php echo esc_html( $success_message ); ?></p>
                        </div>
                        <?php
                    }
                    ?>

                    <form method="post" class="ams-register-form">
                        <div class="mb-3">
                            <label for="ams_register_user_email" class="form-label"><?php esc_html_e( 'Email', 'ams_souviner' ); ?></label>
                            <input type="email" name="user_email" id="ams_register_user_email" class="form-control" required />
                        </div>

                        <?php wp_nonce_field( 'amstheme_register', 'amstheme_register_nonce' ); ?>
                        <input type="hidden" name="amstheme_register_submit" value="1" />
                        
                        <p><?php esc_html_e( 'A link to set a new password will be sent to your email address.', 'ams_souviner' ); ?></p>

                        <p><?php esc_html_e( 'Your personal data will be used to support your experience throughout this website, to manage access to your account, and for other purposes described in our ', 'ams_souviner' ); ?><a href="<?php echo esc_url( home_url() . '/privacy-policy' ) ?>" target="_blank"><?php esc_html_e( 'Privacy Policy', 'ams_souviner' ); ?></a>.</p>

                        <button type="submit" class="btn btn-primary">
                            <?php esc_html_e( 'Register', 'ams_souviner' ); ?>
                        </button>
                    </form>

                    <?php
                } else {
                    ?>
                    <div class="ams-alert ams-alert-warning">
                        <?php esc_html_e( 'Registration is currently disabled.', 'ams_souviner' ); ?>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>

