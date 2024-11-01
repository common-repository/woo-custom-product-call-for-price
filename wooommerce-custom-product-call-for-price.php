<?php
/**
 * Plugin Name: WooCommerce Custom Product - Call For Price
 * Plugin URI:  http://www.logicrays.com/
 * Description: A sample plugin for WooCommerce Custom product type - call for price
 * Version:     1.0
 * Author:      Logicrays Team 
 */

// Check Woocommerce install or not
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    
	add_action('get_footer','lr_callback_for_setting_up_scripts');
	function lr_callback_for_setting_up_scripts() {
		
		wp_enqueue_style( 'lr_custom_product_css', plugins_url( '/assets/css/lr_custom_product.css', __FILE__ ) );
		wp_enqueue_script( 'lr_custom_product_js', plugins_url( '/assets/js/lr_custom_product.js', __FILE__ ) );
	}
	add_action( 'plugins_loaded', 'lr_wcpt_register_call_for_price_type' );
	function lr_wcpt_register_call_for_price_type () {
		// declare the product class
	    class WC_Product_Call_For_Price extends WC_Product {
	        public function __construct( $product ) {
	           $this->product_type = 'call_for_price';
	           parent::__construct( $product );	           
	        }
	    }
	}
	add_filter( 'product_type_selector', 'lr_wcpt_add_call_for_price_type' );
	function lr_wcpt_add_call_for_price_type ( $type ) {
		// Key should be exactly the same as in the class product_type
		$type[ 'call_for_price' ] = __( 'Call For Type' );		
		return $type;
	}
	add_filter( 'woocommerce_product_data_tabs', 'lr_call_for_price_tab' );
	function lr_call_for_price_tab( $tabs) {
		
		$tabs['call_for_price'] = array(
			'label'	 => __( 'Price Note', 'wcpt' ),
			'target' => 'call_for_price_options',
			'class'  => ('show_if_call_for_price'),
		);
		return $tabs;
	}
	add_action( 'woocommerce_product_data_panels', 'lr_wcpt_call_for_price_options_product_tab_content' );
	function lr_wcpt_call_for_price_options_product_tab_content() {
		// Dont forget to change the id in the div with your target of your product tab
		?><div id='call_for_price_options' class='panel woocommerce_options_panel'><?php
			?><div class='options_group'><?php
				// Number Field
				woocommerce_wp_text_input( 
					array( 
						'id'                => '_call_for_price_number_field', 
						'label'             => __( 'Mobile Number', 'woocommerce' ), 
						'placeholder'       => '', 
						'description'       => __( 'Enter the mobile number here with country code Ex : 918814314300.', 'woocommerce' ),
						'type'              => 'number', 
						'custom_attributes' => array(
								'step' 	=> 'any',
								'min'	=> '0'
							) 
					)
				);
				// Textarea
				woocommerce_wp_textarea_input( 
					array( 
						'id'          => '_call_for_price_textarea', 
						'label'       => __( 'My Textarea', 'woocommerce' ), 
						'placeholder' => '', 
						'description' => __( 'Enter the custom value here.', 'woocommerce' ) 
					)
				);
				 
			?></div>
		</div><?php
	}
	add_action( 'woocommerce_process_product_meta', 'lr_save_call_for_price_options_field' );
	function lr_save_call_for_price_options_field( $post_id ) {

		if ( isset( $_POST['_call_for_price_textarea'] ) ) :
			update_post_meta( $post_id, '_call_for_price_textarea', sanitize_text_field( $_POST['_call_for_price_textarea'] ) );
		endif;

		if ( isset( $_POST['_call_for_price_number_field'] ) ) :
			update_post_meta( $post_id, '_call_for_price_number_field', sanitize_text_field( $_POST['_call_for_price_number_field'] ) );
		endif;
	}
	add_action( 'woocommerce_single_product_summary', 'lr_call_for_price_template', 60 );
	function lr_call_for_price_template () {
		global $product;
		if ( 'call_for_price' == $product->get_type() ) {
			$template_path = plugin_dir_path( __FILE__ ) . 'templates/';
			
			wc_get_template( 'single-product/add-to-cart/call_for_price.php',
				'',
				'',
				trailingslashit( $template_path ) );
		}
	}
	
	
	add_filter('woocommerce_product_add_to_cart_text', 'wh_archive_custom_cart_button_text'); 
	function wh_archive_custom_cart_button_text()
	{
		global $product;
		if ( 'call_for_price' == $product->get_type() ) {
			return __('Call For Price', 'woocommerce');
		}else{
			return __('Add to cart', 'woocommerce');
		}
	}
	
}else{
?>
	<div class="error">
        <p><?php _e( 'Logicrays Woo Custom Product Type - Call For Price is enabled but not effective. It requires WooCommerce in order to work.' ); ?></p>
    </div>
<?php
}