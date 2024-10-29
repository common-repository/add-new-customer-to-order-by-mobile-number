<?php 
/**
 * Plugin Name:       Add new customer to order by mobile number
 * Plugin URI:        https://biawp.ir
 * Description:       با استفاده از شماره موبایل مشتری، یک مشتری جدید بسازید
 * Version:           1.0
 * Author:            Biawp
 * Author URI:        https://Biawp.ir
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       actobm
 */

// If this file is called directly, abort.
if ( ! defined( "WPINC" ) ) {
	die;
}

add_action( 'woocommerce_admin_order_data_after_order_details', 'actobm_woocommerce_admin_order_data_after_order_details' );

function actobm_woocommerce_admin_order_data_after_order_details ()
{
	?>

	<p class="form-field" style="margin-top: 30px; width: 100%">
		<input style="width: 17px;height: 17;float: right;" type="checkbox" value="1" id="actobm" name="actobm" />
		<input type="hidden" name="nonce" value="<?php echo esc_attr(wp_create_nonce()); ?>" />
		<label for="actobm">افزودن مشتری جدید از آدرس صورتحساب (شماره موبایل مشتری را در فیلد تلفن وارد نمایید)</label>
	</p>

	<?php
}

add_action('woocommerce_new_order', 'actobm_woocommerce_new_order', 10, 2);

function actobm_woocommerce_new_order ($order_id, $order)
{
    if ( 
		! isset( $_POST[ 'nonce' ] ) || 
		! wp_verify_nonce( sanitize_text_field( wp_unslash(  $_POST[ 'nonce' ] ) ) )
	)
	{
		return;
	}
	

	if ( ! is_admin() ) 
	{
		return;
	}

	if ( isset ( $_POST['actobm'] ) && $_POST['actobm'] == 1 ) 
	{
		$billing_first_name = $order->get_billing_first_name();
		$billing_last_name  = $order->get_billing_last_name();
		$billing_company    = $order->get_billing_company();
		$billing_address_1  = $order->get_billing_address_1();
		$billing_address_2  = $order->get_billing_address_2();
		$billing_city       = $order->get_billing_city();
		$billing_state      = $order->get_billing_state();
		$billing_postcode   = $order->get_billing_postcode();
		$billing_country    = $order->get_billing_country();
		$billing_email  	= $order->get_billing_email();
		$billing_phone  	= $order->get_billing_phone();

		$shipping_first_name = $order->get_shipping_first_name();
		$shipping_last_name  = $order->get_shipping_last_name();
		$shipping_company    = $order->get_shipping_company();
		$shipping_address_1  = $order->get_shipping_address_1();
		$shipping_address_2  = $order->get_shipping_address_2();
		$shipping_city       = $order->get_shipping_city();
		$shipping_state      = $order->get_shipping_state();
		$shipping_postcode   = $order->get_billing_email();
		$shipping_country    = $order->get_billing_phone();

		$user = get_user_by('login', $billing_phone);
		if ( $user ) return;

		$user_id = wp_insert_user([
			'user_login'	=> $billing_phone,
			'user_pass'		=> wp_generate_password(20),
			'user_email'	=> $billing_email,
			'first_name'	=> $billing_first_name,
			'last_name'		=> $billing_last_name,
		]);

		update_post_meta( $order->ID, '_customer_user', $user_id );

		update_user_meta( $user_id, 'billing_first_name', 	$billing_first_name );
		update_user_meta( $user_id, 'billing_last_name', 	$billing_last_name );
		update_user_meta( $user_id, 'billing_company', 		$billing_company );
		update_user_meta( $user_id, 'billing_address_1', 	$billing_address_1 );
		update_user_meta( $user_id, 'billing_address_2', 	$billing_address_2 );
		update_user_meta( $user_id, 'billing_city', 		$billing_city );
		update_user_meta( $user_id, 'billing_state', 		$billing_state );
		update_user_meta( $user_id, 'billing_email', 		$billing_email );
		update_user_meta( $user_id, 'billing_phone', 		$billing_phone );
		update_user_meta( $user_id, 'billing_postcode', 	$billing_postcode );
		update_user_meta( $user_id, 'billing_country', 		$billing_country );

		update_user_meta( $user_id, 'shipping_first_name', 	$billing_first_name );
		update_user_meta( $user_id, 'shipping_last_name', 	$billing_last_name );
		update_user_meta( $user_id, 'shipping_company', 	$billing_company );
		update_user_meta( $user_id, 'shipping_address_1', 	$billing_address_1 );
		update_user_meta( $user_id, 'shipping_address_2', 	$billing_address_2 );
		update_user_meta( $user_id, 'shipping_city', 		$billing_city );
		update_user_meta( $user_id, 'shipping_state', 		$billing_state );
		update_user_meta( $user_id, 'shipping_phone', 		$billing_phone );
		update_user_meta( $user_id, 'shipping_postcode', 	$billing_postcode );
		update_user_meta( $user_id, 'shipping_country', 	$billing_country );
	}
}