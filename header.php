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

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
	<div class="container">
		<a class="navbar-brand" href="<?php echo home_url(); ?>">
			<?php bloginfo( 'name' ); ?>
		</a>

		<button 
			class="navbar-toggler" 
			type="button" 
			data-bs-toggle="collapse" 
			data-bs-target="#mainNav">
			<span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse" id="mainNav">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'menu_class'     => 'navbar-nav ms-auto mb-2 mb-lg-0',
					'container'      => false,
					'walker'         => new WP_Bootstrap_Navwalker(),
				)
			);
			?>
		</div>
	</div>
</nav>
