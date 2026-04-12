<?php get_header(); ?>

<div class="container my-5">
	<div class="row">
		<div class="col-12">
			<?php if ( amstheme_is_woocommerce_active() ) : ?>
				<?php woocommerce_content(); ?>
			<?php else : ?>
				<div class="alert alert-warning" role="alert">
					<?php esc_html_e( 'WooCommerce is not active. Please activate WooCommerce to view the shop content.', 'amstheme' ); ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>

<?php get_footer(); ?>
