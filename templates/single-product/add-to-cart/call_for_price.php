<?php
/**
 * Simple custom product
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
global $product;
do_action( 'woocommerce_before_add_to_cart_form' );  ?>

<form class="call_for_price_cart cart" method="post" enctype='multipart/form-data'>
	
	<button data-popup-open="popup-1" type="button" name="add-to-cart" value="<?php //echo esc_attr( $product->get_id() ); ?>" class="single_add_to_cart_button button alt btn"><?php echo esc_html( "Call For Price" ); ?></button>	
	<div class="popup" data-popup="popup-1">
		<div class="popup-inner">
		<h2><strong><u>This Is Call For Price Product</u></strong></h2>
		<?php 
			$number = get_post_meta ( $product->get_id(), '_call_for_price_number_field' );
			$number = implode(" ",$number);	
			$country_code = substr($number, 0, 2);	
			$my_number = substr($number, 3, 12);
			$_call_for_price_desc = get_post_meta ( $product->get_id(), '_call_for_price_textarea' );		
			$_call_for_price_desc = implode(" ",$_call_for_price_desc);
		?>
		<label for="call_for_price_amount"><strong style="color:black;"><?php echo __( "Mobile Number : ", 'wcpt' ); ?></strong></label><a href="tel:+<?php echo $country_code; ?>"><span><?php echo $my_number; ?></span></a><br>
		<label for="call_for_price_amount"><strong style="color:black;"><?php echo __( "Description : ", 'wcpt' ); ?></strong></label><span><?php echo $_call_for_price_desc; ?></span>

		<a class="popup-close" data-popup-close="popup-1" href="#">x</a>
		</div>
	</div>
</form>
<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>