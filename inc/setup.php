<?php 

// -----------------------------------------------------------------------------
// Theme Setup
function amstheme_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('woocommerce');

    register_nav_menus([
        'primary' => __('Primary Menu', 'amstheme'),
    ]);
}
add_action('after_setup_theme', 'amstheme_setup');

// Move product price above title
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
add_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 5);

// Add custom message after Add to Cart button
add_action('woocommerce_single_product_summary', function() {
    echo '<p class="text-muted mt-2">Free shipping on orders over $50!</p>';
}, 35);

// Display WooCommerce Featured Products
function amstheme_featured_products($number = 4) {

    $args = [
        'post_type'      => 'product',
        'posts_per_page' => $number,
        'tax_query'      => [
            [
                'taxonomy' => 'product_visibility',
                'field'    => 'name',
                'terms'    => 'featured',
                'operator' => 'IN',
            ]
        ],
    ];

    $featured = new WP_Query($args);

    if ($featured->have_posts()) {
        echo '<div class="row featured-products">';
        while ($featured->have_posts()) {
            $featured->the_post();
            global $product;
            ?>
            <div class="col-md-3 mb-4">
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
                        <h5 class="card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
                        <p class="card-text"><?php echo $product->get_price_html(); ?></p>
                        <?php woocommerce_template_loop_add_to_cart(); ?>
                    </div>
                </div>
            </div>
            <?php
        }
        echo '</div>';
    }

    wp_reset_postdata();
}
