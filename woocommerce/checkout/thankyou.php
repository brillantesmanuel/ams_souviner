<?php
/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.1.0
 *
 * @var WC_Order $order
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="woocommerce-order mt-4">

	<?php
	if ( $order ) :

		do_action( 'woocommerce_before_thankyou', $order->get_id() );
		?>

		<?php if ( $order->has_status( 'failed' ) ) : ?>

			<div class="alert alert-danger alert-dismissible fade show" role="alert">
				<strong><?php esc_html_e( 'Order Failed', 'woocommerce' ); ?></strong>
				<p><?php esc_html_e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'woocommerce' ); ?></p>
				<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="btn btn-warning"><?php esc_html_e( 'Try Payment Again', 'woocommerce' ); ?></a>
				<?php if ( is_user_logged_in() ) : ?>
					<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="btn btn-secondary"><?php esc_html_e( 'My account', 'woocommerce' ); ?></a>
				<?php endif; ?>
				<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			</div>

		<?php else : ?>

			<?php wc_get_template( 'checkout/order-received.php', array( 'order' => $order ) ); ?>

			<div class="row mt-4">
				<div class="col-lg-8">
					<div class="card mb-4">
						<div class="card-header bg-light">
							<h5 class="card-title mb-0"><?php esc_html_e( 'Order Details', 'woocommerce' ); ?></h5>
						</div>
						<div class="card-body">
							<dl class="row mb-0">
								<dt class="col-sm-4"><?php esc_html_e( 'Order number:', 'woocommerce' ); ?></dt>
								<dd class="col-sm-8"><em><?php echo $order->get_order_number(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></em></dd>

								<dt class="col-sm-4"><?php esc_html_e( 'Date:', 'woocommerce' ); ?></dt>
								<dd class="col-sm-8"><em><?php echo wc_format_datetime( $order->get_date_created() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></em></dd>

								<?php if ( is_user_logged_in() && $order->get_user_id() === get_current_user_id() && $order->get_billing_email() ) : ?>
									<dt class="col-sm-4"><?php esc_html_e( 'Email:', 'woocommerce' ); ?></dt>
									<dd class="col-sm-8"><em><?php echo $order->get_billing_email(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></em></dd>
								<?php endif; ?>

								<dt class="col-sm-4"><?php esc_html_e( 'Total:', 'woocommerce' ); ?></dt>
								<dd class="col-sm-8"><strong><?php echo $order->get_formatted_order_total(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong></dd>

								<?php if ( $order->get_payment_method_title() ) : ?>
									<dt class="col-sm-4"><?php esc_html_e( 'Payment method:', 'woocommerce' ); ?></dt>
									<dd class="col-sm-8"><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></dd>
								<?php endif; ?>
							</dl>
						</div>
					</div>
				</div>
			</div>

		<?php endif; ?>

		<?php do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() ); ?>
		<?php do_action( 'woocommerce_thankyou', $order->get_id() ); ?>

	<?php else : ?>

		<?php wc_get_template( 'checkout/order-received.php', array( 'order' => false ) ); ?>

	<?php endif; ?>

</div>
