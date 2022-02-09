<?php

// A set of api routes to fetch data 

add_action( 'rest_api_init', function() {
	register_rest_route( 'orders/v1', '/getdata/(?P<order_id>[\d]+)', [
		'method'   => WP_REST_Server::READABLE,
		'callback' => 'route_getordersdata',
        'args'     => [
			'order_id' => [
				'required' => true,
				'type'     => 'number',
			],
		],
	] );
} );


function get_orders($order_id) {
    global $wpdb;
    $data = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}orders_report WHERE order_id = $order_id");
    return $data;
}

function route_getordersdata($request) {
	$order_id = $request->get_param( 'order_id' );
    $orders_data = get_orders( $order_id );
	if ( empty( $orders_data ) ) {
		return new WP_REST_Response( [
			'message' => 'Order was not found',
		], 400 );
	}
	return new WP_REST_Response( $orders_data, 200 );
}
