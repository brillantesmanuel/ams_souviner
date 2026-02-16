<?php get_header(); ?>

<div class="container my-5">
	<?php
	if ( have_posts() ) :
		while ( have_posts() ) :
			the_post();
			?>
		<div class="mb-4">
			<h2><?php the_title(); ?></h2>
			<?php the_content(); ?>
		</div>
			<?php
	endwhile;
endif;
	?>
</div>

<?php get_footer(); ?>
