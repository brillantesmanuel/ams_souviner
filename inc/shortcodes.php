<?php

add_shortcode(
	'featured_products',
	function ( $atts ) {
		$atts = shortcode_atts( array( 'number' => 4 ), $atts );
		ob_start();
		amstheme_featured_products( $atts['number'] );
		return ob_get_clean();
	}
);
