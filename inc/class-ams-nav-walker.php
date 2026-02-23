<?php
/**
 * Custom Nav Walker for AM Souviner Theme
 *
 * @package Ams_Souviner
 */

class AMS_Nav_Walker extends Walker_Nav_Menu {
	/**
	 * Starts the list before the elements are added.
	 *
	 * @param string   $output Used to append additional content (passed by reference).
	 * @param int      $depth  Depth of menu item. Used for padding.
	 * @param stdClass $args   An object of wp_nav_menu() arguments.
	 */
	public function start_lvl( &$output, $depth = 0, $args = null ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = str_repeat( $t, $depth );

		$classes = array( 'sub-menu' );

		if ( 0 === $depth ) {
			$classes[] = 'dropdown-menu';
		}

		$class_names = implode( ' ', apply_filters( 'nav_menu_submenu_css_class', $classes, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$output .= "{$n}{$indent}<ul{$class_names}>{$n}";
	}

	/**
	 * Starts the element output.
	 *
	 * @param string   $output            Used to append additional content (passed by reference).
	 * @param WP_Post  $data_object       Menu item data object.
	 * @param int      $depth             Depth of menu item. Used for padding.
	 * @param stdClass $args              An object of wp_nav_menu() arguments.
	 * @param int      $current_object_id Optional. ID of the current menu item. Default 0.
	 */
	public function start_el( &$output, $data_object, $depth = 0, $args = null, $current_object_id = 0 ) {
		$menu_item = $data_object;

		if ( ! isset( $args ) || ! is_object( $args ) ) {
			return;
		}

		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = ( $depth ) ? str_repeat( $t, $depth ) : '';

		$classes   = empty( $menu_item->classes ) ? array() : (array) $menu_item->classes;
		$classes[] = 'menu-item-' . $menu_item->ID;

		if ( 0 === $depth ) {
			$classes[] = 'nav-item';
		}

		$args = apply_filters( 'nav_menu_item_args', $args, $menu_item, $depth );

		$class_names = implode( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $menu_item, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $menu_item->ID, $menu_item, $args, $depth );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

		$output .= $indent . '<li' . $id . $class_names . '>';

		$title = apply_filters( 'the_title', $menu_item->title, $menu_item->ID );
		$title = apply_filters( 'nav_menu_item_title', $title, $menu_item, $args, $depth );

		$atts = array(
			'title'  => ! empty( $menu_item->attr_title ) ? $menu_item->attr_title : '',
			'target' => ! empty( $menu_item->target ) ? $menu_item->target : '',
			'rel'    => ! empty( $menu_item->xfn ) ? $menu_item->xfn : '',
			'href'   => ! empty( $menu_item->url ) ? $menu_item->url : '',
		);

		if ( $menu_item->current ) {
			$atts['aria-current'] = 'page';
		}

		if ( 0 === $depth ) {
			$atts['class'] = 'nav-link';
		}

		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $menu_item, $args, $depth );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value       = 'href' === $attr ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		$link_before = isset( $args->link_before ) ? $args->link_before : '';
		$link_after  = isset( $args->link_after ) ? $args->link_after : '';
		$before      = isset( $args->before ) ? $args->before : '';
		$after       = isset( $args->after ) ? $args->after : '';

		$title_output = $link_before . $title . $link_after;

		$item_output  = $before;
		$item_output .= '<a' . $attributes . '>' . $title_output . '</a>';
		$item_output .= $after;

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $menu_item, $depth, $args );
	}
}
