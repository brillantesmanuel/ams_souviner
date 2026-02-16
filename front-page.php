<?php get_header(); ?>

<!-- Hero / Banner Slider -->
<section class="hero-slider mb-5">
      <div id="myCarousel" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
          <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
          <li data-target="#myCarousel" data-slide-to="1"></li>
          <li data-target="#myCarousel" data-slide-to="2"></li>
        </ol>
        <div class="carousel-inner">
          <div class="carousel-item active">
            <img class="first-slide" src="data:image/gif;base64,R0lGODlhAQABAIAAAHd3dwAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw==" alt="First slide">
            <div class="container">
              <div class="carousel-caption text-left">
                <h1>Example headline.</h1>
                <p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.</p>
                <p><a class="btn btn-lg btn-primary" href="#" role="button">Sign up today</a></p>
              </div>
            </div>
          </div>
          <div class="carousel-item">
            <img class="second-slide" src="data:image/gif;base64,R0lGODlhAQABAIAAAHd3dwAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw==" alt="Second slide">
            <div class="container">
              <div class="carousel-caption">
                <h1>Another example headline.</h1>
                <p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.</p>
                <p><a class="btn btn-lg btn-primary" href="#" role="button">Learn more</a></p>
              </div>
            </div>
          </div>
          <div class="carousel-item">
            <img class="third-slide" src="data:image/gif;base64,R0lGODlhAQABAIAAAHd3dwAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw==" alt="Third slide">
            <div class="container">
              <div class="carousel-caption text-right">
                <h1>One more for good measure.</h1>
                <p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.</p>
                <p><a class="btn btn-lg btn-primary" href="#" role="button">Browse gallery</a></p>
              </div>
            </div>
          </div>
        </div>
        <a class="carousel-control-prev" href="#myCarousel" role="button" data-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#myCarousel" role="button" data-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="sr-only">Next</span>
        </a>
      </div>
</section>


<!-- Category Section -->
<section class="categories py-5 bg-light">
    <div class="container">
        <div class="row">
            <?php
            // Example categories (replace with WooCommerce categories dynamically)
            $categories = get_terms([
                'taxonomy' => 'product_cat',
                'hide_empty' => true,
                'number' => 6,
            ]);
            foreach ($categories as $cat) : ?>
                <div class="col-6 col-md-2 mb-3">
                    <a href="<?php echo get_term_link($cat); ?>" class="d-block text-center">
                        <?php echo get_the_post_thumbnail($cat->term_id, 'medium', ['class' => 'img-fluid']); ?>
                        <p class="mt-2"><?php echo esc_html($cat->name); ?></p>
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
        <?php amstheme_featured_products(8); ?>
    </div>
</section>

<!-- Deal of the Day / Promo Section -->
<section class="deals py-5 bg-light">
    <div class="container">
        <h3 class="mb-4">Deal of the Day</h3>
        <div class="row">
            <?php
            $deal_args = [
                'post_type' => 'product',
                'posts_per_page' => 4,
                'meta_query' => [
                    [
                        'key' => '_featured',
                        'value' => 'yes',
                    ]
                ],
            ];
            $deal_query = new WP_Query($deal_args);
            if ($deal_query->have_posts()) :
                while ($deal_query->have_posts()) : $deal_query->the_post();
                    global $product;
                    ?>
                    <div class="col-6 col-md-3 mb-4">
                        <div class="card h-100">
                            <a href="<?php the_permalink(); ?>">
                                <?php
                                if (has_post_thumbnail()) {
                                    the_post_thumbnail('medium', ['class' => 'card-img-top']);
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
