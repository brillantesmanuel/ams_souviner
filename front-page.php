<?php
/**
 * Template Name: Front Page
 *
 * PHP version: 8.0+
 *
 * @category Theme
 * @package  Ams_Soviner
 * @author   Manuel Brillantes II <brillantesmanuel@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-2.0.html GPL v2 or later
 * @link     https://brillantesmanuel.website
 */

get_header(); ?>

<!-- Category Section -->
<section class="categories py-5 bg-light">
	<div class="container">
		<div class="row">
			<?php
			$categories = get_terms(
				array(
					'taxonomy'   => 'product_cat',
					'hide_empty' => true,
					'number'     => 6,
				)
			);
			foreach ( $categories as $cate ) :
				?>
				<div class="col-6 col-md-2 mb-3">
					<a
						href="<?php echo esc_url( get_term_link( $cate ) ); ?>" 
						class="d-block text-center">
						<?php
						echo get_the_post_thumbnail(
							$cate->term_id,
							'medium',
							array( 'class' => 'img-fluid' )
						);
						?>
						<p class="mt-2">
							<?php echo esc_html( $cate->name ); ?>
						</p>
					</a>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<!-- Featured Products Section -->
<section class="featured-products py-5">
	<div class="container">
		<h3 class="mb-4">Featured Products</h3>
		<?php amstheme_featured_products( 8 ); ?>
	</div>
</section>

<!-- Deal of the Day / Promo Section -->
<section class="deals py-5 bg-light">
	<div class="container">
		<h3 class="mb-4">Deal of the Day</h3>
		<div class="row">
			<?php
			$deal_args  = array(
				'post_type'      => 'product',
				'posts_per_page' => 4,
				'tax_query'     => array(
					array(
						'key'   => 'product_visibility',
                        'field' => 'name',
                        'terms' => 'featured'
					),
				),
			);
			$deal_query = new WP_Query( $deal_args );
			if ( $deal_query->have_posts() ) :
				while ( $deal_query->have_posts() ) :
					$deal_query->the_post();
					global $product;
					?>
					<div class="col-6 col-md-3 mb-4">
						<div class="card h-100">
							<a href="<?php the_permalink(); ?>">
								<?php
								if ( has_post_thumbnail() ) {
									the_post_thumbnail(
										'medium',
										array(
											'class' => 'card-img-top',
										)
									);
								} else {
									echo '<img class="card-img-top" src="' . wc_placeholder_img_src() . '" alt="Placeholder">';
								}
								?>
							</a>
							<div class="card-body">
								<h6 class="card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h6>
								<p class="card-text"><?php echo $product->get_price_html(); ?></p>
								<?php woocommerce_template_loop_add_to_cart(); ?>
							</div>
						</div>
					</div>
					<?php
				endwhile;
			endif;
			wp_reset_postdata();
			?>
		</div>
	</div>
</section>

<?php get_footer(); ?>
