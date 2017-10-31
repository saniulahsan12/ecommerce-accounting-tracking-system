<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

add_action( 'woocommerce_order_status_completed', 'track_order_details' );

function track_order_details($order_id = null) {

	if( $order_id == null ):
		return;
	endif;

	// 1) Get the Order object
    $order = wc_get_order( $order_id );
	// 2) Get the Order meta data
    $order_meta = get_post_meta($order_id);
	// 3) Get the order items
    $items = $order->get_items();

	// variables
	$api_user_name   			 = get_option('acc_it_username');
	$api_app_key     			 = get_option('acc_it_appkey');
	$api_company_key 			 = get_option('acc_it_company');
	$accit 			 			 = new AccountAPI( $api_user_name, $api_app_key, $api_company_key );
	$acc_it_docdetials 			 = array();
	$acc_it_rcptdetials 		 = array();
	// ends

	foreach ( $items as $item_id => $item_data ):

	    $acc_it_docdetials[] = array(
	        "num" 				=> "",
	        "cat_num" 			=> "204",
	        "description" 		=> $item_data['name'],
	        "qty" 				=> $order->get_item_meta($item_id, '_qty', true),
	        "unit_price" 		=> get_post_meta($item_id, '_price', true),
	        "currency" 			=> get_woocommerce_currency(),
	        "price" 			=> $order->get_item_meta($item_id, '_line_total', true),
        	"nisprice" 			=> "",
	        "id" 				=> $item_id,
	        "status" 			=> "2",
	        "discount_type" 	=> "0",
	        "discount_price"	=> "0",
	        "discount" 			=> "0.00"
	        );

	endforeach;

	$bacs_accounts = new WC_Gateway_BACS();

	if ( !empty( $bacs_accounts ) ):
		$accounts = $bacs_accounts->account_details;
		foreach( $accounts as $account ):

			  $acc_it_rcptdetials[] = array(
					"type"			=> "1",
					"creditcompany"	=> $order->get_total(),
					"cheque_num"	=> $account['sort_code'],
					"bank"			=> $account['bank_name'],
					"branch"		=> $account['bic'],
					"cheque_acct"	=> $account['iban'],
					"cheque_date"	=> date('Y-m-d'),
					"sum"			=> $order->get_total(),
					"bank_refnum"	=> $account['bic'],
					"dep_date"		=> "",
					"id"			=> "1"
			   );
		endforeach;
	endif;

	$acc_it_data = array(
	    "doctype"					=> 9,
		"company"					=> $order_meta['_billing_first_name'][0].' '.$order_meta['_billing_last_name'][0],
		"address"					=> $order_meta['_billing_address_1'][0],
		"city"						=> $order_meta['_shipping_city'][0],
		"zip"						=> $order_meta['_shipping_postcode'][0],
		"phone"						=> $order_meta['_billing_phone'][0],
		"issue_date"				=> date('Y-m-d'),
		"due_date"					=> date('Y-m-d'),
		"sub_total"					=> $order->get_total(),
		"total"						=> $order->get_total(),
		"src_tax"					=> $order_meta['_order_tax'][0],
		"issue_time"				=> date('H:i:s'),
		"total_discount_percent"	=> ( $order_meta['_cart_discount'][0] / $order->get_total() ) * 100,
		"total_discount"			=> $order_meta['_cart_discount'][0],
		"total_no_discount"			=> $order->get_total() - $order_meta['_cart_discount'][0],
        "docdetials"                => $acc_it_docdetials,
        "rcptdetials"               => $acc_it_rcptdetials
    );

    $acc_stat = $accit->putData($acc_it_data);

	if( !empty($acc_stat) ):
		// after successfull data passing
		add_action( 'admin_notices', 'success_message' );
		// ends
	endif;
}
