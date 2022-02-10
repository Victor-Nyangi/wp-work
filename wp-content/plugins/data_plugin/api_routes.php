<?php

// A set of api routes to fetch order data

add_action('rest_api_init', function () {
	register_rest_route('/orders/v1', '/getdata/(?P<order_id>[\d]+)', [
		'method'   => WP_REST_Server::READABLE,
		'callback' => 'route_getordersdata',
		'args'     => [
			'order_id' => [
				'required' => true, 
				'type'     => 'number' // data type validation
			],
		],
	]);
});
// $current_user = wp_get_current_user();
$user_id = get_current_user_id();

// GET route: http://localhost/wp/wp-json/orders/v1/getdata/187

function route_getordersdata($request)
{
	$order_id = $request->get_param('order_id');
	$order = wc_get_order($order_id);

	// Check if order exists and return a not found message
	if (is_null($order)) {
		return new WP_REST_Response([
			'message' => 'Order was not found',
		], 400);
	} else {
		$order_data['user_id'] = $GLOBALS['user_id'];
		$order_data['customer'] = $order->get_user_id();
		$order_data['order_id'] = $order->get_id();
		$order_data['bonus_offer'] = '';
		$order_data['sub_total'] = $order->get_subtotal();
		$order_data['discount'] = $order->get_discount_total();
		$order_data['shipping'] = $order->get_shipping_total();
		$order_data['total'] = $order->get_total();
		$order_data['tracking_url'] = $order->get_shipping_address_map_url();

		// Order shipping information
		$order_data['shipping_information'] = array(
			"first_name" => $order->get_shipping_first_name(),
			"last_name" => $order->get_shipping_last_name(),
			"company" => $order->get_shipping_company(),
			"address_1" => $order->get_shipping_address_1(),
			"address_2" => $order->get_shipping_address_2(),
			"city" => $order->get_shipping_city(),
			"state" => $order->get_shipping_state(),
			"postcode" => $order->get_shipping_postcode(),
			"country" => $order->get_shipping_country()
		);
		$order_data['payment_method'] = $order->get_payment_method();
		// Order's billing information
		$order_data['billing'] = array(
			"first_name" => $order->get_billing_first_name(),
			"last_name" => $order->get_billing_last_name(),
			"company" => $order->get_billing_company(),
			"address_1" => $order->get_billing_address_1(),
			"address_2" => $order->get_billing_address_2(),
			"city" => $order->get_billing_city(),
			"state" => $order->get_billing_state(),
			"postcode" => $order->get_billing_postcode(),
			"country" => $order->get_billing_country(),
			"email" => $order->get_billing_email(),
			"phone" => $order->get_billing_phone()
		);

		$products = array();
		// Product information from the order items
		foreach ($order->get_items() as $item_id => $item) {
			$product = [];
			$product['product_name'] = $item->get_name();
			$product['quantity'] = $item->get_quantity();
			$product['total_price'] = $item->get_total();
			$products[] = $product;
		}
		$order_data['products'] = $products;

		// return Order if successful
		return new WP_REST_Response($order_data, 200);
	}
}
