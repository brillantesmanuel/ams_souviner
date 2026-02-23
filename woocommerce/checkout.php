<?php
/**
 * Custom Checkout Page Template
 * Provides enhanced Bootstrap structure for WooCommerce checkout
 */

get_header();
?>

<main id="primary" class="site-main">
	<div class="container-fluid my-4">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<div class="checkout-header mb-5">
						<h1 class="page-title mb-1"><?php esc_html_e( 'Checkout', 'woocommerce' ); ?></h1>
						<p class="text-muted"><?php esc_html_e( 'Please review your order and enter your shipping and payment information.', 'woocommerce' ); ?></p>
					</div>
				</div>
			</div>

			<div class="row checkout-wrapper">
				<div class="col-lg-8">
					<?php woocommerce_content(); ?>
				</div>

				<div class="col-lg-4 mt-5 mt-lg-0">
					<div class="order-summary sticky-top" style="top: 20px;">
						<div class="card">
							<div class="card-header bg-light">
								<h5 class="card-title mb-0"><?php esc_html_e( 'Order Summary', 'woocommerce' ); ?></h5>
							</div>
							<div class="card-body">
								<?php
								// Display cart items summary
								if ( is_user_logged_in() && WC()->cart ) {
									echo '<ul class="list-unstyled">';
									foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
										$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
										if ( $_product && $_product->exists() ) {
											echo '<li class="d-flex justify-content-between align-items-center mb-2">';
											echo '<span>' . wp_kses_post( $_product->get_name() ) . ' × ' . $cart_item['quantity'] . '</span>';
											echo '<span class="font-weight-bold">' . wp_kses_post( WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ) ) . '</span>';
											echo '</li>';
										}
									}
									echo '</ul>';
									echo '<hr>';
									echo '<div class="mb-2"><strong>' . esc_html__( 'Total:', 'woocommerce' ) . '</strong> ' . wp_kses_post( WC()->cart->get_cart_total() ) . '</div>';
								}
								?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>

<?php get_footer(); ?>
