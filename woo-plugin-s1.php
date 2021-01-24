<?php
/**
 * Plugin Name: woocommerce-plugin-s1
 * Plugin URI: http://techsambd.com
 * Author: Md Sabbir Ahmed
 * Author URI: http://techsambd.com
 * Description: This Plugin can remove add to cart on shop and single page also the price so the shop become a catalog
 * License: GPL2 or later
 * Version: 1.00
 * Text-domain: sam_woocommerce
 *
 */

defined ('ABSPATH') or die;


//remove price from shop ----------------------start

add_action('woocommerce_after_shop_loop_item_title','sam_woocommerce_remove_template_loop_price');
function sam_woocommerce_remove_template_loop_price(){
	global $product;
//	remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
}

//remove price from shop ----------------------End


//remove Add to cart button from shop ----------------------start
add_action('woocommerce_after_shop_loop_item_title','sam_woocommerce_remove_template_add_to_cart');
function sam_woocommerce_remove_template_add_to_cart(){
	global $product;
//	remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
}

//remove Add to cart button from shop ----------------------End


//remove single price from singl product page shop ----------------------start
add_action('woocommerce_single_product_summary','sam_woocommerce_remove_single_page_price',1);
function sam_woocommerce_remove_single_page_price(){
	global $product;
//	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
}

//remove Add to cart button from shop ----------------------End


//remove single add to cart from single product page shop ----------------------start
add_action('woocommerce_single_product_summary','sam_woocommerce_remove_single_page_add_to_cart');
function sam_woocommerce_remove_single_page_add_to_cart(){
	global $product;
//	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
}

//remove single add to cart from singl product page shop ----------------------End

//2nd part----------------------------------------------------------------=======================================

//add extra fee on cart--------
function lab_package_cost(){
	global  $woocommerce;
	$woocommerce->cart->add_fee(__('Sakrian:','om-service-widget'),5);
}

add_action('woocommerce_cart_calculate_fees','lab_package_cost');
/*



//filed--------
function lab_checkout($fields){
//var_dump($fields);
//    unset($fields['billing']['id or classname']);
	unset($fields['billing']['billing_phone']);
	return $fields;
}
add_filter('woocommerce_checkout_fields','lab_checkout');



*/

add_action('woocommerce_before_order_notes','lab_add_id_filed');
function lab_add_id_filed($checkout){
	$current_user = wp_get_current_user();
	$saved_id_number= $current_user->add_id_filed;
	woocommerce_form_field(
		'add_id_filed',
		array(
			'type'=>'text',
			'class'=> array( 'form-row-wide'),
			'label'=>'IUBAT ID Number',
			'placeholder'=>"Put Your IUBAT ID to get 10% cash back on Bkash if you are student of Iubat Ex:17103188",
			'required'=> false,
			'default'=>$saved_id_number
		),
		$checkout->get_value('add_id_filed')
	);
}


/*add_action('woocommerce_checkout_process','valided_id_filed');
function valided_id_filed(){

    if(! $_POST['add_id_filed']){
        wc_add_notice('Please provide ID card','error');
    }
}*/


add_action('woocommerce_checkout_update_order_meta','save_id_filed');
function save_id_filed($order_id){
	if( $_POST['add_id_filed']){
		update_post_meta($order_id,'_add_id_filed',esc_attr($_POST['add_id_filed']));
	}
}




add_action('woocommerce_admin_order_data_after_billing_address','show_id_filed');
function show_id_filed($order){
	$order_id=$order->get_id();
	if(get_post_meta($order_id,'_add_id_filed',true)){
		echo '<p><strong>IUBAT ID Number:'. get_post_meta($order_id,'_add_id_filed',true).'</strong><p>';
	}
}




add_action('woocommerce_email_after_order_table','show_id_filed_email',20,4);
function show_id_filed_email($order, $sent_to_admin, $plain_text, $email){
	$order_id = $order->get_id();
	if(get_post_meta($order_id,'_add_id_filed',true)){
		echo '<p><strong>IUBAT ID Number:'. get_post_meta($order_id,'_add_id_filed',true).'</strong><p>';
	}
}


/* WooCommerce Add To Cart Text change */

add_filter('woocommerce_product_single_add_to_cart_text', 'sa_woocommerce_custom_add_to_cart_text');

function sa_woocommerce_custom_add_to_cart_text() {
	return __('Add to the basket', 'woocommerce');
}

//shop page add to cart text change
add_filter( 'woocommerce_product_add_to_cart_text', 'woo_custom_product_add_to_cart_text' );

function woo_custom_product_add_to_cart_text() {

	return __( 'My Button Text', 'woocommerce' );

}


//Skip cart and redirect to checkout
add_filter( 'woocommerce_add_to_cart_redirect', 'cw_redirectadd_to_cart');
function cw_redirectadd_to_cart() {

	$url = wc_get_checkout_url(); // since WC 2.5.0
	return $url;
}




