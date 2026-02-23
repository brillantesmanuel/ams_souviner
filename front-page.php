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

<!-- Hero Section -->
<section class="hero-section bg-primary text-white py-5 mb-5">
	<div class="container">
		<div class="row align-items-center">
			<div class="col-lg-6">
				<h1 class="display-4 fw-bold mb-3">Welcome to AM Souviner Shop</h1>
				<p class="lead mb-4">Discover our amazing collection of unique and memorable souvenirs. Find the perfect gift for yourself or your loved ones.</p>
				<a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="btn btn-light btn-lg">Shop Now</a>
			</div>
			<div class="col-lg-6">
				<img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/hero-placeholder.jpg'); ?>" alt="Hero" class="img-fluid rounded" loading="lazy">
			</div>
		</div>
	</div>
</section>

<!-- Category Section -->
<section class="categories-section py-5 bg-light">
	<div class="container">
		<div class="section-header text-center mb-5">
			<h2 class="mb-2">Shop by Category</h2>
			<p class="text-muted">Browse our diverse range of products</p>
		</div>
		<div class="row g-4">
			<?php
			$categories = get_terms(
				array(
					'taxonomy'   => 'product_cat',
					'hide_empty' => true,
					'number'     => 6,
				)
			);
			if ( ! is_wp_error( $categories ) && ! empty( $categories ) ) :
				foreach ( $categories as $cate ) :
					?>
					<div class="col-6 col-md-4 col-lg-2">
						<a href="<?php echo esc_url( get_term_link( $cate ) ); ?>" class="category-card text-decoration-none">
							<div class="category-card-inner bg-white rounded shadow-sm overflow-hidden h-100">
								<div class="category-image-wrapper">
									<?php
									$cat_image = get_term_meta( $cate->term_id, 'thumbnail_id', true );
									if ( $cat_image ) {
										echo wp_get_attachment_image(
											$cat_image,
											'medium',
											false,
											array( 'class' => 'img-fluid w-100' )
										);
									} else {
										echo '<img src="' . esc_url( wc_placeholder_img_src() ) . '" alt="' . esc_attr( $cate->name ) . '" class="img-fluid w-100">';
									}
									?>
								</div>
								<div class="category-info p-3 text-center">
									<h6 class="fw-bold text-dark mb-1"><?php echo esc_html( $cate->name ); ?></h6>
									<small class="text-muted"><?php echo sprintf( _n( '%d product', '%d products', $cate->count, 'ams_souviner' ), $cate->count ); ?></small>
								</div>
							</div>
						</a>
					</div>
				<?php endforeach;
			endif;
			?>
		</div>
	</div>
</section>

<!-- Featured Products Section -->
<section class="featured-products-section py-5">
	<div class="container">
		<div class="section-header text-center mb-5">
			<h2 class="mb-2">Featured Products</h2>
			<p class="text-muted">Check out our hand-picked selection</p>
		</div>
		<?php amstheme_featured_products( 8 ); ?>
		<div class="text-center mt-5">
			<a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="btn btn-outline-primary btn-lg">View All Products</a>
		</div>
	</div>
</section>

<!-- Deal of the Day / Promo Section -->
<section class="deals-section py-5 bg-light">
	<div class="container">
		<div class="section-header text-center mb-5">
			<h2 class="mb-2">Deal of the Day</h2>
			<p class="text-muted">Limited time offers on selected items</p>
		</div>
		<div class="row g-4">
			<?php
			$deal_args  = array(
				'post_type'      => 'product',
				'posts_per_page' => 4,
				'orderby'        => 'date',
				'order'          => 'DESC',
				'meta_query'     => array(
					array(
						'key'     => '_sale_price',
						'compare' => 'EXISTS',
					),
				),
			);
			$deal_query = new WP_Query( $deal_args );
			if ( $deal_query->have_posts() ) :
				while ( $deal_query->have_posts() ) :
					$deal_query->the_post();
					global $product;
					?>
					<div class="col-6 col-md-6 col-lg-3 mb-4">
						<div class="product-card card h-100 border-0 shadow-sm overflow-hidden">
							<div class="product-image-wrapper position-relative overflow-hidden">
								<?php
								if ( has_post_thumbnail() ) {
									echo '<a href="' . esc_url( get_permalink() ) . '">';
									the_post_thumbnail(
										'medium',
										array(
											'class' => 'card-img-top product-img img-fluid',
										)
									);
									echo '</a>';
								} else {
									echo '<a href="' . esc_url( get_permalink() ) . '"><img class="card-img-top product-img img-fluid" src="' . esc_url( wc_placeholder_img_src() ) . '" alt="' . esc_attr( get_the_title() ) . '"></a>';
								}
								
								// Sale badge
								if ( $product->is_on_sale() ) {
									echo '<span class="badge bg-danger position-absolute top-0 start-0 m-2">Sale</span>';
								}
								?>
							</div>
							<div class="card-body d-flex flex-column">
								<h6 class="card-title mb-2">
									<a href="<?php echo esc_url( get_permalink() ); ?>" class="text-decoration-none text-dark">
										<?php the_title(); ?>
									</a>
								</h6>
								<div class="product-rating mb-2">
									<?php woocommerce_template_loop_rating(); ?>
								</div>
								<p class="card-text price mb-3"><?php echo wp_kses_post( $product->get_price_html() ); ?></p>
								<div class="mt-auto">
									<?php woocommerce_template_loop_add_to_cart(); ?>
								</div>
							</div>
						</div>
					</div>
					<?php
				endwhile;
			else :
				echo '<div class="col-12"><p class="text-center text-muted">' . esc_html__( 'No products on sale at the moment.', 'ams_souviner' ) . '</p></div>';
			endif;
			wp_reset_postdata();
			?>
		</div>
	</div>
</section>

<!-- Newsletter Section -->
<section class="newsletter-section py-5">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-lg-6">
				<div class="card border-0 bg-primary text-white">
					<div class="card-body p-4 text-center">
						<h3 class="mb-3">Subscribe to Our Newsletter</h3>
						<p class="mb-4">Get special offers and updates delivered to your inbox.</p>
						<?php
						// Newsletter signup form (you can replace with a plugin like Newsletter or MailerLite)
						if ( shortcode_exists( 'mailchimp' ) ) {
							echo do_shortcode( '[mailchimp]' );
						} else {
							?>
							<form class="d-flex gap-2" method="post">
								<input 
									type="email" 
									class="form-control form-control-lg" 
									placeholder="Enter your email" 
									required
									aria-label="Email for newsletter"
								>
								<button type="submit" class="btn btn-light btn-lg">Subscribe</button>
							</form>
							<?php
						}
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<?php get_footer(); ?>
