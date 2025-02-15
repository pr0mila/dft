<?php
/**
 * A single course loop add to cart
 *
 * @since v.1.0.0
 * @author themeum
 * @url https://themeum.com
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$product_id = tutor_utils()->get_course_product_id();
$product = wc_get_product($product_id);

if (! $product_id || ! $product){
	return;
}

$args = array();
$defaults = array(
	'quantity'   => 1,
	'class'      => implode( ' ', array_filter( array(
		'button',
		'product_type_' . $product->get_type(),
		$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
		$product->supports( 'ajax_add_to_cart' ) ? 'ajax_add_to_cart' : '',
	) ) ),
	'attributes' => array(
		'data-product_id'  => $product->get_id(),
		'data-product_sku' => $product->get_sku(),
		'aria-label'       => $product->add_to_cart_description(),
		'rel'              => 'nofollow',
	),
);

$args = apply_filters( 'woocommerce_loop_add_to_cart_args', wp_parse_args( $args, $defaults ), $product );

if ( isset( $args['attributes']['aria-label'] ) ) {
	$args['attributes']['aria-label'] = strip_tags( $args['attributes']['aria-label'] );
}

?>

<div class="tutor-loop-cart-btn-wrap">
	<?php
	echo apply_filters( 'woocommerce_loop_add_to_cart_link', // WPCS: XSS ok.
		sprintf( '<a href="%s" data-quantity="%s" class="%s" %s>%s</a>',
			esc_url( $product->add_to_cart_url() ),
			esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
			esc_attr( isset( $args['class'] ) ? $args['class'] : 'button' ),
			isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
			esc_html( $product->add_to_cart_text() )
		),
		$product, $args );

	?>

</div>

