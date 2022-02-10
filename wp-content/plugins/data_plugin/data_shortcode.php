<?php

/**
 * Plugin Name: Data Plugin
 * Description: This is a custom data plugin.
 * Version: 1.0
 * Author: Victor
 **/

// add api routing file

$order = '';
include(plugin_dir_path(__FILE__) . 'api_routes.php');

// Export button with name dataExport
if (isset($_GET["dataExport"])) {
	header("Content-type: application/vnd.ms-excel"); // tells browser to download
	header("Content-disposition: csv" . date("Y-m-d") . ".csv");
	header("Content-disposition: filename=data.csv");

	$fileName = 'orders.csv';

	$output = fopen("php://output", "w");

	// Set table header
	$table_head = array('Id', 'Order', 'Customer', 'Email');
	fputcsv($output, $table_head);
	// ARRAY_N to return output as a numerically indexed array of numerically indexed arrays.
	$results = $wpdb->get_results("SELECT id, order_id, customer_id, email FROM {$wpdb->prefix}orders_report", ARRAY_N);

	foreach ($results as $row) {
		fputcsv($output, $row);
	}
	fclose($output);
	exit;
}

function data_shortcode($atts)
{
	global $wpdb;

	ob_start();

	wp_enqueue_script('exportscript', plugins_url('/exporttocsv.js', __FILE__), array('jquery'), '1.0.0.', true);
	wp_enqueue_style('style', plugins_url('/style.css', __FILE__));

	$current_page = get_the_title();
	$order_value = get_query_var('sortby');

	$GLOBAL['order'] = $order_value;

	$number = 5;
	// Getting current page pagination number
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	// $parameters = $request->get_params();

	$offset = ($paged - 1) * $number;

	$args = shortcode_atts(array(
		'pars' => null,
		'table' => ''
	), $atts);

	if (is_null($args['pars'])) {
		$total = $wpdb->get_var("SELECT COUNT(`id`) FROM {$wpdb->prefix}$args[table]");
	} else {
		$total = $wpdb->get_var("SELECT COUNT(`id`) FROM {$wpdb->prefix}$args[table] WHERE post_type in('$args[pars]')");
	}

	// To add an additional pagination page in case values exceed
	if (intval($total % $number) != 0) {
		$total_pages = intval($total / $number) + 1;
	} else {
		$total_pages = intval($total / $number);
	}

	if ($current_page === 'Orders') {
		$results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}$args[table] ORDER BY id ASC LIMIT $offset, $number");
	} else {
		$results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}$args[table] ORDER BY $order_value ASC LIMIT $offset, $number");
	}

?>
	<div id="primary" class="site-content">
		<main id="main" class="site-main" role="main">
			<div id="data-table">
				<select class="custom-select" style="width:200px;" id="sort_option">
					<option value="id">Id</option>
					<option value="order_id">Order</option>
					<option value="customer_id">Customer</option>
					<option value="created">Date</option>
				</select>
				<form action="" method="GET">
					<button name="dataExport">Export</button>
				</form>
			</div>
			<table id="dataTable">
				<tbody>
					<tr>
						<th class="tak">ID</th>
						<th class="tak">ORDER</th>
						<th class="tak">CUSTOMER</th>
						<th class="tak">EMAIL</th>
					</tr>
					<?php
					foreach ($results as $row) {
						$id = $row->id;
						$ord_id = $row->order_id;
						$cust_id = $row->customer_id;
						$email = $row->email;
						echo '
							<tr>
							<td class="tako">' . $id . '</td>
							<td class="tako">' . $ord_id . '</td>
							<td class="tako">' . $cust_id . '</td>
							<td class="tako">' . $email . '</td></tr>';
					}
					?>
				</tbody>
			</table>
		</main>
		<div id="pagination" class="clearfix">
			<?php
			$current = max(1, get_query_var('paged'));
			if ($current_page === 'Orders') {
				echo paginate_links(array(
					'base' => get_pagenum_link(1) . '%_%',
					'format' => 'page/%#%',
					'current' => $current,
					'total' => $total_pages,
					'prev_text'    => __('« prev'),
					'next_text'    => __('next »'),
					'type'         => 'list',
				));
			} else {
				$current = max(1, get_query_var('paged'));
				echo paginate_links(array(
					'base' => @add_query_arg('paged', '%#%'),
					'format' => '?paged=%#%',
					'current' => $current,
					'total' => $total_pages,
					'prev_text'    => __('<<'),
					'next_text'    => __('>>'),
					'type'         => 'list',
				));
			}
			?>
		</div>
	</div>
<?php
	// return the table
	return ob_get_clean();
}

function view_sort_option()
{
	echo $_REQUEST['value'];
	exit;
}

function add_option_to_wp_footer()
{
?>
	<script type="text/javascript">
		var val = jQuery('#sort_option').val();
		jQuery('#sort_option').on("change", function(e) {
			e.preventDefault();
			val = jQuery('#sort_option').val();
			jQuery.ajax({
				type: 'POST',
				url: '<?php echo admin_url('admin-ajax.php'); ?>',
				data: {
					"action": "view_sort_option",
					value: val
				},
				success: function(data) {
					//redirect to new page orders-sort with $data as a variable
					window.location.href = `../orders-sort?sortby=${data}`
				}
			});
			return false;
		});
	</script>
<?php }

add_action('wp_footer', 'add_option_to_wp_footer');

add_action('wp_ajax_view_sort_option', 'view_sort_option');
add_action('wp_ajax_nopriv_view_sort_option', 'view_sort_option');

add_shortcode('data-table', 'data_shortcode');

function w4dev_enqueue_jquery()
{
	wp_enqueue_script('jquery');
}

add_action('wp_enqueue_scripts', 'w4dev_enqueue_jquery');

// add the sortby url parameter
function add_sort_val()
{
	global $wp;
	$wp->add_query_var('sortby');
}

add_action('init', 'add_sort_val');
