<?php
/**
 * Header template for the ams_souviner theme
 *
 * This template displays the <head> section and opening of the body
 *
 * PHP version: 8.0+
 *
 * @category Theme
 * @package  Ams_Soviner
 * @author   Manuel Brillantes II <brillantesmanuel@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-2.0.html GPL v2 or later
 * @link     https://brillantesmanuel.website
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<!-- Top Header Bar -->
<div style="background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%); padding: 0.5rem 0; border-bottom: 1px solid #444;">
	<div class="container">
		<div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
			<div style="color: #999; font-size: 0.85rem; letter-spacing: 0.5px;">
				Welcome to AM Souviner Shop
			</div>
			<div style="display: flex; align-items: center; gap: 2rem; flex-wrap: wrap;">
				<?php if ( is_user_logged_in() ) : ?>
					<a href="<?php echo esc_url( wc_get_account_endpoint_url( 'dashboard' ) ); ?>" style="color: #fff; text-decoration: none; font-size: 0.9rem; font-weight: 500; transition: color 0.3s ease;">
						<span style="margin-right: 0.35rem;">👤</span>My Account
					</a>
					<a href="<?php echo esc_url( wp_logout_url( home_url() ) ); ?>" style="color: #fff; text-decoration: none; font-size: 0.9rem; font-weight: 500; transition: color 0.3s ease;">
						Logout
					</a>
				<?php else : ?>
					<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" style="color: #fff; text-decoration: none; font-size: 0.9rem; font-weight: 500; transition: color 0.3s ease;">
						Login / Register
					</a>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>

<!-- Main Navbar -->
<nav style="background: #fff; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08); border-bottom: 1px solid #f0f0f0;" class="navbar navbar-expand-lg">
	<div class="container">
		<a class="navbar-brand" href="<?php echo esc_url( home_url() ); ?>" style="font-size: 1.5rem; color: #0d6efd !important; letter-spacing: -0.5px; font-weight: bold;">
			<?php bloginfo( 'name' ); ?>
		</a>

		<button 
			class="navbar-toggler" 
			type="button" 
			data-bs-toggle="collapse" 
			data-bs-target="#mainNav"
			aria-controls="mainNav"
			aria-expanded="false"
			aria-label="Toggle navigation"
			style="border-color: #0d6efd;">
			<span class="navbar-toggler-icon" style="background-image: url('data:image/svg+xml;charset=utf8,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 30 30%22><path stroke=%22%230d6efd%22 stroke-linecap=%22round%22 stroke-miterlimit=%2210%22 stroke-width=%222%22 d=%22M4 7h22M4 15h22M4 23h22%22/></svg>');"></span>
		</button>

		<div class="collapse navbar-collapse" id="mainNav">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'menu_class'     => 'navbar-nav ms-auto mb-2 mb-lg-0',
					'container'      => false,
					'walker'         => new AMS_Nav_Walker(),
				)
			);
			?>
		</div>

		<!-- Cart Icon -->
		<div style="display: flex; align-items: center; margin-left: 1.5rem;">
			<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" style="position: relative; color: #0d6efd; text-decoration: none; font-size: 1.3rem; transition: color 0.3s ease; display: flex; align-items: center;">
				🛒
				<span style="font-size: 0.7rem; min-width: 20px; height: 20px; background: #0d6efd; color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; position: absolute; top: -8px; right: -8px; font-weight: bold;">
					<?php echo WC()->cart->get_cart_contents_count(); ?>
				</span>
			</a>
		</div>
	</div>
</nav>
