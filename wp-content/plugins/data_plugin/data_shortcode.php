<?php

/**
 * Plugin Name: Data Plugin
 * Description: This is a custom data plugin.
 * Version: 1.0
 * Author: Victor
 **/

add_action('wp_enqueue_script', 'stylesheet');

// function stylesheet() {
// 	wp_register_style('style',plugins_url('style.css',__FILE__), array()); // points to the file location of the CSS file and gives it a short name.
// 	wp_enqueue_style('stylesheet'); // registers the style with WordPress
// }

function data_shortcode($atts)
{
	global $wpdb;
	ob_start();

	wp_enqueue_script('exportscript', plugins_url('/exporttocsv.js', __FILE__), array('jquery'), '1.0.0.', true);
	wp_enqueue_script('sortscript', plugins_url('/sort.js', __FILE__), array('jquery'), '1.0.0.', true);

	$current_page = get_the_title();
	$order_value = get_query_var('sortby');

	$number = 5;
	// Getting current page pagination number
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	// $parameters = $request->get_params();
	echo $_REQUEST['page'];

	$offset = ($paged - 1) * $number;

	// $val = do_shortcode($atts['drpdwn']);

	$args = shortcode_atts(array(
		'pars' => null,
		'table' => ''
	), $atts);

	if (is_null($args['pars'])) {
		$total = $wpdb->get_var("SELECT COUNT(`id`) FROM {$wpdb->prefix}$args[table]");
	} else {
		$total = $wpdb->get_var("SELECT COUNT(`id`) FROM {$wpdb->prefix}$args[table] WHERE post_type in('$args[pars]')");
	}

	if (intval($total % $number) != 0) {
		$total_pages = intval($total / $number) + 1;
	} else {
		$total_pages = intval($total / $number);
	}

	if ($current_page === 'Orders') {
		$order_by = 'id';
		$results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}$args[table] ORDER BY $order_by ASC LIMIT $offset, $number");
	} else {
		$results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}$args[table] ORDER BY $order_value ASC LIMIT $offset, $number");
	}
?>
	<div id="primary" class="site-content">
		<main id="main" class="site-main" role="main">
			<select class="custom-select" style="width:200px; margin-bottom: 20px;" id="sort_option">
				<option value="id">Sort:</option>
				<option value="order_id">Order</option>
				<option value="customer_id">Customer</option>
				<option value="created">Date</option>
			</select>

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

						// Modify these to match the database structure
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
			<span class="pages">Pages:</span>
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
				echo paginate_links(array(
					'base' => @add_query_arg('page', '%#%'),
					'format' => '/?page=%#%',
					'current' => $current,
					'total' => $total_pages,
					'prev_text'    => __('prev'),
					'next_text'    => __('next'),
					'type'         => 'list',
				));
			}
			?>
		</div>
		<button style='margin-top:12px;' id='btn_csv_export'>Export</button>
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
