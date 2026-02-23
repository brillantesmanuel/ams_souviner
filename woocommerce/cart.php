<?php
/**
 * The template for displaying cart page
 *
 * @package WooCommerce
 */

get_header();
?>

<main id="primary" class="site-main">
	<div class="container my-4">
		<div class="row">
			<div class="col-12">
				<h1 class="page-title mb-4"><?php esc_html_e( 'Shopping Cart', 'woocommerce' ); ?></h1>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-8">
				<?php woocommerce_content(); ?>
			</div>

			<div class="col-lg-4 mt-5 mt-lg-0">
				<div class="card">
					<div class="card-header bg-light">
						<h5 class="card-title mb-0"><?php esc_html_e( 'Cart Summary', 'woocommerce' ); ?></h5>
					</div>
					<div class="card-body">
						<?php
						if ( WC()->cart ) {
							echo '<div class="cart-summary">';
							echo '<p class="mb-2"><strong>' . esc_html__( 'Items:', 'woocommerce' ) . '</strong> ' . WC()->cart->get_cart_contents_count() . '</p>';
							echo '<hr>';
							echo '<p class="mb-3"><strong>' . esc_html__( 'Subtotal:', 'woocommerce' ) . '</strong> ' . wp_kses_post( WC()->cart->get_cart_subtotal() ) . '</p>';
							echo '</div>';
						}
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>

<?php get_footer(); ?>
